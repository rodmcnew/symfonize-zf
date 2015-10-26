<?php


namespace Reliv\SymfonizeZF\Config;

use Reliv\SymfonizeZF\RouteBridge\RouteBridge;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader
{
    /**
     * @param $bundlePaths
     * @param $cacheDirPath
     * @param $cacheEnabled
     * @param RouteCollection $routeCollection
     * @return RouteCollection
     */
    public function loadRouting($bundlePaths, $cacheDirPath, $cacheEnabled, RouteCollection $routeCollection)
    {
        $cacheClass = 'CachedRouteCollection';
        $cachePath = $cacheDirPath . '/' . $cacheClass . '.php';
        $cache = new ConfigCache($cachePath, !$cacheEnabled);

        if (!$cache->isFresh()) {
            foreach ($bundlePaths as $path) {
                if (file_exists($path . '/Resources/config/routing.yml')) {
                    $loader = new YamlFileLoader(new FileLocator($path . '/Resources/config'));
                    $routeCollection->addCollection($loader->load('routing.yml'));
                }
            }

            $dumper = new PhpMatcherDumper($routeCollection);

            $cache->write($dumper->dump([
                'class' => $cacheClass,
//                'base_class' => 'Symfony\Component\Routing\RouteCollection',
                'base_class' => 'Reliv\SymfonizeZF\RouteBridge\SymfonizeRouteCollection',
            ]));
        }
        require $cachePath;

        $routeCollection = new $cacheClass(new RequestContext('/'));

        return $routeCollection;
    }
}
