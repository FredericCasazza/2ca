<?php


namespace App\Subscriber;


use App\Constant\NotificationType;
use App\Constant\Role;
use App\Entity\Notification;
use App\Event\CustomerRequest\CreateCustomerRequestEvent;
use App\Event\Notification\CreateNotificationEvent;
use App\Event\Notification\RemoveNotificationEvent;
use App\Event\Notification\UpdateNotificationEvent;
use App\Event\Order\ValidateOrderEvent;
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
            CreateCustomerRequestEvent::class => [
                ['newCustomerRequest', 10]
            ],
            ValidateOrderEvent::class => [
                ['newOrderValidated', 10]
            ]
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

    /**
     * @param CreateCustomerRequestEvent $event
     * @throws \Exception
     */
    public function newCustomerRequest(CreateCustomerRequestEvent $event)
    {
        $customerRequest = $event->getCustomerRequest();
        $notification = new Notification();
        $notification->setRole(Role::ROLE_ADMIN)
            ->setType(NotificationType::NEW_CUSTOMER_REQUEST)
            ->setMessage("Demande client: {$customerRequest->getUser()->getFirstname()} {$customerRequest->getUser()->getLastname()} souhaite devenir client de l'établissement \"{$customerRequest->getEstablishment()->getLabel()}\"")
            ->setAction("Modifier l'utilisateur")
            ->setUrl($this->router->generate('admin_user_edit', ['id' => $customerRequest->getUser()->getId()]));
        $this->notificationManager->create($notification);
    }

    /**
     * @param ValidateOrderEvent $event
     * @throws \Exception
     */
    public function newOrderValidated(ValidateOrderEvent $event)
    {
        $order = $event->getOrder();
        $notification = new Notification();
        $notification->setRole(Role::ROLE_ADMIN)
            ->setType(NotificationType::NEW_ORDER_VALIDATED)
            ->setMessage("Nouvelle commande validée par {$order->getUser()->getFirstname()} {$order->getUser()->getLastname()} pour le {$order->getMeal()->getDate()->format('d/m/Y')} {$order->getMeal()->getPeriod()->getLabel()}")
            ->setAction("Voir la commande")
            //->setUrl($this->router->generate('admin_user_edit', ['id' => $user->getId()]));
        ;
        $this->notificationManager->create($notification);
    }

}