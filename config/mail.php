<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),
    'mailers' => [
        'smtp' => ['transport' => 'smtp', 'scheme' => env('MAIL_SCHEME'), 'url' => env('MAIL_URL'), 'host' => env('MAIL_HOST', 'smtp.gmail.com'), 'port' => env('MAIL_PORT', 587), 'username' => env('MAIL_USERNAME', env('EMAIL_USER')), 'password' => env('MAIL_PASSWORD', env('EMAIL_PASSWORD')), 'timeout' => null, 'local_domain' => env('MAIL_EHLO_DOMAIN')],
        'log' => ['transport' => 'log', 'channel' => env('MAIL_LOG_CHANNEL')],
        'array' => ['transport' => 'array'],
    ],
    'from' => ['address' => env('MAIL_FROM_ADDRESS', env('EMAIL_USER', 'hello@example.com')), 'name' => env('MAIL_FROM_NAME', 'AsisGo')],
];
