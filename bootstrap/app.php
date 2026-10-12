<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->alias(['profesor' => App\Http\Middleware\AutenticarProfesor::class]);
        $middleware->validateCsrfTokens(except: [
            'login',
            'sign_up',
            'inscribirse/*',
            'marcar_asistencia',
            'crear_materia',
            'materia/*',
            'estudiante/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $exception, Request $request) {
            if ($request->expectsJson()) {
                $firstError = collect($exception->errors())->flatten()->first();

                return response()->json(['detail' => $firstError ?? 'Datos inválidos. Revisa el formulario.'], 422);
            }
        });
    })->create();
