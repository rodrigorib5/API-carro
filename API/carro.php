<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$carro = $app['controllers_factory'];

$carro->get("/modelo", function() use ($app) {
    $sql ='SELECT * FROM modelo_carro as m';
    $carros = $app['db']->fetchAll($sql);
           
    if (empty($carros)) {
        return new JsonResponse(array("mensagem" => "Infelizmente nada foi encontrado"));
    }
    
    return new JsonResponse(getAcessorio($carros, $app), 200);
})->bind('modeloCarro');

$carro->get("/modelo/{modelo}", function($modelo) use ($app){
    $sql ='SELECT * FROM modelo_carro as m
            WHERE m.noModelo = ?';
    
    $carros = $app['db']->fetchAll($sql, array($modelo));
    
    if (empty($carros)) {
        return new JsonResponse(array("mensagem" => "Infelizmente nada foi encontrado este modelo"));
    }
    
    return new JsonResponse(getAcessorio($carros, $app), 200);
    
});

$carro->get("/aro/{aro}", function($aro) use ($app){
    $sql ='SELECT * FROM modelo_carro as m
        WHERE m.nuAroRoda = ?';

    $carros = $app['db']->fetchAll($sql, array($aro));

    if (empty($carros)) {
        return new JsonResponse(array("mensagem" => "Infelizmente nada foi encontrado este aro"));
    }

    return new JsonResponse(getAcessorio($carros, $app), 200);

});

$carro->get("/ano/{ano}", function($ano) use ($app){
    $sql ='SELECT * FROM modelo_carro as m
        WHERE m.anoModelo = ?';

    $carros = $app['db']->fetchAll($sql, array($ano));

    if (empty($carros)) {
        return new JsonResponse(array("mensagem" => "Infelizmente nada foi encontrado neste ano"));
    }

    return new JsonResponse(getAcessorio($carros, $app), 200);

});

$carro->get("/acessorio", function() use ($app){
     
    $sql ='SELECT * FROM acessorio_carro as a';
    $acessorios = $app['db']->fetchAll($sql, array($acessorio));

    if (empty($acessorios)) {
        return new JsonResponse(array("mensagem" => "Infelizmente nada foi encontrado"));
    }

    return new JsonResponse($acessorios, 200);

})->bind('acessorioCarro');

$carro->get("/acessorio/{acessorio}", function($acessorio) use ($app){
   
    $sql ='SELECT * FROM acessorio_carro as a Where a.noAcessorio = ?';
    $acessorios = $app['db']->fetchAll($sql, array($acessorio));

    
    foreach ($acessorios as $key => $value) {        
        $sql ='SELECT m.idModelo, m.noModelo, m.anoModelo, m.nuAroRoda FROM detalhe_carro as d
                   INNER JOIN modelo_carro = m
                        ON m.idModelo = d.idModelo
                WHERE d.idAcessorio = ?';
        
        $acessorios[$key]['modelo'] = $app['db']->fetchAll($sql, array($value['idAcessorio']));
    }


    if (empty($acessorios)) {
        return new JsonResponse(array("mensagem" => "Infelizmente nada foi encontrado com esse acessorio"));
    }

    return new JsonResponse($acessorios, 200);

});

$carro->get("/acessorio/opcional/{opcional}", function($opcional) use ($app){
     
    $sql ='SELECT * FROM acessorio_carro as a Where a.isOpcional = ?';
    $acessorios = $app['db']->fetchAll($sql, array($opcional));


    foreach ($acessorios as $key => $value) {
        $sql ='SELECT m.idModelo, m.noModelo, m.anoModelo, m.nuAroRoda FROM detalhe_carro as d
               INNER JOIN modelo_carro = m
                    ON m.idModelo = d.idModelo
            WHERE d.idAcessorio = ?';

        $acessorios[$key]['modelo'] = $app['db']->fetchAll($sql, array($value['idAcessorio']));
    }


    if (empty($acessorios)) {
        return new JsonResponse(array("mensagem" => "Infelizmente nada foi neste ano"));
    }

    return new JsonResponse($acessorios, 200);

});
    
function getAcessorio($carros, $app)
{
    foreach ($carros as $key => $value) {
        $sql ='SELECT a.idAcessorio, a.noAcessorio, a.isOpcional FROM detalhe_carro as d
                RIGHT JOIN acessorio_carro as a
                    ON a.idAcessorio = d.idAcessorio
                WHERE d.idModelo = ?';
    
        $stmt = $app['db']->prepare($sql);
        $stmt->bindValue(1, $carros[$key]['idModelo']);
        $stmt->execute();
    
        $carros[$key]['acessorio'] = $stmt->fetchAll();
    }
    
    return $carros;
}

return $carro;