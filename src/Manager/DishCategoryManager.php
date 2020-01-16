<?php


namespace App\Manager;

use App\Entity\DishCategory;
use App\Event\DishCategory\CreateDishCategoryEvent;
use App\Event\DishCategory\DisableDishCategoryEvent;
use App\Event\DishCategory\EnableDishCategoryEvent;
use App\Event\DishCategory\UpdateDishCategoryEvent;
use App\Repository\DishCategoryRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DishCategoryManager
 * @package App\Manager
 */
class DishCategoryManager extends AbstractManager
{
    /**
     * @var DishCategoryRepository
     */
    private $dishCategoryRepository;

    /**
     * DishCategoryManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param DishCategoryRepository $dishCategoryRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        DishCategoryRepository $dishCategoryRepository
    )
    {
        $this->dishCategoryRepository = $dishCategoryRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return DishCategory|null
     */
    public function find($id)
    {
        return $this->dishCategoryRepository->find($id);
    }

    /**
     * @return DishCategory[]
     */
    public function findEnableOrderedByPosition()
    {
        return $this->dishCategoryRepository->findBy(
            [
                'enable' => true
            ],
            [
                'position' => 'ASC',
                'label' => 'ASC'
            ]
        );
    }

    /**
     * @param DishCategory $establishment
     */
    public function enable(DishCategory $establishment)
    {
        $establishment->setEnable(true);
        $event = new EnableDishCategoryEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param DishCategory $establishment
     */
    public function disable(DishCategory $establishment)
    {
        $establishment->setEnable(false);
        $event = new DisableDishCategoryEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param DishCategory $establishment
     */
    public function create(DishCategory $establishment)
    {
        $event = new CreateDishCategoryEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param DishCategory $establishment
     */
    public function update(DishCategory $establishment)
    {
        $event = new UpdateDishCategoryEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

}