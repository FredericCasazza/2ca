<?php


namespace App\Manager;

use App\Entity\Establishment;
use App\Entity\Meal;
use App\Entity\Order;
use App\Entity\Period;
use App\Entity\User;
use App\Event\Dish\RemoveDishEvent;
use App\Event\Order\CreateOrderEvent;
use App\Event\Order\RemoveOrderEvent;
use App\Event\Order\UpdateOrderEvent;
use App\Event\Order\ValidateOrderEvent;
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
     * @return Order[]
     */
    public function findAll()
    {
        return $this->orderRepository->findAll();
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
     * @param \DateTimeInterface $date
     * @param Period $period
     * @param Establishment $establishment
     * @return array
     */
    public function findValidByDatePeriodAndEstablishment(\DateTimeInterface $date, Period $period, Establishment $establishment)
    {
        return $this->orderRepository->findValidByDatePeriodAndEstablishment($date, $period, $establishment);
    }


    /**
     * @param Order $order
     * @throws \Exception
     */
    public function validate(Order $order)
    {
        $order->setValidationDate(new \DateTime());
        $event = new ValidateOrderEvent($order);
        $this->eventDispatcher->dispatch($event);
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
        $event = new RemoveOrderEvent($order);
        $this->eventDispatcher->dispatch($event);
    }

}