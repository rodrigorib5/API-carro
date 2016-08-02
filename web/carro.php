<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$carro = $app['controllers_factory'];



$carro->get("/", function() use ($app) {
    $sql ='SELECT * FROM modelo_carro as m';
    $carros = $app['db']->fetchAll($sql);
    
       
    if (empty($carros)) {
        return new JsonResponse('Infelizmente nada foi encontrado neste ano');
    }
    
    return new JsonResponse($carros, 200);
});

return $carro;