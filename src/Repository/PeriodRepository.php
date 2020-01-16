<?php

namespace App\Repository;

use App\Entity\Period;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Period|null find($id, $lockMode = null, $lockVersion = null)
 * @method Period|null findOneBy(array $criteria, array $orderBy = null)
 * @method Period[]    findAll()
 * @method Period[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodRepository extends ServiceEntityRepository
{

    /**
     * PeriodRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Period::class);
    }

    /**
     * @param Period $period
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Period $period)
    {
        $this->_em->persist($period);
        $this->_em->flush();
    }

    /**
     * @param Period $period
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Period $period)
    {
        $this->_em->persist($period);
        $this->_em->flush();
    }

}
