<?php

namespace Reliv\SymfonizeZF;

use Reliv\SymfonizeZF\Loader\BundleLoader;
use Zend\Mvc\MvcEvent;

class Module
{
    protected $appConfigPath = 'config/application.config.php';
    public static $symfonizeConfig = [];
    public static $zendServiceManager;

    /**
     * Constructor
     */
    public function __construct()
    {
        //Ignore if no file to keep ZF controller unit tests working
        if (file_exists($this->appConfigPath)) {
            $appConfig=require($this->appConfigPath);
            self::$symfonizeConfig = $appConfig['symfonize_zf'];

        }
        $bundleLoader = new BundleLoader();
        $bundleLoader->loadBundles(self::$symfonizeConfig);
    }

    /**
     * Runs when ZF boots
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        /**
         * Ensure Service manager gets set during
         * symfony-handled routes.
         */
        self::$zendServiceManager = $e->getApplication()->getServiceManager();
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
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'controller_plugins' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'filters' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'form_elements' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'hydrators' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'input_filters' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'route_manager' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
                'invokables' => [
                    'symfonize_zf.route_bridge' =>
                        'Reliv\SymfonizeZF\RouteBridge\RouteBridge'
                ]
            ],
            'serializers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'service_manager' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ]
            ],
            'validators' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'view_helpers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'log_processors' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ],
            'log_writers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZF\ContainerBridge\ContainerBridge'
                ],
            ]
        ];
    }
}
