<?php
	class RoutesController
	{
		// Ruta Principal
		// Cuando no son estaticos las funciones, estas se disparan automaticamente.		
		
		public function index()
		{
			include "Routes/Route.php";
		}
		
		// Nombre de la Base De Datos.
		static public function database()
		{
			return "bd_marketplace";
		}

	}
?>