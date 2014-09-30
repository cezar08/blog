<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        ),
    ),
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'pgsql:dbname=blog;host=localhost',
    ),
    'acl' => array(
        'roles' => array(
            'visitante' => null,
            'redator' => 'visitante',
            'admin' => 'redator'
        ),
        'resources' => array(
            'Main\Controller\Index.index',
            'Main\Controller\Index.more',
            'Main\Controller\Comments.index',
            'Admin\Controller\Users.index',
            'Admin\Controller\Users.save',
            'Admin\Controller\Users.delete',
            'Admin\Controller\Categories.index',
            'Admin\Controller\Categories.save',
            'Admin\Controller\Categories.delete',
            'Admin\Controller\Posts.index',
            'Admin\Controller\Posts.save',
            'Admin\Controller\Posts.delete',
            'Admin\Controller\Index.delete',
            'Admin\Controller\Auth.index',
            'Admin\Controller\Auth.login',
            'Admin\Controller\Auth.logout',
        ),
        'privilege' => array(
            'visitante' => array(
                'allow' => array(
                    'Main\Controller\Index.index',
                    'Main\Controller\Index.more',
                    'Main\Controller\Comments.index',
                    'Admin\Controller\Auth.index',
                    'Admin\Controller\Auth.login',
                    'Admin\Controller\Auth.logout',
                )
            ),
            'redator' => array(
                'allow' => array(
                    'Admin\Controller\Posts.index',
                    'Admin\Controller\Posts.save',
                    'Admin\Controller\Posts.delete',
                )
            ),
            'admin' => array(
                'allow' => array(
                    'Admin\Controller\Users.index',
                    'Admin\Controller\Users.save',
                    'Admin\Controller\Users.delete',
                    'Admin\Controller\Categories.index',
                    'Admin\Controller\Categories.save',
                    'Admin\Controller\Categories.delete',
                )
            ),
        )
    )
);
