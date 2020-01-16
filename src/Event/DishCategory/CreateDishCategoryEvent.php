<?php


namespace App\Event\DishCategory;

use App\Entity\DishCategory;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class CreateDishCategoryEvent
 * @package App\Event\Establishment
 */
class CreateDishCategoryEvent extends Event
{

    /**
     * @var DishCategory
     */
    private $dishCategory;

    /**
     * CreateDishCategoryEvent constructor.
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