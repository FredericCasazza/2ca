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
        if(
            !$meal->isPublished() ||
            $meal->getDate() < new \DateTime('today') ||
            $meal->getBookDateLimit() < new \DateTime('now')
        )
        {
            return false;
        }
        return true;
    }
}