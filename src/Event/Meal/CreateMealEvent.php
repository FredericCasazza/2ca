<?php


namespace App\Event\Meal;

use App\Entity\Meal;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class CreateMealEvent
 * @package App\Event\User
 */
class CreateMealEvent extends Event
{

    /**
     * @var Meal
     */
    private $meal;

    /**
     * CreateMealEvent constructor.
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