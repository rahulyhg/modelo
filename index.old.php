<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array('debug'=>false));

$app->error(function(Exception $e) use ($app){
	$erro = new stdClass();
	$erro->message = $e->getMessage();
	echo "{'error':". json_decode($erro) ."}";
});

$app->get('/:controller/:action(/:params)', function($controller, $action, $params=null){
	$controller = ucfirst($controller);
	include_once "classes/{$controller}.php";
	
	$classe = new $controller();
	$ret = call_user_func_array(array($classe,'get_'.$action), array($params));
	echo json_decode($ret);
});