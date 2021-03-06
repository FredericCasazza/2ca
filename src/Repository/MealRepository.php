<?php

namespace App\Repository;

use App\Entity\Establishment;
use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Meal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meal[]    findAll()
 * @method Meal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealRepository extends ServiceEntityRepository
{
    /**
     * MealRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Meal::class);
    }


    /**
     * @param \DateTime $date
     * @param Establishment $establishment
     * @return mixed
     */
    public function findBookableByDateAndEstablishment(\DateTime $date, Establishment $establishment)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->leftJoin('m.period', 'p')
            ->leftJoin('m.establishments', 'e')
            ->andWhere($qb->expr()->eq('m.published', true))
            ->andWhere($qb->expr()->eq('m.date', ':date'))
            ->andWhere($qb->expr()->eq('e.id', ':establishment'))
            ->addOrderBy('m.date', 'asc')
            ->addOrderBy('p.position', 'asc')
            ->setParameter('date', $date)
            ->setParameter('establishment', $establishment);

        return $qb->getQuery()->execute();
    }

    /**
     * @param Meal $meal
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Meal $meal)
    {
        $this->_em->persist($meal);
        $this->_em->flush();
    }

    /**
     * @param Meal $meal
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Meal $meal)
    {
        $this->_em->persist($meal);
        $this->_em->flush();
    }

    /**
     * @param Meal $meal
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Meal $meal)
    {
        $this->_em->remove($meal);
        $this->_em->flush();
    }
}
