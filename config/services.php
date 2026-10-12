<?php

return [
    'asisgo' => [
        'jwt_secret' => env('JWT_SECRET', env('FIRMA_DEL_DIRECTOR', '')),
        'jwt_ttl' => (int) env('JWT_TTL', 120),
        'base_url' => env('BASE_URL', env('APP_URL', 'http://localhost')),
    ],
];
