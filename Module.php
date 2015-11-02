<?php

namespace Reliv\SymfonizeZF;

use Reliv\SymfonizeZF\Loader\BundleLoader;
use Zend\Mvc\MvcEvent;

class Module
{
    protected $appConfigPath = 'config/application.config.php';
    public static $symfonizeConfig = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        //Ignore if no file to keep ZF controller unit tests working
        if (file_exists($this->appConfigPath)) {
            $appConfig = require($this->appConfigPath);
            self::$symfonizeConfig = $appConfig['symfonize_zf'];

        }
        $bundleLoader = new BundleLoader();
        $bundleLoader->loadBundles(self::$symfonizeConfig);
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
            'route_manager' => [
                'invokables' => [
                    'symfonize_zf.route_bridge' =>
                        'Reliv\SymfonizeZF\RouteBridge\RouteBridge'
                ]
            ]
        ];
    }
}
