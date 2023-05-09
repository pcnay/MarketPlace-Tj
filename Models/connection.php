<?php
	class Connection
	{
		// Se utilizara un metodo estatico, ya que almacena la informacion, ya que no se requiere que se dispare inmediatamente como sucede con los metodos estaticos.
		static public function connect()
		{
			try
			{
				$link = new PDO("mysql:host=localhost;dbname=bd_marketplace","usuario_marketplace","MarketPlace*2023-05-02");

				$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);											
				
				$mitz="America/Tijuana";
				$tz = (new DateTime('now', new DateTimeZone($mitz)))->format('P');
				$link->exec("SET time_zone='$tz';");

				$link->exec("set names utf8");
			}
			catch(PDOException $e)
			{
				die("Error".$e->getMessage());
			}
			
			return $link;

		}
	}
?>
