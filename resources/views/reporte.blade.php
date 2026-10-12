<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>{!! file_get_contents(public_path('css/reporte.css')) !!}</style>
</head>
<body>
    <h1>Reporte de Asistencia - {{ $materia->nombre }} ({{ $materia->seccion }})</h1>
    <p>Horario: {{ ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'][$materia->dia_semana ?? 0] }} {{ $materia->hora_inicio }}-{{ $materia->hora_fin }}</p>
    <table>
        <thead><tr><th>Estudiante</th>@foreach ($fechas_columnas as $fecha)<th>{{ $fecha }}</th>@endforeach<th>Faltas</th></tr></thead>
        <tbody>
            @foreach ($estudiantes as $estudiante)
                <tr><td>{{ $estudiante['nombre'] }}</td>@foreach ($estudiante['fechas'] as $fecha)<td>{{ $fecha['presente'] ? 'P' : 'F' }}</td>@endforeach<td>{{ $estudiante['faltas'] }}</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
