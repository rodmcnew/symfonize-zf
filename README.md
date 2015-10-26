SymfonizeZF allows you to include Symfony 2 Bundles in your Zend Framework 2 application.

Symfonize provides the following 3 features to ensure the seamless integration of Symfony bundles:

####Bundle Loader
- Bundle loading configured through application.config.php. This caches DI config and routing for performance.

####Container Bridge
- A bridge between the SF DI container and the ZF service maneger. Requests for ZF-module-defined services from the SF container work. Requests for SF-bundle-defined services from the ZF service manager also work. 

####Routing bridge
- A routing bridge that allows SF-bundle-defined routes to be dispatched to SF controllers. Symfony does not boot for ZF-defined routes.

####How to install and register Symfony Bundles:
```php
// In config/autoload/application.config.php
[
    'modules' => [
        //Add Symfonize to the end of your ZF modules list.
        'Reliv\SymfonizeZF'
    ],
    'symfonize_zf' => [
        /**
         * This is the place to register bundles.This is similar to:
         * http://symfony.com/doc/current/cookbook/bundles/installation.html
         */
        'bundles' => [
            new \Fun\FunBundle(),
            new \SuperFun\SuperFunBundle()
        ],
        /**
          * This should be false on dev, and true on prod.
          */
        'cache_enabled' => $setMeProperly,
        'cache_dir' => __DIR__ . '/../data/SymfonizeZF'
    ]
]
