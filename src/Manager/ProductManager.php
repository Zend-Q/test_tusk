<?php

namespace Raketa\BackendTestTask\Manager;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\Service\Uuid;

final readonly class ProductManager
{
    public function __construct(
        private ProductRepository $productRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function addToCart(Cart $cart, string $productUuid, int $quantity): Cart
    {
        $product = $this->productRepository->getByUuid($productUuid);

        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $quantity,
        ));

        return $cart;
    }
}