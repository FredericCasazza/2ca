<?php

namespace App\Repository;

use App\Entity\DishCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method DishCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DishCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DishCategory[]    findAll()
 * @method DishCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishCategoryRepository extends ServiceEntityRepository
{

    /**
     * DishCategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DishCategory::class);
    }

    /**
     * @param DishCategory $establishment
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(DishCategory $establishment)
    {
        $this->_em->persist($establishment);
        $this->_em->flush();
    }

    /**
     * @param DishCategory $establishment
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(DishCategory $establishment)
    {
        $this->_em->persist($establishment);
        $this->_em->flush();
    }

}
