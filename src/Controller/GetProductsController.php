<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\View\ProductsView;
use Throwable;

readonly class GetProductsController
{
    public function __construct(
        private ProductsView $productsView,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new ResponseInterface();

        $rawRequest = json_decode($request->getBody()->getContents(), true);

        try {
            $products = $this->productsView->getProducts($rawRequest['category']);
        } catch (Throwable $e) {
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
                $products,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
