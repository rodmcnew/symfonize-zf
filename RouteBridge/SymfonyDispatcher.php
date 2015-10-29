<?php

namespace Reliv\SymfonizeZF\RouteBridge;

use App\Model\ProductionCheck;
use Reliv\SymfonizeZF\Kernel\SymfonizeKernel;
use Reliv\SymfonizeZF\Module;
use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

class SymfonyDispatcher
{
    /**
     * This function has Symfony handle the incoming request.
     * @TODO investigate improving performance but un-commenting the caching below
     */
    public function dispatchRouteToSymfony()
    {
//        $loader = require_once __DIR__.'/../SymfonyRoot/app/bootstrap.php.cache';
//        $apcLoader = new ApcClassLoader('SymfonizeZF', $loader);
//        $loader->unregister();
//        $apcLoader->register(true);
        $kernel = new SymfonizeKernel(
            Module::$symfonizeConfig['environment'],
            Module::$symfonizeConfig['debug']
        );
        if (!Module::$symfonizeConfig['debug']) {
            $kernel->loadClassCache();
        }
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
        //Do not run the rest of ZF since Symfony is handling this request.
        exit;
    }
}
