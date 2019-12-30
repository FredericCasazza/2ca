<?php


namespace App\Event\Order;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OrderUserEvent
 * @package App\Event\Order
 */
class CreateOrderEvent extends Event
{

    /**
     * @var Order
     */
    private $Order;

    /**
     * OrderUserEvent constructor.
     * @param Order $Order
     */
    public function __construct(Order $Order)
    {
        $this->Order = $Order;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->Order;
    }
}