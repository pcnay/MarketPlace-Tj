<?php
	class TemplateController
	{
		// Traer la plantilla principal de la Plantilla
		public function index()
		{
			include "Views/template.php";
		}

		// Metodo utilizado para obtener el nombre del dominio.
		// Para quitar en la URL la carpeta de "Views".
		static public function path()
		{
			// Cuando cambie de ruta, modificarlo, en esta ocacion "api": "https://www.miportalweb.org/curso-web/MarketPlace/";
			return CurlController::api().'Tienda/'; 
			
		}

	} // class TemplateController
?>
