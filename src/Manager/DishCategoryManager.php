<?php


namespace App\Manager;

use App\Entity\DishCategory;
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

}