<?php
return array(
    'm.jd.rx-tech.cn' => array(
        'type' => 'Zend\Mvc\Router\Http\Hostname',
        'options' => array(
            'route' => ':5th.[:4th.]:3rd.:2nd.:1st', // domain levels from right to left

            'constraints' => array(
                '5th' => 'm',
                '4th' => '.*?', // optional level domain such as .ci, .dev or .test
                '3rd' => 'jd',
                '2nd' => 'rx-tech',
                '1st' => 'cn'
            ),

            // Purposely omit default controller and action
            // to let the child routes control the route match
            'defaults' => array(
                'module' => 'Mobile',
                'action' => 'index',
                '__NAMESPACE__' => 'Mobile\Controller'
            )
        ),
        'child_routes' => require (__DIR__ . "/child.router.config.php")
    ),


);
