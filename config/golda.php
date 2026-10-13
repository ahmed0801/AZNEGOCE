<?php

// Les identifiants FTP GOLDA doivent être définis dans .env (jamais dans le code)
return [
    'ftp' => [
        'host'     => env('GOLDA_FTP_HOST', 'golda.fr'),
        'username' => env('GOLDA_FTP_USER'),
        'password' => env('GOLDA_FTP_PASS'),
        'ssl'      => filter_var(env('GOLDA_FTP_SSL', false), FILTER_VALIDATE_BOOLEAN),
        'port'     => (int) env('GOLDA_FTP_PORT', 21),
    ],
];