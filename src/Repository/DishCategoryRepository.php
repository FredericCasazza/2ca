<?php

namespace App\Repository;

use App\Entity\DishCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DishCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DishCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DishCategory[]    findAll()
 * @method DishCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DishCategory::class);
    }

}
