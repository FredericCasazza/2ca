<?php


namespace App\Specification\Meal;


use App\Entity\Meal;
use Tanigami\Specification\Specification;

/**
 * Class IsBookableMealSpecification
 * @package App\Specification\Meal
 */
class IsBookableMealSpecification extends Specification
{

    /**
     * @param Meal $meal
     * @return bool
     * @throws \Exception
     */
    public function isSatisfiedBy($meal): bool
    {
        $today = new \DateTime('today');
        if(
            !$meal->isPublished() ||
            $meal->getDate() < $today
        )
        {
            return false;
        }
        return true;
    }
}