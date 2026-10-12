<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use App\Services\TokenProfesor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AutenticacionController extends Controller
{
    public function login(Request $request, TokenProfesor $tokens): JsonResponse
    {
        $datos = $request->validate(['usuario' => ['required', 'string', 'min:4', 'max:50'], 'contrasena' => ['required', 'string', 'min:8', 'max:72']]);
        $usuario = Usuario::where('usuario', $datos['usuario'])->first();

        if (! $usuario || ! Hash::check($datos['contrasena'], $usuario->contrasena)) {
            return response()->json(['detail' => $usuario ? 'Contrasena incorrecta!' : 'Usuario no encontrado'], 401);
        }

        return response()->json(['mensaje' => 'Bienvenido', 'token' => $tokens->emitir($usuario->id_usuario, $usuario->usuario), 'token_type' => 'bearer']);
    }

    public function registro(Request $request, TokenProfesor $tokens): JsonResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:100'],
            'telefono' => ['required', 'string', 'min:7', 'max:20'],
            'correo' => ['required', 'email', 'max:150', Rule::unique('clientes', 'correo')],
            'usuario' => ['required', 'string', 'min:4', 'max:50'],
            'contrasena' => ['required', 'string', 'min:8', 'max:72'],
        ]);

        if (Usuario::where('usuario', $datos['usuario'])->exists()) {
            return response()->json(['detail' => "Usuario {$datos['usuario']} ya esta siendo utilizado."], 409);
        }

        $usuario = DB::transaction(function () use ($datos): Usuario {
            $usuarioNuevo = Usuario::create(['usuario' => $datos['usuario'], 'contrasena' => Hash::make($datos['contrasena'])]);
            Cliente::create(['nombre' => $datos['nombre'], 'telefono' => $datos['telefono'], 'correo' => $datos['correo'], 'id_usuario' => $usuarioNuevo->id_usuario]);

            return $usuarioNuevo;
        });

        return response()->json(['mensaje' => "Bienvenido {$usuario->usuario}!", 'token' => $tokens->emitir($usuario->id_usuario, $usuario->usuario), 'token_type' => 'bearer']);
    }
}
