<?php


namespace App\Event\Configuration;

use App\Entity\Configuration;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class CreateConfigurationEvent
 * @package App\Event\Configuration
 */
class CreateConfigurationEvent extends Event
{

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * CreateConfigurationEvent constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration =$configuration;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }
}