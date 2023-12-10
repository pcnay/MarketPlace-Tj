<?php
	/*

	https://www.php.net/manual/en/function.file-get-contents.php
	Para revisar el siguiente error.
	
	
<pre>Array
(
    [----------------------------826398124009380664301105
Content-Disposition:_form-data;_name] => "name_category"

Customer Electronic 3
----------------------------826398124009380664301105
Content-Disposition: form-data; name="title_list_category"

"[""Electronic"",""Accessories and Parts""]"
----------------------------826398124009380664301105
Content-Disposition: form-data; name="url_category"

consumer-electric-3
----------------------------826398124009380664301105
Content-Disposition: form-data; name="image_category"

comsumer-electric3.jpg
----------------------------826398124009380664301105
Content-Disposition: form-data; name="icon_category"

icon-laundry
----------------------------826398124009380664301105
Content-Disposition: form-data; name="views_category"

"3"
----------------------------826398124009380664301105
Content-Disposition: form-data; name="date_created_category"

2023-11-20
----------------------------826398124009380664301105--

)
</pre>
-----------------------------------------------------------------------
	MarketPlace: bd_marketPlace
	usuario_marketplace
	MarketPlace*2023-05-02

		Revisando el Video 56 ; 11:00 Min
		1.- Revisar en Get.Controller.php -> getData(), linea 11, Validar cuando no existe la Tabla.

		Para la seccion de POST:

		t_Categories -> Tabla
		
		id_category							-> Se rellenan de forma automatica

		name_category
		title_list_category
		url_category
		image_category
		icon_category
		views_category
		date_created_category
		
		date_updated_category		-> Se rellena de forma automatica.

		

	*/
?>
