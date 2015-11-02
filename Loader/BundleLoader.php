<?php

namespace Reliv\SymfonizeZF\Loader;

use Reliv\SymfonizeZFContainerBridge\ContainerBridge;
use Reliv\SymfonizeZF\RouteBridge\RouteBridge;

class BundleLoader
{
    /**
     * Called after all addConfigFolder() calls, this function
     * actually loads all the symfony config files such as
     * services.yml
     */
    public function loadBundles($symfonizeConfig)
    {
        $extensions = [];
        $bundlePaths = [];
        foreach ($symfonizeConfig['bundles'] as $bundle) {
            $extension = $bundle->getContainerExtension();
            if ($extension) {
                $extensions[] = $extension;
            }
            $bundlePaths[] = $bundle->getPath();
        }

        $cacheDir = $symfonizeConfig['cache_dir'] . '/symfonize-cache';

        $containerLoader = new ContainerLoader();
        $loadedContainer = $containerLoader->loadDIConfig(
            $cacheDir,
            !$symfonizeConfig['debug'],
            $extensions,
            ContainerBridge::getContainer()
        );
        ContainerBridge::setContainer($loadedContainer);

        $routingLoader = new RoutingLoader();
        $loadedRouteCollection = $routingLoader->loadRouting(
            $bundlePaths,
            $cacheDir,
            !$symfonizeConfig['debug'],
            RouteBridge::getRouteCollection()
        );
        RouteBridge::setRouteCollection($loadedRouteCollection);
    }
}
