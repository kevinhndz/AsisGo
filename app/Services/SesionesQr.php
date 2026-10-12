<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SesionesQr
{
    private function llave(int $idMateria): string
    {
        return 'asisgo:qr:'.$idMateria;
    }

    public function generar(int $idMateria): array
    {
        $ahora = now()->timestamp;
        $sesiones = collect(Cache::get($this->llave($idMateria), []))
            ->filter(fn (array $sesion): bool => $sesion['expira'] > $ahora)
            ->values();
        $sesiones->push(['token' => Str::random(32), 'expira' => $ahora + 60]);
        Cache::put($this->llave($idMateria), $sesiones->all(), now()->addMinutes(5));

        return ['token' => $sesiones->last()['token'], 'expira_en_segundos' => 20];
    }

    public function valido(int $idMateria, string $token): bool
    {
        return collect(Cache::get($this->llave($idMateria), []))
            ->contains(fn (array $sesion): bool => hash_equals($sesion['token'], $token) && $sesion['expira'] > now()->timestamp);
    }

    public function iniciarClase(int $idMateria): void
    {
        Cache::put('asisgo:clase:'.$idMateria, now()->timestamp, now()->addHours(3));
    }

    public function claseEnCurso(int $idMateria): bool
    {
        return Cache::has('asisgo:clase:'.$idMateria);
    }
}
