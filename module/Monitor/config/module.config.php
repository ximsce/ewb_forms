<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Monitor\Controller\Monitor' => 'Monitor\Controller\MonitorController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'monitor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/monitor[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Monitor\Controller\Monitor',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'monitor' => __DIR__ . '/../view/',
        ),
    ),
);
