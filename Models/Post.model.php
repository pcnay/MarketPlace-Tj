<?php
	require_once "connection.php";

	class PostModel
	{

			// Obteniendo los nombres de los campos.
			static public function getColumnsData($table, $database)
			{
				return Connection::connect()->query("SELECT COLUMN_NAME AS item FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'")->fetchAll(PDO::FETCH_OBJ);

			}



		// Peticion POST para crear datos
		static public function postData($table, $data)
		{
			/*
			// Revisando que es lo que contiene la varaible $data.
			echo '<pre>';
			print_r($data);
			print_r($data["name_category"]);
			echo '</pre>';
			return;
			*/

			// Para hacerlo dinamico las siguientes lineas.
			// $data = Contiene los valores de los campos con sus respectivos valores, ejemplo: nombre = Cauqluier cosa
			$columns = "(";
			$params = "(";
			foreach ($data as $key => $value)
			{
				$columns .= $key.",";
				$params .= ":".$key.",";
			}
			
			// Mostrando el contenido de la variable.
			//echo '<pre>';
			//print_r($columns);
			//print_r($params);
			//echo '</pre>';
			
			// Obtiene de forma dinamica , los nombres de los campos de la tabla, y los parametros.
			$columns = substr($columns, 0, -1); // Extrae el ultimo caracter ","
			$params = substr($params,0,-1);
			$columns .= ")";
			$params .= ")";

			//echo '<pre>';
			//print_r($columns);
			//print_r($params);
			//echo '</pre>';
			
			//return;

			// Para la llamada en Postman, o Thunder Client
			// https://www.miportalweb.org/curso-web/MarketPlace/t_Subcategories	
			// Cuando se esten agregando los campos, en la seccion de "Field Name" de Postmane o Thunder Cliente
			// Se tiene que omitir el primer campo = "id_XXXX" y el ultimo "date_updated_xxxx" ya que no se requieren, de lo contrario va a fallar al comprar el numero de columans. 

			//$stmt = Connection::connect()->prepare("INSERT INTO $table(name_category, title_list_category, url_category, image_category, icon_category, views_category, date_created_category) VALUES (:name_category, :title_list_category, :url_category, :image_category, :icon_category, :views_category, :date_created_category)");

			$stmt = Connection::connect()->prepare("INSERT INTO $table $columns VALUES $params");


			/*
			$stmt->bindParam(":name_category",$data["name_category"],PDO::PARAM_STR);
			$stmt->bindParam(":title_list_category",$data["title_list_category"],PDO::PARAM_STR);
			$stmt->bindParam(":url_category",$data["url_category"],PDO::PARAM_STR);
			$stmt->bindParam(":image_category",$data["image_category"],PDO::PARAM_STR);
			$stmt->bindParam(":icon_category",$data["icon_category"],PDO::PARAM_STR);
			$stmt->bindParam(":views_category",$data["views_category"],PDO::PARAM_STR);
			$stmt->bindParam(":date_created_category",$data["date_created_category"],PDO::PARAM_STR);
			*/



			// Ahora hacerlo de forma dinamica:
			// $data = Contiene el valor que tiene el formulario, por lo que se tiene que extraer y agregarlos al p"params"
			// Para mostrar el contenido
			foreach($data as $key => $value)
			{
				//echo "<pre>";
				//echo print_r($key);
				//echo "</pre>";

				//echo "<pre>";
				//echo print_r($value);
				//echo "</pre>";
				// echo ":".$key.",".$data[$key];
				$stmt->bindParam(":".$key,$data[$key],PDO::PARAM_STR);	

			} 
			//return;
			
			if ($stmt->execute())
			{
				return "The process was successful";
			}
			else
			{
				/*
				echo "\nPDO::errorInfo():\n";
				print_r(Connection::connect()->errorInfo());
				*/
				return Connection::connect()->errorInfo();
			}

		} // static public function postData($table, $data)

	} //class PostModel
?>
