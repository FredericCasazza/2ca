<?php


namespace App\Subscriber;


use App\Event\Dish\CreateDishEvent;
use App\Event\Dish\RemoveDishEvent;
use App\Event\Dish\UpdateDishEvent;
use App\Repository\DishRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DishSubscriber
 * @package App\Subscriber
 */
class DishSubscriber implements EventSubscriberInterface
{
    /**
     * @var DishRepository
     */
    private $dishRepository;

    /**
     * DishSubscriber constructor.
     * @param DishRepository $dishRepository
     */
    public function __construct(DishRepository $dishRepository)
    {
        $this->dishRepository = $dishRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateDishEvent::class => [
                ['create', 20]
            ],
            UpdateDishEvent::class => [
                ['update', 20]
            ],
            RemoveDishEvent::class => [
                ['remove', 20]
            ],
        ];
    }

    /**
     * @param CreateDishEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateDishEvent $event)
    {
        $dish = $event->getDish();
        $this->dishRepository->create($dish);
    }

    /**
     * @param UpdateDishEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateDishEvent $event)
    {
        $dish = $event->getDish();
        $this->dishRepository->update($dish);
    }

    /**
     * @param RemoveDishEvent $event
     * @throws ORMException
     */
    public function remove(RemoveDishEvent $event)
    {
        $dish = $event->getDish();
        $this->dishRepository->remove($dish);
    }

}