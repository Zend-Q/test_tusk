<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Manager;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;

class CartManager extends ConnectorFacade
{
    public function __construct($host, $port, $password)
    {
        parent::__construct($host, $port, $password, 1);
        parent::build();
    }

    public function saveCart(Cart $cart): void
    {
        $this->connector->set(session_id(), $cart);
    }

    /**
     * @throws ConnectorException
     */
    public function getCart(): ?Cart
    {
        return $this->connector->get(session_id());
    }
}
