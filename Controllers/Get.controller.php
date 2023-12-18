<?php
	class GetController
	{
		// *****************************************		
		// Peticiones GET sin Filtro
		// *****************************************

		// NO se asigna de forma estatica, ya que no se requiere almacenar, solo se mostrara.
		public function getData($table,$orderBy,$orderMode,$startAt,$endAt)
		{			
			// PENDIENTE ::: Si se teclea de forma erronea el nombre de la tabla. 

			// Obtener los datos de la tabla que se envio.
			$response = GetModel::getData($table,$orderBy,$orderMode,$startAt,$endAt);
		
			// return // Si se desea saber que error esta pasando en el modelo

			$return = new GetController();
			$return->fncResponse($response,"getData");
		} // public function getData($table)



		// =============================================================================================
		// 	Get Con Filtro:
		//	Cuando solo se requiere un campo en especifico.
		// 	Pasan variables GET: https://www.miportalweb.org/curso-web/MarketPlace/t_Categories?linkTo=url_category&equalTo=kitchen
		//	Partiendo del "?", el resto de las dos variables "GET" "linkTo" y "equalTo" se utilizan en la base de datos para filtrar.
		// ============================================================================================
		public function getFilterData($table,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
		{

			$response = GetModel::getFilterData($table,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt);

			// Esta funcion se define abajo ya que se repite varias veces 
			$return = new GetController();
			$return->fncResponse($response,"getFilterData");		
		}


		// =================================================
		// Peticiones GET tablas relacionadas sin filtro.
		// https://www.miportalweb.org/relations?rel=t_Categories,t_Products&type=category,product
		// rel = 	t_Categories
		//				t_Products
		// category = id_category
		//	product = id_product
		// =================================================
		public function getRelData($rel,$type,$orderBy,$orderMode,$startAt,$endAt)
		{
			$response = GetModel::getRelData($rel,$type,$orderBy,$orderMode,$startAt,$endAt);		
			/*
			// Para que despliegue los valores en pantalla de la extraccion del metodo GetModel
			$json = array(
				"status" => 200,
				"summary" => count($response),
				"result" => $response
			);

			echo json_encode($json,http_response_code($json["status"]));
			return;		
			*/

			$return = new GetController();
			$return->fncResponse($response,"getRelData");
		}

		// Peticiones GET tablas relacionadas Con filtro.
		public function getRelFilterData($rel,$type,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
		{
			$response = GetModel::getRelFilterData($rel,$type,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt);

			$return = new GetController();
			$return->fncResponse($response,"getRelFilterData");
		}

		// Peticiones GET buscar en Tabla.
		public function getSearchData($table,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt)
		{
			$response = GetModel::getSearchData($table,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt);

			// Esta funcion se define abajo ya que se repite varias veces 
			$return = new GetController();
			$return->fncResponse($response,"getSearchData");		
		}
		
		// =================================================
		// Peticiones GET tablas relacionadas sin filtro.
		// https://www.miportalweb.org/relations?rel=t_Categories,t_Products&type=category,product
		// rel = 	t_Categories
		//				t_Products
		// category = id_category
		//	product = id_product
		// =================================================
		// https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store&linkTo=name_product&search=portable&orderBy=id_product&orderMode=DESC&startAt=0&$endAt=1

		
		public function getSearchRelData($rel,$type,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt)
		{
			$response = GetModel::getSearchRelData($rel,$type,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt);
			$return = new GetController();
			$return->fncResponse($response,"getSearchRelData");
		}

		// Respuestas del controlador, cuando realizan los GET a la tabla de datos.
		public function fncResponse($response,$method)
		{
			if (!empty($response))
			{
				$json = array(
					"status" => 200,
					"summary" => count($response),
					"results" => $response
				);

			} //if (!empty($response))
			else
			{
				$json = array(
					"status" => 404,					
					"results" => "Not found",
					"method" => $method
				);
			}

			echo json_encode($json,http_response_code($json["status"]));
			return;		
		}

	} // class GetController
?>
