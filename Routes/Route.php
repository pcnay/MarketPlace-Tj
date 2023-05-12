<?php
	// REQUEST_URI = Para extraerlas palabras despues del nombre de dominio
	// HTTP_HOST = Extraer el nombre del dominio
	
	// Usando comandos de PHP para convertir la cadena en un arreglo y poder extraer lo que se requiere
	$routesArray = explode("/",$_SERVER['REQUEST_URI']);

	/*
		[0]] => 
    [1] => curso-web
    [2] => MarketPlace
    [3] => Categorias
	*/
	$routesArray = (array_filter($routesArray));
	//$routesArray = $routesArray[3];
	$name_table = $routesArray[3];
	
	/*
	echo '<pre>';
	print_r($routesArray2);
	echo '</pre>';
	return;
	*/


	if (count($routesArray) == 0)
	{
		$json = array(
			"status" => 404,
			"result" =>"Not Found"
		);
		echo json_encode($json,http_response_code($json["status"]));
		return;
	}
	else
	{
		// Peticiones GET
		// REQUEST_METHOD = Metodo de requerimiento : GET,POST, DELETE, POST 
		if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="GET"))
		{
			// Desplegando la ruta despues del Dominio en la URL
			/*
			$json = array(
				"status" => 200,
				"result" => "GET"
			);
			echo json_encode($json,http_response_code($json["status"]));
			return;
			*/

			// Peticiones GET Con Filtro
			// t_Categories?linkTo=url_category&equalTo=home-kitchen 
			if (isset($_GET["linkTo"]) && isset($_GET["equalTo"]))
			{
				// Revisando primero que esta reciviendo en el URL, ya que se agrego el signo "?"
				// Separando por el caracter "?"
				// Extraer solo el nombre de la tabla.
				/*
				$json = array(
					"status" => 200,
					"result" => explode("?",$name_table)[0]
				);
				echo json_encode($json,http_response_code($json["status"]));
				return;
				*/

				$response = new GetController();
				$response->getFilterData(explode("?",$name_table)[0],$_GET["linkTo"],$_GET["equalTo"]);

			}
			else if(isset($_GET["rel"]) && isset($_GET["type"]) && explode("?",$name_table)[0] == "relations")
			{
				// Peticions GET entre tablas relacionas sin filtro.
				$response = new GetController();
				$response->getRelData($_GET["rel"],$_GET["type"]);

			}
			else
			{
				// Peticiones GET Sin Filtro.
				$response = new GetController();
				$response->getData($name_table);
			}

		} // ["REQUEST_METHOD"]=="GET"))


		// Peticiones POST
		// REQUEST_METHOD = Metodo de requerimiento : GET,POST, DELETE, POST 
		if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="POST"))
		{
			// Desplegando la ruta despues del Dominio en la URL
			$json = array(
				"status" => 200,
				"result" => "POST"
			);
			echo json_encode($json,http_response_code($json["status"]));
			return;
		} // ["REQUEST_METHOD"]=="POST"))

		// Peticiones PUT
		// REQUEST_METHOD = Metodo de requerimiento : GET,POST, DELETE, POST 
		if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="PUT"))
		{
			// Desplegando la ruta despues del Dominio en la URL
			$json = array(
				"status" => 200,
				"result" => "PUT"
			);
			echo json_encode($json,http_response_code($json["status"]));
			return;
		} // ["REQUEST_METHOD"]=="POST"))

		// Peticiones DELETE
		// REQUEST_METHOD = Metodo de requerimiento : GET,POST, DELETE, POST 
		if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="DELETE"))
		{
			// Desplegando la ruta despues del Dominio en la URL
			$json = array(
				"status" => 200,
				"result" => "DELETE"
			);
			echo json_encode($json,http_response_code($json["status"]));
			return;
		} // ["REQUEST_METHOD"]=="POST"))

	} // else if (count($routesArray) == 0)

?>
