<?php


namespace App\Subscriber;


use App\Event\Period\CreatePeriodEvent;
use App\Event\Period\DisablePeriodEvent;
use App\Event\Period\EnablePeriodEvent;
use App\Event\Period\UpdatePeriodEvent;
use App\Manager\PeriodManager;
use App\Repository\PeriodRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PeriodSubscriber
 * @package App\Subscriber
 */
class PeriodSubscriber implements EventSubscriberInterface
{
    /**
     * @var PeriodRepository
     */
    private $periodRepository;

    /**
     * @var PeriodManager
     */
    private $periodManager;

    /**
     * PeriodSubscriber constructor.
     * @param PeriodRepository $periodRepository
     * @param PeriodManager $periodManager
     */
    public function __construct(PeriodRepository $periodRepository, PeriodManager $periodManager)
    {
        $this->periodRepository = $periodRepository;
        $this->periodManager = $periodManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreatePeriodEvent::class => [
                ['create', 20]
            ],
            UpdatePeriodEvent::class => [
                ['update', 20]
            ],
            EnablePeriodEvent::class => [
                ['enable', 20]
            ],
            DisablePeriodEvent::class => [
                ['disable', 20]
            ],
        ];
    }

    /**
     * @param CreatePeriodEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreatePeriodEvent $event)
    {
        $period = $event->getPeriod();
        $this->periodRepository->create($period);
    }

    /**
     * @param UpdatePeriodEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdatePeriodEvent $event)
    {
        $period = $event->getPeriod();
        $this->periodRepository->update($period);
    }

    /**
     * @param EnablePeriodEvent $event
     */
    public function enable(EnablePeriodEvent $event)
    {
        $period = $event->getPeriod();
        $this->periodManager->update($period);
    }

    /**
     * @param DisablePeriodEvent $event
     */
    public function disable(DisablePeriodEvent $event)
    {
        $period = $event->getPeriod();
        $this->periodManager->update($period);
    }

}