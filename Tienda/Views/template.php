<?php

	// Traer el dominio principal, se quita la carpeta de "Views" de la URL amigable.
	// Se implementa otro metodo para agregar la ruta, en "Tienda/Controller/Template.controller.php"
	$path = TemplateController::path(); // "curso-web/MarketPlace/Tienda"
	/* 
		[1] => curso-web
    [2] => MarketPlace
    [3] => Tienda    
    [4] => rayban-rounded-sunglass-brown-color
		Por lo que se tiene que dejar solo el indice [5], por lo que se tiene que modificar en ".htaccess" y en "Controllers" (template.controller.php)

	*/
	// Debe ser el indice 3, ya que contiene el nombre del producto (URL amigables)
	// Para este ejemplo : /curso-web/MarketPlace/Tienda/url_subcategoria

	// Traer tk total de productos.
	$url = CurlController::api()."t_Products"; // Ruta desde donde se ejecutara la API
	$method = "GET";
	$fields = array();
	$header = array();

	$totalProducts = CurlController::request($url,$method,$fields,$header);
	// summary = Esta definido en la funcion "fncResponse" en Get.Controllers, obtiene el total de productos.

	$total = CurlController::request($url,$method,$fields,$header)->summary; 
	//echo '<pre>';
	// print_r($totalProducts);
	//$totalProducts = "Total Productos";
	//var_dump($totalProducts);
	//print_r($total); // Mas legible para leer utilizando "print_r"
	//print_r($totalProducts); // Mas legible para leer utilizando "print_r"
	//echo '</pre>';

	// REQUEST_URI = Para extraerlas palabras despues del nombre de dominio
	// HTTP_HOST = Extraer el nombre del dominio
	
	// ==========================================================
	// ============= CAPTURAR LAS RUTAS DE LA URL ===============
	// ==========================================================

	// Usando comandos de PHP para convertir la cadena en un arreglo y poder extraer lo que se requiere
		/*
---------->>>>>> 	Se debe tener encuenta, cuando cambie la URL en la barra de direcciones. Ya que por lo general solo es:
	https://www.miportalweb.org/sitio

		[0]] => 
    [1] => curso-web
    [2] => MarketPlace
    [3] => Categorias
*/

$routesArray = explode("/",$_SERVER['REQUEST_URI']);

$routesArray = (array_filter($routesArray));
// Elimina el espacio en blanco, quedando
/*
[1] => curso-web
[2] => MarketPlace
[3] => Categorias
*/

//echo '<pre>';
//print_r($routesArray[4]); // Determina que URL se acceso en un momento dado.
//print_r($path);
//echo '</pre>';

// Muestra el arreglo de : 
/*
	//https://www.miportalweb.org/curso-web/MarketPlace/Tienda/consumer-electric

    [1] => curso-web
    [2] => MarketPlace
    [3] => Tienda
		[4] => customer electric
	// Por lo que se utilizara el indice 4 que es el nombre de la liga para accesar (Categoria, subcategoria, Boton de Producto, etc.)
*/


//$routesArray = (array_filter($routesArray)); Retorna: "curso-web", "MarketPlace", el dominio es : "wwww.miportalweb.org"
// Se tiene que cambiar dependiendo de las carpetas que se usen.
// Por esta razon se selecciona : "$routesArray[3]
	//$routesArray = $routesArray[3];

	// Para extraer palabra despues del dominio. "curso-web/MarketPlace/Tienda", desplegara la URL amigable, "curso-web/MarketPlace/Tienda/customer-electric"
	//$name_table = $routesArray[3];
	//$routesArray = $routesArray[3];

	

	/*
echo '<pre>';
print_r($path); // Muestra la ruta raiz del proyecto actualmente. Puede cambiar de acuerdo al dominio o ruta que se este usando (servidor interno).
echo '</pre>';
*/

//if (count($routesArray)==2)

	if (count($routesArray)==3)
	{
		$urlParams = Null;
	}
	else
	{
		$urlParams = $routesArray[4]; // Es el nombre de la liga que accesa "Categoria, Subcategoria, etc.."
		//echo '<pre>';	print_r(array_filter($routesArray)[4]); echo '</pre>'; 
	}

	if (!empty($urlParams))
	{
		// Tener encuenta que que este indice cambia cuando la ruta donde se encuentra el proyecto, para este caso es: /curso-web/MarketPlace/Tienda/
		// Para trabajar con la paginacion, utilizando el caracter comidin "&"
		$urlParams = explode("&",$routesArray[4]); // Se obtiene solo la URL amigable.
		// Si se cambia a $urlParams[1]  = 20  de la ruta customer-electric&20
		// Si se cambia a $urlParams[0]  = customer-electric ; de la ruta customer-electric&20
		//echo '<pre>';	print_r($urlParams[0]); echo '</pre>';			
	}

	if (!empty($urlParams[0])) // Cuando se envia la url por ejemplo "customer-electric"
	{
		// Filtrar Categorias con el parámetro URL
		// Confirmar si existe la url en la tabla de "Categorias"
		$url = CurlController::api()."t_Categories?linkTo=url_category&equalTo=".$urlParams[0];
		$method = "GET";
		$fields = array();
		$header = array();
		$urlCategories = CurlController::request($url,$method,$fields,$header);
		//echo '<pre>';	print_r($urlCategories->status); echo '</pre>';		
		
		// No encontro la "Categoria"
		// Filtrar SubCategorias con el parámetro URL
		if ($urlCategories->status == 404)	
		{			
			// Como no se encontro, ahora filtra por "Subcategorias"
			$url = CurlController::api()."t_Subcategories?linkTo=url_subcategory&equalTo=".$urlParams[0];
			$method = "GET";
			$fields = array();
			$header = array();
			$urlSubCategories = CurlController::request($url,$method,$fields,$header);
			//echo '<pre>';	print_r("urlCategories = 404"); echo '</pre>';			
			//echo '<pre>';	print_r($urlSubCategories); echo '</pre>';			
			// 			echo '<pre>';	print_r($urlSubCategories->status); echo '</pre>';			
		
			// Filtrar Productos con el parámetro URL
			if ($urlSubCategories->status == 404)
			{			
				$url = CurlController::api()."t_Products?linkTo=url_product&equalTo=".$urlParams[0];
				$method = "GET";
				$fields = array();
				$header = array();
				$urlProduct = CurlController::request($url,$method,$fields,$header);
				//echo '<pre>';	print_r($urlProduct); echo '</pre>';			
				//echo '<pre>';	print_r($urlProduct->status); echo '</pre>';			
			}

		} // 	if ($urlCategories->status == 404)	


	} // if (!empty($urlParams[0]))

?>


<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

	<title>MarketPlace | Home</title>
	
	<base href="Views/">
	<!-- <link rel="shortcut icon" href="img/template/favicon.ico"> -->
	<link rel="icon" type="image/png" href="img/template/favicon.ico">
	
	
	<!-- Etiqueta de HML para agregar una ruta para buscar las carpetas de IMG, JS, CSS-->
	
	<!-- <link rel="icon" href="img/template/favicon.ico"> -->
	

	<!-- <link rel="icon" href="../favicon.ico"> -->

	<!--=====================================
	CSS
	======================================-->
	
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&display=swap" rel="stylesheet">

	<!-- font awesome -->
	<link rel="stylesheet" href="css/plugins/fontawesome.min.css">

	<!-- linear icons -->
	<link rel="stylesheet" href="css/plugins/linearIcons.css">

	<!-- Bootstrap 4 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	
	<!-- Owl Carousel -->
	<link rel="stylesheet" href="css/plugins/owl.carousel.css">

	<!-- Slick -->
	<link rel="stylesheet" href="css/plugins/slick.css">

	<!-- Light Gallery -->
	<link rel="stylesheet" href="css/plugins/lightgallery.min.css">

	<!-- Font Awesome Start -->
	<link rel="stylesheet" href="css/plugins/fontawesome-stars.css">

	<!-- jquery Ui -->
	<link rel="stylesheet" href="css/plugins/jquery-ui.min.css">

	<!-- Select 2 -->
	<link rel="stylesheet" href="css/plugins/select2.min.css">

	<!-- Scroll Up -->
	<link rel="stylesheet" href="css/plugins/scrollUp.css">
    
    <!-- DataTable -->
    <link rel="stylesheet" href="css/plugins/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/plugins/responsive.bootstrap.datatable.min.css">
	
	<!-- estilo principal -->
	<link rel="stylesheet" href="css/style.css">

	<!-- Market Place 4 -->
	<link rel="stylesheet" href="css/market-place-4.css">

	<!--=====================================
	PLUGINS JS
	======================================-->

	<!-- jQuery library -->
	<script src="js/plugins/jquery-1.12.4.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

	<!-- Owl Carousel -->
	<script src="js/plugins/owl.carousel.min.js"></script>

	<!-- Images Loaded -->
	<script src="js/plugins/imagesloaded.pkgd.min.js"></script>

	<!-- Masonry -->
	<script src="js/plugins/masonry.pkgd.min.js"></script>

	<!-- Isotope -->
	<script src="js/plugins/isotope.pkgd.min.js"></script>

	<!-- jQuery Match Height -->
	<script src="js/plugins/jquery.matchHeight-min.js"></script>

	<!-- Slick -->
	<script src="js/plugins/slick.min.js"></script>

	<!-- jQuery Barrating -->
	<script src="js/plugins/jquery.barrating.min.js"></script>

	<!-- Slick Animation -->
	<script src="js/plugins/slick-animation.min.js"></script>

	<!-- Light Gallery -->
	<script src="js/plugins/lightgallery-all.min.js"></script>
    <script src="js/plugins/lg-thumbnail.min.js"></script>
    <script src="js/plugins/lg-fullscreen.min.js"></script>
    <script src="js/plugins/lg-pager.min.js"></script>

	<!-- jQuery UI -->
	<script src="js/plugins/jquery-ui.min.js"></script>

	<!-- Sticky Sidebar -->
	<script src="js/plugins/sticky-sidebar.min.js"></script>

	<!-- Slim Scroll -->
	<script src="js/plugins/jquery.slimscroll.min.js"></script>

	<!-- Select 2 -->
	<script src="js/plugins/select2.full.min.js"></script>

	<!-- Scroll Up -->
	<script src="js/plugins/scrollUP.js"></script>

    <!-- DataTable -->
    <script src="js/plugins/jquery.dataTables.min.js"></script>
    <script src="js/plugins/dataTables.bootstrap4.min.js"></script>
    <script src="js/plugins/dataTables.responsive.min.js"></script>

    <!-- Chart -->
    <script src="js/plugins/Chart.min.js"></script>
	
</head>

<body>

    <!--=====================================
    Preload
    ======================================-->

    <div id="loader-wrapper">
        <img src="img/template/loader.jpg">
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>  

	<!--=====================================
	Header Promotion
	======================================-->
	<?php include "Modules/Top-Banner.php" ?>

    <!--=====================================
	Header
	======================================-->
<?php include"Modules/Header.php" ?>
   
  	<!--=====================================
	Header Mobile
	======================================-->
	<?php include"Modules/Header-Mobile.php" ?>

<!--=====================================
	Pages - Paginas del Home.
	======================================-->     
	<?php
		if (!empty($urlParams[0]))
		{
			//$urlSubCategories
			if (($urlCategories->status == 200) || ($urlSubCategories->status == 200))
			{
				include "Pages/Products/Products.php";
			}
			else if ($urlProduct->status == 200)
			{
				include "Pages/Product/Product.php";
			}
			else
			{
				include "Pages/404/404.php";
			}
		}
		else // if (!empty($urlParams[0]))
		{
			include "Pages/Home/Home.php"; 
		} //if (!empty($urlParams[0]))

	?>

	<!--=====================================
	Newsletter
	======================================-->     
	<?php include "Modules/Newsletter.php"; ?>
    
  <!--=====================================
	Footer
	======================================-->  
	<?php include "Modules/Footer.php"; ?>

    <!--=====================================
    PopUp
    ======================================-->

   <!--
		<div class="ps-site-overlay"></div>

    <div class="ps-popup" id="subscribe" data-time="500">
        <div class="ps-popup__content bg--cover" data-background="img/bg/subscribe.jpg" style="background: url(img/bg/subscribe.jpg);"><a class="ps-popup__close" href="#"><i class="icon-cross"></i></a>
            <form class="ps-form--subscribe-popup" action="index.html" method="get">
                <div class="ps-form__content">
                    <h4>Get <strong>25%</strong> Discount</h4>
                    <p>Subscribe to the Martfury mailing list <br> to receive updates on new arrivals, special offers <br> and our promotions.</p>
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="Email Address" required="">
                            <button class="ps-btn">Subscribe</button>
                        </div>
                        <div class="ps-checkbox">
                            <input class="form-control" type="checkbox" id="not-show" name="not-show">
                            <label for="not-show">Don't show this popup again</label>
                        </div>
                </div>
            </form>
        </div>
    </div>
-->

	<!--=====================================
	JS PERSONALIZADO
	======================================-->

	<script src="js/main.js"></script>
	
</body>
</html>