<?php

return [
    'paths' => ['/api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_origins' => ['https://sistema-doacoes.up.railway.app'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => [''],
    'exposed_headers' => [],
    'max_age' => 0,
    'support_credentials' => false
];