<?php


namespace App\Subscriber;


use App\Event\Configuration\CreateConfigurationEvent;
use App\Event\Configuration\UpdateConfigurationEvent;
use App\Repository\ConfigurationRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConfigurationSubscriber
 * @package App\Subscriber
 */
class ConfigurationSubscriber implements EventSubscriberInterface
{
    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * ConfigurationSubscriber constructor.
     * @param ConfigurationRepository $configurationRepository
     */
    public function __construct(ConfigurationRepository $configurationRepository)
    {
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateConfigurationEvent::class => [
                ['create', 20]
            ],
            UpdateConfigurationEvent::class => [
                ['update', 20]
            ],
        ];
    }

    /**
     * @param CreateConfigurationEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(CreateConfigurationEvent $event)
    {
        $configuration = $event->getConfiguration();
        $this->configurationRepository->create($configuration);
    }

    /**
     * @param UpdateConfigurationEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UpdateConfigurationEvent $event)
    {
        $configuration = $event->getConfiguration();
        $this->configurationRepository->update($configuration);
    }

}