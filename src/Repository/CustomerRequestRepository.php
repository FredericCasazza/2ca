<?php

namespace App\Repository;

use App\Entity\CustomerRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method CustomerRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerRequest[]    findAll()
 * @method CustomerRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerRequest::class);
    }

    /**
     * @param CustomerRequest $customerRequest
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CustomerRequest $customerRequest)
    {
        $this->_em->persist($customerRequest);
        $this->_em->flush();
    }

    /**
     * @param CustomerRequest $customerRequest
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(CustomerRequest $customerRequest)
    {
        $this->_em->persist($customerRequest);
        $this->_em->flush();
    }

    /**
     * @param CustomerRequest $customerRequest
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(CustomerRequest $customerRequest)
    {
        $this->_em->remove($customerRequest);
        $this->_em->flush();
    }
}
