<?php


namespace App\Factory;

use Psr\Container\ContainerInterface;

/**
 * Class HelperFactory
 * @package App\Factory
 */
class HelperFactory
{
    const HELPER_NAMESPACE = 'App\\Helper\\';
    const HELPER_SUFFIXE = 'Helper';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * HelperFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
    }

    /**
     * @param $helper
     * @return mixed
     */
    public function get($helper)
    {
        $service = self::HELPER_NAMESPACE.ucfirst($helper).self::HELPER_SUFFIXE;
        return $this->container->get($service);
    }


}