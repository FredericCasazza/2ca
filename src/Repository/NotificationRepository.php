<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class NotificationRepository
 * @package App\Repository
 */
class NotificationRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * NotificationRepository constructor.
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Notification::class);
    }

    /**
     * @param int $user_id
     * @param array $roles
     * @return mixed
     */
    public function findUncheckedByUserOrRoles(int $user_id, array $roles=[])
    {
        $qb = $this->createQueryBuilder('n');

        $qb
            ->andWhere($qb->expr()->eq('n.checked', ':checked'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('n.user', ':user_id'),
                $qb->expr()->in('n.role', ':roles')
            ))
            ->setParameter('checked', false)
            ->setParameter('user_id', $user_id)
            ->setParameter('roles', $roles)
            ->addOrderBy('n.creationDate', 'DESC');

        return $qb->getQuery()->execute();
    }

    /**
     * @param int $user_id
     * @param array $roles
     * @return mixed
     */
    public function paginateByUserOrRoles(int $user_id, array $roles, $page, $limit)
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

        return $this->paginator->paginate($qb, $page, $limit);
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

    /**
     * @param Notification $notification
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Notification $notification)
    {
        $this->_em->persist($notification);
        $this->_em->flush();
    }

    /**
     * @param Notification $notification
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Notification $notification)
    {
        $this->_em->remove($notification);
        $this->_em->flush();
    }
}
