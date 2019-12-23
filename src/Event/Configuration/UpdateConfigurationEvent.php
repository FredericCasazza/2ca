<?php


namespace App\Event\Configuration;

use App\Entity\Configuration;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UpdateConfigurationEvent
 * @package App\Event\Meal
 */
class UpdateConfigurationEvent extends Event
{

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * UpdateConfigurationEvent constructor.
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