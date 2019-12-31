<?php

namespace App\Repository;

use App\Entity\Period;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Period|null find($id, $lockMode = null, $lockVersion = null)
 * @method Period|null findOneBy(array $criteria, array $orderBy = null)
 * @method Period[]    findAll()
 * @method Period[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * PeriodRepository constructor.
     * @param PaginatorInterface $paginator
     * @param ManagerRegistry $registry
     */
    public function __construct(PaginatorInterface $paginator, ManagerRegistry $registry)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Period::class);
    }

    /**
     * @param $page
     * @param $limit
     * @return PaginationInterface
     */
    public function paginate($page, $limit)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->addOrderBy('p.position', 'ASC');

        return $this->paginator->paginate($qb, $page, $limit);
    }

    /**
     * @param Period $period
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Period $period)
    {
        $this->_em->persist($period);
        $this->_em->flush();
    }

}
