<?php


namespace App\Specification\Order;


use App\Constant\Role;
use App\Entity\Order;
use App\Helper\RoleHelper;
use Symfony\Component\Security\Core\Security;
use Tanigami\Specification\Specification;

/**
 * Class IsAuthorizedUserOrderSpecification
 * @package App\Specification\Order
 */
class IsAuthorizedUserOrderSpecification extends Specification
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var RoleHelper
     */
    private $roleHelper;

    /**
     * IsAuthorizedUserOrderSpecification constructor.
     * @param Security $security
     * @param RoleHelper $roleHelper
     */
    public function __construct(Security $security, RoleHelper $roleHelper)
    {
        $this->security = $security;
        $this->roleHelper = $roleHelper;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy($order): bool
    {
        return (
            !empty($this->security->getUser()) &&
            (
                $order->getUser()->getId() === $this->security->getUser()->getId() ||
                $this->roleHelper->isGranted($this->security->getUser(), Role::ROLE_ADMIN)
            )
        );
    }
}