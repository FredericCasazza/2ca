<?php


namespace App\Specification\Order;


use App\Entity\Order;
use Tanigami\Specification\Specification;

class IsValidateOrderSpecification extends Specification
{

    /**
     * IsValidateOrderSpecification constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy($order): bool
    {
        return !empty($order->getValidationDate());
    }
}