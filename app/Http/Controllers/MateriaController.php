<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Grabacion;
use App\Models\Materia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MateriaController extends Controller
{
    private function profesor(Request $request): int { return (int) $request->attributes->get('id_profesor'); }

    private function propia(Request $request, int $id): ?Materia
    {
        return Materia::where('id_materia', $id)->where('id_usuario', $this->profesor($request))->first();
    }

    public function crear(Request $request): JsonResponse
    {
        $datos = $request->validate(['nombre' => ['required', 'string', 'min:3', 'max:50'], 'seccion' => ['required', 'string', 'min:2', 'max:20'], 'dia_semana' => ['required', 'integer', 'between:0,6'], 'hora_inicio' => ['required', 'date_format:H:i'], 'hora_fin' => ['required', 'date_format:H:i'], 'fecha_inicio_periodo' => ['required', 'date'], 'semanas_duracion' => ['sometimes', 'integer', 'between:1,52']]);
        if (Materia::where('seccion', $datos['seccion'])->exists()) {
            return response()->json(['detail' => "La seccion {$datos['seccion']} ya esta registrada."], 400);
        }
        Materia::create([...$datos, 'id_usuario' => $this->profesor($request)]);

        return response()->json(['mensaje' => "{$datos['nombre']} ha sido creada con exito!"]);
    }

    public function listar(Request $request): JsonResponse
    {
        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $resultado = Materia::where('id_usuario', $this->profesor($request))->get()->map(function (Materia $materia) use ($dias): array {
            return ['id_materia' => $materia->id_materia, 'nombre' => $materia->nombre, 'seccion' => $materia->seccion, 'horario_texto' => $materia->dia_semana !== null ? $dias[$materia->dia_semana].' '.$materia->hora_inicio.'-'.$materia->hora_fin : 'Horario sin configurar', 'dia_semana' => $materia->dia_semana, 'hora_inicio' => $materia->hora_inicio, 'hora_fin' => $materia->hora_fin, 'fecha_inicio_periodo' => $materia->fecha_inicio_periodo?->format('Y-m-d'), 'semanas_duracion' => $materia->semanas_duracion];
        });

        return response()->json($resultado);
    }

    public function eliminar(Request $request, int $id): JsonResponse
    {
        $materia = $this->propia($request, $id);
        if (! $materia) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }
        DB::transaction(function () use ($materia, $id): void {
            $estudiantes = Estudiante::where('id_materia', $id)->pluck('id_estudiante');
            Asistencia::whereIn('id_estudiante', $estudiantes)->delete();
            Estudiante::where('id_materia', $id)->delete();
            Grabacion::where('id_materia', $id)->delete();
            $materia->delete();
        });

        return response()->json(['mensaje' => 'Clase eliminada correctamente.']);
    }

    public function linkInscripcion(Request $request, int $id): JsonResponse
    {
        $materia = $this->propia($request, $id);
        if (! $materia) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }

        return response()->json(['seccion' => $materia->seccion, 'ruta' => '/inscribirse/'.$materia->seccion]);
    }

    public function configurarUbicacion(Request $request, int $id): JsonResponse
    {
        $datos = $request->validate(['lat' => ['required', 'numeric', 'between:-90,90'], 'lng' => ['required', 'numeric', 'between:-180,180']]);
        $materia = $this->propia($request, $id);
        if (! $materia) { return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404); }
        $materia->update(['lat_aula' => (string) $datos['lat'], 'lng_aula' => (string) $datos['lng']]);

        return response()->json(['mensaje' => 'Ubicacion del aula guardada correctamente.']);
    }

    public function publicarGrabacion(Request $request, int $id): JsonResponse
    {
        $datos = $request->validate(['url' => ['required', 'url', 'max:300']]);
        $materia = $this->propia($request, $id);
        if (! $materia) { return response()->json(['detail' => 'Esa materia no te pertenece.'], 403); }
        Grabacion::create(['id_materia' => $id, 'url_video' => $datos['url'], 'fecha_publicacion' => now()]);
        $correos = Estudiante::where('id_materia', $id)->pluck('correo');
        foreach ($correos as $correo) {
            Mail::html('<p>Hola, la grabación de la clase de <strong>'.e($materia->nombre).'</strong> ya está disponible.</p><p><a href="'.e($datos['url']).'">Ver grabación</a></p><p>Saludos,<br>Asis GO+</p>', function ($mensaje) use ($correo, $materia): void {
                $mensaje->to($correo)->subject('Grabación disponible: '.$materia->nombre);
            });
        }

        return response()->json(['mensaje' => 'Grabacion publicada. Enviando correo a '.$correos->count().' estudiante(s).']);
    }
}
