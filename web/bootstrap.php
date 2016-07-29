<?php

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'seu_carro',
        'user'      => 'root',
        'password'  => '',
        'charset'   => 'utf8',
    ),
));

return $app;