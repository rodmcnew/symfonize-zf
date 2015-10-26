<?php


namespace Reliv\SymfonizeZF\ContainerBridge;

use Reliv\SymfonizeZF\Module;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SymfonyContainerWithZFFallback extends ContainerBuilder
{
//    protected $zendServiceManager;

    public function compile()
    {
        /**
         * @todo find better way
         *
         * this is here because the container refuses to compile with
         * "unknown" zend service dependencies in it.
         */
        $passConfig = $this->getCompiler()->getPassConfig();
        $class = new \ReflectionClass($passConfig);
        $prop = $class->getProperty('removingPasses');
        $prop->setAccessible(true);
        $removingPasses = $prop->getValue($passConfig);
        //Remove CheckExceptionOnInvalidReferenceBehaviorPass
        unset($removingPasses[4]);
        $prop->setValue($passConfig, $removingPasses);
        parent::compile();
    }

//    public function setZendServiceManager(ServiceLocatorInterface $zendServiceManager)
//    {
//        $this->zendServiceManager = $zendServiceManager;
//    }

    /**
     * Returns true if the given service is defined.
     *
     * @param string $id The service identifier
     *
     * @return bool true if the service is defined, false otherwise
     *
     * @api
     */
    public function has($id)
    {
        return parent::has($id)
        || (Module::$zendServiceManager && Module::$zendServiceManager->has($id));
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     * @param int $invalidBehavior The behavior when the service does not exist
     *
     * @return object The associated service
     *
     * @throws \Exception
     *
     * @see Reference
     *
     * @api
     */
    public function get($id, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        if (!parent::has($id) && Module::$zendServiceManager) {
            return Module::$zendServiceManager->get($id);
        }

        return parent::get($id);
    }
}
