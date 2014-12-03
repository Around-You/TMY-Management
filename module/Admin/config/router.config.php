<?php
return array(
    'wds.admin.local' => array(
        'type' => 'Zend\Mvc\Router\Http\Hostname',
        'options' => array(
            'route' => ':4th.[:3rd.]:2nd.:1st', // domain levels from right to left
            'constraints' => array(
                '4th' => 'wds',
                '3rd' => '.*?', // optional 3rd level domain such as .ci, .dev or .test
                '2nd' => 'admin',
                '1st' => 'local'
            ),

            // Purposely omit default controller and action
            // to let the child routes control the route match
            'defaults' => array(
                'module' => 'Admin',
                'action' => 'index'
            )
        ),
        'child_routes' => require (__DIR__ . "/child.router.config.php")
    ),

    'admin' => array(
        'type' => 'literal',
        'may_terminate' => true,
        'options' => array(
            'route' => '/admin',
            'defaults' => array(
                'module' => 'Admin',
                '__NAMESPACE__' => 'Admin\Controller',
                'controller' => 'index',
                'action' => 'index'
            )
        ),
        'child_routes' => require (__DIR__ . "/child.router.config.php")
    )
);
