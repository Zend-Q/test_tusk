<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Dto\CartDto;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class CartView
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function getCart(Cart $cart): CartDto
    {
        $totalSum = 0;
        $items = [];

        /** @var CartItem $item */
        foreach ($cart->getItems() as $item) {
            $total = 0;
            $total += $item->getPrice() * $item->getQuantity();
            $product = $this->productRepository->getByUuid($item->getProductUuid());

            $items[] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'total' => $total,
                'quantity' => $item->getQuantity(),
                'product' => $product->asArray()
            ];

            $totalSum += $total;
        }

        return new CartDto(
            $cart,
            $items,
            $totalSum
        );
    }
}
