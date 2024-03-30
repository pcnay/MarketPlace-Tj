<?php
	// https://www.miportalweb.org/curso-web/MarketPlace/t_Products?linkTo=url_category&equalTo=id_product

	// Obtiene la ruta donde esta ubicado el Proyecto : https://www.miportalweb.org/curso-web/MarketPlace

	$randomId = rand(1,$total);
	//$randomId = 4;

	// $url = CurlController::api()."t_Products?linkTo=id_product&equalTo=".$randomId;
	// Tablas relacionadas. 
	$url = CurlController::api()."relations?rel=t_Products,t_Categories&type=product,category&linkTo=id_product&equalTo=".$randomId;

	$method = "GET";
	$fields = array();
	$header = array();

	//$topBanner = CurlController::request($url,$method,$fields,$header)->results;
	// Extraer solo un campo:
	//$topBanner = CurlController::request($url,$method,$fields,$header)->results[0]->top_banner_product;

	// Convertiendolo a un objecto de PHP
	// Accesando a un solo elemento.
	// Se agrega el parametro "true" para que sea un arreglo.
	// $topBanner["H3 tag"] = Para obtener el valor de este arreglo

	//$topBanner = CurlController::request($url,$method,$fields,$header)->results[0]->top_banner_product;

	//$topBanner = CurlController::request($url,$method,$fields,$header)->results[0]->top_banner_product;

	
	//$topBanner2 = json_decode('{"H3 tag":"20%","P1 tag":"Discount", "H4 tag":"For Books Of March", "P2 tag":"Enter Promotion", "Span tag":"Sale2019","Button tag":"Shop now","IMG tag":"header-promotion-1.jpg"}',True);

	//$topBanner2 = json_decode('{"A":"20%","B":"Discount", "C":"For Books Of March", "D":"Enter Promotion", "E":"Sale2019","F":"Shop now","G":"header-promotion-1.jpg"}',True);
	
	
	//$topBanner2 = json_decode{"H3 tag":"20%","P1 tag":"Discount", "H4 tag":"For Books Of March", "P2 tag":"Enter Promotion", "Span tag":"Sale2019","Button tag":"Shop now","IMG tag":"header-promotion-1.jpg"}"

	//$topBanner2 = json_decode(CurlController::request($url,$method,$fields,$header)->results[0]->top_banner_product,True,4);
	//$obj = CurlController::request($url,$method,$fields,$header)->results[0]->top_banner_product;
	//$topBanner2 = json_decode(CurlController::request($url,$method,$fields,$header)->results[0]->top_banner_product);

	//$topBanner2 = json_decode($obj,True,512,JSON_INVALID_UTF8_IGNORE);

	$randomProduct = CurlController::request($url,$method,$fields,$header)->results[0];
	$topBanner = json_decode($randomProduct->top_banner_product,True,4);

 
	//echo '<pre>';
		//print_r($topBanner);
		//print_r($url);
		//print_r($randomProduct);
		//$cadena ='img/products/'.$randomProduct->url_category.'/top/'.$topBanner['IMG tag']; 
		//print_r($cadena);

		//img/products/jewelry-watches/top/Enter Promotion

		//print_r(json_last_error());
		//var_dump($topBanner["H3 tag"]);
	//echo '</pre>';	
	
?>


<div class="ps-block--promotion-header bg--cover"  style="background: url(img/products/<?php echo $randomProduct->url_category; ?>/top/<?php echo $topBanner["IMG tag"]; ?>);">
        <div class="container">
            <div class="ps-block__left">
                <h3><?php echo $topBanner["H3 tag"]; ?></h3>
                <figure>
                    <p><?php echo $topBanner["P1 tag"]; ?></p>
                    <h4><?php echo $topBanner["H4 tag"]; ?></h4>
                </figure>
            </div>
            <div class="ps-block__center">
                <p><?php echo $topBanner["P2 tag"]; ?><span><?php echo $topBanner["Span tag"]; ?></span></p>
            </div><a class="ps-btn ps-btn--sm" href="<?php echo $path.$randomProduct->url_product; ?>"><?php echo $topBanner["Button tag"]; ?></a>
        </div>
    </div>
