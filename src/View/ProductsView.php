<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class ProductsView
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function getProducts(string $category): array
    {
        return array_map(
            fn (Product $product) => $product->asArray(),
            $this->productRepository->getByCategory($category)
        );
    }
}
