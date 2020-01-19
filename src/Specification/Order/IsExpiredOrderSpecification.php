<?php


namespace App\Specification\Order;


use App\Entity\Order;
use App\Helper\ConfigurationHelper;
use Tanigami\Specification\Specification;

/**
 * Class IsExpiredOrderSpecification
 * @package App\Specification\Order
 */
class IsExpiredOrderSpecification extends Specification
{

    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;

    /**
     * IsExpiredOrderSpecification constructor.
     * @param ConfigurationHelper $configurationHelper
     */
    public function __construct(ConfigurationHelper $configurationHelper)
    {
        $this->configurationHelper = $configurationHelper;
    }

    /**
     * @param Order $order
     * @return bool
     * @throws \Exception
     */
    public function isSatisfiedBy($order): bool
    {
        $retentionDays = $this->configurationHelper->getConfiguration()->getOrdersRetentionDays();
        if($retentionDays < 1)
        {
            return false;
        }
        $expiredDate = new \DateTime();
        $expiredDate->modify("-{$retentionDays} days");
        return ($order->getMeal()->getDate() < $expiredDate );
    }
}