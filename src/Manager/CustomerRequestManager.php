<?php


namespace App\Manager;

use App\Entity\CustomerRequest;
use App\Entity\User;
use App\Event\CustomerRequest\CreateCustomerRequestEvent;
use App\Event\CustomerRequest\RemoveCustomerRequestEvent;
use App\Event\CustomerRequest\UpdateCustomerRequestEvent;
use App\Repository\CustomerRequestRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CustomerRequestManager
 * @package App\Manager
 */
class CustomerRequestManager extends AbstractManager
{
    /**
     * @var CustomerRequestRepository
     */
    private $customerRequestRepository;

    /**
     * CustomerRequestManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param CustomerRequestRepository $customerRequestRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CustomerRequestRepository $customerRequestRepository
    )
    {
        $this->customerRequestRepository = $customerRequestRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return CustomerRequest|null
     */
    public function find($id)
    {
        return $this->customerRequestRepository->find($id);
    }

    /**
     * @param User $user
     * @return CustomerRequest|null
     */
    public function findByUser(User $user)
    {
        return $this->customerRequestRepository->findOneBy([
            'user' => $user
        ]);

    }

    /**
     * @param CustomerRequest $customerRequest
     * @throws \Exception
     */
    public function create(CustomerRequest $customerRequest)
    {
        $customerRequest->setCreationDate(new \DateTime());
        $event = new CreateCustomerRequestEvent($customerRequest);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param CustomerRequest $customerRequest
     */
    public function remove(CustomerRequest $customerRequest)
    {
        $event = new RemoveCustomerRequestEvent($customerRequest);
        $this->eventDispatcher->dispatch($event);
    }
}