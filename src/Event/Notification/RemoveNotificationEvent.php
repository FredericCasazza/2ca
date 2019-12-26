<?php


namespace App\Event\Notification;

use App\Entity\Notification;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class RemoveNotificationEvent
 * @package App\Event\Notification
 */
class RemoveNotificationEvent extends Event
{

    /**
     * @var Notification
     */
    private $notification;

    /**
     * RemoveNotificationEvent constructor.
     * @param Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return Notification
     */
    public function getNotification(): Notification
    {
        return $this->notification;
    }
}