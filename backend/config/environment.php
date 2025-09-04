<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Carrinho de Compras API',
        'version' => '1.0.0',
        'debug' => true,
        'url' => 'http://localhost:8000',
    ],
    'database' => [
        'driver' => 'sqlite',
        'database' => __DIR__ . '/../database/database.sqlite',
    ],
];
