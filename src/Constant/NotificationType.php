<?php


namespace App\Constant;

/**
 * Class NotificationType
 * @package App\Constant
 */
class NotificationType
{
    const NEW_REGISTRATION = 'new_registration';
    const OTHER = 'other';

    const NOTIFICATIONS_TYPES = [
        self::NEW_REGISTRATION => "nouvelle(s) inscription(s)"
    ];
}