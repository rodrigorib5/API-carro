<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$carroDao = $app['controllers_factory'];

$carroDao->post('/cadastrar/modelo', function(Request $request) use ($app) {
    
   $cadastrou =  $app['db']->insert('modelo_carro', array(
        "noModelo" => $request->get('nomeModelo'),
        "anoModelo" => $request->get('anoModelo'),
        "nuAroRoda" => $request->get('aroModelo')
    ));
   
   if ($cadastrou) {
       return new JsonResponse(array("mensagem" => "Cadastro realizado com sucesso"));
   }
   
   return new JsonResponse(array("mensagem" => "Um erro ocorreu"));
    
   
})->bind('cadastrarModelo');

$carroDao->post('/cadastrar/acessorio', function(Request $request) use ($app) {

    $cadastrou =  $app['db']->insert('acessorio_carro', array(
        "noAcessorio" => $request->get('noAcessorio'),
        "isOpcional" => $request->get('isOpcional')
    ));
     
    if ($cadastrou) {
        return new JsonResponse(array("mensagem" => "Cadastro realizado com sucesso"));
    }
     
    return new JsonResponse(array("mensagem" => "Um erro ocorreu"));

     
})->bind('cadastrarAcessorio');

return $carroDao;