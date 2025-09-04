<?php

declare(strict_types=1);

namespace App\Interface\Controllers;

use App\Application\Services\CheckoutService;
use App\Interface\Requests\CheckoutRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use InvalidArgumentException;

class CheckoutController
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {
    }

    public function process(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        try {
            $checkoutRequest = new CheckoutRequest($request);
            
            $result = $this->checkoutService->processCheckout(
                $checkoutRequest->getItems(),
                $checkoutRequest->getPaymentStrategy(),
                $checkoutRequest->getInstallments()
            );

            $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (InvalidArgumentException $e) {
            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error, JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);

        } catch (\Exception $e) {
            $error = ['error' => 'Erro interno do servidor'];
            $response->getBody()->write(json_encode($error, JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public function getPaymentMethods(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        try {
            $paymentMethods = $this->checkoutService->getAvailablePaymentMethods();
            
            $response->getBody()->write(json_encode($paymentMethods, JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Exception $e) {
            $error = ['error' => 'Erro interno do servidor'];
            $response->getBody()->write(json_encode($error, JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
