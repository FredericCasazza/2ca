<?php


namespace App\Constant;

/**
 * Class NotificationType
 * @package App\Constant
 */
class NotificationType
{
    const NEW_REGISTRATION = 'new_registration';
    const NEW_CUSTOMER_REQUEST = 'new_customer_request';
    const OTHER = 'other';

    const NOTIFICATIONS_TYPES = [
        self::NEW_REGISTRATION => "nouvelles inscriptions",
        self::NEW_CUSTOMER_REQUEST => "nouvelles demandes client"
    ];
}