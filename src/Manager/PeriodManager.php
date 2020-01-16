<?php


namespace App\Manager;

use App\Entity\Period;
use App\Event\Period\CreatePeriodEvent;
use App\Event\Period\DisablePeriodEvent;
use App\Event\Period\EnablePeriodEvent;
use App\Event\Period\UpdatePeriodEvent;
use App\Repository\PeriodRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class PeriodManager
 * @package App\Manager
 */
class PeriodManager extends AbstractManager
{
    /**
     * @var PeriodRepository
     */
    private $periodRepository;

    /**
     * PeriodManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param PeriodRepository $periodRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        PeriodRepository $periodRepository
    )
    {
        $this->periodRepository = $periodRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return \App\Entity\Period|null
     */
    public function find($id)
    {
        return $this->periodRepository->find($id);
    }
    
    /**
     * @param Period $period
     */
    public function enable(Period $period)
    {
        $period->setEnable(true);
        $event = new EnablePeriodEvent($period);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Period $period
     */
    public function disable(Period $period)
    {
        $period->setEnable(false);
        $event = new DisablePeriodEvent($period);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Period $period
     */
    public function create(Period $period)
    {
        $event = new CreatePeriodEvent($period);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Period $period
     */
    public function update(Period $period)
    {
        $event = new UpdatePeriodEvent($period);
        $this->eventDispatcher->dispatch($event);
    }

}