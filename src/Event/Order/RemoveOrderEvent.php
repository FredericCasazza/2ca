<?php


namespace App\Event\Order;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class RemoveOrderEvent
 * @package App\Event\Order
 */
class RemoveOrderEvent extends Event
{

    /**
     * @var Order
     */
    private $Order;

    /**
     * RemoveOrderEvent constructor.
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