<?php


namespace App\Event\Establishment;

use App\Entity\Establishment;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EnableEstablishmentEvent
 * @package App\Event\Establishment
 */
class EnableEstablishmentEvent extends Event
{

    /**
     * @var Establishment
     */
    private $establishment;

    /**
     * EnableEstablishmentEvent constructor.
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