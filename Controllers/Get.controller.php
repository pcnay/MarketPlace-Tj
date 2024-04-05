<?php
	class GetController
	{
		// =============================================================
		// PETICIONES GET "SIN" FILTRO
		// =============================================================

		// Metodo NO static = Se ejecuta inmediatamente, por lo que no se necesita Almacenar
		public function getData($arreglo_parametros)
		{				
						
			//echo "<pre>";print_r($arreglo_parametros);echo"</pre>";
			//exit;

			$return = new GetController();
			$return->fncResponse('GetData',$arreglo_parametros);
		} // public function getData($tabla)

		// PETICIONES GET "CON"FILTRO.
		public function getDataOrder($arreglo_parametros)
		{				
			$return = new GetController();
			$return->fncResponse('GetDataOrder',$arreglo_parametros);
		} // public function getDataOrder($tabla)

		public function getOrderData($arreglo_parametros)
		{				

			//echo "<pre>";print_r($arreglo_parametros);echo"</pre>";
			//exit;

			$return = new GetController();
			$return->fncResponse('GetOrderData',$arreglo_parametros);
		} // public function getLimiteData($tabla)

		public function getLimiteData($arreglo_parametros)
		{				

			//echo "<pre>";print_r($arreglo_parametros);echo"</pre>";
			//exit;

			$return = new GetController();
			$return->fncResponse('GetLimiteData',$arreglo_parametros);
		} // public function getLimiteData($tabla)

		// Metodo NO static = Se ejecuta inmediatamente, por lo que no se necesita Almacenar
		// Peticiones GET CON Filtro.
		public function getFilterData($arreglo_parametros)
		{				
			$return = new GetController();
			$return->fncResponse('GetFilterData',$arreglo_parametros);
		} // public function getData($tabla)

		// Metodo NO static = Se ejecuta inmediatamente, por lo que no se necesita Almacenar
		public function getFilterDataOrder($arreglo_parametros)
		{				
			$return = new GetController();
			$return->fncResponse('GetFilterDataOrder',$arreglo_parametros);
		} // public function getData($tabla)


	// Metodo NO static = Se ejecuta inmediatamente, por lo que no se necesita Almacenar

	// ==================================================
	// Peticiones GET con tablas relacionadas SIN Filtro
	// ==================================================
	public function getRelData($arreglo_parametros)
	{	
		$return = new GetController();
		$return->fncResponse('GetRelData',$arreglo_parametros);
	} // public function getRelData($tabla)

	// ==================================================
	// Peticiones GET con tablas relacionadas CON Filtro
	// ==================================================	
	public function getRelFilterData($arreglo_parametros)
	{
		$return = new GetController();
		$return->fncResponse('GetRelFilterData',$arreglo_parametros);
	}

	// ==================================================
	// Peticiones GET para buscar con 1 Tabla
	// ==================================================
	
	public function getSearchData($arreglo_parametros)
	{		
		$return = new GetController();
		$return->fncResponse('GetSearchData',$arreglo_parametros);
	}


	// Funcion para obtener respuesta de la peticion GET que se realiza.
	public function fncResponse($nombre_funcion,$arreglo_parametros)
	{
		$Table_found = new GetController();

		if ($nombre_funcion == 'GetData')
		{
			// Determinar si la tabla existe.
			//$Table_found = new GetController();
			//echo '<pre>';print_r($arreglo_parametros['tabla']);echo'</pre>';
			//exit;
			
			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{
				// Obtener la informacion desde la base de datos.
				$response = GetModel::getData($arreglo_parametros);

				$json = array(
					'status' => 200,
					'total' => count($response),
					'results' => $response
				);			
			}
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetData'
				);
			} // if ($Table_found->found_Table($table) == 'S')

			echo json_encode($json,http_response_code($json["status"]));		
			return;

		} // if ($nombre_funcion == 'GetData')


		if ($nombre_funcion == 'GetDataOrder')
		{
			// Determinar si la tabla existe.
			//$Table_found = new GetController();
			//echo '<pre>';print_r($arreglo_parametros['tabla']);echo'</pre>';
			//exit;
			
			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{						
				if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				{
					// Obtener la informacion desde la base de datos.
					$response = GetModel::getData($arreglo_parametros);

					$json = array(
						'status' => 200,
						'total' => count($response),
						'results' => $response
					);			

				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
					$json = array(
						'status' => 404,					
						'results' => "Not Field Found",
						'method' => 'GetData'
					);
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')		
					
			} // if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetData'
				);
			} // if ($Table_found->found_Table($table) == 'S')

			echo json_encode($json,http_response_code($json["status"]));		
			return;

		} // if ($nombre_funcion == 'GetDataOrder')

		if ($nombre_funcion == 'GetOrderData')
		{
			// Determinar si la tabla existe.
			//$Table_found = new GetController();
			//echo '<pre>';print_r($arreglo_parametros['tabla']);echo'</pre>';
			//exit;
			
			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{						
				if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				{
					// Obtener la informacion desde la base de datos.
					$response = GetModel::getData($arreglo_parametros);

					$json = array(
						'status' => 200,
						'total' => count($response),
						'results' => $response
					);			

				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
					$json = array(
						'status' => 404,					
						'results' => "Not Field Found",
						'method' => 'GetLimiteData'
					);
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')		
					
			} // if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetData'
				);
			} // if ($Table_found->found_Table($table) == 'S')

			echo json_encode($json,http_response_code($json["status"]));		
			return;

		} // if ($nombre_funcion == 'GetLimiteData')
		
		if ($nombre_funcion == 'GetLimiteData')
		{
			// Determinar si la tabla existe.
			//$Table_found = new GetController();
			//echo '<pre>';print_r($arreglo_parametros['tabla']);echo'</pre>';
			//exit;
			
			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{						
				if ($Table_found->Valor_Positivo($arreglo_parametros) == 'S')
				{
					// Obtener la informacion desde la base de datos.
					$response = GetModel::getData($arreglo_parametros);

					$json = array(
						'status' => 200,
						'total' => count($response),
						'results' => $response
					);			

				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
					$json = array(
						'status' => 404,					
						'results' => "Not Field Found",
						'method' => 'GetLimiteData'
					);
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')		
					
			} // if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetLimiteData'
				);
			} // if ($Table_found->found_Table($table) == 'S')

			echo json_encode($json,http_response_code($json["status"]));		
			return;

		} // if ($nombre_funcion == 'GetLimiteData')

		if  ($nombre_funcion == 'GetFilterData')
		{
			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{					
				if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				{
					// Verificando el nombre del Filtro : "linkTo","equalTo"
					// Obtener la informacion desde la base de datos.						
					$response = GetModel::getFilterData($arreglo_parametros);	

					if (!empty($response))						
					{
						$json = array(
							'status' => 200,
							'total' => count($response),
							'results' => $response
						);				
						echo json_encode($json,http_response_code($json["status"]));		
						return;										
					}
					else
					{
						$json = array(
							'status' => 404,					
							'results' => "Not Datas Found"								
						);
						echo json_encode($json,http_response_code($json["status"]));		
						return;								

					} //	if (!empty($response))	

				}	// if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
						$json = array(
							'status' => 404,					
							'results' => "Not Field Found",
							'method' => 'GetFilterData'
						);
	
						echo json_encode($json,http_response_code($json["status"]));		
						return;	
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')

			} //if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetFilterData'
				);

				echo json_encode($json,http_response_code($json["status"]));		
				return;
			}	
		} //if  ($nombre_funcion == 'GetFilterData')
		if  ($nombre_funcion == 'GetFilterDataOrder')
		{
			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{					
				if ((($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S') && (($Table_found->found_Field_Table($arreglo_parametros['orderBy'])) == 'S' && ($Table_found->Valor_Positivo($arreglo_parametros) == 'S')))
				{					
					// Verificando el nombre del Filtro : "linkTo","equalTo"
					// Obtener la informacion desde la base de datos.						
					$response = GetModel::getFilterData($arreglo_parametros);	

					if (!empty($response))						
					{
						$json = array(
							'status' => 200,
							'total' => count($response),
							'results' => $response
						);				
						echo json_encode($json,http_response_code($json["status"]));		
						return;										
					}
					else
					{
						$json = array(
							'status' => 404,					
							'results' => "Not Datas Found"								
						);
						echo json_encode($json,http_response_code($json["status"]));		
						return;								

					} //	if (!empty($response))	

				}	// if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
						$json = array(
							'status' => 404,					
							'results' => "Not Field Found or numbers incorrect ",
							'method' => 'GetFilterData'
						);
	
						echo json_encode($json,http_response_code($json["status"]));		
						return;	
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')

			} //if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetFilterData'
				);

				echo json_encode($json,http_response_code($json["status"]));		
				return;
			}	
		} //if  ($nombre_funcion == 'GetFilterDataOrder')

		if  ($nombre_funcion == 'GetSearchData')
		{
			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{					
				if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S' || ($Table_found->found_Field_Table($arreglo_parametros['orderBy'])) == 'S')
				{
					
					// Obtener la informacion desde la base de datos.						
					$response = GetModel::getSearchData($arreglo_parametros);	

					if (!empty($response))						
					{
						$json = array(
							'status' => 200,
							'total' => count($response),
							'results' => $response
						);				
						echo json_encode($json,http_response_code($json["status"]));		
						return;										
					}
					else
					{
						$json = array(
							'status' => 404,					
							'results' => "Not Datas Found"								
						);
						echo json_encode($json,http_response_code($json["status"]));		
						return;								

					} //	if (!empty($response))	

				}	// if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
						$json = array(
							'status' => 404,					
							'results' => "Not Field Found",
							'method' => 'GetSearchData'
						);
	
						echo json_encode($json,http_response_code($json["status"]));		
						return;	
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')

			} //if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetSearchData'
				);

				echo json_encode($json,http_response_code($json["status"]));		
				return;
			}	
		} //if  ($nombre_funcion == 'GetSearchData')

		if ($nombre_funcion == 'GetRelData')
		{
			//echo'<pre>';print_r($arreglo_parametros);echo'</pre>';					
			//exit;
			$arreglo_campos_original['campo_tabla'] = $arreglo_parametros['campo_tabla'];

			$separar_campos = explode(",",$arreglo_parametros['campo_tabla']);

			//echo'<pre>';print_r($separar_campos);echo'</pre>';					
			for ($k=0;$k<count($separar_campos);$k++)
			{
				$separar_campos[$k] = "id_".$separar_campos[$k];
			}
			$arreglo_parametros['campo_tabla'] = null;
			for ($k=0;$k<count($separar_campos);$k++)
			{
				$arreglo_parametros['campo_tabla'] = $separar_campos[$k].','.$arreglo_parametros['campo_tabla'];
			}

			// Eliminando el último caracter ","
			$arreglo_parametros["campo_tabla"] = substr($arreglo_parametros["campo_tabla"],0,-1);
			
			//echo'<pre>';print_r($arreglo_parametros['campo_tabla']);echo'</pre>';			
			//exit;

			/*
			for ($k=0;$k<count($arreglo_parametros);$k++)
			{
				echo'<pre>';print_r($arreglo_parametros['campos']);echo'</pre>';					
				//$arreglo_parametros[$k]['campo_tabla'] = "id_".$arreglo_parametros[$k]['campo_tabla'];
			}
			exit;
			*/
			//echo'<pre>';print_r($arreglo_parametros['tabla']);echo'</pre>';					
			//exit;


			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{					
				if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				{
					// Verificando el nombre del Filtro : "linkTo","equalTo"
					// Obtener la informacion desde la base de datos.						
					$arreglo_parametros['campo_tabla'] = $arreglo_campos_original['campo_tabla'];

					$response = GetModel::getRelData($arreglo_parametros);	

					if (!empty($response))						
					{
						$json = array(
							'status' => 200,
							'total' => count($response),
							'results' => $response
						);				
						echo json_encode($json,http_response_code($json["status"]));		
						return;										
					}
					else
					{
						$json = array(
							'status' => 404,					
							'results' => "Not Datas Found"								
						);
						echo json_encode($json,http_response_code($json["status"]));		
						return;								

					} //	if (!empty($response))	

				}	// if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
						$json = array(
							'status' => 404,					
							'results' => "Not Field Found",
							'method' => 'GetRelData'
						);
	
						echo json_encode($json,http_response_code($json["status"]));		
						return;	
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')

			} //if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetRelData'
				);

				echo json_encode($json,http_response_code($json["status"]));		
				return;
			}	

		} // if ($nombre_funcion == 'GetRelData')

		if ($nombre_funcion == 'GetRelFilterData')
		{
			//echo'<pre>';print_r($arreglo_parametros);echo'</pre>';					
			//exit;
			$arreglo_campos_original['campo_tabla'] = $arreglo_parametros['campo_tabla'];

			$separar_campos = explode(",",$arreglo_parametros['campo_tabla']);

			//echo'<pre>';print_r($separar_campos);echo'</pre>';					
			for ($k=0;$k<count($separar_campos);$k++)
			{
				$separar_campos[$k] = "id_".$separar_campos[$k];
			}

			$arreglo_parametros['campo_tabla'] = null;
			for ($k=0;$k<count($separar_campos);$k++)
			{
				$arreglo_parametros['campo_tabla'] = $separar_campos[$k].','.$arreglo_parametros['campo_tabla'];
			}

			// Eliminando el último caracter ","
			$arreglo_parametros["campo_tabla"] = substr($arreglo_parametros["campo_tabla"],0,-1);
			$arreglo_parametros["campo_tabla"] = $arreglo_parametros["campo_tabla"].','.$arreglo_parametros["campo_teclado"];

			//echo '<pre>';print_r($arreglo_parametros['campo_tabla']);echo'</pre>';
			//echo '<pre>';print_r("GetRelFilterData");echo'</pre>';
			//exit;

			if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			{
				//$Campos = $arreglo_parametros['campo_tabla'].','.$arreglo_parametros['linkTo'].','.$arreglo_parametros['orderBy'];

				if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				//if (($Table_found->found_Field_Table($Campos)) == 'S')
				{
					// Verificando el nombre del Filtro : "linkTo","equalTo"
					// Obtener la informacion desde la base de datos.			
					if (($Table_found->found_Field_Table($arreglo_parametros['linkTo'])) == 'S')
					{
						$arreglo_parametros['campo_tabla'] = $arreglo_campos_original['campo_tabla'];

						$response = GetModel::getRelFilterData($arreglo_parametros);	

						if (!empty($response))						
						{
							$json = array(
								'status' => 200,
								'total' => count($response),
								'results' => $response
							);				
							echo json_encode($json,http_response_code($json["status"]));		
							return;										
						}
						else
						{
							$json = array(
								'status' => 404,					
								'results' => "Not Datas Found"								
							);
							echo json_encode($json,http_response_code($json["status"]));		
							return;								

						} //	if (!empty($response))	

					} // if (($Table_found->found_Field_Table($arreglo_parametros['linkTo'])) == 'S')
					else
					{
						$json = array(
							'status' => 404,					
							'results' => "LinkTo Field Not Found",
							'method' => 'GetRelFilterData'
						);
	
						echo json_encode($json,http_response_code($json["status"]));		
						return;	

					}// if (($Table_found->found_Field_Table($arreglo_parametros['linkTo'])) == 'S')

				}	// if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')
				else
				{
						$json = array(
							'status' => 404,					
							'results' => "Table Field Not Found",
							'method' => 'GetRelFilterData'
						);
	
						echo json_encode($json,http_response_code($json["status"]));		
						return;	
				} // if (($Table_found->found_Field_Table($arreglo_parametros['campo_tabla'])) == 'S')

			} //if ($Table_found->found_Table($arreglo_parametros['tabla']) == 'S')
			else
			{
				$json = array(
					'status' => 404,					
					'results' => "Not Table Found",
					'method' => 'GetRelFilterData'
				);

				echo json_encode($json,http_response_code($json["status"]));		
				return;
			}	

		} // if ($nombre_funcion == 'GetRelFilterData)

	} // public function fncResponse($nombre_funcion,$table)


		// Verifica si la tabla existe
		static public function found_Table($table)
		{		
			$tablas = array('t_Categories','t_Disputes','t_Messages','t_Orders','t_Products','t_Sales','t_Stores','t_Subcategories','t_Users');

			$ArrayTablas = explode(",",$table);	// Para separar si viene mas de una tabla.

			$found = 'N';			
			for ($k=0;$k<=count($tablas);$k++)
			{				
				for ($l=0;$l<count($ArrayTablas);$l++)
				{
					if (in_array($ArrayTablas[$l],$tablas))
					{
						$found = 'S';
						//break;			
					}
					else
					{
						$found = 'N';
						break;
					} // if (in_array($ArrayTablas[$l],$tablas))

				} //for ($l=0;$l<num_tablas_usadas;$l++)

				if ($found == 'S' || $found == 'N')
				{
					break;
				}

			} //for ($k=0;$k<=count($tablas);$k++)

			return $found;

		} // static public function found_Table($table)

		// Verifica si el Campo de la Tabla, los nombres de los filtros si existe
		static public function found_Field_Table($Field)
		{				
			$campos_tablas = array('id_user','rol_user','picture_user','displayname_user','username_user','password_user','email_user','country_user','city_user','phone_user','address_user','token_user','method_user','wishlist_user','date_created_user','date_updated_user','token_exp_user','id_category','name_category','title_list_category','url_category','image_category','icon_category','views_category','date_created_category','date_updated_category','id_dispute','id_order_dispute','stage_dispute','id_user_dispute','id_store_dispute','content_dispute','answer_dispute','date_created_dipute','date_update_dipute','id_message','id_product_message','id_user_message','id_store_message','content_message','answer_message','date_created_message','date_updated_message','id_order','id_product_order','id_store_order','id_user_order','details_order','quantity_order','price_order','email_order','country_order','city_order','phone_order','address_order','process_order','status_order','date_created_order','date_updated_order','id_product','feedback_product','state_product','id_store_product','id_category_product','id_subcategory_product','title_list_product','name_product','url_product','image_product','price_product','shipping_product','stock_product','delivery_time_product','offer_product','description_product','sumary_product','details_product','specifications_product','galley_product','video_product','top_banner_product','default_banner_product','horizontal_slider_product','vertical_slider_product','reviews_product','tags_product','sales_product','views_product','date_created_product','date_updated_product','id_sale','id_order_sale','unit_price_sale','commision_sale','payment_method_sale','id_payment_sale','status_sale','date_created_sale','date_updated_sale','id_store','id_user_store','name_store','url_store','logo_store','cover_store','about_store','abstract_store','email_store','country_store','city_store','address_store','phone_store','socialnetwork_store','products_store','date_created_store','date_updated_store','id_subcategory','id_category_subcategory','title_list_subcategory','name_subcategory','url_subcategory','image_subcategory','views_subcategory','date_created_subcategory','date_updated_subcategory','linkTo','equalTo');

			$ArrayFields = explode(",",$Field);	// Para separar si viene mas de una tabla.
			//echo '<pre>';print_r($ArrayFields); echo'</pre>';
			//return false;
			//exit;

			// <?php foreach ($menuCategories as $key => $value): 
			// $value->url_category 
			$found = 'N';			
			$Buscar = 'S';
			if ($Buscar == 'S')
			{		
				for ($k=0;$k<=count($campos_tablas);$k++)
				{				
					for ($l=0;$l<count($ArrayFields);$l++)
					{
						if (in_array($ArrayFields[$l],$campos_tablas))
						{
							$found = 'S';
							//break;			
						}
						else
						{
							$found = 'N';
							break;
						} // if (in_array($ArrayTablas[$l],$tablas))

					} //for ($l=0;$l<num_tablas_usadas;$l++)

					if ($found == 'S' || $found == 'N')
					{
						break;
					}

				} //for ($k=0;$k<=count($campos_tablas);$k++)		

			}	// if ($Buscar == 'S')

			return $found;

		} // static public function found_Field_Table($table,$Field)

		static public function Valor_Positivo($arreglo_parametros)
		{			
			if (($arreglo_parametros['startAt'] >= 0) && ($arreglo_parametros['endAt'] > 0))
			{
				$Positivo = 'S';
			}
			else
			{
				$Positivo = 'N';
			}
			return $Positivo;
		}

	} // class GetController
?>
