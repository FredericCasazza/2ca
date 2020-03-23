<?php


namespace App\Helper;


use App\Entity\Order;
use App\Specification\Order\IsAuthorizedUserOrderSpecification;
use App\Specification\Order\IsExpiredOrderSpecification;
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
     * @var IsExpiredOrderSpecification
     */
    private $isExpiredOrderSpecification;

    /**
     * OrderHelper constructor.
     * @param IsValidateOrderSpecification $isValidateOrderSpecification
     * @param IsAuthorizedUserOrderSpecification $isAuthorizedUserOrderSpecification
     * @param IsExpiredOrderSpecification $isExpiredOrderSpecification
     */
    public function __construct(
        IsValidateOrderSpecification $isValidateOrderSpecification,
        IsAuthorizedUserOrderSpecification $isAuthorizedUserOrderSpecification,
        IsExpiredOrderSpecification $isExpiredOrderSpecification
    )
    {
        $this->isValidateOrderSpecification = $isValidateOrderSpecification;
        $this->isAuthorizedUserOrderSpecification = $isAuthorizedUserOrderSpecification;
        $this->isExpiredOrderSpecification = $isExpiredOrderSpecification;
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

    /**
     * @param $order
     * @return bool
     * @throws \Exception
     */
    public function isExpiredOrderSpecification($order)
    {
        return $this->isExpiredOrderSpecification->isSatisfiedBy($order);
    }
}