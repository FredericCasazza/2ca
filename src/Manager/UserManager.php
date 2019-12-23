<?php


namespace App\Manager;

use App\Constant\Role;
use App\Entity\User;
use App\Event\User\CreateUserEvent;
use App\Event\User\UpdateUserEvent;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserManager constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param UserRepository $userRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        UserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return User|null
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param $page
     * @param $limit
     * @return PaginationInterface
     */
    public function paginate($page, $limit)
    {
        return $this->userRepository->paginate($page, $limit);
    }

    /**
     * @param User $user
     */
    public function create(User $user)
    {
        // Set Roles
        $user->setRoles([Role::ROLE_USER]);

        // Encode plain text password
        if(!empty($user->getPlainTextPassword()))
        {
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPlainTextPassword()));
        }

        $event = new CreateUserEvent($user);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $user->addRole(Role::ROLE_USER);

        $event = new UpdateUserEvent($user);
        $this->eventDispatcher->dispatch($event);
    }
}