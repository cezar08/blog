<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Main\Controller\Index' => 'Main\Controller\IndexController',
            'Main\Controller\Comments' => 'Main\Controller\CommentsController',            
        ),
    ),

    'router' => array(
        'routes' => array(
            'main' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/main',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Main\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'module' => 'main'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'child_routes' => array(//permite mandar dados pela url 
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Session' => function($sm) {
                return new Zend\Session\Container('Blog');
            },            
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'main' => __DIR__ . '/../view',
        ),
    ),
);
