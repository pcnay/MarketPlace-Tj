<?php
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

	} // class GetModel

?>
