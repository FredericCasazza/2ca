<?php


namespace App\Subscriber;


use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class DoctrineSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $connection;

    /**
     * DoctrineSubscriber constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->connection = $this->em->getConnection();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['startTransaction', 50]
            ],
            KernelEvents::RESPONSE => [
                ['commit', 5]
            ],
            KernelEvents::EXCEPTION => [
                ['rollback', 5]
            ]
        ];
    }

    /**
     *
     */
    public function startTransaction()
    {
        if(!$this->connection->isTransactionActive())
        {
            $this->connection->beginTransaction();
            $this->connection->setAutoCommit(false);
        }
    }

    /**
     * @throws ConnectionException
     */
    public function commit()
    {
        if($this->connection->isTransactionActive())
        {
            $this->connection->commit();
        }
    }

    /**
     * @throws ConnectionException
     */
    public function rollback()
    {
        if($this->connection->isTransactionActive())
        {
            $this->connection->rollBack();
        }
    }

}