<?php


namespace App\Helper;


use App\Entity\Order;
use App\Specification\Order\IsAuthorizedUserOrderSpecification;
use App\Specification\Order\IsValidateOrderSpecification;

/**
 * Class OrderHelper
 * @package App\Helper
 */
class OrderHelper
{
    /**
     * @var IsValidateOrderSpecification
     */
    private $isValidateOrderSpecification;

    /**
     * @var IsAuthorizedUserOrderSpecification
     */
    private $isAuthorizedUserOrderSpecification;

    /**
     * OrderHelper constructor.
     * @param IsValidateOrderSpecification $isValidateOrderSpecification
     * @param IsAuthorizedUserOrderSpecification $isAuthorizedUserOrderSpecification
     */
    public function __construct(
        IsValidateOrderSpecification $isValidateOrderSpecification,
        IsAuthorizedUserOrderSpecification $isAuthorizedUserOrderSpecification
    )
    {
        $this->isValidateOrderSpecification = $isValidateOrderSpecification;
        $this->isAuthorizedUserOrderSpecification = $isAuthorizedUserOrderSpecification;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isValidate(Order $order)
    {
        return $this->isValidateOrderSpecification->isSatisfiedBy($order);
    }

    /**
     * @param $order
     * @return bool
     */
    public function isAuthorizedUser($order)
    {
        return $this->isAuthorizedUserOrderSpecification->isSatisfiedBy($order);
    }
}