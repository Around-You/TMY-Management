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
        'routes' => require( __DIR__ . "/router.config.php" )
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Account' => 'Admin\Controller\AccountController',
            'Admin\Controller\Product\Product' => 'Admin\Controller\Product\ProductController',
            'Admin\Controller\Product\Category' => 'Admin\Controller\Product\CategoryController',
            'Admin\Controller\Product\Profile' => 'Admin\Controller\Product\ProfileController',
            'Admin\Controller\Product\Order' => 'Admin\Controller\Product\OrderController',
            'Admin\Controller\Test' => 'Admin\Controller\TestController',
            'Admin\Controller\OAuth' => 'Admin\Controller\OAuthController'
        ),
    ),
    'view_manager' => array(

        // The TemplatePathStack takes an array of directories. Directories
        // are then searched in LIFO order (it's a stack) for the requested
        // view script. This is a nice solution for rapid application
        // development, but potentially introduces performance expense in
        // production due to the number of static calls necessary.
        //
        // The following adds an entry pointing to the view directory
        // of the current module. Make sure your keys differ between modules
        // to ensure that they are not overwritten -- or simply omit the key!

        'template_map' => array(
            'admin/layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'admin/layout/login' => __DIR__ . '/../view/layout/login.phtml',
        ),
        'template_path_stack' => array(
             'admin' =>__DIR__ . '/../view',
        ),
    ),
    'system_params' => array(
        'upload' => array(
    	   'upload_file_path' => '/asserts/product/images',
    	   'hostname' => 'http://182.92.180.124/'
        )
    ),

);

