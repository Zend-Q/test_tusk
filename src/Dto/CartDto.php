<?php

namespace Raketa\BackendTestTask\Dto;

use Raketa\BackendTestTask\Domain\Cart;

final readonly class CartDto
{
    public function __construct(
        private Cart $cart,
        private array $items,
        private float $total,
    )
    {
    }

    public function getUuid():  string
    {
        return $this->cart->getUuid();
    }

    public function getCustomerId(): int
    {
        return $this->cart->getCustomer()->getId();
    }

    public function getCustomerName():  string
    {
        return implode(' ', [
            $this->cart->getCustomer()->getLastName(),
            $this->cart->getCustomer()->getFirstName(),
            $this->cart->getCustomer()->getMiddleName(),
        ]);
    }

    public function getCustomerEmail():  string
    {
        return $this->cart->getCustomer()->getEmail();
    }

    public function getPaymentMethod():  string
    {
        return $this->cart->getPaymentMethod();
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function asArray(): array
    {
        return [
            'uuid' => $this->getUuid(),
            'customer' => [
                'id' => $this->getCustomerId(),
                'name' => $this->getCustomerName(),
                'email' => $this->getCustomerEmail(),
            ],
            'payment_method' => $this->getPaymentMethod(),
            'items' => $this->getItems(),
            'total' => $this->getTotal(),
        ];
    }
}