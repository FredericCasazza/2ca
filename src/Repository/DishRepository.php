<?php

namespace App\Repository;

use App\Entity\Dish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Dish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dish[]    findAll()
 * @method Dish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishRepository extends ServiceEntityRepository
{
    /**
     * DishRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dish::class);
    }

    /**
     * @param Dish $dish
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Dish $dish)
    {
        $this->_em->persist($dish);
        $this->_em->flush();
    }

    /**
     * @param Dish $dish
     * @throws ORMException
     */
    public function remove(Dish $dish)
    {
        $this->_em->remove($dish);
        $this->_em->flush();
    }
}
