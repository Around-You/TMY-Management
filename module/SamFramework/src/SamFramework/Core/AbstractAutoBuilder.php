<?php
namespace SamFramework\Core;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

abstract class AbstractAutoBuilder implements AutoBuildInterface, ServiceLocatorAwareInterface
{

    protected $service_manager;

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->service_manager = $serviceLocator;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->service_manager;
    }

    public static function getInstance(ServiceLocatorInterface $serviceLocator)
    {
        throw new \Exception('Not Used');
    }
}

