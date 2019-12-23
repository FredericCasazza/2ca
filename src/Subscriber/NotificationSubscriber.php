<?php


namespace App\Subscriber;


use App\Constant\NotificationType;
use App\Constant\Role;
use App\Entity\Notification;
use App\Event\Notification\CreateNotificationEvent;
use App\Event\User\CreateUserEvent;
use App\Event\User\UpdateUserEvent;
use App\Manager\NotificationManager;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class NotificationSubscriber
 * @package App\Subscriber
 */
class NotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * NotificationSubscriber constructor.
     * @param NotificationRepository $notificationRepository
     * @param NotificationManager $notificationManager
     */
    public function __construct(NotificationRepository $notificationRepository, NotificationManager $notificationManager)
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateNotificationEvent::class => [
                ['create', 20]
            ],
            CreateUserEvent::class => [
                ['newRegistration', 10]
            ],
        ];
    }

    /**
     * @param CreateNotificationEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateNotificationEvent $event)
    {
        $notification = $event->getNotification();
        $this->notificationRepository->create($notification);
    }

    /**
     * @param CreateUserEvent $event
     */
    public function newRegistration(CreateUserEvent $event)
    {
        $user = $event->getUser();
        $notification = new Notification();
        $notification->setRole(Role::ROLE_ADMIN)
            ->setType(NotificationType::NEW_REGISTRATION)
            ->setMessage("Nouvel utilisateur: {$user->getFirstname()} {$user->getLastname()} s'est inscrit !");
        $this->notificationManager->create($notification);
    }

}