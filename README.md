SymfonizeZF allows you to include Symfony 2 Bundles in your Zend Framework 2 application.

Symfonize provides the following 3 features to ensure the seamless integration of Symfony bundles:

####Bundle Loader
- Bundle loading configured through application.config.php. This caches DI config and routing for performance.

####Container Bridge
- A bridge between the SF DI container and the ZF service manager. Requests for ZF-module-defined services from the SF container work. Requests for SF-bundle-defined services from the ZF service manager also work. 

####Routing bridge
- A routing bridge that allows SF-bundle-defined routes to be dispatched to SF controllers. Symfony does not boot for ZF-defined routes.

####How to install and register Symfony Bundles:
```php
// In config/autoload/application.config.php
[
    'modules' => [
        //Add Symfonize modules to the end of your ZF modules list.
        'Reliv\\SymfonizeZF',
        'Reliv\\SymfonizeZFContainerBridge',
    ],
    'symfonize_zf' => [
        /**
         * This is the place to register your own bundles. This is similar to:
         * http://symfony.com/doc/current/cookbook/bundles/installation.html
         */
        'bundles' => [
            new \MyOwnFun\MyOwnFunBundle(),
        ],
        /**
         * These bundles do not load for zend routes. They only load if
         * a Symfony route is detected and Symfony is booted.
         */
        'symfony_only_bundles' => [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        ],
        /**
         * These bundles load only if environment is dev or test.
         * These bundles do not load for zend routes.
         */
        'symfony_only_dev_bundles' => [
            new \Symfony\Bundle\DebugBundle\DebugBundle(),
            new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
            new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
        ],
        'environment' => $onProd ? 'prod' : 'dev',
        /**
         * Should be true on dev, and false on prod. This disables caching.
         */
        'debug' => !$onProd,
        /**
         * Cache files are written here
         */
        'cache_dir' => __DIR__ . '/../data/SymfonizeZf',
        /**
         * Log files go here
         */
        'log_dir' => __DIR__ . '/../data/SymfonizeZf/log',
        /**
         * This can be changed if you want to control your own Symfony root
         */
        'symfony_root_dir' => __DIR__ . '/../vendor/reliv/symfonize-zf/SymfonyRoot'
    ]
]
