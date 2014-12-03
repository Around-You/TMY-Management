<?php
namespace SamFramework\Core;

use Zend\ServiceManager\ServiceLocatorInterface;

interface AutoBuildInterface
{
    public static function getInstance( ServiceLocatorInterface $sl );
}

