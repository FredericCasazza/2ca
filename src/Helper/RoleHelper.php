<?php


namespace App\Helper;


use App\Constant\Role;

/**
 * Class RoleHelper
 * @package App\Helper
 */
class RoleHelper
{
    /**
     * @param $role
     * @return |null
     */
    public function getLabel($role)
    {
        return array_key_exists($role, Role::ROLES)? Role::ROLES[$role] : null;
    }
}