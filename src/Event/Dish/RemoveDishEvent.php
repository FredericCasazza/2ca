<?php


namespace App\Event\Dish;

use App\Entity\Dish;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class RemoveDishEvent
 * @package App\Event\Dish
 */
class RemoveDishEvent extends Event
{

    /**
     * @var Dish
     */
    private $dish;

    /**
     * RemoveDishEvent constructor.
     * @param Dish $dish
     */
    public function __construct(Dish $dish)
    {
        $this->dish = $dish;
    }

    /**
     * @return Dish
     */
    public function getDish(): Dish
    {
        return $this->dish;
    }
}