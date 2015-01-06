<?php
namespace SamFramework\Core;

use Zend\Authentication\AuthenticationService;
use Zend\XmlRpc\Client\Exception\HttpException;
use Zend\Db\Adapter\AdapterInterface;

class App
{

    protected static $AuthenticationService;

    protected static $writeDBAdapter;

    protected static $readDBAdapter;

    public static function getAuthenticationService()
    {
        if (! self::$AuthenticationService) {
            self::$AuthenticationService = new AuthenticationService();
        }
        return self::$AuthenticationService;
    }

    public static function isGuest()
    {
        return ! self::getAuthenticationService()->hasIdentity();
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

    /**
     *
     * @return AdapterInterface
     */
    public static function getWriteDBAdapter()
    {
        return App::$writeDBAdapter;
    }

    /**
     *
     * @return AdapterInterface
     */
    public static function getReadDBAdapter()
    {
        return App::$readDBAdapter;
    }

    /**
     *
     * @param AdapterInterface $writeDBAdapter
     */
    public static function setWriteDBAdapter(AdapterInterface $writeDBAdapter)
    {
        App::$writeDBAdapter = $writeDBAdapter;
    }

    /**
     *
     * @param AdapterInterface $readDBAdapter
     */
    public static function setReadDBAdapter(AdapterInterface $readDBAdapter)
    {
        App::$readDBAdapter = $readDBAdapter;
    }
}

