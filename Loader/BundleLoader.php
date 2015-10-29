<?php

namespace Reliv\SymfonizeZF\Loader;

use Reliv\SymfonizeZF\ContainerBridge\ContainerBridge;
use Reliv\SymfonizeZF\RouteBridge\RouteBridge;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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

        $containerLoader = new ContainerLoader();
        $loadedContainer = $containerLoader->loadDIConfig(
            $symfonizeConfig['cache_dir'],
            !$symfonizeConfig['debug'],
            $extensions,
            ContainerBridge::getContainer()
        );
        ContainerBridge::setContainer($loadedContainer);

        $routingLoader = new RoutingLoader();
        $loadedRouteCollection = $routingLoader->loadRouting(
            $bundlePaths,
            $symfonizeConfig['cache_dir'],
            !$symfonizeConfig['debug'],
            RouteBridge::getRouteCollection()
        );
        RouteBridge::setRouteCollection($loadedRouteCollection);
    }
}