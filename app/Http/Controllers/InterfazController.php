<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Response;

class InterfazController extends Controller
{
    public function inicio(): View { return view('pagina', ['pagina' => 'inicio']); }
    public function login(): View { return view('pagina', ['pagina' => 'login']); }
    public function registro(): View { return view('pagina', ['pagina' => 'registro']); }
    public function interface(): Response { return response()->view('pagina', ['pagina' => 'interface'])->withHeaders($this->noCache()); }
    public function workspace(): Response { return response()->view('pagina', ['pagina' => 'workspace'])->withHeaders($this->noCache()); }

    private function noCache(): array
    {
        return ['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0', 'Pragma' => 'no-cache', 'Expires' => '0'];
    }
}
