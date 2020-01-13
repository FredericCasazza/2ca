<?php


namespace App\Specification\User;


use App\Entity\User;
use Tanigami\Specification\Specification;

/**
 * Class IsExpiredReinitTokenUserSpecification
 * @package App\Specification\Order
 */
class IsExpiredReinitTokenUserSpecification extends Specification
{

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function isSatisfiedBy($user): bool
    {
        return (new \DateTime() > $user->getReinitExpirationDate());
    }
}