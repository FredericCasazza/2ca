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
    private $Order;

    /**
     * UpdateOrderEvent constructor.
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