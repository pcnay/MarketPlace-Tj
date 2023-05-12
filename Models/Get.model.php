<?php


https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Orders,t_Messages&type=product,order,message


	require_once "connection.php";

	class GetModel
	{
		// Peticion GET sin filtro
		// Se coloca "static" porque en la variable que se asigna va almacenar los valores.
		// Obtener los datos de la tabla que se envio.
		//	$response = GetModel::getData($table);

		// Cuando se quiere que se ejecute inmediatamente para que te devuelve el valor
		// Se define la funcion "getData() como "static"
		//$reponse = new GetController();
		//$response->getData()

		static public function getData($table)
		{
			$stmt = Connection::connect()->prepare("SELECT * FROM $table");
			$stmt->execute();
			// fetchAll = Retorna todas las filas
			// PDO:FETCH_CLASS = Solo mostrara los nombre de columna con su contenido
			$Data= $stmt->fetchAll(PDO::FETCH_CLASS); 
			//$Data = "DAtos retornados";
			
			$stmt->closeCursor();
			$stmt=null;

			return $Data;
		}

		// Get con Filtro
		static public function getFilterData($table,$linkTo,$equalTo)
		{
			$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo ");
			$stmt->bindParam(":".$linkTo,$equalTo, PDO::PARAM_STR);
			$stmt->execute();
			// fetchAll = Retorna todas las filas
			// PDO:FETCH_CLASS = Solo mostrara los nombre de columna con su contenido
			$Data= $stmt->fetchAll(PDO::FETCH_CLASS); 
			//$Data = "DAtos retornados";
			
			$stmt->closeCursor();
			$stmt=null;

			return $Data;
		}

		// Peticiones GET tablas relacionadas sin filtro
		static public function getRelData($rel,$type)
		{
			$relArray = explode(",",$rel);
			$typeArray = explode(",",$type);

			// Relacionar dos tablas.
			if (count($relArray) == 2 && count($typeArray) == 2)
			{
				$On1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[1];
				/*
				echo '<pre>';
				print_r($typeArray);
				echo '</pre>';
				*/
			
				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");
				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");

			} // if (count($relArray) == 2 && count($typeArray) == 2)

			// Relacionar 3 tablas.
			if (count($relArray) == 3 && count($typeArray) == 3)
			{

				$On1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
				$On1b = $relArray[1].".id_".$typeArray[1];
				$On2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];
				$On2b = $relArray[2].".id_".$typeArray[2];

				/*
				echo '<pre>';
				print_r($relArray);
				print_r($typeArray);
				echo '</pre>';
				*/
			
				// Estableciendo la relacion de las tablas.
				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b");

				//$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");

			} // if (count($relArray) == 2 && count($typeArray) == 2)

			// Relacionar 4 tablas.
			if (count($relArray) == 4 && count($typeArray) == 4)
			{

				/*
				$On1 = $relArray[0].".id_".$typeArray[0]; // t_Products.id_product
				// No existen las columnas.
				// Se tiene que relacionar de Hijos a Padre.
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1];	//t_Categories.id_product_category
				$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2]; //t_Subcategories.id_product_subcategory
				$On4 = $relArray[3].".id_".$typeArray[0]."_".$typeArray[3]; //t_Stores.id_product_store
				*/

				/*
				echo '<pre>';
				print_r($relArray);
				print_r($typeArray);
				echo '</pre>';
				*/
			
				// t_Products.id_category.product = t_Categories.id_category
				// t_Products.id_subcategory.product = t_Subcategories.id_subcategory
				// t_Products.id_store.product = t_Stores.id_store

				$On1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
				$On1b = $relArray[1].".id_".$typeArray[1];
				$On2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];
				$On2b = $relArray[2].".id_".$typeArray[2];
				$On3a = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0];
				$On3b = $relArray[3].".id_".$typeArray[3];


				// Estableciendo la relacion de las tablas.
				// $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 INNER JOIN $relArray[2] ON $On1 = $On3");
				$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b INNER JOIN $relArray[3] ON $On3a = $On3b");


				//$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");

			} // if (count($relArray) == 2 && count($typeArray) == 2)

			$stmt->execute();

			// fetchAll = Retorna todas las filas
			// PDO:FETCH_CLASS = Solo mostrara los nombre de columna con su contenido
			$Data= $stmt->fetchAll(PDO::FETCH_CLASS); 
			//$Data = "DAtos retornados";
		
			$stmt->closeCursor();
			$stmt=null;

			return $Data;
			

		}

	} // class GetModel

?>
