<?php


namespace App\Manager;

use App\Entity\Establishment;
use App\Event\Establishment\CreateEstablishmentEvent;
use App\Event\Establishment\DisableEstablishmentEvent;
use App\Event\Establishment\EnableEstablishmentEvent;
use App\Event\Establishment\UpdateEstablishmentEvent;
use App\Repository\EstablishmentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class EstablishmentManager
 * @package App\Manager
 */
class EstablishmentManager extends AbstractManager
{
    /**
     * @var EstablishmentRepository
     */
    private $establishmentRepository;

    /**
     * EstablishmentManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param EstablishmentRepository $establishmentRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EstablishmentRepository $establishmentRepository
    )
    {
        $this->establishmentRepository = $establishmentRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return Establishment|null
     */
    public function find($id)
    {
        return $this->establishmentRepository->find($id);
    }

    /**
     * @param Establishment $establishment
     */
    public function enable(Establishment $establishment)
    {
        $establishment->setEnable(true);
        $event = new EnableEstablishmentEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Establishment $establishment
     */
    public function disable(Establishment $establishment)
    {
        $establishment->setEnable(false);
        $event = new DisableEstablishmentEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Establishment $establishment
     */
    public function create(Establishment $establishment)
    {
        $event = new CreateEstablishmentEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Establishment $establishment
     */
    public function update(Establishment $establishment)
    {
        $event = new UpdateEstablishmentEvent($establishment);
        $this->eventDispatcher->dispatch($event);
    }

}