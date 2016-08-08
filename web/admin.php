<?php

$admin = $app['controllers_factory'];

$admin->get('/cadastro/modelo', function () use ($app) {

    return $app['twig']->render('modelo.twig');

});

$admin->get('/cadastro/acessorio', function () use ($app) {
    
    return $app['twig']->render('acessorio.twig');
    
});

$admin->get('/cadastro/montar', function () use ($app) {
    return $app['twig']->render('montar.twig');
});

$admin->get('/auth', function () use ($app) {
    return $app['twig']->render('auth.twig');
});

return $admin;