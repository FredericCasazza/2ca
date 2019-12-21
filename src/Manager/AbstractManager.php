<?php


namespace App\Manager;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractManager
 * @package App\Manager
 */
abstract class AbstractManager
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * AbstractManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}