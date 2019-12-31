<?php


namespace App\Event\Period;

use App\Entity\Period;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EnablePeriodEvent
 * @package App\Event\Meal
 */
class EnablePeriodEvent extends Event
{

    /**
     * @var Period
     */
    private $period;

    /**
     * EnablePeriodEvent constructor.
     * @param Period $period
     */
    public function __construct(Period $period)
    {
        $this->period = $period;
    }

    /**
     * @return Period
     */
    public function getPeriod(): Period
    {
        return $this->period;
    }
}