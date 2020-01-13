<?php


namespace App\Subscriber;


use App\Event\User\CreateLostPasswordTokenUserEvent;
use App\Event\User\CreateUserEvent;
use App\Event\User\UpdateUserEvent;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserSubcriber
 * @package App\Subscriber
 */
class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserSubcriber constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateUserEvent::class => [
                ['create', 20]
            ],
            UpdateUserEvent::class => [
                ['update', 20]
            ],
            CreateLostPasswordTokenUserEvent::class => [
                ['createLostPasswordToken', 20]
            ]
        ];
    }

    /**
     * @param CreateUserEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateUserEvent $event)
    {
        $user = $event->getUser();
        $this->userRepository->create($user);
    }

    /**
     * @param UpdateUserEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateUserEvent $event)
    {
        $user = $event->getUser();
        $this->userRepository->update($user);
    }

    /**
     * @param CreateLostPasswordTokenUserEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createLostPasswordToken(CreateLostPasswordTokenUserEvent $event)
    {
        $user = $event->getUser();
        $this->userRepository->update($user);
    }
}