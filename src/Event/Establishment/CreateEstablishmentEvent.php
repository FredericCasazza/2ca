<?php


namespace App\Event\Establishment;

use App\Entity\Establishment;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UpdatePeriodEvent
 * @package App\Event\Order
 */
class CreateEstablishmentEvent extends Event
{

    /**
     * @var Establishment
     */
    private $establishment;

    /**
     * CreateEstablishmentEvent constructor.
     * @param Establishment $establishment
     */
    public function __construct(Establishment $establishment)
    {
        $this->establishment = $establishment;
    }

    /**
     * @return Establishment
     */
    public function getEstablishment(): Establishment
    {
        return $this->establishment;
    }
}