<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class NotificationRepository
 * @package App\Repository
 */
class NotificationRepository extends ServiceEntityRepository
{

    /**
     * NotificationRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @param int $user_id
     * @param array $roles
     * @return mixed
     */
    public function findByUserOrRoles(int $user_id, array $roles=[])
    {
        $qb = $this->createQueryBuilder('n');

        $qb
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('n.user', ':user_id'),
                $qb->expr()->in('n.role', ':roles')
            ))
            ->setParameter('user_id', $user_id)
            ->setParameter('roles', $roles)
            ->addOrderBy('n.creationDate', 'DESC');

        return $qb->getQuery()->execute();
    }

    /**
     * @param Notification $notification
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Notification $notification)
    {
        $this->_em->persist($notification);
        $this->_em->flush();
    }
}
