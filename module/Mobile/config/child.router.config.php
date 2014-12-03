<?php
return array(

    'default' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/:storeId',
            'constraints' => array(
                'store' => '[0-9]+'
            ),
            'defaults' => array(
                'module' => 'Mobile',
                'controller' => 'index',
                'action' => 'index',
                '__NAMESPACE__' => 'Mobile\Controller'
            )
        )
    ),
    'g' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/g/:categoryId',
            'constraints' => array(
                'categoryId' => '[0-9]+'
            ),
            'defaults' => array(
                'controller' => 'product',
                'action' => 'list',
                '__NAMESPACE__' => 'Mobile\Controller\Product'
            )
        )
    ),
    'p' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/p[/:action]/:productId',
            'constraints' => array(
                'productId' => '[0-9]+',
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
            ),
            'defaults' => array(
                'controller' => 'product',
                'action' => 'single',
                '__NAMESPACE__' => 'Mobile\Controller\Product'
            )
        )
    )
)
;
