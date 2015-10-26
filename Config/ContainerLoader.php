<?php

namespace Reliv\SymfonizeZF\Config;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
//Following class will exist after runtime. Do not delete it.
use Reliv\SymfonizeZF\ContainerBridge\CachedSymfonyContainerWithZFFallback;

class ContainerLoader
{
    /**
     * Load DI config for all symfonized modules
     *
     * @param string $cacheDirPath
     * @param boolean $cacheEnabled
     * @param array $extensions
     * @param ContainerInterface $container
     * @return ContainerInterface
     */
    public function loadDIConfig($cacheDirPath, $cacheEnabled, $extensions, ContainerInterface $container)
    {
        $cacheClass = 'CachedSymfonyContainerWithZFFallback';
        $cachePath = $cacheDirPath . '/' . $cacheClass . '.php';
        $cache = new ConfigCache($cachePath, !$cacheEnabled);

        if (!$cache->isFresh()) {
            foreach ($extensions as $extension) {
                $extension->load([], $container);
            }

            $dumper = new PhpDumper($container);

            $cache->write($dumper->dump([
                'class' => $cacheClass,
                'base_class' => '\Reliv\SymfonizeZF\ContainerBridge\SymfonyContainerWithZFFallback',
            ]));
        }
        require $cachePath;

        return new $cacheClass();
    }
}
