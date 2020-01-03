<?php


namespace App\Helper;


use App\Constant\Role;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 * Class RoleHelper
 * @package App\Helper
 */
class RoleHelper
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    /**
     * RoleHelper constructor.
     * @param AccessDecisionManagerInterface $accessDecisionManager
     */
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager) {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    /**
     * @param $role
     * @return |null
     */
    public function getLabel($role)
    {
        return array_key_exists($role, Role::ROLES)? Role::ROLES[$role] : null;
    }

    /**
     * @param User $user
     * @param $attributes
     * @param null $object
     * @return bool
     */
    public function isGranted(User $user, $attributes, $object = null) {
        if (!is_array($attributes))
            $attributes = [$attributes];
        $token = new UsernamePasswordToken($user, 'none', 'none', $user->getRoles());
        return ($this->accessDecisionManager->decide($token, $attributes, $object));
    }

}