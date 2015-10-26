<?php

namespace Reliv\SymfonizeZF;

use Reliv\SymfonizeZf\Loader\Loader;
use Zend\Mvc\MvcEvent;

class Module
{
    protected $appConfigPath = 'config/application.config.php';
    public static $bundles = [];
    public static $zendServiceManager;

    public function onBootstrap(MvcEvent $e)
    {
        /**
         * Ensure Service manager gets set during
         * symfony-handled routes.
         */
        self::$zendServiceManager=$e->getApplication()->getServiceManager();
//        ContainerBridge::getContainer();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        //Ignore if no file to keep ZF controller unit tests working
        if (file_exists($this->appConfigPath)) {
            $this->loadAppConfig(require($this->appConfigPath));

        }
        Loader::loadAll();
    }

    public function loadAppConfig($appConfig)
    {
        if (isset($appConfig['symfonize_zf'])) {
            $symfonizeConfig = $appConfig['symfonize_zf'];
            $this->processCacheConfig($symfonizeConfig);
            $this->processBundleConfig($symfonizeConfig);
        }
    }

    public function processBundleConfig($symfonizeConfig)
    {
        $bundles = $symfonizeConfig['bundles'];
        self::$bundles = $bundles;
        foreach ($bundles as $bundle) {
            Loader::addBundle($bundle);
        }
    }

    public function processCacheConfig($symfonizeConfig)
    {
        if (isset($symfonizeConfig['cache_enabled'])
            && $symfonizeConfig['cache_enabled']
        ) {
            Loader::setCacheEnabled(true);
        }
        Loader::setCacheDirPath($symfonizeConfig['cache_dir'].'/loader');
    }

    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return [
            'router' => [
                'routes' => [
                    'symfonize_zf.route_bridge' => [
                        'type' => 'symfonize_zf.route_bridge',
                    ],
                ]
            ],
            'controllers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'controller_plugins' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'filters' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'form_elements' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'hydrators' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'input_filters' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'route_manager' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
                'invokables' => [
                    'symfonize_zf.route_bridge' =>
                        'Reliv\SymfonizeZF\RouteBridge\RouteBridge'
                ]
            ],
            'serializers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'service_manager' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ]
            ],
            'validators' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'view_helpers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'log_processors' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'log_writers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ]
        ];
    }
}
