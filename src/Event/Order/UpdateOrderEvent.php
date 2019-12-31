<?php


namespace App\Event\Order;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UpdateOrderEvent
 * @package App\Event\Order
 */
class UpdateOrderEvent extends Event
{

    /**
     * @var Order
     */
    private $order;

    /**
     * UpdateOrderEvent constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}