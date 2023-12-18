<?php


//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Orders,t_Messages&type=product,order,message


	require_once "connection.php";

	class GetModel
	{
		// Peticion GET sin filtro

		// Formas de involcar el llamado de la funcion.
		// Se coloca "static" porque en la variable que se asigna va almacenar los valores.
		// Obtener los datos de la tabla que se envio. Almacenar informacion, reutilizarla en cualquier parte del programa
		//	$response = GetModel::getData($table);

		// Cuando se quiere que se ejecute inmediatamente para que te devuelve el valor
		// Se define la funcion "getData() como "static" 
		//$reponse = new GetController();
		//$response->getData()

		static public function getData($table,$orderBy,$orderMode,$startAt,$endAt)
		{
			// Para mostrar que valor tiene la variable $table
			/*
			echo '<pre>';
			print_r($table);
			echo '</pre>';
			return;
			*/

			//https://www.miportalweb.org/curso-web/MarketPlace/t_Categories?orderBy=name_category&orderMode=ASC

			if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $orderBy $orderMode ");
			}
			else if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))						
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/t_Categories?orderBy=id_category&orderMode=ASC&startAt=3&endAt=7

					$stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");	
			}
			else
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table");
			}

			$stmt->execute();
			// fetchAll = Retorna todas las filas
			// PDO:FETCH_CLASS = Solo mostrara los nombre de columna con su contenido, no muestra los indices.
			$Data= $stmt->fetchAll(PDO::FETCH_CLASS); 
			
			$stmt->closeCursor();
			$stmt=null;

			return $Data;
		}

		// =============================================================================
		// Peticion Get con Filtro
		// ============================================================================
		static public function getFilterData($table,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
		{
			if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
			}
			else if(($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");
			}
			else
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo");
			}

			// bindParam = Envia los parametros ocultos.
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
		
		// ==============================================================
		// Peticiones GET tablas relacionadas sin filtro		
		// Se debe colocar la Tabla Hijo, y despues la tabla Padre
		// ==============================================================
		static public function getRelData($rel,$type,$orderBy,$orderMode,$startAt,$endAt)
		{
			//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Categories,t_Products&type=category,product
			
			// Separa los nombres de las tablas.
			$relArray = explode(",",$rel);
			$typeArray = explode(",",$type);
			/* Para mostrar que valores esta obteniendo 
				echo '<pre>';
				print_r($relArray);
				echo '</pre>';
			
				t_Categories
				t_Products
			
				echo '<pre>';
				print_r($typeArray);
				echo '</pre>';
				category
				product
			*/

			if (count($relArray) == 2 && count($typeArray) == 2)
			{
				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				/*
				$On1 = $relArray[0].".id_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1];
				*/
				$On1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[1];

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 ORDER BY $orderBy $orderMode");				
				}
				else if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");				
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2");	
				}

			} //if (count($relArray) == 2 && count($typeArray) == 2)

			// Relacionar 3 tablas.
			if (count($relArray) == 3 && count($typeArray) == 3)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Categories,t_Subcategories,t_Products&type=category,subcategory,product				
				
				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				// Hacerlo de forma dinamico.
				/* 
				Es relacion de Padre a Hijo.
				$On1 = $relArray[0].".id_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1];
				$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2];

				Se realiza de la siguiente manera.

				*/

				$On1A = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product = t_Categories.id_category";
				$On1B = $relArray[1].".id_".$typeArray[1];	//"t_Categories.id_category";

				$On2A = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];	//"t_Products.id_subcategory_product";
				$On2B = $relArray[2].".id_".$typeArray[2]; 	//"t_Subcategories.id_subcategory";

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B ORDER BY $orderBy $orderMode");				
				}				
				else if(($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");				
				}				
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B");				
				}	
							

			} // if (count($relArray) == 3 && count($typeArray) == 3)

			/// Falta por revisar
			

			// Relacionar 4 tablas.
			if (count($relArray) == 4 && count($typeArray) == 4)
			{

				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store				
				
				// Hacerlo de forma dinamico.
				

				// Estos tres parametros no existen, por lo que se debe primero utilizar la tabaja Hija, para despues la tabla Padre.
				//$On1 = $relArray[0].".id_".$typeArray[0]; 	//t_Product.id_product
				//$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1]; //t_Categories.id_product_category
				//$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2];	//t_Subcategories.id_product_subcategory
				//$On4 = $relArray[3].".id_".$typeArray[0]."_".$typeArray[3];	//t_Stores,id_product_store
				// La relaciones correctas seran:
				/*
				t_Products.id_category_product = t_Categories.id_category
				t_Products.id_subcategory_product = t_Subcategories.id_subcategory
				t_Products.id_store_product = t_Stores.id_store		
				*/

				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store

				$On1A = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product = t_Categories.id_category";
				$On1B = $relArray[1].".id_".$typeArray[1];	//"t_Categories.id_category";

				$On2A = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];	//"t_Products.id_subcategory_product";
				$On2B = $relArray[2].".id_".$typeArray[2]; 	//"t_Subcategories.id_subcategory";

				$On3A = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0];	// "t_Products.id_store_product";
				$On3B = $relArray[3].".id_".$typeArray[3]; // "t_Stores.id_store";
			
				// Para este caso se debe iniciar primero con la tabla Hija primero
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store				
				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B ORDER BY $orderBy $orderMode");							
				}
				else if(($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B");							
				}
			}

			$stmt->execute();
			$Data = $stmt->fetchAll(PDO::FETCH_CLASS);

			$stmt->closeCursor();
			$stmt=null;

			return $Data;
		} // static public function getRelData($rel,$type)


		// ==============================================================
		// Peticiones GET tablas relacionadas para el buscador entre tablas relacionadas 
		// Se debe colocar la Tabla Hijo, y despues la tabla Padre
		// ==============================================================
		
		// https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store&linkTo=name_product&search=portable&orderBy=id_product&orderMode=DESC&startAt=0&$endAt=1



		static public function getSearchRelData($rel,$type,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt)
		{
			// Separa los nombres de las tablas.
			$relArray = explode(",",$rel);
			$typeArray = explode(",",$type);
			/* Para mostrar que valores esta obteniendo 
				echo '<pre>';
				print_r($relArray);
				echo '</pre>';
			
				//t_Categories
				//t_Products
			
				echo '<pre>';
				print_r($typeArray);
				echo '</pre>';
				category
				product
			*/

			if (count($relArray) == 2 && count($typeArray) == 2)
			{
				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				// "&linkto" = Es el nombre de la Columna ; "&equalTo" = Es el valor de la columna				
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories&type=product,category&linkTo=url_category&equalTo=consumer-electric		

				// https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Subcategories&type=product,subcategory&linkTo=url_category&equalTo=home-audio-theathers

				$On1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[1];

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode");				
				}
				else if(($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");				
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 WHERE $linkTo LIKE '%$search%'");				
				}

			} //if (count($relArray) == 2 && count($typeArray) == 2)

			// Relacionar 3 tablas.
			if (count($relArray) == 3 && count($typeArray) == 3)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories&type=product,category,subcategory&linkTo=url_subcategory&equalTo=home-audio-theathers				
				

				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				// Hacerlo de forma dinamico.
				/* 
				Es relacion de Padre a Hijo.
				$On1 = $relArray[0].".id_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1];
				$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2];

				Se realiza de la siguiente manera.

				*/

				$On1A = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product = t_Categories.id_category";
				$On1B = $relArray[1].".id_".$typeArray[1];	//"t_Categories.id_category";

				$On2A = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];	//"t_Products.id_subcategory_product";
				$On2B = $relArray[2].".id_".$typeArray[2]; 	//"t_Subcategories.id_subcategory";

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode");
				}
				else if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");
				}
				else				
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B WHERE $linkTo LIKE '%$search%'");
				}

			} // if (count($relArray) == 3 && count($typeArray) == 3)

			// Relacionar 4 tablas.
			if (count($relArray) == 4 && count($typeArray) == 4)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store&linkTo=url_subcategory&equalTo=home-audio-theathers
				
				
				// Hacerlo de forma dinamico.		
				// Estos tres parametros no existen, por lo que se debe primero utilizar la tabaja Hija, para despues la tabla Padre.
				//$On1 = $relArray[0].".id_".$typeArray[0]; 	//t_Product.id_product
				//$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1]; //t_Categories.id_product_category
				//$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2];	//t_Subcategories.id_product_subcategory
				//$On4 = $relArray[3].".id_".$typeArray[0]."_".$typeArray[3];	//t_Stores,id_product_store
				// La relaciones correctas seran:
				/*
				t_Products.id_category_product = t_Categories.id_category
				t_Products.id_subcategory_product = t_Subcategories.id_subcategory
				t_Products.id_store_product = t_Stores.id_store		
				*/

				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store

				$On1A = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product = t_Categories.id_category";
				$On1B = $relArray[1].".id_".$typeArray[1];	//"t_Categories.id_category";

				$On2A = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];	//"t_Products.id_subcategory_product";
				$On2B = $relArray[2].".id_".$typeArray[2]; 	//"t_Subcategories.id_subcategory";

				$On3A = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0];	// "t_Products.id_store_product";
				$On3B = $relArray[3].".id_".$typeArray[3]; // "t_Stores.id_store";
			
				// Para este caso se debe iniciar primero con la tabla Hija primero
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store				

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode");
				}
				else if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B WHERE $linkTo LIKE '%$search%' LIMIT $startAt,$endAt");
				}
				else 
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B WHERE $linkTo LIKE '%$search%'");
				}
			}

			$stmt->bindParam(":".$linkTo,$equalTo,PDO::PARAM_STR);
			$stmt->execute();
			$Data = $stmt->fetchAll(PDO::FETCH_CLASS);

			$stmt->closeCursor();
			$stmt=null;

			return $Data;

		} // static public function getRelData($rel,$type)

		// ==============================================================
		// Peticiones GET tablas relacionadas CON filtro		
		// Se debe colocar la Tabla Hijo, y despues la tabla Padre
		// ==============================================================
		static public function getRelFilterData($rel,$type,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
		{
			// Separa los nombres de las tablas.
			$relArray = explode(",",$rel);
			$typeArray = explode(",",$type);
			/* Para mostrar que valores esta obteniendo 
				echo '<pre>';
				print_r($relArray);
				echo '</pre>';
			
				//t_Categories
				//t_Products
			
				echo '<pre>';
				print_r($typeArray);
				echo '</pre>';
				category
				product
			*/

			if (count($relArray) == 2 && count($typeArray) == 2)
			{
				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				// "&linkto" = Es el nombre de la Columna ; "&equalTo" = Es el valor de la columna				
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories&type=product,category&linkTo=url_category&equalTo=consumer-electric		

				// https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Subcategories&type=product,subcategory&linkTo=url_category&equalTo=home-audio-theathers

				$On1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[1];

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");				
				}
				else if(($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");				
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1 = $On2 WHERE $linkTo = :$linkTo");				
				}

			} //if (count($relArray) == 2 && count($typeArray) == 2)

			// Relacionar 3 tablas.
			if (count($relArray) == 3 && count($typeArray) == 3)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories&type=product,category,subcategory&linkTo=url_subcategory&equalTo=home-audio-theathers				
				

				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				// Hacerlo de forma dinamico.
				/* 
				Es relacion de Padre a Hijo.
				$On1 = $relArray[0].".id_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1];
				$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2];

				Se realiza de la siguiente manera.

				*/

				$On1A = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product = t_Categories.id_category";
				$On1B = $relArray[1].".id_".$typeArray[1];	//"t_Categories.id_category";

				$On2A = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];	//"t_Products.id_subcategory_product";
				$On2B = $relArray[2].".id_".$typeArray[2]; 	//"t_Subcategories.id_subcategory";

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
				}
				else if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");
				}
				else				
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B WHERE $linkTo = :$linkTo");
				}

			} // if (count($relArray) == 3 && count($typeArray) == 3)

			// Relacionar 4 tablas.
			if (count($relArray) == 4 && count($typeArray) == 4)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store&linkTo=url_subcategory&equalTo=home-audio-theathers
				
				
				// Hacerlo de forma dinamico.		
				// Estos tres parametros no existen, por lo que se debe primero utilizar la tabaja Hija, para despues la tabla Padre.
				//$On1 = $relArray[0].".id_".$typeArray[0]; 	//t_Product.id_product
				//$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1]; //t_Categories.id_product_category
				//$On3 = $relArray[2].".id_".$typeArray[0]."_".$typeArray[2];	//t_Subcategories.id_product_subcategory
				//$On4 = $relArray[3].".id_".$typeArray[0]."_".$typeArray[3];	//t_Stores,id_product_store
				// La relaciones correctas seran:
				/*
				t_Products.id_category_product = t_Categories.id_category
				t_Products.id_subcategory_product = t_Subcategories.id_subcategory
				t_Products.id_store_product = t_Stores.id_store		
				*/

				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store

				$On1A = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product = t_Categories.id_category";
				$On1B = $relArray[1].".id_".$typeArray[1];	//"t_Categories.id_category";

				$On2A = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0];	//"t_Products.id_subcategory_product";
				$On2B = $relArray[2].".id_".$typeArray[2]; 	//"t_Subcategories.id_subcategory";

				$On3A = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0];	// "t_Products.id_store_product";
				$On3B = $relArray[3].".id_".$typeArray[3]; // "t_Stores.id_store";
			
				// Para este caso se debe iniciar primero con la tabla Hija primero
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store				

				if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
				}
				else if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B WHERE $linkTo = :$linkTo LIMIT $startAt,$endAt");
				}
				else 
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1A = $On1B INNER JOIN $relArray[2] ON $On2A = $On2B INNER JOIN $relArray[3] ON $On3A = $On3B WHERE $linkTo = :$linkTo");
				}
			}

			$stmt->bindParam(":".$linkTo,$equalTo,PDO::PARAM_STR);
			$stmt->execute();
			$Data = $stmt->fetchAll(PDO::FETCH_CLASS);

			$stmt->closeCursor();
			$stmt=null;

			return $Data;

		} // static public function getRelData($rel,$type)

		

		// Peticiones GET para el Buscador
		// 
		static public function getSearchData($table,$linkTo,$search,$startAt,$endAt)
		{
			//https://www.miportalweb.org/curso-web/MarketPlace/t_Products?linkTo=name_product&search=wireless


			$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%' LIMIT $startAt,$endAt");
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
