<?php

declare(strict_types=1);

use App\Interface\Controllers\CheckoutController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/checkout', [CheckoutController::class, 'process']);
        $group->get('/payment-methods', [CheckoutController::class, 'getPaymentMethods']);
    });
};
