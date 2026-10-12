<?php

use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\InterfazController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InterfazController::class, 'inicio']);
Route::get('/iniciar_sesion', [InterfazController::class, 'login']);
Route::get('/sign_up', [InterfazController::class, 'registro']);
Route::get('/interface', [InterfazController::class, 'interface']);
Route::get('/workspace', [InterfazController::class, 'workspace']);
Route::get('/inscripcion', [InscripcionController::class, 'formulario']);
Route::get('/inscribirse/{seccion}', [InscripcionController::class, 'formularioSeccion']);
Route::get('/marcar', [AsistenciaController::class, 'formulario']);
Route::get('/qr_pantalla/{id_materia}', [AsistenciaController::class, 'pantallaQr']);

Route::post('/login', [AutenticacionController::class, 'login']);
Route::post('/sign_up', [AutenticacionController::class, 'registro']);
Route::post('/inscribirse/{seccion}', [InscripcionController::class, 'crear']);
Route::post('/marcar_asistencia', [AsistenciaController::class, 'marcar']);
Route::get('/materia/{id_materia}/generar_qr_token_publico', [AsistenciaController::class, 'tokenPublico']);

Route::middleware('profesor')->group(function (): void {
    Route::post('/crear_materia', [MateriaController::class, 'crear']);
    Route::get('/mis_materias', [MateriaController::class, 'listar']);
    Route::delete('/materia/{id_materia}', [MateriaController::class, 'eliminar']);
    Route::get('/materia/{id_materia}/link_inscripcion', [MateriaController::class, 'linkInscripcion']);
    Route::post('/materia/{id_materia}/configurar_ubicacion', [MateriaController::class, 'configurarUbicacion']);
    Route::get('/materia/{id_materia}/generar_qr_token', [AsistenciaController::class, 'tokenPrivado']);
    Route::get('/materia/{id_materia}/clase_en_curso', [AsistenciaController::class, 'claseEnCurso']);
    Route::get('/materia/{id_materia}/asistencia_hoy', [AsistenciaController::class, 'hoy']);
    Route::get('/materia/{id_materia}/estudiantes', [EstudianteController::class, 'listar']);
    Route::get('/materia/{id_materia}/reporte_asistencia', [AsistenciaController::class, 'reporte']);
    Route::put('/materia/{id_materia}/corregir_asistencia', [AsistenciaController::class, 'corregir']);
    Route::put('/estudiante/{id_estudiante}/notas', [EstudianteController::class, 'notas']);
    Route::post('/materia/{id_materia}/agregar_estudiante', [EstudianteController::class, 'crear']);
    Route::put('/estudiante/{id_estudiante}', [EstudianteController::class, 'actualizar']);
    Route::delete('/estudiante/{id_estudiante}', [EstudianteController::class, 'eliminar']);
    Route::put('/estudiante/{id_estudiante}/ajustar_faltas', [AsistenciaController::class, 'ajustarFaltas']);
    Route::get('/materia/{id_materia}/reporte_pdf', [AsistenciaController::class, 'pdf']);
    Route::post('/materia/{id_materia}/publicar_grabacion', [MateriaController::class, 'publicarGrabacion']);
});
