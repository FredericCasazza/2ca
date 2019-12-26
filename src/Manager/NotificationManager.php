<?php


namespace App\Manager;

use App\Entity\Notification;
use App\Entity\User;
use App\Event\Notification\CreateNotificationEvent;
use App\Event\Notification\RemoveNotificationEvent;
use App\Event\Notification\UpdateNotificationEvent;
use App\Repository\ConfigurationRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class NotificationManager
 * @package App\Manager
 */
class NotificationManager extends AbstractManager
{
    /**
     * @var ConfigurationRepository
     */
    private $notificationRepository;

    /**
     * ConfigurationManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        NotificationRepository $notificationRepository
    )
    {
        $this->notificationRepository = $notificationRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return object|null
     */
    public function find($id)
    {
        return $this->notificationRepository->find($id);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findUncheckedByUser(User $user)
    {
        return $this->notificationRepository->findUncheckedByUserOrRoles($user->getId(), $user->getRoles());
    }

    /**
     * @param User $user
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function paginateByUser(User $user, $page, $limit)
    {
        return $this->notificationRepository->paginateByUserOrRoles($user->getId(), $user->getRoles(), $page, $limit);
    }

    /**
     * @param Notification $notification
     */
    public function check(Notification $notification)
    {
        $notification->setChecked(true);
        $this->update($notification);
    }

    /**
     * @param Notification $notification
     * @throws \Exception
     */
    public function create(Notification $notification)
    {
        $notification->setCreationDate(new \DateTime());
        $event = new CreateNotificationEvent($notification);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Notification $notification
     */
    public function update(Notification $notification)
    {
        $event = new UpdateNotificationEvent($notification);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Notification $notification
     */
    public function remove(Notification $notification)
    {
        $event = new RemoveNotificationEvent($notification);
        $this->eventDispatcher->dispatch($event);
    }
}