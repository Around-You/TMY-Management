<?php
namespace SamFramework\Core;

use Zend\Authentication\AuthenticationService;
use Zend\XmlRpc\Client\Exception\HttpException;

class App
{

    protected static $AuthenticationService;

    public static function getAuthenticationService()
    {
        if (! self::$AuthenticationService) {
            self::$AuthenticationService = new AuthenticationService();
        }
        return self::$AuthenticationService;
    }

    public static function isGuest()
    {
        return !self::getAuthenticationService()->hasIdentity();
    }

    public static function getUser()
    {
        $authenticationService = self::getAuthenticationService();

        if ($authenticationService->hasIdentity()) {
            return $authenticationService->getIdentity();
        } else {
        	throw new HttpException('You should login first', 403);
        }
    }
    public static function clearUser()
    {
        $authenticationService = self::getAuthenticationService();
        $authenticationService->clearIdentity();
    }
}

