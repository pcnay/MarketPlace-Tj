<?php
	
	// Permitir los CORS en PHP
	// Con esto ya se podra recibir peticions HTTP(GET, POST,PUT, DELETE) desde cualquier otro servidor
	
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('content-type: application/json; charset=utf-8');
	
	require_once "Controllers/Route.controller.php";
	
	require_once "Controllers/Get.controller.php";
	require_once "Controllers/Post.controller.php";
	require_once "Controllers/Put.controller.php";
	require_once "Controllers/Delete.controller.php";

	require_once "Models/Get.model.php";
	require_once "Models/Post.model.php";		
	require_once "Models/Put.model.php";
	require_once "Models/Delete.model.php";

	$index = new RoutesController();
	$index->index();


?>