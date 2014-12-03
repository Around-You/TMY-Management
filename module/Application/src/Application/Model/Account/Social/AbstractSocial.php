<?php
namespace Application\Model\Account\Social;

use SamFramework\Core\AbstractAutoBuilder;
use Zend\ServiceManager\ServiceLocatorInterface;
abstract class AbstractSocial extends AbstractAutoBuilder
{
    public abstract function getToken($code);
    function http($url, $postfields = '', $method = 'GET', $headers = array())
    {
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        if ($method == 'POST') {
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if ($postfields != '')
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        curl_close($ci);
        $json_r = array();
        if ($response != '')
            $json_r = json_decode($response, true);
        return $json_r;
    }

    public static function getInstance(ServiceLocatorInterface $serviceLocator)
    {
       $class = __CLASS__;
       return new $class();
    }
}

