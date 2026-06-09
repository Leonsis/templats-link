<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuração de Email para XAMPP
    |--------------------------------------------------------------------------
    |
    | Esta configuração é específica para ambiente XAMPP local.
    | Para produção, configure um servidor SMTP real.
    |
    */

    'driver' => 'log', // Use 'log' para desenvolvimento, 'smtp' para produção
    
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => env('MAIL_USERNAME', ''),
        'password' => env('MAIL_PASSWORD', ''),
    ],
    
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@templats-link.com'),
        'name' => env('MAIL_FROM_NAME', 'Templats Link'),
    ],
];
