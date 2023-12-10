<?php	
	require_once "connection.php";

	class deleteModel
	{
		// Peticion DELETE para eliminar datos
		// https://www.miportalweb.org/curso-web/MarketPlace/t_Categories?id=4&nameId=id_category
		
		static public function deleteData($table,$id,$nameId)
		{
			$stmt = Connection::connect()->prepare("DELETE FROM $table WHERE $nameId = :$nameId ");
			$stmt->bindParam(":".$nameId,$id,PDO::PARAM_INT);

			if ($stmt->execute())
			{
				return "The process was successful";
			}
			else
			{
				return Connection::connect()->errorInfo();
			}

		} // static public function deleteData($table,$id,$nameId)
	} // class deleteModel

?>
