<?php


	//$url = "https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories&type=product,category&orderBy=id_product&orderMode=ASC&startAt=0&endAt=5";
	// $url = "https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories,t_Subcategories&type=product,category,subcategory&linkTo=id_category_product&equalTo=1&orderBy=stock_product&orderMode=DESC";

	$url = "https://www.miportalweb.org/curso-web/MarketPlace/relations?rel=t_Products,t_Categories&type=product,category&orderBy=id_product&orderModeASC&startAt=0&endAt=5";
	$method = "GET";
	$fields = array();
	$header = array();

	$productsHSlider = CurlController::request($url,$method,$fields,$header)->results;
	echo '<pre>'; print_r($productsHSlider); echo'</pre>';


?>


<!--=====================================
	Home Banner
	======================================-->  

<div class="ps-home-banner">
	<div class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn" data-owl-animate-out="fadeOut">

	<div class="ps-banner--market-4" data-background="img/slider/horizontal/1.jpg">
		<img src="img/slider/horizontal/1.jpg" alt="">
			<div class="ps-banner__content">
					<h4>Limit Edition</h4>
					<h3>HAPPY SUMMER <br/> 
						COMBO SUPER COOL <br/> 
						<p>UP TO <strong> 40%</strong></p>
					</h3>
					<a class="ps-btn" href="#">Shop Now</a>
			</div>
	</div>

		<div class="ps-banner--market-4" data-background="img/slider/horizontal/2.jpg">
			<img src="img/slider/horizontal/2.jpg" alt="">
				<div class="ps-banner__content">
						<h4>Version 2018</h4>
						<h3>EXPERIENCE FEEL <br/> 
							GREATEST WITH VITURAL <br/> 
							<p>REALITY JUST <strong> $599</strong></p>
						</h3>
						<a class="ps-btn" href="#">Shop Now</a>
				</div>
		</div>

		<div class="ps-banner--market-4" data-background="img/slider/horizontal/3.jpg">
			<img src="img/slider/horizontal/3.jpg" alt="">
				<div class="ps-banner__content">
						<h4>Mega Sale Nov 2019</h4>
						<h3>DOUBLE COMBO WITH <br/> 
							THE BODY SHOP <br/> 
							<p>Sale up to <strong> 50%</strong></p>
						</h3>
						<a class="ps-btn" href="#">Shop Now</a>
				</div>
		</div>

		<div class="ps-banner--market-4" data-background="img/slider/horizontal/4.jpg">
			<img src="img/slider/horizontal/4.jpg" alt="">
				<div class="ps-banner__content">
						<h4>Mega Sale Nov 2017</h4>
						<h3>IKEA MINIMALIST <br/> 
							OTOMAN <br/>
							<p>Discount <strong> 50% OFF</strong></p>
						</h3>
						<a class="ps-btn" href="#">Shop Now</a>
				</div>
		</div>
		
	</div>

</div><!-- End Home Banner-->
