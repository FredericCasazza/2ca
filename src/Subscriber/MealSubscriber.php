<?php


namespace App\Subscriber;


use App\Event\Meal\CreateMealEvent;
use App\Event\Meal\UpdateMealEvent;
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
     * MealSubscriber constructor.
     * @param MealRepository $mealRepository
     */
    public function __construct(MealRepository $mealRepository)
    {
        $this->mealRepository = $mealRepository;
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
}