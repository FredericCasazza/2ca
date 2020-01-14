<?php


namespace App\Event\Establishment;

use App\Entity\Establishment;
use App\Entity\Period;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class DisableEstablishmentEvent
 * @package App\Event\Establishment
 */
class DisableEstablishmentEvent extends Event
{

    /**
     * @var Establishment
     */
    private $establishment;

    /**
     * DisableEstablishmentEvent constructor.
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