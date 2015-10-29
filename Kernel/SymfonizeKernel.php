<?php


namespace Reliv\SymfonizeZF\Kernel;

use Reliv\SymfonizeZF\ContainerBridge\SymfonyContainerWithZFFallback;
use Reliv\SymfonizeZF\Module;
use Reliv\SymfonizeZF\RouteBridge\RouteBridge;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonizeKernel extends Kernel
{
    /**
     * Initializes the service container.
     *
     * The cached version of the service container is used when fresh, otherwise the
     * container is built.
     *
     * @TODO investigate improving performance by un-commenting the caching below
     */
    protected function initializeContainer()
    {
        $this->container = $this->buildContainer();
        $this->useSymfonizeRoutes();
        $this->container->compile();
//        $class = $this->getContainerClass();
//        $cache = new ConfigCache($this->getCacheDir().'/'.$class.'.php', $this->debug);
//        $fresh = true;
//        if (!$cache->isFresh()) {
//            $container = $this->buildContainer();
//            $container->compile();
//            $this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass());
//
//            $fresh = false;
//        }

//        require_once $cache->getPath();
//
//        $this->container = new $class();
//        $this->container=$container;
        $this->container->set('kernel', $this);
//
//        if (!$fresh && $this->container->has('cache_warmer')) {
//            $this->container->get('cache_warmer')->warmUp($this->container->getParameter('kernel.cache_dir'));
//        }
    }

    /**
     * Add symfonize loaded routes to symfony
     */
    public function useSymfonizeRoutes()
    {
        /**
         * @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router
         */
        $this->container->set('router', RouteBridge::getRouteCollection());

    }

    public function getCacheDir()
    {
        return Module::$symfonizeConfig['cache_dir'] . '/symfony-cache';
    }

    public function getLogDir()
    {
        return Module::$symfonizeConfig['log_dir'];
    }

    /**
     * Gets a new ContainerBuilder instance used to build the service container.
     *
     * @return ContainerBuilder
     */
    protected function getContainerBuilder()
    {
        $container = new SymfonyContainerWithZFFallback(new ParameterBag($this->getKernelParameters()));

        if (class_exists('ProxyManager\Configuration')
            && class_exists('Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator')
        ) {
            $container->setProxyInstantiator(new RuntimeInstantiator());
        }

        return $container;
    }

    public function registerBundles()
    {
        $bundles = array_merge(
            Module::$symfonizeConfig['symfony_only_bundles'],
            Module::$symfonizeConfig['bundles']
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles = array_merge(
                $bundles,
                Module::$symfonizeConfig['symfony_only_dev_bundles']
            );
        }

        return $bundles;
    }

    /**
     * Returns the app's root dir
     *
     * @return string
     */
    public function getSymfonyRootDir()
    {
        return Module::$symfonizeConfig['symfony_root_dir'];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getSymfonyRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
