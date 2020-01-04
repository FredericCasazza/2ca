<?php


namespace App\Event\CustomerRequest;

use App\Entity\CustomerRequest;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class RemoveCustomerRequestEvent
 * @package App\Event\Dish
 */
class RemoveCustomerRequestEvent extends Event
{

    /**
     * @var CustomerRequest
     */
    private $customerRequest;

    /**
     * RemoveCustomerRequestEvent constructor.
     * @param CustomerRequest $customerRequest
     */
    public function __construct(CustomerRequest $customerRequest)
    {
        $this->customerRequest = $customerRequest;
    }

    /**
     * @return CustomerRequest
     */
    public function getCustomerRequest(): CustomerRequest
    {
        return $this->customerRequest;
    }
}