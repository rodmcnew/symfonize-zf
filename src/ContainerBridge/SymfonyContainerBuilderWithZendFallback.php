<?php


namespace Reliv\SymfonizeZF\ContainerBridge;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SymfonyContainerBuilderWithZendFallback extends ContainerBuilder
{
    protected $zendServiceManager;

    public function setZendServiceManager(ServiceLocatorInterface $zendServiceManager)
    {
        $this->zendServiceManager = $zendServiceManager;
    }

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
        || ($this->zendServiceManager && $this->zendServiceManager->has($id));
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
        if (!parent::has($id) && $this->zendServiceManager) {
            return $this->zendServiceManager->get($id);
        }

        return parent::get($id);
    }
}
