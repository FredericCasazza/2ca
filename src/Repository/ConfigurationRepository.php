<?php

namespace App\Repository;

use App\Entity\Configuration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class ConfigurationRepository
 * @package App\Repository
 */
class ConfigurationRepository extends ServiceEntityRepository
{
    /**
     * ConfigurationRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Configuration::class);
    }

    /**
     * @param Configuration $configuration
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Configuration $configuration)
    {
        $this->_em->persist($configuration);
        $this->_em->flush();
    }

    /**
     * @param Configuration $configuration
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Configuration $configuration)
    {
        $this->_em->persist($configuration);
        $this->_em->flush();
    }
}
