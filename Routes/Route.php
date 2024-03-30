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

		[0] => 
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
else // if (count($routesArray)==2)
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

		// ===================================================
		// Petecion GET CON Filtro.
		// ===================================================
		if ( (isset($_GET["linkTo"])) && (isset($_GET["equalTo"])) && (!isset($_GET["rel"])) && (!isset($_GET["type"])) )
		{	
		
			if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];

			} // if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			else
			{
				$orderBy = null;
				$orderMode = null;
			}

			if ((isset($_GET["startAt"])) && (isset($_GET["endAt"])))
			{
				$startAt = $_GET["startAt"];
				$endAt = $_GET["endAt"];

			} // if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			else
			{
				$startAt = null;
				$endAt = null;
			}

			// Separando los componentes de la URL que teclea el usuario.
			$URL_separado = explode("?",$routesArray[3]);
			$tabla = $URL_separado[0];
			// $ tabla = $URL_separado[0][0]; Solo para obtener el indica 0 del arreglo retornado
			$Filtros = explode("=",$URL_separado[1]);
			//$Obtener_startAt = explode("&",$Filtros[4]);
			//$startAt = $Obtener_startAt[1];
			//$Obtener_endAt = explode("&",$Filtros[5]);
			//$endAt = $Obtener_endAt[1];
			//echo '<pre>';print_r($startAt);echo'</pre>';
			//echo '<pre>';print_r($endAt);echo'</pre>';
			//exit;
	
			
			$Obtener_linkTo = $Filtros[0];
			$Campo_Filtro = explode("&",$Filtros[1]);
			$Obtener_campo = $Campo_Filtro[0];

					
			$Obtener_equalTo = $Campo_Filtro[1];
			$Valor_linkTo = $_GET['linkTo'];
			$Valor_equalTo = $_GET['equalTo'];			
			$arreglo_parametros = array();
			$arreglo_parametros['tabla'] = $tabla;
			$arreglo_parametros['linkTo'] = $Obtener_linkTo;
			$arreglo_parametros['Valor_linkTo'] = $Valor_linkTo;

			$arreglo_parametros['campo_tabla'] = $Obtener_campo;
			$arreglo_parametros['equalTo'] = $Obtener_equalTo;
			$arreglo_parametros['Valor_equalTo'] = $Valor_equalTo;
			$arreglo_parametros['orderBy'] = $orderBy;
			$arreglo_parametros['orderMode'] = $orderMode;
			$arreglo_parametros['startAt'] = $startAt;
			$arreglo_parametros['endAt'] = $endAt;

			
			//echo '<pre>';print_r($URL_separado); echo'</pre>';
			//echo '<pre>';print_r($tabla); echo'</pre>';
			//echo '<pre>';print_r($Filtros); echo'</pre>';
			
			$response = new GetController();
			if (($arreglo_parametros['orderBy'] == null) && ($arreglo_parametros['orderMode'] == null))
			{
				$response->getFilterData($arreglo_parametros); 	//Lo ejecuta inmediatamente.
			}
			else
			{
				$response->getFilterDataOrder($arreglo_parametros); 	//Lo ejecuta inmediatamente.
			}
		}
			// ===================================================
			// Petecion GET entre tablas Relacionadas CON Filtro.
			// ===================================================
			// explode("?",$routesArray[3])[0] = Es donde incluye la palabra "relations" (https://www..../MarketPlace/"relations"?rel=t_Products/...)
		else if ((isset($_GET["rel"])) && (isset($_GET["type"])) && (explode("?",$routesArray[3])[0] == "relations") && (!isset($_GET["linkTo"])) && (!isset($_GET["equalTo"])))
		{
			// Separando los componentes de la URL que teclea el usuario.
			$URL_separado = explode("?",$routesArray[3]);
			//echo '<pre>';print_r($URL_separado);echo'</pre>';
			//echo 'Se esta ejecutando la condicion';
			//exit;


			$relations = $URL_separado[0];
			$separar_tablas = explode("=",$URL_separado[1]);

			// Se verifica si vienen variables para Ordenar los registros.
			if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];
				$obtener_campos = explode("&",$separar_tablas[2]); 
				$campos = $obtener_campos[0];
			} // if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			else
			{
				$orderBy = null;
				$orderMode = null;
				$campos  = $separar_tablas[2];
			}
						
			$Obtener_tablas = explode("&",$separar_tablas[1]);
			$tablas = $Obtener_tablas[0];

			
			//echo '<pre>';print_r($URL_separado); echo'</pre>';
			//echo '<pre>';print_r($separar_tablas); echo'</pre>';
			//echo '<pre>';print_r($Obtener_tablas); echo'</pre>';
			//echo '<pre>';print_r($relations); echo'</pre>';
			//echo '<pre>';print_r($tablas); echo'</pre>';
			//echo '<pre>';print_r($campos); echo'</pre>';
			//exit;			
			
			$response = new GetController();
			$response->getRelData($tablas,$campos,$orderBy,$orderMode); 	//Lo ejecuta inmediatamente.

		}

		// ===================================================
		// Petecion GET Para el Buscador.
		// ===================================================
		// linkTo = Es el nombre de la Columna.
		else if ((isset($_GET["linkTo"])) && (isset($_GET["search"])))
		{

			// https://www.miportalweb.org/curso-web/MarketPlace/t_Products?linkTo=name_product&search=portable

			// Obtiene la tabla.
			$URL_separado = explode("?",$routesArray[3]);
			
			//echo"<pre>";print_r($URL_separado);echo"</pre>";
			$tabla = $URL_separado[0];
			//echo"<pre>";print_r($tabla);echo"</pre>";

			$Obtener_linkTo = explode("=",$URL_separado[1]);			
			//echo"<pre>";print_r($Obtener_linkTo);echo"</pre>";
			$Obtener_nombreFiltro = $Obtener_linkTo[0];
			//echo"<pre>";print_r($Obtener_nombreFiltro);echo"</pre>";
			$Separar_nombreCampo = explode("&",$Obtener_linkTo[1]);
			//echo"<pre>";print_r($Separar_nombreCampo);echo"</pre>";
			$Nombre_Campo = $Separar_nombreCampo[0];
			//echo"<pre>";print_r($Nombre_Campo);echo"</pre>";
			$Palabra_Search =  $Separar_nombreCampo[1];
			//echo"<pre>";print_r($Palabra_Search);echo"</pre>";
			$Palabra_Buscar = $_GET["search"];
			//echo"<pre>";print_r($Palabra_Buscar);echo"</pre>";
			//exit;

			// Se verifica si vienen variables para Ordenar los registros.
			if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];
			} // if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			else
			{
				$orderBy = null;
				$orderMode = null;
			}

			// Se verifica si vienen variables para establecer limites.
			if ((isset($_GET["startAt"])) && (isset($_GET["endAt"])))
			{
				$startAt = $_GET["startAt"];
				$endAt = $_GET["endAt"];
			} // if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			else
			{
				$startAt = null;
				$endAt = null;
			}
			
			
			// Obteniendo el nombre del campo.

			$arreglo_parametros = array();
			$arreglo_parametros['tabla'] = $tabla;
			$arreglo_parametros['campo_tabla'] = $Nombre_Campo;
			$arreglo_parametros['buscar'] = $Palabra_Buscar;
			$arreglo_parametros['orderBy'] = $orderBy;
			$arreglo_parametros['orderMode'] = $orderMode;
			$arreglo_parametros['startAt'] = $startAt;
			$arreglo_parametros['endAt'] = $endAt;
	
			// validar que exista el campo"linkTo"
			$response = new GetController();
			$response->getSearchData($arreglo_parametros);
			
		}

		else // if ( (isset($_GET["linkTo"])) && (isset($_GET["equalTo"])) && (!isset($_GET["rel"])) && (!isset($_GET["type"])) )
		{
			// ===================================================
			// Petecion GET SIN Filtro, 1 Tabla
			// ===================================================

			// Este codigo se coloca cuando es la primera peticion GET basica, pero como se agrego otra condicion, mas abajo se usa.
			//$response = new GetController();
			//$tabla = $routesArray[3];
			//$response->getData($tabla); 	//Lo ejecuta inmediatamente.


			// ===================================================
			// Se pregunta si vienen variables para Ordenar 
			// ===================================================
			if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			{
				$orderBy = $_GET["orderBy"];
				$orderMode = $_GET["orderMode"];

			} // if ((isset($_GET["orderBy"])) && (isset($_GET["orderMode"])))
			else
			{
				$orderBy = null;
				$orderMode = null;
			}

			// Preguntamos si no vienen variables de Limite.
			
			if ((isset($_GET["startAt"])) && (isset($_GET["endAt"])))
			{
				$startAt = $_GET["startAt"];
				$endAt = $_GET["endAt"];
			}
			else
			{
				$startAt = null;
				$endAt = null;
			}

			// Obtiene la tabla.
			$URL_separado = explode("?",$routesArray[3]);				
			$tabla = $URL_separado[0];
		
			//echo"<pre>";print_r($URL_separado);echo"</pre>";
			//echo"<pre>";print_r($tabla);echo"</pre>";
			//exit;
			$arreglo_parametros['tabla'] = $tabla;

			$arreglo_parametros = array();
			$arreglo_parametros['campo_tabla'] = $orderBy;
			$arreglo_parametros['tipo_ordenar'] = $orderMode;
			$arreglo_parametros['startAt'] = $startAt;
			$arreglo_parametros['endAt'] = $endAt;

			$response = new GetController();			

			if (($orderBy == null) && ($orderMode == null) && ($startAt == null) && ($endAt == null))
			{
				$tabla = $routesArray[3];
				$arreglo_parametros['tabla'] = $tabla;
				//echo '<pre>';print_r("Condicion GetData");echo'</pre>';
				//exit;

				$response->getData($arreglo_parametros); 	//Lo ejecuta inmediatamente.
			}

			if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
			{
				// Obtiene la tabla.
				$URL_separado = explode("?",$routesArray[3]);				
				$tabla = $URL_separado[0];			
				//echo"<pre>";print_r($URL_separado);echo"</pre>";
				//echo"<pre>";print_r($tabla);echo"</pre>";
				//exit;
				$arreglo_parametros['tabla'] = $tabla;

				$response->getLimiteData($arreglo_parametros); 	//Lo ejecuta inmediatamente.				
			}

			
			if (($startAt != null) && ($endAt != null))			
			{
				// Obtiene la tabla.
				$URL_separado = explode("?",$routesArray[3]);				
				$tabla = $URL_separado[0];
			
				//echo"<pre>";print_r($URL_separado);echo"</pre>";
				//echo"<pre>";print_r($tabla);echo"</pre>";
				//exit;

				$arreglo_parametros['tabla'] = $tabla;
				
				//echo '<pre>';print_r($tabla);echo'</pre>';
				//echo '<pre>';print_r($orderBy);echo'</pre>';
				//echo '<pre>';print_r($orderMode);echo'</pre>';
				//exit;

				$response->getLimiteData($arreglo_parametros); 	//Lo ejecuta inmediatamente.
			}


		}


	} // if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	// ==========================================================================
	// Peticion "POST"	
	// ==========================================================================
	if ((count($routesArray) === 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="POST"))
	{
		$json = array(
			"status" => 200,					
			"results" => "Found POST",
			"method" => "POST"
		);

		echo json_encode($json,http_response_code($json["status"]));
		return;				

	} // if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

	// ==========================================================================
	// Peticion "PUT"	
	// ==========================================================================
	if ((count($routesArray) === 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="PUT"))
	{
		$json = array(
			"status" => 200,					
			"results" => "Found PUT",
			"method" => "PUT"
		);

		echo json_encode($json,http_response_code($json["status"]));
		return;				

	} // if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

// ==========================================================================
	// Peticion "PUT"	
	// ==========================================================================
	if ((count($routesArray) === 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"]=="DELETE"))
	{
		$json = array(
			"status" => 200,					
			"results" => "Found DELETE",
			"method" => "delete"
		);

		echo json_encode($json,http_response_code($json["status"]));
		return;				

	} // if ((count($routesArray) == 3) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER

} // if (count($routesArray)==2)

