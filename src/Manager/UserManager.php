<?php


namespace App\Manager;

use App\Entity\User;
use App\Event\User\CreateUserEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager extends AbstractManager
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * UserManager constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, EventDispatcherInterface $eventDispatcher)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;

        parent::__construct($eventDispatcher);
    }

    /**
     * @param User $user
     */
    public function create(User $user)
    {
        // Set Roles
        $user->setRoles(['ROLE_USER']);

        // Encode plain text password
        if(!empty($user->getPlainTextPassword()))
        {
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPlainTextPassword()));
        }

        $event = new CreateUserEvent($user);
        $this->eventDispatcher->dispatch($event);
    }
}