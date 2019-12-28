<?php


namespace App\Manager;

use App\Entity\Meal;
use App\Event\Meal\CreateMealEvent;
use App\Event\Meal\PublishMealEvent;
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
     * @param $date
     * @return mixed
     */
    public function findBookableByDate($date)
    {
        return $this->mealRepository->findBookableByDate($date);
    }

    /**
     * @param Meal $meal
     */
    public function publish(Meal $meal)
    {
        $meal->setPublished(true);
        $event = new PublishMealEvent($meal);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Meal $meal
     */
    public function unpublish(Meal $meal)
    {
        $meal->setPublished(false);
        $event = new PublishMealEvent($meal);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Meal $meal
     * @throws \Exception
     */
    public function create(Meal $meal)
    {
        $currentDate = new \DateTime();
        $meal->setCreationDate($currentDate)
            ->setModificationDate($currentDate);
        $event = new CreateMealEvent($meal);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Meal $meal
     * @throws \Exception
     */
    public function update(Meal $meal)
    {
        $meal->setModificationDate(new \DateTime());
        $event = new UpdateMealEvent($meal);
        $this->eventDispatcher->dispatch($event);
    }
}