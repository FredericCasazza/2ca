<?php


namespace App\Manager;

use App\Entity\Configuration;
use App\Event\Configuration\CreateConfigurationEvent;
use App\Event\Configuration\UpdateConfigurationEvent;
use App\Repository\ConfigurationRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ConfigurationManager
 * @package App\Manager
 */
class ConfigurationManager extends AbstractManager
{
    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * ConfigurationManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param ConfigurationRepository $configurationRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ConfigurationRepository $configurationRepository
    )
    {
        $this->configurationRepository = $configurationRepository;
        parent::__construct($eventDispatcher);
    }

    /**
     * @param $id
     * @return object|null
     */
    public function find($id)
    {
        return $this->configurationRepository->find($id);
    }

    /**
     * @param $environment
     * @return object|null
     */
    public function findByEnvironment($environment)
    {
        return $this->configurationRepository->findOneBy(['env' => $environment]);
    }

    /**
     * @param Configuration $configuration
     */
    public function create(Configuration $configuration)
    {
        $event = new CreateConfigurationEvent($configuration);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Configuration $configuration
     */
    public function update(Configuration $configuration)
    {
        $event = new UpdateConfigurationEvent($configuration);
        $this->eventDispatcher->dispatch($event);
    }
}