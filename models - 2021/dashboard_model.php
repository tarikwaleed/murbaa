<?php
	/**
	* dashboard MODEL, 
	*/
	class dashboard_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function land_type
		* get land_type data
		*/
		public function land_type()
		{
			return $this->db->select("SELECT ty_id AS ID, ty_name AS NAME
									,ty_name_en AS NAME_EN, ty_for AS TY_FOR
									,ty_builed AS BUILD 
										FROM ".DB_PREFEX."land_type
										WHERE 1 = 1"
										,array());
		}
		
		/**
		* function city_list
		* get city data
		*/
		public function city_list()
		{
			$x = $this->db->select("SELECT c_id, c_name,c_name_EN  
										FROM ".DB_PREFEX."city
										WHERE 1 = 1"
										,array());
			$ret = array();
			foreach($x as $val)
			{
				$x = array(
							'ID'		=>$val['c_id']
							,'NAME'		=>$val['c_name']
							,'NAME_EN'	=>$val['c_name_EN']
							,'NEIGHBOR'	=>$this->neighborhood($val['c_id'])
							);
				array_push($ret,$x);
			}
			return $ret;
		}
		
		/**
		* function neighborhood
		* get neighborhood data
		*/
		public function neighborhood($city_id = 0)
		{
			$wh = "";
			$wh_array = array();
			if(!empty($city_id))
			{
				$form	= new form();
				if($form->single_valid($city_id,'Integer'))
				{
					$wh = "nei_city = :ID AND ";
					$wh_array[':ID'] = $city_id;
				}
			}
			
			$x = $this->db->select("SELECT nei_id, nei_name,nei_name_EN,nei_city ,nei_letter
										FROM ".DB_PREFEX."neighborhood
										WHERE $wh 1 = 1"
										,$wh_array);
			$ret = array();
			foreach($x as $val)
			{
				$ret[$val['nei_id']] = array('ID'=>$val['nei_id']
											,'NAME'=>$val['nei_name']
											,'NAME_EN'=>$val['nei_name_EN']
											,'LETTER'=>$val['nei_letter']
											,'CITY'=>$val['nei_city']
											);
			}
			return array_values($ret);
		}
		
		/**
		* function search
		* search lands
		* AJAX
		*/
		public function search()
		{
			$form	= new form();
			
			$form	->post('chat',false,true)
					
					->post('id',false,true)
					->valid('numeric')
					
					->post('city',false,true)
					->valid('numeric')
					
					->post('neighborhood',false,true)
					->valid('numeric')
					
					->post('block',false,true)
					->valid('numeric')
					
					->post('types',false,true)
					->valid('numeric')
					
					->post('adv',false,true)
					->valid('In_Array',array_keys(lib::$adv_type))
					
					->post('land_for',false,true)
					->valid('In_Array',array_keys(lib::$land_for))
					
					->post('rooms',false,true)
					->valid('numeric')
					->post('rooms_min',false,true)
					->valid('numeric')
					->post('rooms_max',false,true)
					->valid('numeric')
					
					->post('baths',false,true)
					->valid('numeric')
					->post('baths_max',false,true)
					->valid('numeric')
					->post('baths_min',false,true)
					->valid('numeric')
					
					->post('size',false,true)
					->valid('numeric')
					->post('size_max',false,true)
					->valid('numeric')
					->post('size_min',false,true)
					->valid('numeric')
					
					->post('floor',false,true)
					->valid('numeric')
					->post('floor_max',false,true)
					->valid('numeric')
					->post('floor_min',false,true)
					->valid('numeric')
					
					->post('interface',false,true)
					->valid('numeric')
					
					->post('corner',false,true)
					->valid('numeric')
					
					->post('price',false,true)
					->valid('numeric')
					->post('price_max',false,true)
					->valid('numeric')
					->post('price_min',false,true)
					->valid('numeric')
					
					->post('road_max',false,true)
					->valid('numeric')
					->post('road_min',false,true)
					->valid('numeric')
					
					->post('limit',false,true)
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$txt = "";
			$limit = "";
			$arr = array();
			
			if(!empty($fdata['id']))
			{
				$txt .= "l_id = :ID AND ";
				$arr[':ID'] = $fdata['id'];
			}else
			{
				if(!empty($fdata['city']))
				{
					$txt .= "c_id = :CITY AND ";
					$arr[':CITY'] = $fdata['city'];
				}
				//_________________________NEIGHBORHOOD
				if(!empty($fdata['neighborhood']) && !empty($fdata['neighbor_letter']))
				{
					$txt .= "(l_neighborhood = :NEIGHBOR OR nei_letter = :LETT ) AND ";
					$arr[':NEIGHBOR'] = $fdata['neighborhood'];
					$arr[':LETT'] = $fdata['neighbor_letter'];
				}elseif(!empty($fdata['neighborhood']))
				{
					$txt .= "l_neighborhood = :NEIGHBOR AND ";
					$arr[':NEIGHBOR'] = $fdata['neighborhood'];
				}elseif(!empty($fdata['neighbor_letter']))
				{
					$txt .= "nei_letter = :LETT AND ";
					$arr[':LETT'] = $fdata['neighbor_letter'];
				}
				
				if(!empty($fdata['block']))
				{
					$txt .= "l_block = :BLOCK AND ";
					$arr[':BLOCK'] = $fdata['block'];
				}
				if(!empty($fdata['types']))
				{
					$txt .= "l_type LIKE :TYPE AND ";
					$arr[':TYPE'] = $fdata['types'];
				}
				if(!empty($fdata['adv']))
				{
					$txt .= "l_adv LIKE :ADV AND ";
					$arr[':ADV'] = $fdata['adv'];
				}
				if(!empty($fdata['land_for']))
				{
					$txt .= "l_for LIKE :LFOR AND ";
					$arr[':LFOR'] = $fdata['land_for'];
				}
				//_________________________ROOMS
				if(!empty($fdata['rooms']))
				{
					$txt .= "l_rooms = :ROOMS AND ";
					$arr[':ROOMS'] = $fdata['rooms'];
				}
				if(!empty($fdata['rooms_min']))
				{
					$txt .= "l_rooms >= :ROOMS_MIN AND ";
					$arr[':ROOMS_MIN'] = $fdata['rooms'];
				}
				if(!empty($fdata['rooms_max']))
				{
					$txt .= "l_rooms <= :ROOMS_MAX AND ";
					$arr[':ROOMS_MAX'] = $fdata['rooms'];
				}
				//_________________________BATHS
				if(!empty($fdata['baths']))
				{
					$txt .= "l_baths = :BATHS AND ";
					$arr[':BATHS'] = $fdata['baths'];
				}
				if(!empty($fdata['baths_min']))
				{
					$txt .= "l_baths >= :BATHS_MIN AND ";
					$arr[':BATHS_MIN'] = $fdata['baths_min'];
				}
				if(!empty($fdata['baths_max']))
				{
					$txt .= "l_baths <= :BATHS_MAX AND ";
					$arr[':BATHS_MAX'] = $fdata['baths_max'];
				}
				//_________________________SIZE
				if(!empty($fdata['size']))
				{
					$txt .= "l_size = :SIZE AND ";
					$arr[':SIZE'] = $fdata['size'];
				}
				if(!empty($fdata['size_min']))
				{
					$txt .= "l_size >= :SIZE_MIN AND ";
					$arr[':SIZE_MIN'] = $fdata['size_min'];
				}
				if(!empty($fdata['size_max']))
				{
					$txt .= "l_size <= :SIZE_MAX AND ";
					$arr[':SIZE_MAX'] = $fdata['size_max'];
				}
				//_________________________FLOOR
				if(!empty($fdata['floor']))
				{
					$txt .= "l_floor = :FLOOR AND ";
					$arr[':FLOOR'] = $fdata['floor'];
				}
				if(!empty($fdata['floor_min']))
				{
					$txt .= "l_floor >= :FLOOR_MIN AND ";
					$arr[':FLOOR_MIN'] = $fdata['floor_min'];
				}
				if(!empty($fdata['floor_max']))
				{
					$txt .= "l_floor <= :FLOOR_MAX AND ";
					$arr[':FLOOR_MAX'] = $fdata['floor_max'];
				}
				//_________________________PRICE
				if(!empty($fdata['price']))
				{
					$txt .= "l_price = :PRICE AND ";
					$arr[':PRICE'] = $fdata['price'];
				}
				if(!empty($fdata['price_min']))
				{
					$txt .= "l_price >= :PRICE_MIN AND ";
					$arr[':PRICE_MIN'] = $fdata['price_min'];
				}
				if(!empty($fdata['price_max']))
				{
					$txt .= "l_price <= :PRICE_MAX AND ";
					$arr[':PRICE_MAX'] = $fdata['price_max'];
				}
				//_________________________ROAD
				if(!empty($fdata['road_min']))
				{
					$txt .= "l_road >= :ROAD_MIN AND ";
					$arr[':ROAD_MIN'] = $fdata['road_min'];
				}
				if(!empty($fdata['road_max']))
				{
					$txt .= "l_road <= :ROAD_MAX AND ";
					$arr[':ROAD_MAX'] = $fdata['road_max'];
				}
				//_________________________interface
				if(!empty($fdata["interface"]))
				{
					$txt .= "l_interface IS NOT NULL AND ";
				}
				//_________________________corner
				if(!empty($fdata["corner"]))
				{
					$txt .= "l_corner IS NOT NULL AND ";
				}
			}
			
			if(!empty($fdata["limit"]))
			{
				//$limit = "LIMIT ".$fdata["limit"];
			}
			
			$lands =  $this->db->select("SELECT l_id, l_block, l_loc_lat, l_loc_lng
										, l_img, l_desc, l_size, l_baths ,l_adv
										,l_rooms, l_interface, l_halls, ".DB_PREFEX."land.create_at AS add_date
										,l_cars, l_floor, l_price, l_currency, l_corner, l_for
										,l_active_date, l_bulid_year, l_no, l_road
										,l_tree, l_well, l_mushub
										,l_co_relation, l_co_delegat
										,l_duplex, l_append, l_monster, l_swim, l_kitchen
										,l_basement, l_elevator, l_server_room, l_dr_room
										
										,ty_id, ty_name, ty_name_en, ty_for, ty_builed
										,nei_id, nei_name, nei_name_EN, c_id, c_name, c_name_EN
										,co_id, co_name, co_name_en, co_package, co_phone, co_email, co_img
										,lp_start, lp_end, lp_comment
										,comm_id, comm_accept, comm_co_num, comm_real_no, comm_no
										
										FROM ".DB_PREFEX."land
										JOIN ".DB_PREFEX."neighborhood ON l_neighborhood = nei_id
										JOIN ".DB_PREFEX."city ON nei_city = c_id 
										JOIN ".DB_PREFEX."company ON co_id = l_co AND co_active = 1 
										JOIN ".DB_PREFEX."package ON co_package = pk_id  
										JOIN ".DB_PREFEX."land_type ON l_type = ty_id 
										LEFT JOIN ".DB_PREFEX."land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
										LEFT JOIN ".DB_PREFEX."comm_reg AS COMM
											ON comm_co = co_id 
											AND now() BETWEEN DATE(COMM.create_at) AND comm_exp_date
											AND comm_accept = 1
										WHERE $txt l_status = 1  
											
										GROUP BY l_id
										ORDER BY IF(lp_start IS NOT NULL,1,2) ASC
												,pk_stars DESC
												,l_id DESC
										$limit
									" ,$arr);
			$ret = array();
			
			foreach($lands as $val)
			{
				
				$sing = array();
				$sing["ID"] 			= $val["l_id"];
				$sing["ADV"] 			= $val["l_adv"];
				$sing["ADV_NAME"] 		= lib::$adv_type[$val["l_adv"]];
				$sing["NO"] 			= $val["l_no"];
				$sing["BLOCK"] 			= $val["l_block"];
				if(!empty($val["l_loc_lat"]) && !empty($val["l_loc_lng"]))
				{
					$sing["LOCATION"] 		= "//maps.google.com/maps?q=".$val["l_loc_lat"].",".$val["l_loc_lng"]."&z=16&output=embed";
				}else
				{
					$sing["LOCATION"] 	= "";
				}
				$sing["LOC_LAT"] 		= $val["l_loc_lat"];
				$sing["LOC_LNG"] 		= $val["l_loc_lng"];
				
				$sing["TYPE"] 			= $val["ty_id"];
				$sing["TYPE_NAME"] 		= $val["ty_name"];
				$sing["IS_RES"] 		= ($val["ty_builed"] == 1);
				
				$sing["IMG"] 			= URL."public/IMG/land/".$val["l_id"]."/".$val["l_img"];
				$sing["FOR"] 			= $val["l_for"];
				$sing["FOR_NAME"] 		= lib::$land_for[$val["l_for"]];
				$sing["DESC"] 			= $val["l_desc"];
				$sing["LIM_DESC"] 		= (strlen($val["l_desc"])> 2000)?substr($val["l_desc"],0,2000)."...":$val["l_desc"];
				$sing["SIZE"] 			= number_format($val["l_size"], 2, '.', ',');
				$sing["ROOMS"] 			= $val["l_rooms"];
				$sing["INTERFACE"] 		= $val["l_interface"];
				$sing["HALLS"] 			= $val["l_halls"];
				$sing["BATHS"] 			= $val["l_baths"];
				$sing["CARS"] 			= $val["l_cars"];
				$sing["FLOOR"] 			= $val["l_floor"];
				$sing["PRICE"] 			= number_format($val["l_price"], 2, '.', ',');
				$sing["CURRENCY"] 		= $val["l_currency"];
				$sing["CORNER"] 		= $val["l_corner"];
				$sing["ACT_DATE"] 		= $val["l_active_date"];
				$sing["BULID"] 			= $val["l_bulid_year"];
				$sing["ROAD"] 			= $val["l_road"];
				$sing["DATE"] 			= $val["add_date"];
				
				$sing["DUPLEX"] 		= $val["l_duplex"];
				$sing["APPEND"] 		= $val["l_append"];
				$sing["MONSTER"] 		= $val["l_monster"];
				$sing["SWIM"] 			= $val["l_swim"];
				$sing["KITCHEN"] 		= $val["l_kitchen"];
				$sing["BASEMENT"] 		= $val["l_basement"];
				$sing["ELEVATOR"] 		= $val["l_elevator"];
				$sing["SER_ROOM"] 		= $val["l_server_room"];
				$sing["DR_ROOM"] 		= $val["l_dr_room"];
				
				$sing["TREES"] 			= $val["l_tree"];
				$sing["WELL"] 			= $val["l_well"];
				$sing["MUSHUB"] 		= $val["l_mushub"];
				
				$sing["NEI_ID"] 		= $val["nei_id"];
				$sing["NEI_NAME"] 		= $val["nei_name"];
				$sing["NEI_NAME_EN"]	= $val["nei_name_EN"];
				
				$sing["CIT_ID"] 		= $val["c_id"];
				$sing["CIT_NAME"] 		= $val["c_name"];
				$sing["CIT_NAME_EN"]	= $val["c_name_EN"];
				
				$sing["IS_ADMIN"] 		= (!empty(session::get('user_id')) && empty(session::get('company')));
				$sing["IS_STAFF"] 		= (!empty(session::get('company')));
				$sing["IS_OW"] 			= $val["co_id"] == session::get('company');
				
				$sing["OW_ID"] 			= $val["co_id"];
				$sing["OW_NAME"] 		= $val["co_name"];
				$sing["OW_NAME_EN"] 	= $val["co_name_en"];
				$sing["OW_PHONE"] 		= $val["co_phone"];
				$sing["OW_EMAIL"] 		= $val["co_email"];
				$sing["OW_IMG"] 		= URL."public/IMG/co/".$val["co_img"];
				$sing["OW_LINK"] 		= URL."dashboard/customer/".$val["co_id"];
				$sing["OW_ACCEPT"] 		= $val["comm_accept"];
				$sing["OW_ACCEPT_NO"] 	= $val["comm_id"];
				$sing["OW_ACCEPT_REG"] 	= $val["comm_no"];
				$sing["OW_ACCEPT_REAL"] = $val["comm_real_no"];
				$sing["OW_ACCEPT_NUM"] 	= $val["comm_co_num"];
				$sing["OW_RELATION"] 	= $val["l_co_relation"];
				$sing["OW_DELEGATE"] 	= $val["l_co_delegat"];
				 
				$dir 	= URL_PATH."public/IMG/land/".$val["l_id"]."/";
				$link 	= URL."public/IMG/land/".$val["l_id"]."/";
				
                $files 	= files::get_file_list($dir,$link);
				$sing["OTHER_IMG"] = array();
				$sing["OTHER_VIDEO"] = array();
				
				foreach($files as $f)
				{
					if(strpos($f['FILE_TYPE'],'image')!== false)
					{
						array_push($sing["OTHER_IMG"],$f);
					}elseif(strpos($f['FILE_TYPE'],'video')!== false)
					{
						array_push($sing["OTHER_VIDEO"],$f);
					}
				}
                
				
				if($fdata['chat'])
				{
					$sing['CHAT'] = $this->chatRoom($val["l_id"]);
				}//if chat
				array_push($ret,$sing);
			}
			
			return $ret;
			
		}
		
		//Suggest: get suggestion lands
		public function suggest($land)
		{
			if(empty($land))
			{
				return array();
			}
			
			$lands =  $this->db->select("SELECT l_id, l_block, l_loc_lat, l_loc_lng
										,l_img, l_desc, l_size, l_baths ,l_adv
										,l_rooms, l_interface, l_halls, ".DB_PREFEX."land.create_at AS add_date
										,l_cars, l_floor, l_price, l_currency, l_corner, l_for
										,l_active_date, l_bulid_year, l_no, l_road
										
										,nei_id, nei_name, nei_name_EN, c_id, c_name, c_name_EN
										
										,ty_id, ty_name, ty_name_en, ty_for, ty_builed
										,co_id, co_name, co_name_en, co_package, co_phone, co_email, co_img
										,lp_start, lp_end, lp_comment
										,comm_id,comm_accept
										
										FROM ".DB_PREFEX."land
										JOIN ".DB_PREFEX."neighborhood ON l_neighborhood = nei_id
										JOIN ".DB_PREFEX."city ON nei_city = c_id 
										JOIN ".DB_PREFEX."company ON co_id = l_co AND co_active = 1 
										JOIN ".DB_PREFEX."package ON co_package = pk_id  
										JOIN ".DB_PREFEX."land_type ON l_type = ty_id 
										LEFT JOIN ".DB_PREFEX."land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
										LEFT JOIN ".DB_PREFEX."comm_reg AS COMM
											ON comm_co = co_id 
											AND now() BETWEEN DATE(COMM.create_at) AND comm_exp_date
											AND comm_accept = 1
										WHERE l_id != :ID AND
											l_status = 1 AND 
											l_type = :TYPE AND
											l_for = :FOR AND
											(
												l_neighborhood = :NEI_ID OR
												nei_city = :CIT_ID OR
												1=1
											) 
										GROUP BY l_id
										ORDER BY IF(lp_start IS NOT NULL,1,2) ASC
												,pk_stars DESC
												,l_id DESC
										LIMIT 10
									" ,array(':TYPE'=>$land['TYPE'],':FOR'=>$land['FOR']
											,':NEI_ID'=>$land['NEI_ID'],':CIT_ID'=>$land['CIT_ID']
											,':ID'=>$land['ID']));
			
			$ret = array();
			
			foreach($lands as $val)
			{
				
				$sing = array();
				$sing["ID"] 			= $val["l_id"];
				$sing["ADV"] 			= $val["l_adv"];
				$sing["ADV_NAME"] 		= lib::$adv_type[$val["l_adv"]];
				$sing["NO"] 			= $val["l_no"];
				$sing["BLOCK"] 			= $val["l_block"];
				if(!empty($val["l_loc_lat"]) && !empty($val["l_loc_lng"]))
				{
					$sing["LOCATION"] 		= "//maps.google.com/maps?q=".$val["l_loc_lat"].",".$val["l_loc_lng"]."&z=16&output=embed";
				}else
				{
					$sing["LOCATION"] 	= "";
				}
				$sing["LOC_LAT"] 		= $val["l_loc_lat"];
				$sing["LOC_LNG"] 		= $val["l_loc_lng"];
				
				$sing["TYPE"] 			= $val["ty_id"];
				$sing["TYPE_NAME"] 		= $val["ty_name"];
				$sing["IS_RES"] 		= ($val["ty_builed"] == 1);
				
				$sing["IMG"] 			= URL."public/IMG/land/".$val["l_id"]."/".$val["l_img"];
				$sing["FOR"] 			= $val["l_for"];
				$sing["FOR_NAME"] 		= lib::$land_for[$val["l_for"]];
				$sing["SIZE"] 			= number_format($val["l_size"], 2, '.', ',');
				$sing["ROOMS"] 			= $val["l_rooms"];
				$sing["PRICE"] 			= number_format($val["l_price"], 2, '.', ',');
				$sing["CURRENCY"] 		= $val["l_currency"];
				$sing["CORNER"] 		= $val["l_corner"];
				$sing["ACT_DATE"] 		= $val["l_active_date"];
				$sing["BULID"] 			= $val["l_bulid_year"];
				
				$sing["NEI_ID"] 		= $val["nei_id"];
				$sing["NEI_NAME"] 		= $val["nei_name"];
				$sing["NEI_NAME_EN"]	= $val["nei_name_EN"];
				
				$sing["CIT_ID"] 		= $val["c_id"];
				$sing["CIT_NAME"] 		= $val["c_name"];
				$sing["CIT_NAME_EN"]	= $val["c_name_EN"];
				
				$sing["OW_ID"] 			= $val["co_id"];
				$sing["OW_NAME"] 		= $val["co_name"];
				$sing["OW_NAME_EN"] 	= $val["co_name_en"];
				$sing["OW_PHONE"] 		= $val["co_phone"];
				$sing["OW_EMAIL"] 		= $val["co_email"];
				$sing["OW_IMG"] 		= URL."public/IMG/co/".$val["co_img"];
				$sing["OW_LINK"] 		= URL."dashboard/customer/".$val["co_id"];
				$sing["OW_ACCEPT"] 		= $val["comm_accept"];
				$sing["OW_ACCEPT_NO"] 	= $val["comm_id"];
				
				array_push($ret,$sing);
			}
			return $ret;
		}
		
		/**
		* function customer
		* get customer
		*/
		public function customer($id)
		{
			$form	= new form();
			
			if(!$form->single_valid($id,'numeric'))
			{
				return array();
			}
			
			$cus = $this->db->select("SELECT co_id, co_name, co_name_en, co_package
										,co_phone , co_email, co_img, co_address 
										,co_desc
										,pk_name, pk_name_EN, pk_stars
										,count(st_user) AS stars_user, sum(st_stars) AS stars
										,comm_id, comm_exp_date, comm_accept, comm_no, comm_real_no
										,comm_co_num
										
										FROM ".DB_PREFEX."company
										JOIN ".DB_PREFEX."package ON co_package = pk_id  
										LEFT JOIN ".DB_PREFEX."comm_reg AS COMM
											ON comm_co = co_id 
											AND now() BETWEEN DATE(COMM.create_at) AND comm_exp_date
											AND comm_accept = 1
										
										LEFT JOIN ".DB_PREFEX."co_stars ON st_company = co_id
										WHERE co_id = :ID AND co_active = 1 
									" ,array(":ID"=> $id));
			if(count($cus) != 1)
			{
				return array();
			}
			$cus = $cus[0];
			
			$ret = array();
			$ret['ID'] 			= $cus['co_id'];
			$ret['NAME'] 		= $cus['co_name'];
			$ret['NAME_EN'] 	= $cus['co_name_en'];
			$ret['PHONE'] 		= $cus['co_phone'];
			$ret['EMAIL'] 		= $cus['co_email'];
			$ret['ADDRESS'] 	= $cus['co_address'];
			$ret['DESC']	 	= $cus['co_desc'];
			$ret['REG_NO'] 		= $cus['comm_no'];
			$ret['REG_REAL_NO'] = $cus['comm_real_no'];
			$ret['REG_CO_NO'] 	= $cus['comm_co_num'];
			$ret['PKG_NAME'] 	= $cus['pk_name'];
			$ret['PKG_NAME_EN'] = $cus['pk_name_EN'];
			$ret['PKG_STARS'] 	= $cus['pk_stars'];
			$ret['STARS']		= (!empty($cus['stars_user']))?$cus['stars'] / $cus['stars_user'] : null;
			$ret['IMG'] 		= URL."public/IMG/co/".$cus["co_img"];
			$ret["ACCEPT"] 		= $cus["comm_accept"];
			$ret["ACCEPT_NO"]	= $cus["comm_id"];
				
			$ret['LANDS'] 		= array();
				
			$lands =  $this->db->select("SELECT l_id, l_block, l_loc_lat, l_loc_lng
										,l_img, l_desc, l_size, l_baths 
										,l_rooms, l_interface, l_halls, ".DB_PREFEX."land.create_at AS add_date
										,l_cars, l_floor, l_price, l_currency, l_corner, l_for
										,l_active_date, l_bulid_year, l_no, l_road
										
										,ty_id, ty_name, ty_name_en, ty_for, ty_builed
										,nei_id, nei_name, nei_name_EN, c_id, c_name, c_name_EN
										,lp_start, lp_end, lp_comment
										
										FROM ".DB_PREFEX."land
										JOIN ".DB_PREFEX."neighborhood ON l_neighborhood = nei_id
										JOIN ".DB_PREFEX."city ON nei_city = c_id 
										JOIN ".DB_PREFEX."land_type ON l_type = ty_id 
										LEFT JOIN ".DB_PREFEX."land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
										WHERE l_co = :ID AND l_status = 1  
										GROUP BY l_id
										ORDER BY IF(lp_start IS NOT NULL,1,2) ASC
												,l_id DESC
									" ,array(":ID"=>$ret['ID']));
			
			foreach($lands as $val)
			{
				
				$sing = array();
				$sing["ID"] 			= $val["l_id"];
				$sing["NO"] 			= $val["l_no"];
				$sing["BLOCK"] 			= $val["l_block"];
				$sing["LOCATION"] 		= "http://maps.google.com/maps?q=".$val["l_loc_lat"].",".$val["l_loc_lng"]."&z=16&output=embed";
				$sing["LOC_LAT"] 		= $val["l_loc_lat"];
				$sing["LOC_LNG"] 		= $val["l_loc_lng"];
				
				$sing["TYPE"] 			= $val["ty_id"];
				$sing["TYPE_NAME"] 		= $val["ty_name"];
				$sing["IS_RES"] 		= ($val["ty_builed"] == 1);
				
				$sing["IMG"] 			= URL."public/IMG/land/".$val["l_id"]."/".$val["l_img"];
				$sing["FOR"] 			= $val["l_for"];
				$sing["FOR_NAME"] 		= lib::$land_for[$val["l_for"]];
				$sing["DESC"] 			= $val["l_desc"];
				$sing["LIM_DESC"] 		= (strlen($val["l_desc"])> 2000)?substr($val["l_desc"],0,2000)."...":$val["l_desc"];
				$sing["SIZE"] 			= number_format($val["l_size"], 2, '.', ',');
				$sing["ROOMS"] 			= $val["l_rooms"];
				$sing["INTERFACE"] 		= $val["l_interface"];
				$sing["HALLS"] 			= $val["l_halls"];
				$sing["BATHS"] 			= $val["l_baths"];
				$sing["CARS"] 			= $val["l_cars"];
				$sing["FLOOR"] 			= $val["l_floor"];
				$sing["PRICE"] 			= number_format($val["l_price"], 2, '.', ',');
				$sing["CURRENCY"] 		= $val["l_currency"];
				$sing["CORNER"] 		= $val["l_corner"];
				$sing["ACT_DATE"] 		= $val["l_active_date"];
				$sing["BULID"] 			= $val["l_bulid_year"];
				$sing["ROAD"] 			= $val["l_road"];
				$sing["DATE"] 			= $val["add_date"];
				
				$sing["NEI_ID"] 		= $val["nei_id"];
				$sing["NEI_NAME"] 		= $val["nei_name"];
				$sing["NEI_NAME_EN"]	= $val["nei_name_EN"];
				
				$sing["CIT_ID"] 		= $val["c_id"];
				$sing["CIT_NAME"] 		= $val["c_name"];
				$sing["CIT_NAME_EN"]	= $val["c_name_EN"];
				
				
				array_push($ret['LANDS'],$sing);
			}
			
			return $ret;
			
		}
		
		/**
		* function chatRoom
		* get chatRoom data
		*/
		public function chatRoom($landID=0,$roomID=0)
		{
			if(session::get('user_id') == false)
			{
				return false;
			}
			
			$txt = "";
			$arr = array();
			
			$form	= new form();
			if($form->single_valid($landID,'numeric'))
			{
				$txt .= "room_land = :L_ID AND ";
				$arr[":L_ID"] = $landID;
			}
			if($form->single_valid($roomID,'numeric'))
			{
				$txt .= "room_id = :R_ID AND ";
				$arr[":R_ID"] = $roomID;
			}
			
			if(empty($txt))
			{
				return array();
			}
			
			//if(session::get('user_type') != "admin")
			if(!empty(session::get('company')))
			{
				$txt .= "(room_land IN (SELECT l_id FROM ".DB_PREFEX."land WHERE l_co = :OWN) 
							OR room_customer = :CUS) AND ";
				$arr[":OWN"] = session::get("company");
				$arr[":CUS"] = session::get("user_id");
			}
			
			$chat = $this->db->select("SELECT room_id, room_customer, room_start, room_land
										,staff_id, staff_name, staff_name_en
										FROM ".DB_PREFEX."chatroom
										JOIN ".DB_PREFEX."staff ON room_customer = staff_id
										WHERE $txt 1=1
										GROUP BY room_id
										",$arr);
			
			$ret = array();
			foreach($chat as $ch)
			{
				$c = array();
				$c['ID'] 			= $ch['room_id'];
				$c['START'] 		= $ch['room_start'];
				$c['CUS_ID']		= $ch['staff_id'];
				$c['CUS_NAME'] 		= $ch['staff_name'];
				$c['CUS_NAME_EN'] 	= $ch['staff_name_en'];
				$c['CHAT_DATA'] 	= $this->chat_data($ch['room_id']);
				array_push($ret,$c);
			}
			
			return $ret;
			
		}
		
		/**
		* function chatData
		* get chat data
		*/
		public function chat_data($roomID = 0,$last_msg = 0)
		{
			$form	= new form();
			if(session::get("user_id") == false || !$form->single_valid($roomID,'Integer'))
			{
				return array();
			}
			
			//get land co for sorting chat
			$land =  $this->db->select("SELECT l_co FROM ".DB_PREFEX."land
										JOIN ".DB_PREFEX."chatroom ON room_land = l_id
										WHERE room_id = :ID  
										",array(":ID"=>$roomID)); 
			if(count($land) != 1)
			{
				return array();
			}
			$land = $land[0];
			
			$wh = "ch_room = :ID AND ";
			$wh_array = array(":ID"=>$roomID);
			if(!empty(session::get('company')))
			{
				$wh .= "ch_room IN ( SELECT distinct room_id FROM ".DB_PREFEX."chatroom WHERE room_customer = :CUS OR 
						room_land IN (SELECT l_id FROM ".DB_PREFEX."land WHERE l_co = :OWN)) AND ";
				$wh_array[":CUS"] = session::get("user_id");
				$wh_array[":OWN"] = session::get("company");
			}
			if($form->single_valid($last_msg,'numeric'))
			{
				$wh .= "ch_id > :LST AND ";
				$wh_array[":LST"] = $last_msg;
			}
			
			$x = $this->db->select("SELECT ch_id, ch_txt, CH.create_at  
										,staff_id, staff_name, staff_name_en, staff_img, staff_company
										FROM ".DB_PREFEX."chat AS CH
										JOIN ".DB_PREFEX."staff ON CH.create_by = staff_id 
										WHERE $wh 1 = 1
										ORDER BY ch_id"
										,$wh_array);
			$ret = array();
			foreach($x as $val)
			{
				$x	= array();
				$x['ID']		= $val['ch_id'];
				$x['TEXT']		= str_replace("\n", "<br/>", $val['ch_txt']);
				$x['FR_ID']		= $val['staff_id'];
				$x['FR_NAME']	= $val['staff_name'];
				$x['FR_IMG']	= URL."public/IMG/user/".$val["staff_img"];
				$x['DATE']		= $val['create_at'];
				if(empty(session::get("company")))
				{
					$x['CLASS']		= ($land['l_co'] == $val['staff_company'])?"":"chat-left";
				}else
				{
					$x['CLASS']		= (session::get("company") == $val['staff_company'])?"":"chat-left";
				}
				array_push($ret,$x);
			}
			return $ret;
		}
		
		/**
		* function newChatRoom
		* Create New Chatroom
		* AJAX
		*/
		public function newChatRoom()
		{
			$form	= new form();
			
			$form	->post('land')
					->valid('Integer')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			//insert
			$user_array = array('room_land'		=>$fdata['land']
								,'room_customer'=>session::get("user_id")
								,'room_start'	=>$time
								);
				
			$this->db->insert(DB_PREFEX.'chatroom',$user_array);
				
			return $this->chatRoom($fdata['land'],$this->db->LastInsertedId());
		}
		
		/**
		* function addChat
		* Add New Chat
		* AJAX
		*/
		public function addChat()
		{
			$form	= new form();
			
			$form	->post('chatroom')
					->valid('Integer')
					
					->post('chat_msg')
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check chatRoom
			$room = $this->chatRoom(0,$fdata['chatroom']);
			if(count($room) != 1)
			{
				return array('Error'=>"Chat Room Not Defined");
			}
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			//insert
			$user_array = array('ch_room'	=>$fdata['chatroom']
								,'ch_txt'	=>$fdata["chat_msg"]
								,'create_by'=>session::get("user_id")
								,'create_at'=>$time
								);
				
			$this->db->insert(DB_PREFEX.'chat',$user_array);
				
			return array('ok'=>$this->db->LastInsertedId());
			
		}
		
		/**
		* function mail_list
		* save contact mail
		* AJAX
		*/
		public function mail_list()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			$form	= new form();
			
			$form	->post('email_list')
					->valid('Email')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//insert
			$user_array = array('mail_name'	=>$fdata['email_list']
								,'create_at'	=>$time
								);
				
			$this->db->insert(DB_PREFEX.'mail_list',$user_array);
				
			return array('ok'=>1);
			
		}
		
		/**
		* function new_cont
		* save contact msg
		* AJAX
		*/
		public function new_cont()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			$form	= new form();
			
			$form	->post('name')
					->valid('Min_Length',1)
					
					->post('email')
					->valid('Email')
					
					->post('subject')
					->valid('Min_Length',1)
					
					->post('message')
					->valid('Min_Length',10)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$MSG = "Subject: ".$fdata['subject']." \n";
			$MSG .= "Time: ".$time." \n";
			$MSG .= "Name: ".$fdata['name']." \n";
			$MSG .= "Email: ".$fdata['email']." \n";
			if(session::get('user_id'))
			{
				$MSG .= "User ID: ".session::get('user_id')." \n";
			}
			$MSG .= "MSG: \n ".$fdata['message'];
			
			$email = new Email();
			
			$x = $email->send_email(EMAIL_ADD,$fdata['subject'],$MSG);
			
			$MSG = "لقد تم ارسال رسالتك/ استفسارك \n سيتم الرد عليك قريبا \n الرسالة:\n".$MSG;
			$x = $email->send_email($fdata['email'],'رسالة/ استفسار',$MSG);
			
			return array('ok'=>$x);
			
		}
		
		/**
		* Get terms:
		*/
		public function terms()
		{
			//Get visits
			$x = $this->db->select("SELECT conf_val AS TER FROM ".DB_PREFEX."config WHERE conf_name = 'TERMS'",array());
			return $x[0]['TER'];
		}
		
		/**
		* Get terms:
		*/
		public function policy()
		{
			//Get visits
			$x = $this->db->select("SELECT conf_val AS TER FROM ".DB_PREFEX."config WHERE conf_name = 'POLICY'",array());
			return $x[0]['TER'];
		}
	}
?>