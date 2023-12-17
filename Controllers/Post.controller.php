<?php
	use Firebase\JWT\JWT; // Para llamar a la libreria.

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
			$return->fncResponse($response,"postData",null);			
		}
	
		// Peticion POST para registro de usuarios.
		public function PostRegister($table,$data)
		{

			if ((isset($data["password_user"])) && ($data["password_user"] != null))
			{
				// Encriptar la contraseña
				$crypt = crypt($data["password_user"],'$2a$07$hfjfh4859dfkshmcn3434sdjdj');
				$data["password_user"] = $crypt;
	
				$response = PostModel::postData($table,$data);	
				//return;	
				$return = new PostController();
				$return->fncResponse($response,"postData",null);			
			}

		}

	// Peticion POST para el "Login" de usuarios.
	public function PostLogin($table,$data)
	{
		$response = GetModel::getFilterData($table,"email_user",$data["email_user"],null,null,null,null);
		
		/*
		// Determinando el valor de la respuesta.
		echo '<pre>';
		print_r($response);
		echo '</pre>';					
		return;
		*/
		if (!empty($response)) // Si encontro el correo del usuario.
		{
			// Verificando la contrasena
				// Encriptar la contraseña
				$crypt = crypt($data["password_user"],'$2a$07$hfjfh4859dfkshmcn3434sdjdj');
				// $data["password_user"] = $crypt;
				if ($response[0]->password_user == $crypt)
				{
					// Se crea el JWT
					$time = time();
					// Asignando la firma (caracteres al azar)
					$key = "zxcvbnmqwertyuio23561jdk0251fj";

					$token = array(
						"iat"=>$time, 	// El tiempo en que inicia el token
						//"exp"=>$time+(60*60*24), // Tiempo en que expira el Token 1 Día 
						"exp"=>$time+(60*3),
						"data"=> [
							"id"=>$response[0]->id_user,
							"email"=>$response[0]->email_user
						]
					);
					// Generar el Token
					$jwt = JWT::encode($token,$key,'HS256');
					$data = array("token_user"=>$jwt,"token_exp_user"=>$token["exp"]);

					// Actualizando la base de datos  en el campo "token_user"
					$update = PutModel::putData($table,$data,$response[0]->id_user,"id_user");
					/*
					// Mostrando el contenido de $update
					echo '<pre>';
					print_r($update);
					echo '</pre>';					
					return;
					*/

					if ($update == "The process was successful")
					{
						$response[0]->token_user = $jwt;
						$response[0]->token_exp_user = $token["exp"];
						$return = new PostController();
						$return->fncResponse($response,"postLogin",null);		
					}		
				} //if ($response[0]->password_user == $crypt)
				else
				{
					$response = null;
					$return = new PostController();
					$return->fncResponse($response,"postLogin","Wrong Password");				
				} // if ($response[0]->password_usesr == $crypt)
		}
		else // if (!empty($response))
		{
			$response = null;
			$return = new PostController();
			$return->fncResponse($response,"postLogin","Wrong Email");		

		} //if (!empty($response))

	}
	// Respuestas del controlador, cuando realizan el POST de la tabla.
	public function fncResponse($response,$method,$error)
	{
		if (!empty($response))
		{
			// Para quitar la contraseña 
			if (isset($response[0]->password_user))
			{
				unset($response[0]->password_user); // Quitar elemento del arreglo.
			}

			$json = array(
				"status" => 200,
				//"summary" => count($response),
				"results" => $response
			);

		} //if (!empty($response))
		else
		{
			if (error != null )
			{
				$json = array(
					"status" => 400,					
					"results" => $error
				);
			}
			else
			{
				$json = array(
					"status" => 404,					
					"results" => "Not found",
					"method" => $method
				);
			}
		}

		echo json_encode($json,http_response_code($json["status"]));
		return;		
	}
		

	} // class PostController
?>
