<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;
use Raketa\BackendTestTask\Manager\CartManager;
use Raketa\BackendTestTask\Manager\ProductManager;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private CartView        $cartView,
        private ProductManager  $productManager,
        private CartManager     $cartManager,
        private LoggerInterface $logger,
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        $response = new ResponseInterface();
        try {
            $cart = $this->cartManager->getCart();
            $this->productManager->addToCart($cart, $rawRequest['productUuid'], (int)$rawRequest['quantity']);
        } catch (ConnectorException|Exception $e) {
            $this->logger->error($e->getMessage());

            $response->getBody()->write(
                json_encode(
                    ['message' => $e->getMessage()],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(404);
        }

        $response->getBody()->write(
            json_encode(
                [
                    'status' => 'success',
                    'cart' => $this->cartView->getCart($cart)
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
