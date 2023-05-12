<?php
	class GetController
	{
		// Peticiones GET sin Filtro
		// NO se asigna de forma estatica, ya que no se requiere almacenar, solo se mostrara.
		public function getData($table)
		{
			// Si se teclea de forma erronea el nombre de la tabla.
			// Obtener los datos de la tabla que se envio.
			$response = GetModel::getData($table);

			$return = new GetController();
			$return->fncResponse($response,"getData");

		} // public function getData($table)

		// Get Con Filtro
		public function getFilterData($table,$linkTo,$equalTo)
		{
			$response = GetModel::getFilterData($table,$linkTo,$equalTo);

			$return = new GetController();
			$return->fncResponse($response,"getFilterData");

		}

		// Peticiones GET tablas relacionadas sin filtro.
		public function getRelData($rel,$type)
		{
			$response = GetModel::getRelData($rel,$type);

			$return = new GetController();
			$return->fncResponse($response,"getRelData");
		}

		// Respuestas del controlador, cuando realizan los GET a la tabla de datos.
		public function fncResponse($response,$method)
		{
			if (!empty($response))
			{
				$json = array(
					"status" => 200,
					"summary" => count($response),
					"result" => $response
				);

			} //if (!empty($response))
			else
			{
				$json = array(
					"status" => 404,					
					"result" => "Not found",
					"method" => $method
				);
			}

			echo json_encode($json,http_response_code($json["status"]));
			return;		
		}

	} // class GetController
?>
