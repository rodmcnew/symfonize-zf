<?php

namespace Reliv\SymfonizeZF\RouteBridge;

use App\Model\ProductionCheck;
use Reliv\SymfonizeZF\Kernel\SymfonizeKernel;
use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

class SymfonyDispatcher
{
    /**
     * @TODO use the passed params rather than route matching
     * a second time in symfony.
     *
     * @param array $params
     */
    public function dispatchRouteToSymfony($params)
    {
//        $loader = require_once __DIR__.'/../SymfonyRoot/app/bootstrap.php.cache';
//        $apcLoader = new ApcClassLoader('SymfonizeZF', $loader);
//        $loader->unregister();
//        $apcLoader->register(true);
        //@TODO get ProductionCheck out of here
        $debug = ProductionCheck::onLocal();
        $kernel = new SymfonizeKernel(
            ProductionCheck::onLocal() ? 'dev' : 'prod',
            $debug
        );
        if (!$debug) {
            $kernel->loadClassCache();
        }
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
        exit;
    }
}
