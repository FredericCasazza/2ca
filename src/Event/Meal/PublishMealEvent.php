<?php


namespace App\Event\Meal;

use App\Entity\Meal;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class PublishMealEvent
 * @package App\Event\Meal
 */
class PublishMealEvent extends Event
{

    /**
     * @var Meal
     */
    private $meal;

    /**
     * PublishMealEvent constructor.
     * @param Meal $meal
     */
    public function __construct(Meal $meal)
    {
        $this->meal =$meal;
    }

    /**
     * @return Meal
     */
    public function getMeal(): Meal
    {
        return $this->meal;
    }
}