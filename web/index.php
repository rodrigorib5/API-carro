<?php

// web/index.php
require_once __DIR__.'/../vendor/autoload.php';
require_once 'bootstrap.php';

$app->get('/', function () use ($app) {
    
    $rodrigo = $app['db']->fetchAll('SELECT * FROM modelo_carro');
    
    return 'Hello ';
});

$app->mount('/carro', include 'carro.php');

$app->run();