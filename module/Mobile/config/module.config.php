<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => require (__DIR__ . "/router.config.php")
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Mobile\Controller\Index' => 'Mobile\Controller\IndexController',
            'Mobile\Controller\Product\Product' => 'Mobile\Controller\Product\ProductController'
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'mobile/layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'mobile/layout/footer' => __DIR__ . '/../view/layout/footer.phtml',
            'mobile/layout/breadcrumbs' => __DIR__ . '/../view/layout/breadcrumbs.phtml'
        ),
        'template_path_stack' => array(
            'mobile' => __DIR__ . '/../view'
        )
    ),

    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    )
);
