<?php

namespace App\Services;

use App\Models\Materia;
use Carbon\CarbonImmutable;

class AsistenciaAcademica
{
    public function fechasDelPeriodo(Materia $materia): array
    {
        if ($materia->dia_semana === null || $materia->fecha_inicio_periodo === null) {
            return [];
        }

        $inicio = CarbonImmutable::parse($materia->fecha_inicio_periodo);
        $diaObjetivo = ((int) $materia->dia_semana + 1) % 7;
        $diferencia = ($diaObjetivo - $inicio->dayOfWeek + 7) % 7;
        $primera = $inicio->addDays($diferencia);
        $semanas = (int) ($materia->semanas_duracion ?: 11);

        return collect(range(0, max(0, $semanas - 1)))
            ->map(fn (int $semana) => $primera->addWeeks($semana)->toDateString())
            ->all();
    }

    public function distanciaMetros(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
