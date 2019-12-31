<?php


namespace App\Event\Period;

use App\Entity\Order;
use App\Entity\Period;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UpdatePeriodEvent
 * @package App\Event\Order
 */
class UpdatePeriodEvent extends Event
{

    /**
     * @var Order
     */
    private $period;

    /**
     * UpdatePeriodEvent constructor.
     * @param Period $period
     */
    public function __construct(Period $period)
    {
        $this->period = $period;
    }

    /**
     * @return Order
     */
    public function getPeriod(): Period
    {
        return $this->period;
    }
}