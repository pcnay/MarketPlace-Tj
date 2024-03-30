<?php
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

		static public function getData($arreglo_parametros)
		{

			//https://www.miportalweb.org/curso-web/MarketPlace/t_Products

			$table = $arreglo_parametros['tabla'];
			$orderBy = $arreglo_parametros['campo_tabla'];
			$orderMode = $arreglo_parametros['tipo_ordenar'];
			$startAt = $arreglo_parametros['startAt'];
			$endAt = $arreglo_parametros['endAt'];

			//echo '<pre>';print_r($table);echo'</pre>';
			//echo '<pre>';print_r($orderBy);echo'</pre>';
			//echo '<pre>';print_r($orderMode);echo'</pre>';
			//exit;

			// Verificando los parametros de Ordernar y Limite
			//$cadena = "SELECT * FROM $table";
			// $startAt = Inicia desde 0
			// $endAt = Cuando deseas desplegar
			// Ejemplo: Uniciar en posicion 10 y desplegar 5 registros
			// $startAt = 10, 		$endAt = 5

			if (($orderBy == null) && ($orderMode == null) && ($startAt == null) && ($endAt == null))
			{
				$cadena = "SELECT * FROM $table";
			}
			if (($orderBy != null) && ($orderMode != null)) 
			{
				$cadena = "SELECT * FROM $table ORDER BY $orderBy $orderMode";
			}
			if  (($startAt != null) && ($endAt != null) && ($orderBy != null) && ($orderMode != null) )
			{
				$cadena = "SELECT * FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
			}			
			if  (($startAt != null) && ($endAt != null) && ($orderBy == null) && ($orderMode == null) )
			{
				$cadena = "SELECT * FROM $table LIMIT $startAt,$endAt";
			}

			//echo '<pre>';print_r($cadena);echo'</pre>';
			//exit;

/*
			if (($orderBy != null) && ($orderMode != null)) 
			{
				// $stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $orderBy $orderMode");
				$stmt = Connection::connect()->prepare($cadena);
			}			
			else
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table");				
			}
			
			if (($startAt != null) && ($endAt != null))
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt $endAt");
			}			
			else
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table");				
			}
*/

				// fetchAll = Retorna todas las filas
				// PDO:FETCH_CLASS = Solo mostrara los nombre de columna con su contenido, no muestra los indices.

				$stmt = Connection::connect()->prepare($cadena);				
				$stmt->execute();
				$Data= $stmt->fetchAll(PDO::FETCH_CLASS); 				

				$stmt->closeCursor();
				$stmt=null;

			return $Data;
		}
		// =============================================================================
		// Peticion Get con Filtro
		// ============================================================================
		static public function getFilterData($arreglo_parametros)
		{		
			$tabla = $arreglo_parametros['tabla']; 
			$linkTo = $arreglo_parametros['Valor_linkTo']; 
			$equalTo = $arreglo_parametros['Valor_equalTo']; 
			$orderBy = $arreglo_parametros['orderBy']; 
			$orderMode = $arreglo_parametros['orderMode']; 
			$startAt = $arreglo_parametros['startAt']; 
			$endAt = $arreglo_parametros['endAt']; 
			
			$Cadena_SQL = "SELECT * FROM $tabla WHERE $linkTo = :$linkTo";

			if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
			{
				$Cadena_SQL = $Cadena_SQL.' ORDER BY '.$orderBy.' '.$orderMode. ' LIMIT '.$startAt.','.$endAt;				
			}

			if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null))
			{
				$Cadena_SQL = $Cadena_SQL.' LIMIT '.$startAt.','.$endAt;				
			}

			if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
			{
				//$stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
				$Cadena_SQL = $Cadena_SQL.' ORDER BY '.$orderBy.' '.$orderMode;				
			}
			else
			{
			//	$stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $linkTo = :$linkTo");
				// bindParam = Envia,enlaza los parametros ocultos.
			}

			//echo '<pre>';print_r($Cadena_SQL);echo'</pre>';
			//exit;

			$stmt = Connection::connect()->prepare($Cadena_SQL);
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

		static public function getRelData($arreglo_parametros)
		{
			// ==================> Se debe colocar primero la tabla HIJA y despues la tabla PADRE.

			$relArray = explode(",",$arreglo_parametros['tabla']); // Retorna un arreglo de los nombres de la Tablas.
			$typeArray = explode(",",$arreglo_parametros['campo_tabla']); // Retorna un arreglo de los nombres de la Campos.

			// Cuando sean relaciones de 2 Tablas
			if ((count($relArray) == 2) && (count($typeArray)) == 2)
			{
				// $relArray[0] = t_Categories
				// $relArray[1] = t_Products
				
				// $typeArray[0] = category
				// $typeArray[1] = product 

				//echo '<pre>';print_r($relArray);echo'</pre>';
				//echo '<pre>';print_r($typeArray);echo'</pre>';
				//exit;

				// Se realiza la relacion de Padre a Hijo para la extraccion de datos.
				// Estableciendo la relacion de las tablas.
				$On1a = $relArray[0].'.id_'.$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product"; 
				$On1b = $relArray[1].'.id_'.$typeArray[1]; //"t_Categories.id_category";

				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");
				$orderBy = $arreglo_parametros['orderBy'];
				$orderMode = $arreglo_parametros['orderMode'];

				//echo '<pre>';print_r($orderBy);echo'</pre>';
				//echo '<pre>';print_r($orderMode);echo'</pre>';
				//exit;

				if (($orderBy != null) && ($orderMode != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b ORDER BY $orderBy $orderMode");
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b");
				}


			} // if ((count($relArray) == 2) && (count($typeArray)) == 2)

			// Relaciones de tres tablas.
			if ((count($relArray) == 3) && (count($typeArray)) == 3)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Categories,t_Subcategories,t_Products&type=category,subcategory,product

				// $relArray[0] = t_Categories
				// $relArray[1] = t_Subcategories
				// $relArray[2] = t_Products
				
				// $typeArray[0] = category
				// $typeArray[1] = subcategory
				// $typeArray[2] = product 

				//echo '<pre>';print_r($relArray);echo'</pre>';
				//echo '<pre>';print_r($typeArray);echo'</pre>';
				//exit;

				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				// Se realiza la relacion de Padre a Hijo para la extraccion de datos.
				// Estableciendo la relacion de las tablas.
				$On1a = $relArray[0].'.id_'.$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product"; 
				$On1b = $relArray[1].'.id_'.$typeArray[1]; //"t_Categories.id_category";
				$On2a = $relArray[0].'.id_'.$typeArray[2]."_".$typeArray[0]; //"t_Products.id_subcategory_product";
				$On2b = $relArray[2].'.id_'.$typeArray[2]; //"t_Subcategories.id_subcategory";

				$orderBy = $arreglo_parametros['orderBy'];
				$orderMode = $arreglo_parametros['orderMode'];
				if (($orderBy != null) && ($orderMode != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b ORDER BY $orderBy $oderMode");
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b");
				}
			}

			if ((count($relArray) == 4) && (count($typeArray)) == 4)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store
 

				// $relArray[0] = t_Products 
				// $relArray[1] = t_Categories
				// $relArray[2] = t_Subcategories
				// $relArray[3] = t_Stores
				
				// $typeArray[0] = product 
				// $typeArray[1] = category 
				// $typeArray[2] = subcategory
				// $typeArray[3] = store

				//echo '<pre>';print_r($relArray);echo'</pre>';
				//echo '<pre>';print_r($typeArray);echo'</pre>';
				//exit;
				// Se van a cambiar las relaciones, de Hija a Padre, ya que de lo contrario no funcionaria:				
				$On1a = $relArray[0].'.id_'.$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product"; 
				$On1b = $relArray[1].'.id_'.$typeArray[1]; //"t_Categories.id_category";
				$On2a = $relArray[0].'.id_'.$typeArray[2]."_".$typeArray[0]; //"t_Products.id_subcategory_product";
				$On2b = $relArray[2].'.id_'.$typeArray[2]; //"t_Subcategories.id_subcategory";
				$On3a = $relArray[0].'.id_'.$typeArray[3]."_".$typeArray[0]; //"t_Products.id_store_product";
				$On3b = $relArray[3].'.id_'.$typeArray[3]; //"t_Stores.id_store";

				$orderBy = $arreglo_parametros['orderBy'];
				$orderMode = $arreglo_parametros['orderMode'];
				
				//echo '<pre>';print_r($orderBy);echo'</pre>';
				//echo '<pre>';print_r($orderMode);echo'</pre>';
				//exit;


				if (($orderBy != null) && ($orderMode != null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b INNER JOIN $relArray[3] ON $On3a = $On3b ORDER BY $orderBy $orderMode");
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b INNER JOIN $relArray[3] ON $On3a = $On3b");
				}				

			}
			$stmt->execute();
			$Data = $stmt->fetchAll(PDO::FETCH_CLASS);

			$stmt->closeCursor();
			$stmt=null;

			return $Data;

		}

		// ==============================================================
		// Peticiones GET tablas relacionadas CON filtro		
		// Se debe colocar la Tabla Hijo, y despues la tabla Padre
		// ==============================================================

		static public function getRelFilterData($arreglo_parametros)
		{
			// ==================> Se debe colocar primero la tabla HIJA y despues la tabla PADRE.
			
			$relArray = explode(",",$arreglo_parametros['tabla']); // Retorna un arreglo de los nombres de la Tablas.
			$typeArray = explode(",",$arreglo_parametros['campo_tabla']); // Retorna un arreglo de los nombres de la Campos.
			$linkTo = $arreglo_parametros['campo_teclado'];
			$equalTo = $arreglo_parametros['contenido_campo_teclado'];
			
			$orderBy = $arreglo_parametros['orderBy'];
			$orderMode = $arreglo_parametros['orderMode'];

			//echo'<pre>';print_r($typeArray);echo'</pre>';								
			//echo'<pre>';print_r($linkTo);echo'</pre>';
			//echo'<pre>';print_r($equalTo);echo'</pre>';
			//echo'<pre>';print_r($orderBy);echo'</pre>';
			//echo'<pre>';print_r($orderMode);echo'</pre>';
			//exit;

			// Cuando sean relaciones de 2 Tablas
			if ((count($relArray) == 2) && (count($typeArray)) == 2)
			{
				// $relArray[0] = t_Categories
				// $relArray[1] = t_Products
				
				// $typeArray[0] = category
				// $typeArray[1] = product 

				//echo '<pre>';print_r($relArray);echo'</pre>';
				//echo '<pre>';print_r($typeArray);echo'</pre>';
				//exit;

				// Se realiza la relacion de Padre a Hijo para la extraccion de datos.
				// Estableciendo la relacion de las tablas.
				$On1a = $relArray[0].'.id_'.$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product"; 
				$On1b = $relArray[1].'.id_'.$typeArray[1]; //"t_Categories.id_category";

				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");
				if (($orderBy != Null) && ($orderMode != Null))
				{					 
					 //echo "SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode";

					//exit;
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b WHERE $linkTo = :$linkTo");
				}
				
			} // if ((count($relArray) == 2) && (count($typeArray)) == 2)

			// Relaciones de tres tablas.
			if ((count($relArray) == 3) && (count($typeArray)) == 3)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Categories,t_Subcategories,t_Products&type=category,subcategory,product

				// $relArray[0] = t_Categories
				// $relArray[1] = t_Subcategories
				// $relArray[2] = t_Products
				
				// $typeArray[0] = category
				// $typeArray[1] = subcategory
				// $typeArray[2] = product 

				//echo '<pre>';print_r($relArray);echo'</pre>';
				//echo '<pre>';print_r($typeArray);echo'</pre>';
				//exit;

				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Subcategories ON t_Categories.id_category = t_Subcategories.id_category_subcategory INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				// Se realiza la relacion de Padre a Hijo para la extraccion de datos.
				// Estableciendo la relacion de las tablas.
				$On1a = $relArray[0].'.id_'.$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product"; 
				$On1b = $relArray[1].'.id_'.$typeArray[1]; //"t_Categories.id_category";
				$On2a = $relArray[0].'.id_'.$typeArray[2]."_".$typeArray[0]; //"t_Products.id_subcategory_product";
				$On2b = $relArray[2].'.id_'.$typeArray[2]; //"t_Subcategories.id_subcategory";

				if (($orderBy != Null) && ($orderMode != Null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b WHERE $linkTo = :$linkTo");
				}			
				
			}

			if ((count($relArray) == 4) && (count($typeArray)) == 4)
			{
				//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories,t_Stores&type=product,category,subcategory,store
 

				// $relArray[0] = t_Products 
				// $relArray[1] = t_Categories
				// $relArray[2] = t_Subcategories
				// $relArray[3] = t_Stores
				
				// $typeArray[0] = product 
				// $typeArray[1] = category 
				// $typeArray[2] = subcategory
				// $typeArray[3] = store

				//echo '<pre>';print_r($relArray);echo'</pre>';
				//echo '<pre>';print_r($typeArray);echo'</pre>';
				//exit;
				// Se van a cambiar las relaciones, de Hija a Padre, ya que de lo contrario no funcionaria:				
				$On1a = $relArray[0].'.id_'.$typeArray[1]."_".$typeArray[0]; //"t_Products.id_category_product"; 
				$On1b = $relArray[1].'.id_'.$typeArray[1]; //"t_Categories.id_category";
				$On2a = $relArray[0].'.id_'.$typeArray[2]."_".$typeArray[0]; //"t_Products.id_subcategory_product";
				$On2b = $relArray[2].'.id_'.$typeArray[2]; //"t_Subcategories.id_subcategory";
				$On3a = $relArray[0].'.id_'.$typeArray[3]."_".$typeArray[0]; //"t_Products.id_store_product";
				$On3b = $relArray[3].'.id_'.$typeArray[3]; //"t_Stores.id_store";

				if (($orderBy != Null) && ($orderMode != Null))
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b INNER JOIN $relArray[3] ON $On3a = $On3b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");			

				}
				else
				{
					$stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] INNER JOIN $relArray[1] ON $On1a = $On1b INNER JOIN $relArray[2] ON $On2a = $On2b INNER JOIN $relArray[3] ON $On3a = $On3b WHERE $linkTo = :$linkTo");			
				}		
				
			}

			$stmt->bindParam(":".$linkTo,$equalTo, PDO::PARAM_STR);	
			$stmt->execute();
			$Data = $stmt->fetchAll(PDO::FETCH_CLASS);

			$stmt->closeCursor();
			$stmt=null;

			return $Data;

		}
			// ==============================================================
			// Peticiones GET para el Buscador
			// Se debe colocar la Tabla Hijo, y despues la tabla Padre
			// ==============================================================

		static public function getSearchData($arreglo_parametros)	
		//static public function getSearchData($table,$search,$startAt,$endAt)
		{
			//https://www.miportalweb.org/curso-web/MarketPlace/t_Products?linkTo=name_product&search=wireless

			$table = $arreglo_parametros['tabla'];
			$linkTo = $arreglo_parametros['campo_tabla'];
			$search = $arreglo_parametros['buscar'];
			$orderBy = $arreglo_parametros['orderBy'];
			$orderMode = $arreglo_parametros['orderMode'];
			$startAt = $arreglo_parametros['startAt'];
			$endAt = $arreglo_parametros['endAt'];

			//$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%' LIMIT $startAt,$endAt");

			if (($startAt != Null) && ($endAt != Null) && ($orderBy == Null) && ($orderMode == Null))
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%' LIMIT $startAt,$endAt");
			}
			if (($orderBy != Null) && ($orderMode != Null) && ($startAt != Null) && ($endAt != Null))
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt");
			}
			else
			{
				$stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%'");
			}



			$stmt->execute();
			// fetchAll = Retorna todas las filas
			// PDO:FETCH_CLASS = Solo mostrara los nombre de columna con su contenido
			$Data= $stmt->fetchAll(PDO::FETCH_CLASS); 
			//$Data = "DAtos retornados";
			
			$stmt->closeCursor();
			$stmt=null;

			return $Data;			
		}



		/*
		static public function getRelData($rel,$type,$orderBy,$orderMode,$startAt,$endAt)
		{
			//https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Categories,t_Products&type=category,product
			
			// Separa los nombres de las tablas.
			$relArray = explode(",",$rel);
			$typeArray = explode(",",$type);
			
			if (count($relArray) == 2 && count($typeArray) == 2)
			{
				// Estableciendo la relacion de las tablas.
				//$stmt = Connection::connect()->prepare("SELECT * FROM t_Categories INNER JOIN t_Products ON t_Categories.id_category = t_Products.id_category_product");

				/*
				$On1 = $relArray[0].".id_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[0]."_".$typeArray[1];
				
				$On1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
				$On2 = $relArray[1].".id_".$typeArray[1];

				// Se relacionan dos tablas.
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
*/

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
		static public function getRelFilterDataff($rel,$type,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
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
		
	} // class GetModel

?>
