<?php


namespace App\Event\Establishment;

use App\Entity\Establishment;
use App\Entity\Period;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UpdateEstablishmentEvent
 * @package App\Event\Establishment
 */
class UpdateEstablishmentEvent extends Event
{

    /**
     * @var Establishment
     */
    private $establishment;

    /**
     * UpdateEstablishmentEvent constructor.
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