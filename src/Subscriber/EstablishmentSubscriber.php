<?php


namespace App\Subscriber;


use App\Event\Establishment\CreateEstablishmentEvent;
use App\Event\Establishment\DisableEstablishmentEvent;
use App\Event\Establishment\EnableEstablishmentEvent;
use App\Event\Establishment\UpdateEstablishmentEvent;
use App\Manager\EstablishmentManager;
use App\Repository\EstablishmentRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EstablishmentSubscriber
 * @package App\Subscriber
 */
class EstablishmentSubscriber implements EventSubscriberInterface
{
    /**
     * @var EstablishmentRepository
     */
    private $establishmentRepository;

    /**
     * @var EstablishmentManager
     */
    private $establishmentManager;

    /**
     * EstablishmentSubscriber constructor.
     * @param EstablishmentRepository $establishmentRepository
     * @param EstablishmentManager $establishmentManager
     */
    public function __construct(EstablishmentRepository $establishmentRepository, EstablishmentManager $establishmentManager)
    {
        $this->establishmentRepository = $establishmentRepository;
        $this->establishmentManager = $establishmentManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateEstablishmentEvent::class => [
                ['create', 20]
            ],
            UpdateEstablishmentEvent::class => [
                ['update', 20]
            ],
            EnableEstablishmentEvent::class => [
                ['enable', 20]
            ],
            DisableEstablishmentEvent::class => [
                ['disable', 20]
            ],
        ];
    }

    /**
     * @param CreateEstablishmentEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateEstablishmentEvent $event)
    {
        $establishment = $event->getEstablishment();
        $this->establishmentRepository->create($establishment);
    }

    /**
     * @param CreateEstablishmentEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateEstablishmentEvent $event)
    {
        $establishment = $event->getEstablishment();
        $this->establishmentRepository->update($establishment);
    }

    /**
     * @param EnableEstablishmentEvent $event
     */
    public function enable(EnableEstablishmentEvent $event)
    {
        $establishment = $event->getEstablishment();
        $this->establishmentManager->update($establishment);
    }

    /**
     * @param DisableEstablishmentEvent $event
     */
    public function disable(DisableEstablishmentEvent $event)
    {
        $establishment = $event->getEstablishment();
        $this->establishmentManager->update($establishment);
    }

}