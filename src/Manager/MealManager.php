<?php


namespace App\Manager;

use App\Entity\Meal;
use App\Event\Meal\CreateMealEvent;
use App\Event\Meal\UpdateMealEvent;
use App\Repository\MealRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class MealManager
 * @package App\Manager
 */
class MealManager extends AbstractManager
{
    /**
     * @var MealRepository
     */
    private $mealRepository;

    /**
     * MealManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param MealRepository $mealRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        MealRepository $mealRepository
    )
    {
        $this->mealRepository = $mealRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return Meal|null
     */
    public function find($id)
    {
        return $this->mealRepository->find($id);
    }

    /**
     * @param $page
     * @param $limit
     * @return PaginationInterface
     */
    public function paginate($page, $limit)
    {
        return $this->mealRepository->paginate($page, $limit);
    }

    /**
     * @param Meal $meal
     */
    public function create(Meal $meal)
    {
        $event = new CreateMealEvent($meal);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Meal $meal
     */
    public function update(Meal $meal)
    {
        $event = new UpdateMealEvent($meal);
        $this->eventDispatcher->dispatch($event);
    }
}