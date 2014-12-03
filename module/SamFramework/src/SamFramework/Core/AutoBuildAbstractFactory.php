<?php
namespace SamFramework\Core;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AutoBuildAbstractFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName( ServiceLocatorInterface $serviceLocator, $name, $requestedName )
    {
        if ( class_exists( $requestedName ) ) {
            $class = new \ReflectionClass( $requestedName );
            if ( $class->implementsInterface( 'SamFramework\Core\AutoBuildInterface' ) ) {
                return true;
            }
        }
        return false;
    }

    public function createServiceWithName( ServiceLocatorInterface $serviceLocator, $name, $requestedName )
    {
        return $requestedName::getInstance( $serviceLocator );
    }
}

