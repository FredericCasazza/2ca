<?php


namespace App\Subscriber;


use App\Event\Dish\CreateDishEvent;
use App\Event\Dish\RemoveDishEvent;
use App\Event\Meal\CreateMealEvent;
use App\Event\Meal\PublishMealEvent;
use App\Event\Meal\RemoveMealEvent;
use App\Event\Meal\UnpublishMealEvent;
use App\Event\Meal\UpdateMealEvent;
use App\Manager\MealManager;
use App\Repository\MealRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MealSubscriber
 * @package App\Subscriber
 */
class MealSubscriber implements EventSubscriberInterface
{
    /**
     * @var MealRepository
     */
    private $mealRepository;

    /**
     * @var MealManager
     */
    private $mealManager;

    /**
     * MealSubscriber constructor.
     * @param MealRepository $mealRepository
     * @param MealManager $mealManager
     */
    public function __construct(MealRepository $mealRepository, MealManager $mealManager)
    {
        $this->mealRepository = $mealRepository;
        $this->mealManager = $mealManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateMealEvent::class => [
                ['create', 20]
            ],
            UpdateMealEvent::class => [
                ['update', 20]
            ],
            RemoveMealEvent::class => [
                ['remove', 20]
            ],
            PublishMealEvent::class => [
                ['publish', 20]
            ],
            UnpublishMealEvent::class => [
                ['unpublish', 20]
            ],
            CreateDishEvent::class => [
                ['addDish', 15]
            ],
            RemoveDishEvent::class => [
                ['removeDish', 15]
            ]
        ];
    }

    /**
     * @param CreateMealEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateMealEvent $event)
    {
        $meal = $event->getMeal();
        $this->mealRepository->create($meal);
    }

    /**
     * @param UpdateMealEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateMealEvent $event)
    {
        $meal = $event->getMeal();
        $this->mealRepository->update($meal);
    }

    public function remove(RemoveMealEvent $event)
    {
        $meal = $event->getMeal();
        $this->mealRepository->remove($meal);
    }

    /**
     * @param PublishMealEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function publish(PublishMealEvent $event)
    {
        $meal = $event->getMeal();
        $this->mealRepository->update($meal);
    }

    /**
     * @param PublishMealEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function unpublish(PublishMealEvent $event)
    {
        $meal = $event->getMeal();
        $this->mealRepository->update($meal);
    }

    /**
     * @param CreateDishEvent $event
     * @throws \Exception
     */
    public function addDish(CreateDishEvent $event)
    {
        $dish = $event->getDish();
        $meal = $dish->getMeal();
        $this->mealManager->update($meal);
    }

    /**
     * @param RemoveDishEvent $event
     * @throws \Exception
     */
    public function removeDish(RemoveDishEvent $event)
    {
        $dish = $event->getDish();
        $meal = $dish->getMeal();
        $this->mealManager->update($meal);
    }
}