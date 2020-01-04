<?php


namespace App\Subscriber;


use App\Event\CustomerRequest\CreateCustomerRequestEvent;
use App\Event\CustomerRequest\RemoveCustomerRequestEvent;
use App\Repository\CustomerRequestRepository;
use App\Repository\PeriodRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CustomerRequestSubscriber
 * @package App\Subscriber
 */
class CustomerRequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var PeriodRepository
     */
    private $customerRequestRepository;


    /**
     * CustomerRequestSubscriber constructor.
     * @param CustomerRequestRepository $customerRequestRepository
     */
    public function __construct(CustomerRequestRepository $customerRequestRepository)
    {
        $this->customerRequestRepository = $customerRequestRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateCustomerRequestEvent::class => [
                ['create', 20]
            ],
            RemoveCustomerRequestEvent::class => [
                ['remove', 20]
            ],
        ];
    }

    /**
     * @param CreateCustomerRequestEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateCustomerRequestEvent $event)
    {
        $customerRequest = $event->getCustomerRequest();
        $this->customerRequestRepository->create($customerRequest);
    }

    /**
     * @param RemoveCustomerRequestEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RemoveCustomerRequestEvent $event)
    {
        $customerRequest = $event->getCustomerRequest();
        $this->customerRequestRepository->remove($customerRequest);
    }

}