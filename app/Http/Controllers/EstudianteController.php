<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Materia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    private function profesor(Request $request): int { return (int) $request->attributes->get('id_profesor'); }

    private function esDeProfesor(Request $request, Estudiante $estudiante): bool
    {
        return Materia::where('id_materia', $estudiante->id_materia)->where('id_usuario', $this->profesor($request))->exists();
    }

    public function listar(Request $request, int $id): JsonResponse
    {
        if (! Materia::where('id_materia', $id)->where('id_usuario', $this->profesor($request))->exists()) {
            return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404);
        }
        $estudiantes = Estudiante::where('id_materia', $id)->get()->map(function (Estudiante $estudiante): array {
            return ['id_estudiante' => $estudiante->id_estudiante, 'nombre' => $estudiante->nombre, 'correo' => $estudiante->correo, 'telefono' => $estudiante->telefono, 'numero_cuenta' => $estudiante->numero_cuenta, 'modalidad' => $estudiante->modalidad, 'faltas' => Asistencia::where('id_estudiante', $estudiante->id_estudiante)->where('presente', false)->count(), 'notas' => $estudiante->notas ?: ''];
        });

        return response()->json($estudiantes);
    }

    public function crear(Request $request, int $id): JsonResponse
    {
        if (! Materia::where('id_materia', $id)->where('id_usuario', $this->profesor($request))->exists()) {
            return response()->json(['detail' => 'Esa materia no existe o no te pertenece.'], 404);
        }
        $datos = $this->validarEstudiante($request);
        if (Estudiante::where('id_materia', $id)->where('numero_cuenta', $datos['numero_cuenta'])->exists()) {
            return response()->json(['detail' => "El numero de cuenta {$datos['numero_cuenta']} ya esta registrado en esta clase."], 409);
        }
        Estudiante::create([...$datos, 'id_materia' => $id]);

        return response()->json(['mensaje' => "{$datos['nombre']} agregado correctamente."]);
    }

    public function actualizar(Request $request, int $id): JsonResponse
    {
        $estudiante = Estudiante::find($id);
        if (! $estudiante) { return response()->json(['detail' => 'Ese estudiante no existe.'], 404); }
        if (! $this->esDeProfesor($request, $estudiante)) { return response()->json(['detail' => 'Ese estudiante no pertenece a una de tus materias.'], 403); }
        $datos = $this->validarEstudiante($request);
        $estudiante->update($datos);

        return response()->json(['mensaje' => "{$datos['nombre']} actualizado correctamente."]);
    }

    public function eliminar(Request $request, int $id): JsonResponse
    {
        $estudiante = Estudiante::find($id);
        if (! $estudiante) { return response()->json(['detail' => 'Ese estudiante no existe.'], 404); }
        if (! $this->esDeProfesor($request, $estudiante)) { return response()->json(['detail' => 'Ese estudiante no pertenece a una de tus materias.'], 403); }
        Asistencia::where('id_estudiante', $id)->delete();
        $estudiante->delete();

        return response()->json(['mensaje' => 'Estudiante eliminado del curso.']);
    }

    public function notas(Request $request, int $id): JsonResponse
    {
        $estudiante = Estudiante::find($id);
        if (! $estudiante) { return response()->json(['detail' => 'Ese estudiante no existe.'], 404); }
        if (! $this->esDeProfesor($request, $estudiante)) { return response()->json(['detail' => 'Ese estudiante no pertenece a una de tus materias.'], 403); }
        $datos = $request->validate(['notas' => ['present', 'string', 'max:500']]);
        $estudiante->update($datos);

        return response()->json(['mensaje' => 'Nota guardada correctamente.']);
    }

    private function validarEstudiante(Request $request): array
    {
        $datos = $request->validate(['nombre' => ['required', 'string', 'min:2', 'max:100'], 'telefono' => ['required', 'string', 'min:7', 'max:20'], 'correo' => ['required', 'email', 'max:254'], 'numero_cuenta' => ['required', 'string', 'min:4', 'max:20'], 'modalidad' => ['required', 'string', 'min:6', 'max:15']]);
        $datos['modalidad'] = mb_strtolower(trim($datos['modalidad']));
        if (! in_array($datos['modalidad'], ['presencial', 'virtual'], true)) {
            abort(response()->json(['detail' => "Modalidad debe ser 'presencial' o 'virtual'."], 400));
        }

        return $datos;
    }
}
