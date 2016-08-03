<?php

// web/index.php
require_once __DIR__.'/../vendor/autoload.php';
require_once 'bootstrap.php';

$app->get('/login', function () use ($app) {
    return $app['twig']->render('usage.twig');
});

$app->mount('/admin', include 'admin.php');
$app->mount('/API/carro', include '/../API/carro.php');
$app->mount('/API/carroDao', include '/../API/carroDao.php');

$app->run();