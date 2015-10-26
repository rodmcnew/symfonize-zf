<?php

namespace Reliv\SymfonizeZF\Config;

use Reliv\SymfonizeZF\ContainerBridge\ContainerBridge;
use Reliv\SymfonizeZF\RouteBridge\RouteBridge;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Loader
{
    protected static $cacheDirPath;
    protected static $cacheEnabled = false;
    protected static $extensions = [];
    protected static $bundlePaths = [];

    public static function addBundle(Bundle $bundle)
    {
        $extension = $bundle->getContainerExtension();
        if ($extension) {
            self::$extensions[] = $extension;
        }
        self::$bundlePaths[] = $bundle->getPath();
    }

    /**
     * Setter
     *
     * @param $cacheEnabled
     */
    public static function setCacheEnabled($cacheEnabled)
    {
        self::$cacheEnabled = $cacheEnabled;
    }

    /**
     * Setter
     *
     * @param $cacheDirPath
     */
    public static function setCacheDirPath($cacheDirPath)
    {
        self::$cacheDirPath = $cacheDirPath;
    }

    public static function getCacheDirPath()
    {
        return self::$cacheDirPath;
    }

    /**
     * Called after all addConfigFolder() calls, this function
     * actually loads all the symfony config files such as
     * services.yml
     */
    public static function loadAll()
    {
        $containerLoader = new ContainerLoader();
        $loadedContainer = $containerLoader->loadDIConfig(
            self::$cacheDirPath,
            self::$cacheEnabled,
            self::$extensions,
            ContainerBridge::getContainer()
        );
        ContainerBridge::setContainer($loadedContainer);

        $routingLoader = new RoutingLoader();
        $loadedRouteCollection = $routingLoader->loadRouting(
            self::$bundlePaths,
            self::$cacheDirPath,
            self::$cacheEnabled,
            RouteBridge::getRouteCollection()
        );
        RouteBridge::setRouteCollection($loadedRouteCollection);
    }
}
