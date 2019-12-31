<?php


namespace App\Manager;

use App\Entity\Dish;
use App\Entity\DishCategory;
use App\Entity\Meal;
use App\Entity\Order;
use App\Event\Dish\CreateDishEvent;
use App\Event\Dish\RemoveDishEvent;
use App\Repository\DishRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DishManager
 * @package App\Manager
 */
class DishManager extends AbstractManager
{
    /**
     * @var DishRepository
     */
    private $dishRepository;

    /**
     * DishManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param DishRepository $dishRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        DishRepository $dishRepository
    )
    {
        $this->dishRepository = $dishRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return Dish|null
     */
    public function find($id)
    {
        return $this->dishRepository->find($id);
    }

    /**
     * @param Meal $meal
     * @param DishCategory $dishCategory
     * @return Dish[]
     */
    public function findByMealAndDishCategory(Meal $meal, DishCategory $dishCategory)
    {
        return $this->dishRepository->findBy(
            [
                'meal' => $meal,
                'category' => $dishCategory
            ],
            [
                'label' => 'ASC'
            ]
        );
    }

    /**
     * @param Dish $dish
     */
    public function create(Dish $dish)
    {
        $event = new CreateDishEvent($dish);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Dish $dish
     */
    public function remove(Dish $dish)
    {
        $event = new RemoveDishEvent($dish);
        $this->eventDispatcher->dispatch($event);
    }

}