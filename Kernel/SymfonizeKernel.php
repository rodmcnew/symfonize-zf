<?php


namespace Reliv\SymfonizeZF\Kernel;

use Reliv\SymfonizeZF\Loader\Loader;
use Reliv\SymfonizeZF\ContainerBridge\ContainerBridge;
use Reliv\SymfonizeZF\ContainerBridge\SymfonyContainerWithZFFallback;
use Reliv\SymfonizeZF\Module;
use Reliv\SymfonizeZF\RouteBridge\RouteBridge;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollection;

class SymfonizeKernel extends Kernel
{
    /**
     * Initializes the service container.
     *
     * The cached version of the service container is used when fresh, otherwise the
     * container is built.
     *
     * @TODO check if performance can be improved here by uncommenting caching.
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
        //@TODO get from config;
//        return Loader::getCacheDirPath();
        return 'data/SymfonizeZF' . '/symfony';
    }

    public function getLogDir()
    {
        return $this->getCacheDir() . '/log';
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
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
//            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
//            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
//            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
//            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
//            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
//            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
//            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        $bundles = array_merge($bundles, Module::$bundles);

        return $bundles;
    }

    /**
     * Returns the app's root dir
     *
     * @return string
     */
    public function getSymfonyRootDir()
    {
        return __DIR__ . '/../SymfonyRoot';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getSymfonyRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
