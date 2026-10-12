<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('asisgo:status', function (): void {
    $this->info('AsisGo Laravel está listo.');
})->purpose('Comprueba que la consola de AsisGo responde');
