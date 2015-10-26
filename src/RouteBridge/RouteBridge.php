<?php

namespace Reliv\SymfonizeZF\RouteBridge;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\RequestInterface as Request;

class RouteBridge implements RouteInterface
{
    /**
     * @var RouteCollection
     */
    public static $routeCollection;

    public static function setRouteCollection($routeCollection)
    {
        self::$routeCollection = $routeCollection;
    }

    /**
     * @return RouteCollection
     */
    public static function getRouteCollection()
    {
        if (!self::$routeCollection) {
            self::$routeCollection = new RouteCollection();
        }

        return self::$routeCollection;
    }

    /**
     * Create a new route with given options.
     *
     * @param  array|\Traversable $options
     * @return static
     */
    public static function factory($options = [])
    {
        return new static();
    }

    /**
     * Assemble the route.
     *
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = [], array $options = [])
    {
        // TODO: Implement assemble() method.
    }

    /**
     * Get a list of parameters used while assembling.
     *
     * @return array
     */
    public function getAssembledParams()
    {
        return [];
    }

    /**
     * Match a given request.
     *
     * @param  Request $request
     * @return RouteMatch|null
     */
    public function match(Request $request)
    {
        try {
            //This line is odd but prevents a warning
//            self::$routeCollection->request = $request;

            $params = self::$routeCollection
                ->match($request->getUri()->getPath());
        } catch (ResourceNotFoundException $e) {
            return null;
        }

        $dispatcher = new SymfonyDispatcher();
        $dispatcher->dispatchRouteToSymfony($params);
    }
}
