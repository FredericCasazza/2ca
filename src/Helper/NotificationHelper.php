<?php


namespace App\Helper;


use App\Constant\NotificationType;
use App\Entity\Notification;
use App\Entity\User;
use App\Manager\NotificationManager;
use Symfony\Component\Security\Core\Security;

/**
 * Class NotificationHelper
 * @package App\Helper
 */
class NotificationHelper
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * @var array
     */
    private $notifications = [];

    /**
     * NotificationHelper constructor.
     * @param NotificationManager $notificationManager
     * @param Security $security
     */
    public function __construct(NotificationManager $notificationManager, Security $security)
    {
        $this->security = $security;
        $this->notificationManager = $notificationManager;
        $this->init();
    }

    /**
     *
     */
    private function init()
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if(!$user instanceof User)
        {
            return;
        }

        $this->notifications = $this->notificationManager->findByUser($user);
    }

    /**
     * @param string|null $type
     * @return array
     */
    public function getNotifications(string $type=null)
    {
        $notifications = $this->notifications;
        if(!empty($type))
        {
            $notifications = array_filter($notifications, function (Notification $notification) use ($type) {
                return $notification->getType() === $type;
            });
        }
        return $notifications;
    }

    /**
     * @return array
     */
    public function getNotificationsTypes()
    {
        return NotificationType::NOTIFICATIONS_TYPES;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getIcon(string $type)
    {
        $icon = '<i class="far fa-dot-circle mr-2"></i>';
        switch ($type)
        {
            case NotificationType::NEW_REGISTRATION:
                $icon = '<i class="fas fa-user mr-2"></i>';
                break;
        }
        return $icon;
    }
}