<?php

namespace App\Repository;

use App\Entity\Establishment;
use App\Entity\Meal;
use App\Entity\Order;
use App\Entity\Period;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{

    /**
     * OrderRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param User $user
     * @param Meal $meal
     * @return Order|null
     */
    public function findOneByUserAndMeal(User $user, Meal $meal)
    {
        return $this->findOneBy([
            'user' => $user,
            'meal' => $meal
        ]);
    }

    /**
     * @param \DateTimeInterface $date
     * @param Period $period
     * @param Establishment $establishment
     * @return array
     */
    public function findValidByDatePeriodAndEstablishment(\DateTimeInterface $date, Period $period, Establishment $establishment)
    {
        $qb = $this->createQueryBuilder('o')
            ->innerJoin('o.meal', 'm')
            ->innerJoin('o.user', 'u');
        $qb->andWhere($qb->expr()->isNotNull('o.validationDate'))
            ->andWhere($qb->expr()->eq('m.date', ':date'))
            ->andWhere($qb->expr()->eq('m.period', ':period'))
            ->andWhere($qb->expr()->eq('o.establishment', ':establishment'))
            ->addOrderBy('u.lastname', 'asc')
            ->addOrderBy('u.firstname', 'asc')
            ->setParameter('date', $date)
            ->setParameter('period', $period)
            ->setParameter('establishment', $establishment);
        return $qb->getQuery()->execute();
    }

    /**
     * @param Order $order
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Order $order)
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }

    /**
     * @param Order $order
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Order $order)
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }

    /**
     * @param Order $order
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Order $order)
    {
        $this->_em->remove($order);
        $this->_em->flush();
    }
}
