<?php
	require_once "connection.php";

	class PutModel
	{
		// Para llamarlo desde la URL : 
		// https://www.miportalweb.org/curso-web/MarketPlace/t_Categories?id=4&nameId=id_category

		// Peticion PUT para editar datos.
		static public function putData($table,$data,$id,$nameId)
		{
			$set = "";
			// Obteniendo los campos de la tabla de forma dinamica
			foreach ($data as $key => $value)
			{
				/*
				echo '<pre>';
				print_r($key); // Obtiendo el nombre del campo.
				echo '</pre>';
				*/
				$set .= $key." = :".$key.",";
			}				
				$set = substr($set,0,-1);
				/*
				echo '<pre>';
				print_r($set); // Obtiendo los campos de la tabla.
				echo '</pre>';
				return;
				*/

			//$stmt = Connection::connect()->prepare("UPDATE $table SET name_category = :name_category,title_list_category = :title_list_category,url_category = :url_category,image_category = :image_category,views_category = :views_category WHERE $nameId = :$nameId");
			$stmt = Connection::connect()->prepare("UPDATE $table SET $set  WHERE $nameId = :$nameId");
			
			/*
			$stmt->bindParam(":".$nameId,$id,PDO::PARAM_INT);
			$stmt->bindParam(":name_category",$data["name_category"],PDO::PARAM_STR);
			$stmt->bindParam(":title_list_category",$data["title_list_category"],PDO::PARAM_STR);
			$stmt->bindParam(":url_category",$data["url_category"],PDO::PARAM_STR);
			$stmt->bindParam(":image_category",$data["image_category"],PDO::PARAM_STR);
			$stmt->bindParam(":views_category",$data["views_category"],PDO::PARAM_STR);
			*/
			foreach ($data as $key => $value)
			{
				$stmt->bindParam(":".$key,$data[$key],PDO::PARAM_STR);
			}
			$stmt->bindParam(":".$nameId,$id,PDO::PARAM_INT);
		

			if ($stmt->execute())
			{
				return "The process was successful";
			}
			else
			{
				return Connection::connect()->errorInfo();
				// echo "\nPDO::errorInfo():\n";
				//print_r(Connection::connect()->errorInfo());
			}

		} //static public function putData($table,$data,$id,$nameId)
		
	} // 	class PutModel	

?>
