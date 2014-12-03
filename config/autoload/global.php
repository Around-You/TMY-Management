<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=wds;host=localhost',
        'database' => 'wds',
        'username' => 'root',
        'password' => '1qaz!QAZ',
        'hostname' => 'localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        )
    ),
    'service_manager' => array(
        'factories' => array(
            // 'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
            'Zend\Db\Adapter\Adapter' => function ($serviceManager)
            {
                $adapterFactory = new BjyProfiler\Db\Adapter\ProfilingAdapterFactory();
                $adapter = $adapterFactory->createService($serviceManager);

                \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($adapter);

                return $adapter;
            }
        )
    )
);