<?php

namespace Reliv\SymfonizeZF;

use Reliv\SymfonizeZF\ContainerBridge\ContainerBridge;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Extend this class with you ZF module to Symfonize your module
 *
 * Class SymfonizedModule
 * @package Reliv\SymfonizeZF
 */
class SymfonizedModule
{
    /**
     * Looks for and loads the standard symfony bundle
     * config files like services.yml and routing.yml
     * Call this in your ZF module's getConfig function.
     *
     * @param string $zendModuleClassDirectory pass "__DIR__" here
     */
    public function loadSymfonyConfigFiles($zendModuleClassDirectory)
    {
        $configDir = $zendModuleClassDirectory . '/../config';
        $loader = new YamlFileLoader(ContainerBridge::getContainer(), new FileLocator(__DIR__));
        $loader->load($configDir . '/services.yml');
    }
}
