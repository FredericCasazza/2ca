<?php


namespace App\Helper;


use App\Entity\Meal;
use App\Specification\Meal\IsOverBookingDeadlineMealSpecification;

class MealHelper
{

    /**
     * @param Meal $meal
     * @return bool
     * @throws \Exception
     */
    public function isOverBookingDeadline(Meal $meal)
    {
        $isOverBookingDeadlineMealSpecification = new IsOverBookingDeadlineMealSpecification();
        return $isOverBookingDeadlineMealSpecification->isSatisfiedBy($meal);
    }
}