<?php

namespace Reliv\SymfonizeZF\ContainerBridge;

use Reliv\SymfonizeZF\Module;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContainerBridge implements AbstractFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * Returns the Symfony Container
     *
     * @param ServiceLocatorInterface $zendServiceManager
     * @return ContainerInterface
     */
    public static function getContainer($zendServiceManager = null)
    {
        if (!self::$container) {
            self::$container = new SymfonyContainerWithZFFallback();
        }

        if (!Module::$zendServiceManager && $zendServiceManager) {
            if ($zendServiceManager->has('serviceLocator')) {
                $zendServiceManager = $zendServiceManager->get('serviceLocator');
            }
            Module::$zendServiceManager = $zendServiceManager;
        }

        return self::$container;
    }

    /**
     * Setter for container
     *
     * @param ContainerInterface $containerInterface
     */
    public static function setContainer(ContainerInterface $containerInterface)
    {
        self::$container = $containerInterface;
    }

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getContainer($serviceLocator)->has($requestedName);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getContainer($serviceLocator)->get($requestedName);
    }
}
