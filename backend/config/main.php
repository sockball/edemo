<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'timeZone' => 'PRC',
    'language' => 'zh-CN',
    // 'defaultRoute' => 'site/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'authManager' => [
             'class' => 'yii\rbac\DbManager',
    /*        'itemTable' => 'auth_item', //权限列表
            'assignmentTable' => 'auth_assignment', //用户角色分配表
            'itemChildTable' => 'auth_item_child', //权限角色关联表
            'defaultRoles' => ['user'], //默认角色*/
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'suffix'          => '.html',
            'rules' => [
                '<controller:\w+>s' => '<controller>/index',
                '<controller:\w+>/<id:\d+>/<action:\w+>'    => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
