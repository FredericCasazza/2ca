<?php


namespace App\Specification\Meal;


use App\Entity\Meal;
use Tanigami\Specification\Specification;

/**
 * Class IsOverBookingDeadlineMealSpecification
 * @package App\Specification\Meal
 */
class IsOverBookingDeadlineMealSpecification extends Specification
{

    /**
     * @param Meal $meal
     * @return bool
     * @throws \Exception
     */
    public function isSatisfiedBy($meal): bool
    {
        if(
            $meal->getBookDateLimit() < new \DateTime('now')
        )
        {
            return false;
        }
        return true;
    }

}