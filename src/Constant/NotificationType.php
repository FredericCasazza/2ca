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
    const NEW_ORDER_VALIDATED = 'new_order_validated';
    const OTHER = 'other';

    const NOTIFICATIONS_TYPES = [
        self::NEW_REGISTRATION => "nouvelles inscriptions",
        self::NEW_CUSTOMER_REQUEST => "nouvelles demandes client",
        self::NEW_ORDER_VALIDATED => "nouvelles commandes validées"
    ];
}