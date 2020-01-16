<?php


namespace App\Manager;

use App\Entity\Dish;
use App\Entity\Establishment;
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
     * @var DishCategoryManager
     */
    private $dishCategoryManager;

    /**
     * MealManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param MealRepository $mealRepository
     * @param DishCategoryManager $dishCategoryManager
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        MealRepository $mealRepository,
        DishCategoryManager $dishCategoryManager
    )
    {
        $this->mealRepository = $mealRepository;
        $this->dishCategoryManager = $dishCategoryManager;
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
     * @param \DateTime $date
     * @param Establishment $establishment
     * @return mixed
     */
    public function findBookableByDateAndEstablishment(\DateTime $date, Establishment $establishment)
    {
        return $this->mealRepository->findBookableByDateAndEstablishment($date, $establishment);
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
        $date = \DateTime::createFromFormat(
            'Y-m-d H:i',
            "{$meal->getDate()->format('Y-m-d')} {$meal->getPeriod()->getStartTime()->format('H:i')}"
        );
        $bookDateLimit = $date->modify("-{$meal->getPeriod()->getBookTimeLimit()} hour");
        $meal->setBookDateLimit($bookDateLimit)
            ->setCreationDate($currentDate)
            ->setModificationDate($currentDate);

        $dishCategories = $this->dishCategoryManager->findEnableOrderedByPosition();
        foreach ($dishCategories as $dishCategory)
        {
            foreach ($dishCategory->getDishes() as $label)
            {
                $dish = new Dish();
                $dish->setLabel($label)
                    ->setCategory($dishCategory);
                $meal->addDish($dish);
            }
        }

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