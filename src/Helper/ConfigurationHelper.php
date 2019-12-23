<?php


namespace App\Helper;


use App\Entity\Configuration;
use App\Manager\ConfigurationManager;
use App\Repository\ConfigurationRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class ConfigurationHelper
 * @package App\Helper
 */
class ConfigurationHelper
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @var ConfigurationManager
     */
    private $configurationManager;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * ConfigurationHelper constructor.
     * @param $environment
     * @param ConfigurationManager $configurationManager
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(
        $environment,
        ConfigurationManager $configurationManager,
        PropertyAccessorInterface $propertyAccessor
    )
    {
        $this->environment = $environment;
        $this->configurationManager = $configurationManager;
        $this->propertyAccessor = $propertyAccessor;
        $this->init();
    }

    /**
     *
     */
    private function init()
    {
        if(empty($this->environment))
        {
            throw new EnvNotFoundException("Environment name cannot be null");
        }

        $configuration = $this->configurationManager->findByEnvironment($this->environment);

        if(!$configuration instanceof Configuration)
        {
            $configuration = new Configuration();
            $configuration->setEnv($this->environment);
            $this->configurationManager->create($configuration);
        }

        $this->configuration = $configuration;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->propertyAccessor->getValue($this->configuration, $name);
    }
}