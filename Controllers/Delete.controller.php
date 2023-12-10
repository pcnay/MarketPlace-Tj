<?php
	class DeleteController
	{
		// Peticion para borrar datos.
		public function deleteData($table,$id,$nameId)
		{
			$response = deleteModel::deleteData($table,$id,$nameId);

			$return = new DeleteController();
			$return->fncResponse($response,"deleteData");			

		} // public function deleteData($table,$id,$nameId)

		// Respuestas del controlador, cuando realizan el DELETE de la tabla.
		public function fncResponse($response,$method)
		{
			if (!empty($response))
			{
				$json = array(
					"status" => 200,
					//"summary" => count($response),
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

		} // public function fncResponse($response,$method)

	} // class DeleteController
?>