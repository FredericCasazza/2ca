<?php


namespace App\Event\User;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UpdateUserEvent
 * @package App\Event\User
 */
class UpdateUserEvent extends Event
{

    /**
     * @var User
     */
    private $user;

    /**
     * UserEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}