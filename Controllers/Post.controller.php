<?php
	class PostController
	{
		// Para obtener los nombres de las columnas.
		static public function getColumnsData($table,$database)
		{
			$response = PostModel::getColumnsData($table,$database);
			return $response;

		}


		// Peticion POST para crear datos.
		public function postData($table,$data)
		{
			//$reponse = new PostController();
			//$reponse->postData();
			$response = PostModel::postData($table,$data);
		
			//return;

			$return = new PostController();
			$return->fncResponse($response,"postData");			
		}
	
	// Respuestas del controlador, cuando realizan el POST de la tabla.
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
	}
		

	} // class PostController
?>
