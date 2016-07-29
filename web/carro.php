<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$carro = $app['controllers_factory'];

$carro->get("/", function() use ($app) {
    $sql ='SELECT * FROM modelo_carro as m';
    $carros = $app['db']->fetchAll($sql);
    
    foreach ($carros as $value) {
        $sql ='SELECT * FROM modelo_carro as m
                    INNER JOIN detalhe_carro as d
                        ON d.idModeloCarro = m.idModeloCarro
                    WHERE m.idModeloCarro = ?';
        
        $acessorios = $app['db']->fetchAll($sql, array($value['idModeloCarro']));

        $carros['acessorios'] = $acessorios;
    }
       
    if (empty($carros)) {
        return new JsonResponse('Infelizmente nada foi encontrado neste ano');
    }
    
    return new JsonResponse($carros);
});

$carro->get("/modelo/{modelo}", function($modelo) use ($app) {

    $sql ='SELECT * FROM modelo_carro where noModeloCarro = ?';
    
    $carros = $app['db']->fetchAll($sql, array($modelo));
    
    if (empty($carros)) {
        return new JsonResponse('Infelizmente nada foi encontrado o modelo informado');
    }
    
    return new JsonResponse($carros);
});

$carro->get("/ano/{ano}", function($ano) use ($app) {
    
    $sql = 
        'SELECT * FROM detalhe_carro as d
            INNER JOIN modelo_carro as m
                ON m.idModeloCarro = d.idModeloCarro
            INNER JOIN acessorio_carro as a 
                ON a.idAcessorioCarro = d.idAcessorioCarro
         WHERE m.anoModelo = ? 
         group by m.idModeloCarro';
    
    $carros = $app['db']->fetchAll($sql, array($ano));
    
    if (empty($carros)) {
        return new JsonResponse('Infelizmente nada foi encontrado neste ano');
    }
    
    return new JsonResponse($carros);
});

$carro->get("/aro/{aro}", function($aro) use ($app) {

    $sql =
        'SELECT * FROM detalhe_carro as d
            INNER JOIN modelo_carro as m
                ON m.idModeloCarro = d.idModeloCarro
            INNER JOIN acessorio_carro as a
                ON a.idAcessorioCarro = d.idAcessorioCarro
         WHERE m.nuAroRoda = ? 
         group by m.idModeloCarro';

    $carros = $app['db']->fetchAll($sql, array($aro));

    if (empty($carros)) {
        return new JsonResponse('Infelizmente nao foi encontrado modelo com este aro');
    }

    return new JsonResponse($carros);
});

$carro->get("/acessorio/{opcional}", function($opcional) use ($app) {

    $sql =
        'SELECT * FROM detalhe_carro as d
            INNER JOIN modelo_carro as m
                ON m.idModeloCarro = d.idModeloCarro
            INNER JOIN acessorio_carro as a
                ON a.idAcessorioCarro = d.idAcessorioCarro
            WHERE a.isOpcional = ?';

    $carros = $app['db']->fetchAll($sql, array($opcional));

    if (empty($carros)) {
        return new JsonResponse('Infelizmente nada foi encontrado modelo com este acessorio');
    }

    return new JsonResponse($carros);
});

$carro->get("/acessorio/{acessorio}", function($acessorio) use ($app) {

    $sql =
        'SELECT * FROM detalhe_carro as d
            INNER JOIN modelo_carro as m
                ON m.idModeloCarro = d.idModeloCarro
            INNER JOIN acessorio_carro as a
                ON a.idAcessorioCarro = d.idAcessorioCarro
             WHERE a.noAcessorio = ?
             group by m.idModeloCarro';

    $carros = $app['db']->fetchAll($sql, array($acessorio));

    if (empty($carros)) {
        return new JsonResponse('Infelizmente nada foi encontrado modelo com este acessorio');
    }

    return new JsonResponse($carros);
});

$carro->get("/acessorio/{acessorio}/{opcional}", function($acessorio, $opcional) use ($app) {

    $sql =
        'SELECT * FROM detalhe_carro as d
            INNER JOIN modelo_carro as m
                ON m.idModeloCarro = d.idModeloCarro
            INNER JOIN acessorio_carro as a
                ON a.idAcessorioCarro = d.idAcessorioCarro
            WHERE a.noAcessorio = ? and a.isOpcional = ?
            group by m.idModeloCarro';

    $carros = $app['db']->fetchAll($sql, array($acessorio, $opcional));

    if (empty($carros)) {
        return new JsonResponse('Infelizmente nada foi encontrado modelo com este acessorio');
    }

    return new JsonResponse($carros);
});

return $carro;