<?php


namespace App\Command;


use App\Helper\ConfigurationHelper;
use App\Helper\OrderHelper;
use App\Manager\OrderManager;
use App\Specification\Order\IsExpiredOrderSpecification;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveExpiredOrdersCommand
 * @package App\Command
 */
class RemoveExpiredOrdersCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = '2ca:order:remove-expired';

    /**
     * @var OrderManager
     */
    private $orderManager;

    /**
     * @var OrderHelper
     */
    private $orderHelper;

    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;

    /**
     * RemoveExpiredOrdersCommand constructor.
     * @param OrderManager $orderManager
     * @param OrderHelper $orderHelper
     * @param ConfigurationHelper $configurationHelper
     */
    public function __construct(OrderManager $orderManager, OrderHelper $orderHelper, ConfigurationHelper $configurationHelper)
    {
        $this->orderManager = $orderManager;
        $this->orderHelper = $orderHelper;
        $this->configurationHelper = $configurationHelper;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription("Removes all expired orders");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orders = $this->orderManager->findAll();
        $output->writeln("Nombre de commandes : ".count($orders));
        foreach ($orders as $order)
        {
            if($this->orderHelper->isExpiredOrderSpecification($order))
            {
                $id = $order->getId();
                $this->orderManager->remove($order);
                $output->writeln("Commande n° {$id} supprimée");
            }
        }
    }

}