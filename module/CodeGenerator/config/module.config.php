<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CodeGenerator\Controller\Index' => 'CodeGenerator\Controller\IndexController',
            'CodeGenerator\Controller\Login' => 'CodeGenerator\Controller\LoginController',
            'CodeGenerator\Controller\Test' => 'CodeGenerator\Controller\TestController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'CodeGenerator' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/cg',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CodeGenerator\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'cg' => __DIR__ . '/../view',
        ),
    ),

//     'view_helpers' => array(
//         'invokables' => array(
//             'formRow' => 'BootsrapView\Form\View\Helper\FormRow',
//         ),
//     ),

);