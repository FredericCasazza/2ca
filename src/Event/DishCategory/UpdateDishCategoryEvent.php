<?php

namespace App\Event\DishCategory;

use App\Entity\DishCategory;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UpdateDishCategoryEvent
 * @package App\Event\Establishment
 */
class UpdateDishCategoryEvent extends Event
{

    /**
     * @var DishCategory
     */
    private $dishCategory;

    /**
     * UpdateDishCategoryEvent constructor.
     * @param DishCategory $dishCategory
     */
    public function __construct(DishCategory $dishCategory)
    {
        $this->dishCategory = $dishCategory;
    }

    /**
     * @return DishCategory
     */
    public function getDishCatgory(): DishCategory
    {
        return $this->dishCategory;
    }
}