<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Carbon;
use RuntimeException;

class TokenProfesor
{
    private function secreto(): string
    {
        $secreto = (string) config('services.asisgo.jwt_secret');

        if ($secreto === '') {
            throw new RuntimeException('JWT_SECRET no está configurado.');
        }

        return $secreto;
    }

    public function emitir(int $idUsuario, string $usuario): string
    {
        $ahora = Carbon::now()->timestamp;

        return JWT::encode([
            'id_usuario' => $idUsuario,
            'usuario' => $usuario,
            'iat' => $ahora,
            'exp' => $ahora + ((int) config('services.asisgo.jwt_ttl', 120) * 60),
        ], $this->secreto(), 'HS256');
    }

    public function validar(string $token): int
    {
        $datos = JWT::decode($token, new Key($this->secreto(), 'HS256'));
        $idUsuario = (int) ($datos->id_usuario ?? 0);

        if ($idUsuario < 1) {
            throw new RuntimeException('Esta credencial no contiene un ID de usuario válido.');
        }

        return $idUsuario;
    }
}
