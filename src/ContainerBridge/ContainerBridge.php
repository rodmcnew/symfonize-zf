<?php

namespace Reliv\SymfonizeZF\ContainerBridge;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContainerBridge implements AbstractFactoryInterface
{
    protected static $container;

    /**
     * @param ServiceLocatorInterface $zendServiceManager
     * @return ContainerInterface
     */
    public static function getContainer($zendServiceManager = null)
    {
        if (!self::$container instanceof ContainerInterface) {
            self::$container = new SymfonyContainerBuilderWithZendFallback();
        }

        //@TODO ONLY DO THIS ONCE FOR PERFORMANCE REASONS
        if ($zendServiceManager instanceof ServiceLocatorInterface) {
            if ($zendServiceManager->has('serviceLocator')) {
                $zendServiceManager = $zendServiceManager->get('serviceLocator');
            }
            self::$container->setZendServiceManager($zendServiceManager);
        }

        return self::$container;
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
