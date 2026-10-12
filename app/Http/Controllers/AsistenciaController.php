<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Materia;
use App\Services\AsistenciaAcademica;
use App\Services\SesionesQr;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AsistenciaController extends Controller
{
    private function profesor(Request $request): int { return (int) $request->attributes->get('id_profesor'); }

    private function propia(Request $request, int $id): ?Materia
    {
        return Materia::where('id_materia', $id)->where('id_usuario', $this->profesor($request))->first();
    }

    public function formulario(Request $request): View
    {
        return view('pagina', ['pagina' => 'marcar', 'id_materia' => $request->query('id_materia'), 'token' => $request->query('token')]);
    }

    public function pantallaQr(int $id): Response
    {
        return response()->view('pagina', ['pagina' => 'qr', 'id_materia' => $id])->withHeaders(['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0', 'Pragma' => 'no-cache', 'Expires' => '0']);
    }

    public function tokenPrivado(Request $request, int $id, SesionesQr $sesiones): JsonResponse
    {
        if (! $this->propia($request, $id)) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }
        $sesiones->iniciarClase($id);

        return response()->json([...$sesiones->generar($id), 'base_url' => config('services.asisgo.base_url')]);
    }

    public function tokenPublico(int $id, SesionesQr $sesiones): JsonResponse
    {
        if (! Materia::whereKey($id)->exists() || ! $sesiones->claseEnCurso($id)) {
            return response()->json(['detail' => 'No hay una clase activa para esta materia.'], 403);
        }

        return response()->json([...$sesiones->generar($id), 'base_url' => config('services.asisgo.base_url')]);
    }

    public function claseEnCurso(Request $request, int $id, SesionesQr $sesiones): JsonResponse
    {
        if (! $this->propia($request, $id)) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }

        return response()->json(['en_curso' => $sesiones->claseEnCurso($id)]);
    }

    public function marcar(Request $request, SesionesQr $sesiones, AsistenciaAcademica $academica): JsonResponse
    {
        $datos = $request->validate(['token' => ['required', 'string', 'min:10', 'max:40'], 'numero_cuenta' => ['required', 'string', 'min:4', 'max:20'], 'lat' => ['nullable', 'numeric', 'between:-90,90'], 'lng' => ['nullable', 'numeric', 'between:-180,180']]);
        $idMateria = (int) $request->query('id_materia');
        if (! $sesiones->valido($idMateria, $datos['token'])) { return response()->json(['detail' => 'El codigo QR ya expiro. Pide al profesor que muestre uno nuevo.'], 401); }
        $estudiante = Estudiante::where('numero_cuenta', $datos['numero_cuenta'])->where('id_materia', $idMateria)->first();
        if (! $estudiante) { return response()->json(['detail' => 'Tu numero de cuenta no esta inscrito en esta clase.'], 404); }
        $materia = Materia::find($idMateria);
        $dentro = null;
        $presente = true;
        $distancia = null;
        if ($estudiante->modalidad === 'presencial' && $materia?->lat_aula && $materia?->lng_aula && isset($datos['lat'], $datos['lng'])) {
            $distancia = $academica->distanciaMetros((float) $datos['lat'], (float) $datos['lng'], (float) $materia->lat_aula, (float) $materia->lng_aula);
            $dentro = $distancia <= 50;
            $presente = $dentro;
        }
        if (Asistencia::where('id_estudiante', $estudiante->id_estudiante)->whereDate('fecha', today())->exists()) {
            return response()->json(['detail' => 'Ya marcaste tu asistencia hoy.'], 409);
        }
        Asistencia::create(['id_estudiante' => $estudiante->id_estudiante, 'id_materia' => $idMateria, 'fecha' => today(), 'presente' => $presente, 'modalidad_usada' => $estudiante->modalidad, 'dentro_del_rango' => $dentro]);
        $mensaje = $estudiante->modalidad === 'presencial' && ! $presente ? "Registro recibido, {$estudiante->nombre}, pero estás fuera del rango del aula (".((int) $distancia)."m). No se contará como presente." : ($dentro === null && $estudiante->modalidad === 'presencial' ? "Asistencia registrada, {$estudiante->nombre}! (Sin validación de GPS)" : "Asistencia registrada, {$estudiante->nombre}!");

        return response()->json(['mensaje' => $mensaje]);
    }

    public function hoy(Request $request, int $id): JsonResponse
    {
        if (! $this->propia($request, $id)) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }
        $estudiantes = Estudiante::where('id_materia', $id)->get();
        $presentes = Asistencia::where('id_materia', $id)->whereDate('fecha', today())->where('presente', true)->get()->keyBy('id_estudiante');

        return response()->json(['presentes' => $estudiantes->filter(fn (Estudiante $estudiante) => $presentes->has($estudiante->id_estudiante))->map(fn (Estudiante $estudiante) => ['nombre' => $estudiante->nombre, 'modalidad' => $estudiante->modalidad, 'dentro_del_rango' => $presentes[$estudiante->id_estudiante]->dentro_del_rango])->values(), 'ausentes' => $estudiantes->reject(fn (Estudiante $estudiante) => $presentes->has($estudiante->id_estudiante))->map(fn (Estudiante $estudiante) => ['nombre' => $estudiante->nombre, 'modalidad' => $estudiante->modalidad])->values()]);
    }

    private function datosReporte(Request $request, int $id, AsistenciaAcademica $academica): array|JsonResponse
    {
        $materia = $this->propia($request, $id);
        if (! $materia) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }
        $fechas = collect($academica->fechasDelPeriodo($materia))->filter(fn (string $fecha) => $fecha <= today()->toDateString())->values();
        $asistencias = Asistencia::where('id_materia', $id)->get()->keyBy(fn (Asistencia $registro) => $registro->id_estudiante.'|'.$registro->fecha->format('Y-m-d'));
        $estudiantes = Estudiante::where('id_materia', $id)->get()->map(function (Estudiante $estudiante) use ($fechas, $asistencias): array {
            $filas = $fechas->map(function (string $fecha) use ($estudiante, $asistencias): array {
                $registro = $asistencias->get($estudiante->id_estudiante.'|'.$fecha);

                return ['fecha' => $fecha, 'presente' => (bool) ($registro?->presente ?? false)];
            })->all();
            $faltas = collect($filas)->filter(fn (array $fila) => ! $fila['presente'])->count();

            return ['id_estudiante' => $estudiante->id_estudiante, 'nombre' => $estudiante->nombre, 'correo' => $estudiante->correo, 'numero_cuenta' => $estudiante->numero_cuenta, 'modalidad' => $estudiante->modalidad, 'notas' => $estudiante->notas ?: '', 'faltas' => $faltas, 'fechas' => $filas];
        });

        return ['materia' => $materia, 'fechas_columnas' => $fechas->all(), 'estudiantes' => $estudiantes->all()];
    }

    public function reporte(Request $request, int $id, AsistenciaAcademica $academica): JsonResponse
    {
        $datos = $this->datosReporte($request, $id, $academica);
        if ($datos instanceof JsonResponse) { return $datos; }
        unset($datos['materia']);

        return response()->json($datos);
    }

    public function corregir(Request $request, int $id): JsonResponse
    {
        if (! $this->propia($request, $id)) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }
        $datos = $request->validate(['id_estudiante' => ['required', 'integer', 'exists:estudiantes,id_estudiante'], 'fecha' => ['required', 'date'], 'presente' => ['required', 'boolean']]);
        if (! Estudiante::where('id_estudiante', $datos['id_estudiante'])->where('id_materia', $id)->exists()) { return response()->json(['detail' => 'Ese estudiante no pertenece a la materia.'], 403); }
        Asistencia::updateOrCreate(['id_estudiante' => $datos['id_estudiante'], 'id_materia' => $id, 'fecha' => $datos['fecha']], ['presente' => $datos['presente'], 'modalidad_usada' => null, 'dentro_del_rango' => null, 'editado_manualmente' => true]);

        return response()->json(['mensaje' => 'Asistencia corregida correctamente.']);
    }

    public function ajustarFaltas(Request $request, int $id, AsistenciaAcademica $academica): JsonResponse
    {
        $datos = $request->validate(['faltas' => ['required', 'integer', 'min:0']]);
        $estudiante = Estudiante::find($id);
        if (! $estudiante) { return response()->json(['detail' => 'Estudiante no encontrado.'], 404); }
        $materia = Materia::where('id_materia', $estudiante->id_materia)->where('id_usuario', $this->profesor($request))->first();
        if (! $materia) { return response()->json(['detail' => 'No tienes permiso.'], 403); }
        $fechas = collect($academica->fechasDelPeriodo($materia))->filter(fn (string $fecha) => $fecha <= today()->toDateString())->sortDesc();
        Asistencia::where('id_estudiante', $id)->where('id_materia', $estudiante->id_materia)->delete();
        foreach ($fechas as $indice => $fecha) {
            Asistencia::create(['id_estudiante' => $id, 'id_materia' => $estudiante->id_materia, 'fecha' => $fecha, 'presente' => $indice >= $datos['faltas'], 'editado_manualmente' => true]);
        }

        return response()->json(['mensaje' => 'Faltas ajustadas correctamente.']);
    }

    public function pdf(Request $request, int $id, AsistenciaAcademica $academica): Response|JsonResponse
    {
        $datos = $this->datosReporte($request, $id, $academica);
        if ($datos instanceof JsonResponse) { return $datos; }
        $pdf = Pdf::loadView('reporte', $datos)->setPaper('a4', 'landscape');

        return $pdf->download('asistencia_'.$datos['materia']->seccion.'.pdf');
    }
}
