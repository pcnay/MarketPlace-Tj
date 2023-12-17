<?php

	//error_reporting(0);// NO muestra el error desde otro archivo de PHP (para este caso es JWT)
	use Firebase\JWT\JWT; // Para llamar a la libreria.
	use Firebase\JWT\Key;
	

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
		"status" => 404, 	
		"result" =>"Not Found" 	
	); 

	// Para mandar una respuesta.
	echo json_encode($json,http_response_code($json["status"]));

	return; 	
	
	// Solicitar respuesta del Controlador para crear datos desde cualquier Tabla.
	if (isset($_POST))
	{
		$response = new PostController();

	}
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
	// Cambiar el valor de == 3  (nombre tabla) cuando modifique la ruta: ....curso-web/MarketPlace/Productos
	if ((count($routesArray) === 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="GET"))
	{
		/*
		$json = array( 
			"status" => 200, 	
			"result" => "GET" 	
		); 
		// Para mandar una respuesta a la 
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

			if ((isset($_GET["orderBy"])) && isset($_GET["orderMode"]))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];				
			}
			else
			{
				$orderBy = null;
				$orderMode = null;				
			}

			// Si vienen las variables Globales para los limites.
			if ((isset($_GET["startAt"])) && isset($_GET["endAt"]))
			{
				$startAt = $_GET["startAt"];
				$endAT = $_GET["endAt"];
			}
			else
			{
				$startAT = null;
				$endAt = null;				
			}

			$response = new GetController();
			$response->getFilterData(explode("?",$routesArray[3])[0],$_GET["linkTo"],$_GET["equalTo"],$orderBy,$orderMode,$startAt,$endAt);
		}
		else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?",$routesArray[3])[0] == "relations" && (!isset($_GET["rel"])) && (!isset($_GET["type"])))
		{
			// =======================================================
			// Peticiones GET entre Tablas relacionadas sin Filtros
			// =======================================================	

			if ((isset($_GET["orderBy"])) && isset($_GET["orderMode"]))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];				
			}
			else
			{
				$orderBy = null;
				$orderMode = null;				
			}

			// Si vienen las variables Globales para los limites.
			if ((isset($_GET["startAt"])) && isset($_GET["endAt"]))
			{
				$startAt = $_GET["startAt"];
				$endAT = $_GET["endAt"];
			}
			else
			{
				$startAT = null;
				$endAt = null;				
			}

			// Se envia una solicitud al Controlador.
			$response = new GetController();
			//$response->getRelData(explode("?",$routesArray[3])[0],$_GET["rel"],$_GET["type"]);
			$response->getRelData($_GET["rel"],$_GET["type"],$orderBy,$orderMode,$startAt,$endAt);

		} // else if (isset($_GET["linkTo"]) && isset($_GET["equalTo"]))

		else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?",$routesArray[3])[0] == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"]))
		{
			// =======================================================
			// Peticiones GET entre Tablas relacionadas CON Filtros
			// =======================================================	
			if ((isset($_GET["orderBy"])) && isset($_GET["orderMode"]))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];				
			}
			else
			{
				$orderBy = null;
				$orderMode = null;				
			}

			// Si vienen las variables Globales para los limites.
			if ((isset($_GET["startAt"])) && isset($_GET["endAt"]))
			{
				$startAt = $_GET["startAt"];
				$endAT = $_GET["endAt"];
			}
			else
			{
				$startAT = null;
				$endAt = null;				
			}

			// Se envia una solicitud al Controlador.
			$response = new GetController();
			//$response->getRelData(explode("?",$routesArray[3])[0],$_GET["rel"],$_GET["type"]);
			$response->getRelFilterData($_GET["rel"],$_GET["type"],$_GET["linkTo"],$_GET["equalTo"],$orderBy,$orderMode,$startAt,$endAt);
		}		
			// =======================================================
			// Peticiones GET para buscar 
			// =======================================================	
		else if((isset($_GET["linkTo"])) && (isset($_GET["search"])))
		{ 
			if ((isset($_GET["orderBy"])) && isset($_GET["orderMode"]))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];				
			}
			else
			{
				$orderBy = null;
				$orderMode = null;				
			}

			// Si vienen las variables Globales para los limites.
			if ((isset($_GET["startAt"])) && isset($_GET["endAt"]))
			{
				$startAt = $_GET["startAt"];
				$endAT = $_GET["endAt"];
			}
			else
			{
				$startAT = null;
				$endAt = null;				
			}
			
			// Cuando es mas de una tabla a buscar.
			// Se esta realizando una petecion entre tablas relacionadas
			if ( (explode("?",$routesArray[3])[0] == "relations") && (isset($_GET["rel"])) && (isset($_GET["type"])) )
			{
				// Buscando en una sola tabla.
				$response = new GetController();
				$response->getSearchRelData($_GET["rel"],$_GET["type"],$_GET["linkTo"],$_GET["search"],$orderBy,$orderMode,$startAt,$endAt);
			}
			else
			{
				// Buscando en una sola tabla.
				$response = new GetController();
				$response->getSearchData(explode("?",$routesArray[3])[0],$_GET["linkTo"],$_GET["search"],$orderBy,$orderMode,$startAt,$endAt);
			}


		}
		else // Peticiones GET sin Filtro
		{
			// ======================================================================
			// Peticiones GET Sin Filtros, una tabla
			// ======================================================================
			if ((isset($_GET["orderBy"])) && isset($_GET["orderMode"]))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];				
			}
			else
			{
				$orderBy = null;
				$orderMode = null;				
			}

			// Se verifica si vienen variables de Limite
			if ((isset($_GET["startAt"])) && isset($_GET["endAt"]))
			{
				$startAt = $_GET["startAt"];
				$endAt = $_GET["endAt"];				
			}
			else
			{
				$startAt = null;
				$endAt = null;				
			}

			$response = new GetController();
			// explode("?",$routesArray[3])[0] = Para extraer el nombre de la tabla.
			$response->getData(explode("?",$routesArray[3])[0],$orderBy,$orderMode,$startAt,$endAt);
		}
	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	
	// ==========================================================================
	// Peticion "POST"	
	// ==========================================================================

	// if ((count($routesArray) == 3) && (isset($_SERV
	// Cambiar el valor de == 3 (nombre tabla) cuando modifique la ruta: ....curso-web/MarketPlace/Productos

	if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="POST"))
	{
		/*
		$json = array( 
			"status" => 200, 	
			"result" => "POST" //$_POST
		); 
		// Para mandar una respuesta a la respuesta 
		echo json_encode($json,http_response_code($json["status"]));

		return; 	
		*/

		// Antes de grabar los datos en la tabla, se debe validar el nombre y numero de columnas de la tabla.
		// Obteniendo el nombre de la base de datos.
		$database = RoutesController::database();
		$response = PostController::getColumnsData(explode("?",$routesArray[3])[0],$database);
		$columns = array(); // Para guardar los nombres de columnas.

		// Mostrando el contenido de la variable "$reponse", que contiene los campos de la tabla desde la Base de datos
		foreach ($response as $key => $value)
		{
			//echo '<pre>';
			//print_r ($value->item);
			array_push($columns,$value->item);
			//echo '</pre>';	
		}
			array_shift($columns); // Eliminando el primer elemento "id_XXXXX"
			array_pop($columns); // Elimina el ultimo elemento

			//echo '<pre>';
			//print_r ($columns);
			//echo '</pre>';	
			//return;

		if (isset($_POST))
		{

		/* Esta seccion de codigo es una version vieja para determinar si son iguales el numero de columnas con la peticion POST con la tabla de la Base de Datos.
			Pero no sera necesrio, ya que se tiene otra version mas flexible para el numero de campos a utilizar.

			// Que valores retorna la variable super global de $_POST.
			//echo '<pre>';
			//print_r ($_POST);
			//echo '</pre>';		

			// Validar que los nombres de las columnas de la tabal coincidan con los campos POST(que se envian desde el formulario)
			// Recorriendo el arreglo de "columns"
			$count = 0;
			foreach ($columns as $key => $value)
			{
				// Para que despliegue solo los nombres de los campos
				//echo '<pre>';
				//print_r (array_keys($_POST)[$key]); 
				//echo '</pre>';		

				if (array_keys($_POST)[$key] == $value)
				{
					$count++; // coinciden el numero de columnas de la variable $_POST(formulario) con las campos de la Base de Datos.					
				}
				else
				{
					$json = array(
						'status' => 400,
						"result" => 'Error: Fields in the form do not match the database'
					);
					echo json_encode($json,http_response_code($json["status"]));
					return;
				}
			} //foreach ($columns as $key => $value) 

			//return;
			
			if ($count == count($columns))
			{
				//echo "Coincide";
			
				// Solicitamos respuesta del Controlador para crear datos en cualquer tabla/
				$response = new PostController();
				$response->postData(explode("?",$routesArray[3])[0],$_POST);
			}
			else
			{
				$json = array('status' => 400,'result' => "Error: Fields in the form do not match the datatable");

				echo json_encode($json,http_response_code($json["status"]));
				return;
			}
			*/
			// Esta es la version optimizada para leer numero de columnas variables de la peticion POST, ademas se utiliza tambien en el registro de usuario, ya que en la Tabla "t_Users" no se requieren todos los campos para el registro de Usuario.

				// Se valida que los campos de la variable Global PUT coincidan con los campos de la tabala en curso.
				// Mostrando en contenido de las llaves
				
				$count = 0;
				foreach (array_keys($_POST) as $key => $value)
				{
					// Busca el valor de "$value" en el arreglo "$columns"
					$count = array_search($value,$columns);
					/*
					echo '<pre>';
					print_r($value);
					echo "</br>";
					echo '</pre>';
					*/
				}		
					/*
					// Para determinar cuantos elementos tienen coincidencia.
					echo '<pre>';
					print_r($count);
					echo '</pre>';					
					return;
					*/

					// Si una columna no coincide el valor sera 0.					
					if ($count >0)
					{
						// Solicitamos respuesta del Controllador para Registrar Usuarios.
						if (isset($_GET["register"]) && $_GET["register"] == true)
						{
							/*
							// Para determinar que valor tiene la variable "$_GET["register"]"
							$json = array(
								"status" => 200,
								//"summary" => count($response),
								"result" => $_GET["register"]
							);

							echo json_encode($json,http_response_code($json["status"]));	

							return;
							*/
				
							$response = new PostController();
							$response->PostRegister(explode("?",$routesArray[3])[0],$_POST);		
							
						}
						else if (isset(($_GET["login"])) && ($_GET["login"] == true)) // Para el caso del "Login" Usuario.
						{
							// Solicitamos respuesta del controlador para el ingreso de usuario

							$response = new PostController();
							$response->PostLogin(explode("?",$routesArray[3])[0],$_POST);

							// Validar el "token" de autenticacion.
						}
						else if (isset($_GET["token"]))
						{
							// Validar que el Token no este vencido.
							//$key = "zxcvbnmqwertyuio23561jdk0251fj";
							// $jwt = JWT::decode($_GET["token"],$key,array('HS256'));

							// Este formato lo reconoce con la version 7.4 de PH y 2.2 de JWT
							// Retorna un formato "epoch", por lo que se tiene que accesar a la pagina : https://www.epochconverter.com/
							//$jwt = JWT::decode($_GET["token"], new Key($key, 'HS256'));
							
							/*
							echo '<pre>';
							print_r($jwt->exp);
							echo '</pre>';					
							return;
							*/

								// Traer el usuario de acuerdo al Token
								$user = GetModel::getFilterData("t_Users","token_user",$_GET["token"],null,null,null,null);

								/*
								// Para determinar cuantos elementos tienen coincidencia.
								echo '<pre>';
								print_r($user);
								echo '</pre>';					
								return;
								*/

								if (!empty($user)) // Existe el Usuario
								{
									// Valida que el token no haya expirado.
									$time = time();
									if ($user[0]->token_exp_user > $time ) // El token aun no ha expirado.
									{										
										// Respuesta del controaldor para editar cualquier tabla.
										$response = new PostController();
										$response->PostData(explode("?",$routesArray[3])[0],$_POST);			
									}	
									else
									{
										$json = array(
											'status' => 400,
											"results" => 'Error: the token has expired'
										);
										echo json_encode($json,http_response_code($json["status"]));
										return;		
									}		// if ($user[0]->token_exp_user < $time ) // El token aun no ha expirado.
								}
								else
								{
									$json = array(
										'status' => 400,
										"results" => 'Error: the user is not authorized'
									);
									echo json_encode($json,http_response_code($json["status"]));
									return;		
								} // if (!empty($user)) // Existe el Usuario							
						}
						else
						{
							$json = array(
								'status' => 400,
								"results" => 'Error: Authorization required'
							);
							echo json_encode($json,http_response_code($json["status"]));
							return;	
	
						} //if (isset($_GET["register"]) && $_GET["register"] == true)

					} // if ($count >0)

					else // if ($count >0)
					{
						$json = array(
							'status' => 400,
							"results" => 'Error: Fields in the form do not match the database'
						);
						echo json_encode($json,http_response_code($json["status"]));
						return;	
					} // if ($count >0)

			
		} //if (isset($_POST))

	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	// ==========================================================================
	// Peticion "PUT"	
	// Cambiar el valor de == 3  (nombre tabla) cuando modifique la ruta: ....curso-web/MarketPlace/Productos
	// ==========================================================================
	if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="PUT"))
	{
		/*		
		$json = array( 
			"status" => 200, 	
			"result" => "PUT" 	
		); 
		// Para mandar una respuesta a la respuesta 
		echo json_encode($json,http_response_code($json["status"]));
		*/

		// Validar que existe el ID en la tabla, que se desea Actualizar.
		
		// Validar si existe el "Id"
		if ((isset($_GET["id"])) && (isset($_GET["nameId"])))
		{
			$table = explode("?",$routesArray[3])[0];
			$linkTo = $_GET["nameId"];
			$equalTo = $_GET["id"];
			$orderBy = null;
			$orderMode = null;
			$startAt = null;
			$endAt = null;
				
			$response = PutController::getFilterData($table,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt);
	
			/*
			// Muestrando el contenido de la variable "$response"
			echo '<pre>';
			print_r ($response);
			echo '</pre>';
			return;
			*/
			
			if ($response)
			{
				// Capturar los datos del formulario.
				$data = array();
				 			
				parse_str(file_get_contents('php://input'),$data);
		
				/*
				// Muestrando el contenido de la variable "$data"
				echo '<pre>';
				//print_r ($data);
				print_r(array_keys($data));
				echo '</pre>';
				return;
				*/

				/* 
				$json = array( 
					"status" => 200, 	
					"result" => $data 	
				); 
				// Para mandar una respuesta a la respuesta 
				echo json_encode($json,http_response_code($json["status"]));	
				*/

				// Obtener los listados de Columnas, la tabla a cambiar.
				// Se debe validar el nombre y numero de columnas de la tabla.
				// Obteniendo el nombre de la base de datos.
				$database = RoutesController::database();
				$response = PostController::getColumnsData(explode("?",$routesArray[3])[0],$database);
				$columns = array(); // Para guardar los nombres de columnas.

				// Mostrando el contenido de la variable "$reponse", que contiene los campos de la tabla desde la Base de datos
				foreach ($response as $key => $value)
				{
					//echo '<pre>';
					//print_r ($value->item);
					array_push($columns,$value->item);
					//echo '</pre>';	
				}

				array_shift($columns); // Quita el primer elememto
				// Para quitar los dos ultimos renglones, del arreglo
				array_pop($columns);	// Quita el ultimo elemento
				array_pop($columns);	// Quita el ultimo elemento

				/*
				// Mostrando las columnas
				echo '<pre>';
				print_r($columns);
				echo '</pre>';
				//return;
				*/

				// Se valida que los campos de la variable Global PUT coincidan con los campos de la tabala en curso.
				// Mostrando en contenido de las llaves
				
				$count = 0;
				foreach (array_keys($data) as $key => $value)
				{
					// Busca el valor de "$value" en el arreglo "$columns"
					$count = array_search($value,$columns);
					/*
					echo '<pre>';
					print_r($value);
					echo "</br>";
					echo '</pre>';
					*/
				}		
					/*
					echo '<pre>';
					print_r($count);
					echo '</pre>';					
					return;
					*/

					// Si una columna no coincide el valor sera 0.					
					if ($count >0)
					{
						/////////////////////////////////////////////////
						if (isset($_GET["token"]))
						{
							// Traer el usuario de acuerdo al Token
							$user = GetModel::getFilterData("t_Users","token_user",$_GET["token"],null,null,null,null);

							/*
							// Para determinar cuantos elementos tienen coincidencia.
							echo '<pre>';
							print_r($user);
							echo '</pre>';					
							return;
							*/

							if (!empty($user)) // Existe el Usuario
							{
								// Valida que el token no haya expirado.
								$time = time();
								if ($user[0]->token_exp_user > $time ) // El token aun no ha expirado.
								{																		
									// Respuesta del controaldor para editar cualquier tabla.
									$response = new PutController();
									$response->putData(explode("?",$routesArray[3])[0],$data,$_GET["id"],$_GET["nameId"]);
								}
								else // if ($user[0]->token_exp_user > $time ) // El token aun no ha expirado.
								{
									$json = array(
										'status' => 400,
										"results" => 'Error: the token has expired'
									);
									echo json_encode($json,http_response_code($json["status"]));
									return;		
								}	// if ($user[0]->token_exp_user > $time ) // El token aun no ha expirado.

							}
							else
							{
								$json = array(
									'status' => 400,
									"results" => 'Error: the user is not authorized'
								);
								echo json_encode($json,http_response_code($json["status"]));
								return;		
							} // if (!empty($user)) // Existe el Usuario

						} //if (isset($_GET["token"]))

					}
					else // if ($count >0)
					{
						$json = array('status' => 400,
						'results' => 'Error Fields in the form do not match the Database');
						echo json_encode($json,http_response_code($json["status"]));
						return;		
					} // if ($count >0)
			} // if ($response)
			else
			{
				$json = array('status' => 400,
				'results' => 'Error The ID is not found in the DataBase');
				echo json_encode($json,http_response_code($json["status"]));
				return;
			} // if ($response)
			
		} // 		if ((isset($_GET["id"])) && (isset($_GET["nameId"])))

		return;
		
	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	// ==========================================================================
	// Peticion "DELETE"	
	// Cambiar el valor de == 3  (nombre tabla) cuando modifique la ruta: ....curso-web/MarketPlace/Productos
	// ==========================================================================
	if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="DELETE"))
	{
		// Se verifica si viene un ID.
		if (isset($_GET["id"]) && isset($_GET["nameId"]))
		{
			$table = explode("?",$routesArray[3])[0];
			$linkTo = $_GET["nameId"];
			$equalTo = $_GET["id"];
			$orderBy = null;
			$orderMode = null;
			$startAt = null;
			$endAt = null;
				
			$response = PutController::getFilterData($table,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt);

			if ($response)
			{
				/*
				$json = array( 
					"status" => 200, 	
					"result" => "DELETE" 	
				); 
				// Para mandar una respuesta a la respuesta 
				echo json_encode($json,http_response_code($json["status"]));
				return; 		
				*/

				if (isset($_GET["token"]))
				{
					// Traer el usuario de acuerdo al Token
					$user = GetModel::getFilterData("t_Users","token_user",$_GET["token"],null,null,null,null);

					/*
					// Para determinar cuantos elementos tienen coincidencia.
					echo '<pre>';
					print_r($user);
					echo '</pre>';					
					return;
					*/

					if (!empty($user)) // Existe el Usuario
					{
						// Valida que el token no haya expirado.
						$time = time();
						if ($user[0]->token_exp_user > $time ) // El token aun no ha expirado.
						{																		
							// Solicitamos respuesta del controlador.
							$response_delete = new DeleteController();
							$response_delete->deleteData(explode("?",$routesArray[3])[0],$_GET["id"],$_GET["nameId"]);
						}
						else // if ($user[0]->token_exp_user > $time ) // El token aun no ha expirado.
						{
							$json = array(
								'status' => 400,
								"results" => 'Error: the token has expired'
							);
							echo json_encode($json,http_response_code($json["status"]));
							return;		
						} // if ($user[0]->token_exp_user > $time ) // El token aun no ha expirado.
					}
					else
					{
						$json = array(
							'status' => 400,
							"results" => 'Error: the user is not authorized'
						);
						echo json_encode($json,http_response_code($json["status"]));
						return;		
					} // if (!empty($user)) // Existe el Usuario
				}
			}
			else
			{
				$json = array('status' => 400,
				'results' => 'Error The ID is not found in the DataBase');
				echo json_encode($json,http_response_code($json["status"]));
				return;		
			} // if ($response)

		} //if (isset($_GET["id"]) && isset($_GET["nameId"]))


		

	} // if ((count($routesArray) > 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

} // if (count($routesArray)==2)
