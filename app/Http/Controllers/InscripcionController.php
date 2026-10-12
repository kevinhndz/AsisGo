<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Materia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscripcionController extends Controller
{
    public function formulario(): View { return view('pagina', ['pagina' => 'inscripcion', 'seccion' => '']); }
    public function formularioSeccion(string $seccion): View { return view('pagina', ['pagina' => 'inscripcion', 'seccion' => $seccion]); }

    public function crear(Request $request, string $seccion): JsonResponse
    {
        $datos = $request->validate(['nombre' => ['required', 'string', 'min:2', 'max:100'], 'telefono' => ['required', 'string', 'min:7', 'max:20'], 'correo' => ['required', 'email', 'max:254'], 'numero_cuenta' => ['required', 'string', 'min:4', 'max:20'], 'modalidad' => ['required', 'string', 'min:6', 'max:15']]);
        $materia = Materia::where('seccion', $seccion)->first();

        if (! $materia) {
            return response()->json(['detail' => "La seccion {$seccion} no existe."], 404);
        }

        if (Estudiante::where('numero_cuenta', $datos['numero_cuenta'])->where('id_materia', $materia->id_materia)->exists()) {
            return response()->json(['detail' => "El numero de cuenta {$datos['numero_cuenta']} ya esta registrado en esta clase."], 409);
        }

        $modalidad = mb_strtolower(trim($datos['modalidad']));
        if (! in_array($modalidad, ['presencial', 'virtual'], true)) {
            return response()->json(['detail' => "Modalidad debe ser 'presencial' o 'virtual'."], 400);
        }

        Estudiante::create([...$datos, 'modalidad' => $modalidad, 'id_materia' => $materia->id_materia]);

        return response()->json(['mensaje' => "Listo {$datos['nombre']}! Quedaste inscrito en {$materia->nombre}."]);
    }
}
