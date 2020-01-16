<?php

namespace App\Repository;

use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Establishment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Establishment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Establishment[]    findAll()
 * @method Establishment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstablishmentRepository extends ServiceEntityRepository
{

    /**
     * EstablishmentRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Establishment::class);
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
