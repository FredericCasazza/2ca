<?php

namespace App\Repository;

use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Establishment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Establishment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Establishment[]    findAll()
 * @method Establishment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstablishmentRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * EstablishmentRepository constructor.
     * @param PaginatorInterface $paginator
     * @param ManagerRegistry $registry
     */
    public function __construct(PaginatorInterface $paginator, ManagerRegistry $registry)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Establishment::class);
    }

    /**
     * @param $page
     * @param $limit
     * @return PaginationInterface
     */
    public function paginate($page, $limit)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->addOrderBy('e.label', 'ASC');

        return $this->paginator->paginate($qb, $page, $limit);
    }

    /**
     * @param Establishment $establishment
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Establishment $establishment)
    {
        $this->_em->persist($establishment);
        $this->_em->flush();
    }

    /**
     * @param Establishment $establishment
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Establishment $establishment)
    {
        $this->_em->persist($establishment);
        $this->_em->flush();
    }

}
