<?php


namespace App\Subscriber;


use App\Event\Order\CreateOrderEvent;
use App\Event\Order\RemoveOrderEvent;
use App\Event\Order\UpdateOrderEvent;
use App\Event\Order\ValidateOrderEvent;
use App\Repository\OrderRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OrderSubscriber
 * @package App\Subscriber
 */
class OrderSubscriber implements EventSubscriberInterface
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * OrderSubscriber constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateOrderEvent::class => [
                ['create', 20]
            ],
            UpdateOrderEvent::class => [
                ['update', 20]
            ],
            RemoveOrderEvent::class => [
                ['remove', 20]
            ],
            ValidateOrderEvent::class => [
                ['validate', 20]
            ]
        ];
    }

    /**
     * @param CreateOrderEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateOrderEvent $event)
    {
        $order = $event->getOrder();
        $this->orderRepository->create($order);
    }

    /**
     * @param UpdateOrderEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateOrderEvent $event)
    {
        $order = $event->getOrder();
        $this->orderRepository->update($order);
    }

    /**
     * @param RemoveOrderEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RemoveOrderEvent $event)
    {
        $order = $event->getOrder();
        $this->orderRepository->remove($order);
    }

    /**
     * @param ValidateOrderEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function validate(ValidateOrderEvent $event)
    {
        $order = $event->getOrder();
        $this->orderRepository->update($order);
    }

}