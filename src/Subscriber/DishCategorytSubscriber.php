<?php


namespace App\Subscriber;


use App\Event\DishCategory\CreateDishCategoryEvent;
use App\Event\DishCategory\DisableDishCategoryEvent;
use App\Event\DishCategory\EnableDishCategoryEvent;
use App\Event\DishCategory\UpdateDishCategoryEvent;
use App\Event\Establishment\EnableEstablishmentEvent;
use App\Manager\DishCategoryManager;
use App\Repository\DishCategoryRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DishCategorytSubscriber
 * @package App\Subscriber
 */
class DishCategorytSubscriber implements EventSubscriberInterface
{
    /**
     * @var DishCategoryRepository
     */
    private $dishCategoryRepository;

    /**
     * @var DishCategoryManager
     */
    private $dishCategoryManager;

    /**
     * DishCategorytSubscriber constructor.
     * @param DishCategoryRepository $dishCategoryRepository
     * @param DishCategoryManager $dishCategoryManager
     */
    public function __construct(DishCategoryRepository $dishCategoryRepository, DishCategoryManager $dishCategoryManager)
    {
        $this->dishCategoryRepository = $dishCategoryRepository;
        $this->dishCategoryManager = $dishCategoryManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateDishCategoryEvent::class => [
                ['create', 20]
            ],
            UpdateDishCategoryEvent::class => [
                ['update', 20]
            ],
            EnableDishCategoryEvent::class => [
                ['enable', 20]
            ],
            DisableDishCategoryEvent::class => [
                ['disable', 20]
            ],
        ];
    }

    /**
     * @param CreateDishCategoryEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateDishCategoryEvent $event)
    {
        $dishCategory = $event->getDishCatgory();
        $this->dishCategoryRepository->create($dishCategory);
    }

    /**
     * @param UpdateDishCategoryEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateDishCategoryEvent $event)
    {
        $dishCategory = $event->getDishCatgory();
        $this->dishCategoryRepository->create($dishCategory);
    }

    /**
     * @param EnableDishCategoryEvent $event
     */
    public function enable(EnableDishCategoryEvent $event)
    {
        $dishCategory = $event->getDishCatgory();
        $this->dishCategoryManager->update($dishCategory);
    }

    /**
     * @param DisableDishCategoryEvent $event
     */
    public function disable(DisableDishCategoryEvent $event)
    {
        $dishCategory = $event->getDishCatgory();
        $this->dishCategoryManager->update($dishCategory);
    }

}