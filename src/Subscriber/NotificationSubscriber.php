<?php


namespace App\Subscriber;


use App\Constant\NotificationType;
use App\Constant\Role;
use App\Entity\Notification;
use App\Event\Notification\CreateNotificationEvent;
use App\Event\Notification\RemoveNotificationEvent;
use App\Event\Notification\UpdateNotificationEvent;
use App\Event\User\CreateUserEvent;
use App\Manager\NotificationManager;
use App\Repository\NotificationRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

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
     * @var RouterInterface
     */
    private $router;

    /**
     * NotificationSubscriber constructor.
     * @param NotificationRepository $notificationRepository
     * @param NotificationManager $notificationManager
     */
    public function __construct(NotificationRepository $notificationRepository, NotificationManager $notificationManager, RouterInterface $router)
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationManager = $notificationManager;
        $this->router = $router;
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
            UpdateNotificationEvent::class => [
                ['update', 20]
            ],
            RemoveNotificationEvent::class => [
                ['remove', 20]
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
     * @param UpdateNotificationEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateNotificationEvent $event)
    {
        $notification = $event->getNotification();
        $this->notificationRepository->update($notification);
    }

    /**
     * @param RemoveNotificationEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RemoveNotificationEvent $event)
    {
        $notification = $event->getNotification();
        $this->notificationRepository->remove($notification);
    }

    /**
     * @param CreateUserEvent $event
     * @throws \Exception
     */
    public function newRegistration(CreateUserEvent $event)
    {
        $user = $event->getUser();
        $notification = new Notification();
        $notification->setRole(Role::ROLE_ADMIN)
            ->setType(NotificationType::NEW_REGISTRATION)
            ->setMessage("Nouvel utilisateur: {$user->getFirstname()} {$user->getLastname()} s'est inscrit !")
            ->setAction("Voir l'utilisateur")
            ->setUrl($this->router->generate('admin_user_edit', ['id' => $user->getId()]));
        $this->notificationManager->create($notification);
    }

}