<?php

/*

// REQUEST_URI = Para extraerlas palabras despues del nombre de dominio
	// HTTP_HOST = Extraer el nombre del dominio
	
	// Usando comandos de PHP para convertir la cadena en un arreglo y poder extraer lo que se requiere
	$routesArray = explode("/",$_SERVER['REQUEST_URI']);
	
---------->>>>>> 	Se debe tener encuenta, cuando cambie la URL en la barra de direcciones. Ya que por lo general solo es:
	https://www.miportalweb.org/sitio

		[0]] => 
    [1] => curso-web
    [2] => MarketPlace
    [3] => Categorias
*/

$routesArray = explode("/",$_SERVER['REQUEST_URI']);
// Elimina el espacio en blanco, quedando
/*
[1] => curso-web
[2] => MarketPlace
[3] => Categorias
*/

$routesArray = (array_filter($routesArray));
//$routesArray = (array_filter($routesArray)); Retorna: "curso-web", "MarketPlace", el dominio es : "wwww.miportalweb.org"
// Se tiene que cambiar dependiendo de las carpetas que se usen.
// Por esta razon se selecciona : "$routesArray[3]
	//$routesArray = $routesArray[3];

	// Para extraer palabra despues del dominio. "curso-web/MarketPlace/Categorias"
	//$name_table = $routesArray[3];
	//$routesArray = $routesArray[3];

	/*
	echo '<pre>';
	print_r($routesArray[3]);
	echo '</pre>';
	return;
	*/

// Cambiar este valor ya que actualmente se esta usando : "curso-web/MarketPlace"
if (count($routesArray)==2)
{
	$json = array( 
		"status" => 400, 	
		"result" =>"Not Found" 	
	); 

	// Para mandar una respuesta.
	echo json_encode($json,http_response_code($json["status"]));

	return; 	
}
else
{
	// Peticiones GET
	// Esta seccion es para analizar la URL para la peticion HTTPS
	// REQUEST_METHOD = Metodo de requerimiento : GET,POST, DELETE, POST 

	// ==========================================================================
	// Peticion "GET"	
	// ==========================================================================
	
	// Peticion "GET"	
	// if ((count($routesArray) == 3) && (isset($_SERV
	// Cambiar el valor de == 3 cuando modifique la ruta: ....curso-web/MarketPlace/Productos
	if ((count($routesArray) === 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="GET"))
	{
		/*
		$json = array( 
			"status" => 200, 	
			"result" => "GET" 	
		); 
		// Para mandar una respuesta a la respuesta 
		echo json_encode($json,http_response_code($json["status"]));

		return; 	
		*/

		// ======================================================================
		// Peticiones GET Con Filtros
		// ======================================================================
		
		// Si vienen con variables "GET"
		if ((isset($_GET["linkTo"])) && (isset($_GET["equalTo"])) && (!isset($_GET["rel"])) && (!isset($_GET["type"])))
		{
			/*
			$json = array( 
				"status" => 404, 	
				// Separa en dos elementos del arreglo: 
		//		t_Categories",
    //"linkTo=url_category&equalTo=home-kitchen
				"result" =>explode("?",$routesArray[3])[0]
			);
			// Para mandar una respuesta.
			echo json_encode($json,http_response_code($json["status"]));
			return; 	
			*/

			$response = new GetController();
			$response->getFilterData(explode("?",$routesArray[3])[0],$_GET["linkTo"],$_GET["equalTo"]);
		}
		else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?",$routesArray[3])[0] == "relations" && (!isset($_GET["rel"])) && (!isset($_GET["type"])))
		{
			// =======================================================
			// Peticiones GET entre Tablas relacionadas sin Filtros
			// =======================================================	

			// Se envia una solicitud al Controlador.
			$response = new GetController();
			//$response->getRelData(explode("?",$routesArray[3])[0],$_GET["rel"],$_GET["type"]);
			$response->getRelData($_GET["rel"],$_GET["type"]);

		} // else if (isset($_GET["linkTo"]) && isset($_GET["equalTo"]))

		else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?",$routesArray[3])[0] == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"]))
		{
	
			// =======================================================
			// Peticiones GET entre Tablas relacionadas CON Filtros
			// =======================================================	
			// Se envia una solicitud al Controlador.
			$response = new GetController();
			//$response->getRelData(explode("?",$routesArray[3])[0],$_GET["rel"],$_GET["type"]);
			$response->getRelFilterData($_GET["rel"],$_GET["type"],$_GET["linkTo"],$_GET["equalTo"]);
		}
		else
		{
			// ======================================================================
			// Peticiones GET Sin Filtros
			// ======================================================================
			$response = new GetController();
			$response->getData($routesArray[3]);

		}


	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	
	// ==========================================================================
	// Peticion "POST"	
	// ==========================================================================
	if ((count($routesArray) == 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="POST"))
	{
		$json = array( 
			"status" => 200, 	
			"result" => "POST" 	
		); 
		// Para mandar una respuesta a la respuesta 
		echo json_encode($json,http_response_code($json["status"]));

		return; 	

	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	// ==========================================================================
	// Peticion "PUT"	
	// ==========================================================================
	if ((count($routesArray) == 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="PUT"))
	{
		$json = array( 
			"status" => 200, 	
			"result" => "PUT" 	
		); 
		// Para mandar una respuesta a la respuesta 
		echo json_encode($json,http_response_code($json["status"]));

		return; 	

	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	// ==========================================================================
	// Peticion "DELETE"	
	// ==========================================================================
	if ((count($routesArray) == 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="DELETE"))
	{
		$json = array( 
			"status" => 200, 	
			"result" => "DELETE" 	
		); 
		// Para mandar una respuesta a la respuesta 
		echo json_encode($json,http_response_code($json["status"]));

		return; 	

	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

} // if (count($name_table)==0)



/*
echo '<pre>';
	print_r($routesArray);
echo '</pre>';
return;
*/



/*

// REQUEST_URI = Para extraerlas palabras despues del nombre de dominio
	// HTTP_HOST = Extraer el nombre del dominio
	
	// Usando comandos de PHP para convertir la cadena en un arreglo y poder extraer lo que se requiere
	$routesArray = explode("/",$_SERVER['REQUEST_URI']);

	/*
		[0]] => 
    [1] => curso-web
    [2] => MarketPlace
    [3] => Categorias
	
	$routesArray = (array_filter($routesArray));
	//$routesArray = $routesArray[3];

	// Para extraer palabra despues del dominio. "curso-web/MarketPlace/Categorias"
	$name_table = $routesArray[3];
	
	/*
	echo '<pre>';
	print_r($routesArray2);
	echo '</pre>';
	return;
	


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
		// Esta seccion es para analizar la URL para la peticion HTTPS
		// REQUEST_METHOD = Metodo de requerimiento : GET,POST, DELETE, POST 
		if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="GET"))
		{
			// Desplegando la ruta despues del Dominio en la URL
			/*
			$json = array(
				"status" => 200,
				"result" => "GET"
			);

			// Para mandar una respuesta a la respuesta 
			echo json_encode($json,http_response_code($json["status"]));
			return;
			
			

				// Peticiones GET entre tablas relaciona con filtro
				// relations = Palabra clave para indicar que es una relacion
				// rel = Cuales tablas se van relacionar.
				// type = son los id con el que se relacionan las tablas
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Categories,t_Products&type=product,category

			else if(isset($_GET["rel"]) && isset($_GET["type"]) && explode("?",$name_table)[0] == "relations")
			{
				// Peticions GET entre tablas relacionas sin filtro.
				$response = new GetController();
				$response->getRelData($_GET["rel"],$_GET["type"]);

				
			}

			else if(isset($_GET["rel"]) && isset($_GET["type"]) && explode("?",$name_table)[0] == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"]) ) 
			{
				$response = new GetController();
				$response->getRelFilterData($_GET["rel"],$_GET["type"],$_GET["linkTo"],$_GET["equalTo"]);

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
			/*
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
			/*
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
			/*
			$json = array(
				"status" => 200,
				"result" => "DELETE"
			);
			echo json_encode($json,http_response_code($json["status"]));
			return;
			
		} // ["REQUEST_METHOD"]=="POST"))

	} // else if (count($routesArray) == 0)

?>
*/

?> 



