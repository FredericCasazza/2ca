<?php


namespace App\Constant;


/**
 * Class Role
 * @package App\Constant
 */
final class Role
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_CHIEF = 'ROLE_CHIEF';
    const ROLE_CLIENT = 'ROLE_CLIENT';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const ROLES = [
        self::ROLE_CLIENT => 'Client',
        self::ROLE_CHIEF => 'Chef cuisinier',
        self::ROLE_ADMIN => 'Administrateur',
    ];

}