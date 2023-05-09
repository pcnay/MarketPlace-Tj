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
					"result" => "Not found"
				);
			}

			echo json_encode($json,http_response_code($json["status"]));
			return;


		} // public function getData($table)

		// Get Con Filtro
		public function getFilterData($table,$linkTo,$equalTo)
		{
			$response = GetModel::getFilterData($table,$linkTo,$equalTo);

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
					"result" => "Not found"
				);
			}

			echo json_encode($json,http_response_code($json["status"]));
			return;

		}

	} // class GetController
?>
