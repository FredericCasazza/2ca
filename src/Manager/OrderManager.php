<?php


namespace App\Manager;

use App\Entity\Meal;
use App\Entity\Order;
use App\Entity\User;
use App\Event\Dish\RemoveDishEvent;
use App\Event\Order\CreateOrderEvent;
use App\Event\Order\UpdateOrderEvent;
use App\Repository\OrderRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderManager
 * @package App\Manager
 */
class OrderManager extends AbstractManager
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * OrderManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        OrderRepository $orderRepository
    )
    {
        $this->orderRepository = $orderRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return Order|null
     */
    public function find($id)
    {
        return $this->orderRepository->find($id);
    }

    /**
     * @param User $user
     * @param Meal $meal
     * @return Order|null
     */
    public function findOneByUserAndMeal(User $user, Meal $meal)
    {
        return $this->orderRepository->findOneByUserAndMeal($user, $meal);
    }

    /**
     * @param Order $order
     * @throws \Exception
     */
    public function create(Order $order)
    {
        $current = new \DateTime();
        $order->setCreationDate($current)
            ->setModificationDate($current);
        $event = new CreateOrderEvent($order);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Order $order
     * @throws \Exception
     */
    public function update(Order $order)
    {
        $order->setModificationDate(new \DateTime());
        $event = new UpdateOrderEvent($order);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Order $order
     */
    public function remove(Order $order)
    {
        $event = new RemoveDishEvent($order);
        $this->eventDispatcher->dispatch($event);
    }

}