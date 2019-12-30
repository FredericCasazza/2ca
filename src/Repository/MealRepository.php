<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Meal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meal[]    findAll()
 * @method Meal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * MealRepository constructor.
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginator
    )
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Meal::class);
    }

    /**
     * @param $page
     * @param $limit
     * @return PaginationInterface
     */
    public function paginate($page, $limit)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->addOrderBy('m.date', 'desc');

        return $this->paginator->paginate($qb, $page, $limit);
    }

    /**
     * @param $date
     * @return mixed
     */
    public function findBookableByDate($date)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->join('m.period', 'p')
            ->andWhere($qb->expr()->eq('m.published', true))
            ->andWhere($qb->expr()->eq('m.date', ':date'))
            ->addOrderBy('m.date', 'asc')
            ->addOrderBy('p.position', 'asc')
            ->setParameter('date', $date);

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
}
