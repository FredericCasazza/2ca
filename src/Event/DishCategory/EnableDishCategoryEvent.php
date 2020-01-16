<?php


namespace App\Event\DishCategory;

use App\Entity\DishCategory;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EnableDishCategoryEvent
 * @package App\Event\Establishment
 */
class EnableDishCategoryEvent extends Event
{

    /**
     * @var DishCategory
     */
    private $dishCategory;

    /**
     * EnableDishCategoryEvent constructor.
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