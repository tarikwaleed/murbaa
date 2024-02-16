<?php
	/** my_land MODEL, */
	class my_land_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function conf
		* get package_config
		*/
		public function conf_list()
		{
			$ret = array();
			
			//for Customer
			$ret['AREA'] 	= 1;
			$ret['VIP']		= 0;
			$ret['CURR_VIP']= 0;
			$ret['PAK_PRICE']= 0;
			$pk = $this->db->select("SELECT pk_id, pk_name, pk_stars, pk_users
									,pk_users_msg, pk_adv_area, pk_vip_area, pk_adv_pay
									,count(lp_id) AS vip
									FROM ".DB_PREFEX."package
									JOIN ".DB_PREFEX."company ON pk_id = co_package
									LEFT JOIN ".DB_PREFEX."land_package ON 
										lp_land IN (SELECT l_id FROM ".DB_PREFEX."land WHERE l_co = co_id)
									
									WHERE co_id = :ID
									" ,array(':ID'=>session::get('company'))
									);
			$all_lands = $this->db->select("SELECT count(l_id) AS lands FROM ".DB_PREFEX."land 
											WHERE l_co = :ID
											" ,array(':ID'=>session::get('company'))
										);
			$ret['ALL_LANDS'] = $all_lands[0]['lands'];
			if(count($pk) == 1)
			{
				$ret['PK_PRICE']= $pk[0]['pk_adv_pay'];
				$ret['AREA'] 	= $pk[0]['pk_adv_area'];
				$ret['VIP']		= $pk[0]['pk_vip_area'];
				$ret['CURR_VIP']= $pk[0]['vip'];;
				
			}
			return $ret;
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
			
			$x = $this->db->select("SELECT nei_id, nei_name,nei_name_EN,nei_city  
										FROM ".DB_PREFEX."neighborhood
										WHERE $wh 1 = 1"
										,$wh_array);
			$ret = array();
			foreach($x as $val)
			{
				$ret[$val['nei_id']] = array('ID'=>$val['nei_id']
											,'NAME'=>$val['nei_name']
											,'NAME_EN'=>$val['nei_name_EN']
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
			
			$txt = "l_co = :CO_ID AND ";
			$limit = "";
			$arr = array(":CO_ID"=>session::get('company'));
			
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
				if(!empty($fdata['adv']))
				{
					$txt .= "l_adv LIKE :ADV AND ";
					$arr[':ADV'] = $fdata['adv'];
				}
				if(!empty($fdata['types']))
				{
					$txt .= "l_type LIKE :TYPE AND ";
					$arr[':TYPE'] = $fdata['types'];
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
			
			//l_status = 1
			
			$lands =  $this->db->select("SELECT l_id, l_block, l_loc_lat, l_loc_lng
										,l_img, l_desc, l_size, l_baths ,l_adv
										,l_rooms, l_interface, l_halls, ".DB_PREFEX."land.create_at AS add_date
										,l_cars, l_floor, l_price, l_currency, l_corner, l_for
										,l_active_date, l_bulid_year, l_no, l_road, l_status
										,l_tree, l_well, l_mushub
										
										,l_duplex, l_append, l_monster, l_swim, l_kitchen
										,l_basement, l_elevator, l_server_room, l_dr_room
										
										,ty_id, ty_name, ty_name_en, ty_for, ty_builed
										,nei_id, nei_name, nei_name_EN, c_id, c_name, c_name_EN
										,co_id, co_name, co_name_en, co_package, co_phone, co_email, co_img
										,l_co_relation, l_co_delegat
										,lp_start, lp_end, lp_comment
										
										FROM ".DB_PREFEX."land
										JOIN ".DB_PREFEX."neighborhood ON l_neighborhood = nei_id
										JOIN ".DB_PREFEX."city ON nei_city = c_id 
										JOIN ".DB_PREFEX."company ON co_id = l_co AND co_active = 1 
										JOIN ".DB_PREFEX."package ON co_package = pk_id  
										JOIN ".DB_PREFEX."land_type ON l_type = ty_id 
										LEFT JOIN ".DB_PREFEX."land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
										WHERE $txt 1=1  
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
				$sing["ACTIVE"] 		= $val["l_status"];
				$sing["NO"] 			= $val["l_no"];
				$sing["BLOCK"] 			= $val["l_block"];
				$sing["LOCATION"] 		= "http://maps.google.com/maps?q=".$val["l_loc_lat"].",".$val["l_loc_lng"]."&z=16&output=embed";
				$sing["LOC_LAT"] 		= $val["l_loc_lat"];
				$sing["LOC_LNG"] 		= $val["l_loc_lng"];
				
				$sing["RELATION"] 		= $val["l_co_relation"];
				$sing["DELEGATION"] 	= $val["l_co_delegat"];
				
				$sing["TYPE"] 			= $val["ty_id"];
				$sing["TYPE_NAME"] 		= $val["ty_name"];
				$sing["TYPE_BUILD"] 	= $val["ty_builed"];
				$sing["IS_RES"] 		= ($val["ty_builed"] == 1);
				
				$sing["IMG"] 			= URL."public/IMG/land/".$val["l_id"]."/".$val["l_img"];
				$sing["FOR"] 			= $val["l_for"];
				$sing["FOR_NAME"] 		= lib::$land_for[$val["l_for"]];
				$sing["DESC"] 			= $val["l_desc"];
				$sing["LIM_DESC"] 		= (strlen($val["l_desc"])> 2000)?substr($val["l_desc"],0,2000)."...":$val["l_desc"];
				$sing["SIZE"] 			= number_format($val["l_size"], 2, '.', ',');
				$sing["M_SIZE"] 		= $val["l_size"];
				$sing["ROOMS"] 			= $val["l_rooms"];
				$sing["INTERFACE"] 		= $val["l_interface"];
				$sing["HALLS"] 			= $val["l_halls"];
				$sing["BATHS"] 			= $val["l_baths"];
				$sing["CARS"] 			= $val["l_cars"];
				$sing["FLOOR"] 			= $val["l_floor"];
				$sing["PRICE"] 			= number_format($val["l_price"], 2, '.', ',');
				$sing["M_PRICE"] 		= $val["l_price"];
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
				
				$sing["PACKAGE_START"] 	= $val["lp_start"];
				$sing["PACKAGE_END"] 	= $val["lp_end"];
				$sing["PACKAGE_COMM"] 	= $val["lp_comment"];
				
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
				
				$dir 	= URL_PATH."public/IMG/land/".$val["l_id"]."/";
				$link 	= URL."public/IMG/land/".$val["l_id"]."/";
				$sing["OTHER_IMG"] 		= files::get_file_list($dir,$link);
				
				
				if($fdata['chat'])
				{
					$sing['CHAT'] = $this->chatRoom($val["l_id"]);
				}//if chat
				array_push($ret,$sing);
			}
			
			return $ret;
			
		}
		
		/**
		* function new_land
		* create new land
		*/
		public function new_land()
		{
			$form	= new form();
			$form	->post('new_city') 					// CITY
					->valid('numeric')
					
					->post('new_neighborhood') 			// NEIGHBOR
					->valid('numeric')
					
					->post('new_block') 				// BLOCK
					->valid('numeric')
					
					->post('new_adv')
					->valid('In_Array',array_keys(lib::$adv_type))
					
					->post('new_lng',false,true) 		// LOCATION
					->valid('numeric')
					
					->post('new_lat',false,true) 		// LOCATION
					->valid('numeric')
					
					->post('new_relation') 				// Relation
					->valid('In_Array',array_keys(lib::$land_relation))
					
					->post('new_delegate',false,true) 	// Delegation
					->valid('Min_Length',5)
					
					->post('new_type') 					// TYPE
					->valid('numeric')
					
					->post('new_status') 				// STATUS
					->valid('In_Array',array_keys(lib::$land_for))
					
					->post('new_desc') 					// DESC
					->valid('Min_Length',5)
					
					->post('new_no') 					// NO
					->valid('numeric')
					
					->post('new_space')	 				// Space
					->valid('numeric')
					
					->post('new_rooms',false,true)	 	// ROOMS
					->valid('numeric')
					
					->post('new_bath',false,true)	 	// BATHS
					->valid('numeric')
					
					->post('new_hall',false,true)	 	// HALLS
					->valid('numeric')
					
					->post('new_floor',false,true)	 	// FLOOR
					->valid('numeric')
					
					->post('new_car',false,true)	 	// CARS
					->valid('numeric')
					
					->post('new_road',false,true)	 	// ROAD
					->valid('numeric')
					
					->post('new_price')					// PRICE
					->valid('numeric')
					
					->post('new_currency') 				// CURRENCY
					->valid('In_Array',array_keys(lib::$currency))
					
					->post('new_year',false,true)		// YEAR
					->valid('numeric')
					
					->post('new_corner',false,true)		// CORNER
					->valid('numeric')
					
					->post('new_interface',false,true)	// INTERFACE
					->valid('In_Array',array_keys(lib::$land_interface))
					
					->post('new_duplex',false,true)
					->valid('numeric')
					
					->post('new_append',false,true)
					->valid('numeric')
					
					->post('new_basment',false,true)
					->valid('numeric')
					
					->post('new_monsters',false,true)
					->valid('numeric')
					
					->post('new_swim',false,true)
					->valid('numeric')
					
					->post('new_kitchen',false,true)
					->valid('numeric')
					
					->post('new_elevator',false,true)
					->valid('numeric')
					
					->post('new_ser_room',false,true)
					->valid('numeric')
					
					->post('new_dr_room',false,true)
					->valid('numeric')
					
					->post('new_well',false,true)
					->valid('numeric')
					
					->post('new_tree',false,true)
					->valid('numeric')
					
					->post('new_mushub',false,true)
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check NEIGHBOR:
			$x = $this->db->select("SELECT nei_id  
										FROM ".DB_PREFEX."neighborhood
										WHERE nei_city = :CITY AND nei_id = :ID"
										,array(":CITY"=>$fdata["new_city"],":ID"=>$fdata["new_neighborhood"]));
			if(count($x) != 1)
			{
				return array('Error'=>"In Field new_city : Not Found .. \n In Field new_neighborhood : Not Found .. \n");
			}
			
			//check ID
			$x = $this->db->select("SELECT l_id FROM ".DB_PREFEX."land
										WHERE l_no = :NO AND l_neighborhood = :ID AND l_block = :BLOCK"
										,array(":NO"=>$fdata["new_no"]
											,":ID"=>$fdata["new_neighborhood"]
											,":BLOCK"=>$fdata["new_block"]));
			if(count($x) != 0)
			{
				return array('Error'=>"In Field new_no : Duplicate .. \n ");
			}
			
			//check relation
			//new_relation
			if($fdata['new_relation'] == "SAIL" ||$fdata['new_relation'] == "CREA")
			{
				if(empty($fdata['new_delegate']))
				{
					return array('Error'=>"In Field new_delegate : عليك ان تحدد رقم التفويض.. \n ");
				}
				$cus = $this->db->select("SELECT comm_id, comm_exp_date, comm_accept, comm_no
										,comm_real_no, comm_co_num
										FROM ".DB_PREFEX."comm_reg 
										WHERE comm_co = :ID AND 
										now() BETWEEN DATE(create_at) AND comm_exp_date
										AND comm_accept = 1
									" ,array(":ID"=> session::get('company')));
				if(count($cus)!= 1)
				{
					return array('Error'=>"In Field new_relation : عليك ان تقوم بتوثيق حسابك اولا.. \n ");
				}
				if(empty($cus[0]['comm_real_no']) || empty($cus[0]['comm_co_num']))
				{
					return array('Error'=>"In Field new_relation : عليك ان تضيف رقم رقم ترخيص الهيئة العامة للعقار ورقم المعلن لتوثيق حسابك.. \n ");
				}
			}
			
			$m_time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($m_time);
			
			$pkg 	= array();
			$land 	= array();
			
			$land['l_adv'] 			= $fdata['new_adv'];
			$land['l_neighborhood'] = $fdata['new_neighborhood'];
			$land['l_block'] 		= $fdata['new_block'];
			$land['l_no'] 			= $fdata['new_no'];
			$land['l_type'] 		= $fdata['new_type'];
			$land['l_for'] 			= $fdata['new_status'];
			$land['l_size'] 		= $fdata['new_space'];
			$land['l_desc'] 		= $fdata['new_desc'];
			$land['l_price'] 		= $fdata['new_price'];
			$land['l_currency'] 	= $fdata['new_currency'];
			$land['l_active_date'] 	= $time;
			$land['create_at'] 		= $time;
			$land['create_by'] 		= session::get('user_id');
			
			$land['l_co'] 			= session::get('company');
			$land['l_co_relation'] 	= $fdata['new_relation'];
			$land['l_co_delegat'] 	= (!empty($fdata['new_delegate']))?$fdata['new_delegate']:null;
			
			$land['l_loc_lat'] 		= (!empty($fdata['new_lat']))?$fdata['new_lat']:null;
			$land['l_loc_lng'] 		= (!empty($fdata['new_lng']))?$fdata['new_lng']:null;
			$land['l_rooms'] 		= (!empty($fdata['new_rooms']))?$fdata['new_rooms']:null;
			$land['l_baths'] 		= (!empty($fdata['new_bath']))?$fdata['new_bath']:null;
			$land['l_halls'] 		= (!empty($fdata['new_hall']))?$fdata['new_hall']:null;
			$land['l_floor'] 		= (!empty($fdata['new_floor']))?$fdata['new_floor']:null;
			$land['l_cars'] 		= (!empty($fdata['new_car']))?$fdata['new_car']:null;
			$land['l_bulid_year'] 	= (!empty($fdata['new_year']))?$fdata['new_year']:null;
			$land['l_corner'] 		= (!empty($fdata['new_corner']))?$fdata['new_corner']:null;
			$land['l_interface'] 	= (!empty($fdata['new_interface']))?$fdata['new_interface']:null;
			$land['l_road'] 		= (!empty($fdata['new_road']))?$fdata['new_road']:null;
			
			$land['l_basement'] 	= (!empty($fdata['new_basment']))?$fdata['new_basment']:null;
			$land['l_duplex'] 		= (!empty($fdata['new_duplex']))?$fdata['new_duplex']:null;
			$land['l_append'] 		= (!empty($fdata['new_append']))?$fdata['new_append']:null;
			$land['l_monster'] 		= (!empty($fdata['new_monsters']))?$fdata['new_monsters']:null;
			$land['l_swim'] 		= (!empty($fdata['new_swim']))?$fdata['new_swim']:null;
			$land['l_kitchen'] 		= (!empty($fdata['new_kitchen']))?$fdata['new_kitchen']:null;
			$land['l_elevator'] 	= (!empty($fdata['new_elevator']))?$fdata['new_elevator']:null;
			$land['l_server_room'] 	= (!empty($fdata['new_ser_room']))?$fdata['new_ser_room']:null;
			$land['l_dr_room'] 		= (!empty($fdata['new_dr_room']))?$fdata['new_dr_room']:null;
			
			$land['l_well'] 		= (!empty($fdata['new_well']))?$fdata['new_well']:null;
			$land['l_tree'] 		= (!empty($fdata['new_tree']))?$fdata['new_tree']:null;
			$land['l_mushub'] 		= (!empty($fdata['new_mushub']))?$fdata['new_mushub']:null;
			
			//insert
			$this->db->insert(DB_PREFEX.'land',$land);
			$id = $this->db->LastInsertedId();
			
			$files	= new files(); 
			$main_img = 'default.png';
			
			if(!empty($id))
			{
				if(!empty($_FILES['new_land_img']))
				{
					if($files->check_file($_FILES['new_land_img']))
					{
						$main_img = $files->up_file($_FILES['new_land_img'],URL_PATH.'public/IMG/land/'.$id);
						$this->db->update(DB_PREFEX.'land',array("l_img"=>$main_img),"l_id = ".$id);
					}
					if(!empty($files->error_message))
					{
						return array('Error'=>$files->error_message);
					}
				}else
				{
					$files->copy_file(URL_PATH.'public/IMG/land/default.png',URL_PATH.'public/IMG/land/'.$id,'default.png');
				}
				
				$this->db->update(DB_PREFEX.'land',array("l_img"=>$main_img),"l_id = ".$id);
				
				if(!empty($_FILES['new_file_image']) && count($_FILES['new_file_image'])!= 0)
				{
					$file_array = $files->reArrayFiles($_FILES['new_file_image']);
					
					foreach($file_array as $val)
					{
						if($files->check_file($val))
						{
							$x = $files->up_file($val,URL_PATH.'public/IMG/land/'.$id);
						}
					}
					
					if(!empty($files->error_message))
					{
						return array('Error'=>$files->error_message);
					}
				}
			}
			return array('id'=>$id);
		}
		
		/**
		* function del_img
		* delete land image
		*/
		public function del_img()
		{
			$form	= new form();
			
			$form	->post('id') // id
					->valid('numeric')
					
					->post('img') // img
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return 'Error: '.$fdata['MSG'];
			}
			
			$files	= new files(); 
			$file_path = URL_PATH."public/IMG/land/".$fdata['id']."/".$fdata['img'];
			$files->del_file($file_path);
			
			return array("ok"=>1);
		}
		
		/**
		* function upd_land
		* update land
		*/
		public function upd_land()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form	= new form();
			
			$form	->post('id') 						// ID
					->valid('numeric')
					
					->post('upd_city') 					// CITY
					->valid('numeric')
					
					->post('upd_neighborhood') 			// NEIGHBOR
					->valid('numeric')
					
					->post('upd_block') 				// BLOCK
					->valid('numeric')
					
					->post('upd_adv')
					->valid('In_Array',array_keys(lib::$adv_type))
					
					->post('upd_lng',false,true) 		// LOCATION
					->valid('numeric')
					
					->post('upd_lat',false,true) 		// LOCATION
					->valid('numeric')
					
					->post('upd_relation') 				// Relation
					->valid('In_Array',array_keys(lib::$land_relation))
					
					->post('upd_delegate',false,true) 	// Delegation
					->valid('Min_Length',5)
					
					->post('upd_type') 					// TYPE
					->valid('numeric')
					
					->post('upd_status') 				// STATUS
					->valid('In_Array',array_keys(lib::$land_for))
					
					->post('upd_desc') 					// DESC
					->valid('Min_Length',5)
					
					->post('upd_no') 					// NO
					->valid('numeric')
					
					->post('upd_space')	 				// Space
					->valid('numeric')
					
					->post('upd_rooms',false,true)	 	// ROOMS
					->valid('numeric')
					
					->post('upd_bath',false,true)	 	// BATHS
					->valid('numeric')
					
					->post('upd_hall',false,true)	 	// HALLS
					->valid('numeric')
					
					->post('upd_floor',false,true)	 	// FLOOR
					->valid('numeric')
					
					->post('upd_road',false,true)	 	// ROAD
					->valid('numeric')
					
					->post('upd_car',false,true)	 	// CARS
					->valid('numeric')
					
					->post('upd_price')					// PRICE
					->valid('numeric')
					
					->post('upd_currency') 				// CURRENCY
					->valid('In_Array',array_keys(lib::$currency))
					
					->post('upd_year',false,true)		// YEAR
					->valid('numeric')
					
					->post('upd_corner',false,true)		// CORNER
					->valid('numeric')
					
					->post('upd_interface',false,true)	// INTERFACE
					->valid('In_Array',array_keys(lib::$land_interface))
					
					->post('upd_duplex',false,true)
					->valid('numeric')
					
					->post('upd_append',false,true)
					->valid('numeric')
					
					->post('upd_basment',false,true)
					->valid('numeric')
					
					->post('upd_monsters',false,true)
					->valid('numeric')
					
					->post('upd_swim',false,true)
					->valid('numeric')
					
					->post('upd_kitchen',false,true)
					->valid('numeric')
					
					->post('upd_elevator',false,true)
					->valid('numeric')
					
					->post('upd_ser_room',false,true)
					->valid('numeric')
					
					->post('upd_dr_room',false,true)
					->valid('numeric')
					
					->post('upd_well',false,true)
					->valid('numeric')
					
					->post('upd_tree',false,true)
					->valid('numeric')
					
					->post('upd_mushub',false,true)
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check NEIGHBOR:
			$x = $this->db->select("SELECT nei_id  
										FROM ".DB_PREFEX."neighborhood
										WHERE nei_city = :CITY AND nei_id = :ID"
										,array(":CITY"=>$fdata["upd_city"],":ID"=>$fdata["upd_neighborhood"]));
			if(count($x) != 1)
			{
				return array('Error'=>"In Field upd_city : Not Found .. \n In Field upd_neighborhood : Not Found .. \n");
			}
			
			//check ID
			$x = $this->db->select("SELECT l_id FROM ".DB_PREFEX."land
										WHERE l_id!= :LID AND l_no = :NO AND l_neighborhood = :ID AND l_block = :BLOCK"
										,array(":NO"=>$fdata["upd_no"]
											,":LID"=>$fdata["id"]
											,":ID"=>$fdata["upd_neighborhood"]
											,":BLOCK"=>$fdata["upd_block"]));
			if(count($x) != 0)
			{
				return array('Error'=>"In Field upd_no : Duplicate .. \n ");
			}
			
			$land 	= array();
			
			$land['l_neighborhood'] = $fdata['upd_neighborhood'];
			$land['l_block'] 		= $fdata['upd_block'];
			$land['l_adv'] 			= $fdata['upd_adv'];
			$land['l_no'] 			= $fdata['upd_no'];
			$land['l_type'] 		= $fdata['upd_type'];
			$land['l_for'] 			= $fdata['upd_status'];
			$land['l_size'] 		= $fdata['upd_space'];
			$land['l_desc'] 		= $fdata['upd_desc'];
			$land['l_price'] 		= $fdata['upd_price'];
			$land['l_currency'] 	= $fdata['upd_currency'];
			$land['update_at'] 		= $time;
			$land['update_by'] 		= session::get('user_id');
			
			$land['l_co_relation'] 	= $fdata['upd_relation'];
			$land['l_co_delegat'] 	= (!empty($fdata['upd_delegate']))?$fdata['upd_delegate']:null;
			
			
			$land['l_loc_lat'] 		= (!empty($fdata['upd_lat']))?$fdata['upd_lat']:null;
			$land['l_loc_lng'] 		= (!empty($fdata['upd_lng']))?$fdata['upd_lng']:null;
			$land['l_rooms'] 		= (!empty($fdata['upd_rooms']))?$fdata['upd_rooms']:null;
			$land['l_baths'] 		= (!empty($fdata['upd_bath']))?$fdata['upd_bath']:null;
			$land['l_halls'] 		= (!empty($fdata['upd_hall']))?$fdata['upd_hall']:null;
			$land['l_floor'] 		= (!empty($fdata['upd_floor']))?$fdata['upd_floor']:null;
			$land['l_cars'] 		= (!empty($fdata['upd_car']))?$fdata['upd_car']:null;
			$land['l_bulid_year'] 	= (!empty($fdata['upd_year']))?$fdata['upd_year']:null;
			$land['l_corner'] 		= (!empty($fdata['upd_corner']))?$fdata['upd_corner']:null;
			$land['l_interface'] 	= (!empty($fdata['upd_interface']))?$fdata['upd_interface']:null;
			$land['l_road'] 		= (!empty($fdata['upd_road']))?$fdata['upd_road']:null;
			
			$land['l_basement'] 	= (!empty($fdata['upd_basment']))?$fdata['upd_basment']:null;
			$land['l_duplex'] 		= (!empty($fdata['upd_duplex']))?$fdata['upd_duplex']:null;
			$land['l_append'] 		= (!empty($fdata['upd_append']))?$fdata['upd_append']:null;
			$land['l_monster'] 		= (!empty($fdata['upd_monsters']))?$fdata['upd_monsters']:null;
			$land['l_swim'] 		= (!empty($fdata['upd_swim']))?$fdata['upd_swim']:null;
			$land['l_kitchen'] 		= (!empty($fdata['upd_kitchen']))?$fdata['upd_kitchen']:null;
			$land['l_elevator'] 	= (!empty($fdata['upd_elevator']))?$fdata['upd_elevator']:null;
			$land['l_server_room'] 	= (!empty($fdata['upd_ser_room']))?$fdata['upd_ser_room']:null;
			$land['l_dr_room'] 		= (!empty($fdata['upd_dr_room']))?$fdata['upd_dr_room']:null;
			
			$land['l_well'] 		= (!empty($fdata['upd_well']))?$fdata['upd_well']:null;
			$land['l_tree'] 		= (!empty($fdata['upd_tree']))?$fdata['upd_tree']:null;
			$land['l_mushub'] 		= (!empty($fdata['upd_mushub']))?$fdata['upd_mushub']:null;
			
			$files	= new files(); 
			if(!empty($_FILES['upd_land_img']) )
			{
				if($files->check_file($_FILES['upd_land_img']))
				{
					$land['l_img'] = $files->up_file($_FILES['upd_land_img'],URL_PATH.'public/IMG/land/'.$fdata['id']);
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			if(!empty($_FILES['upd_file_image']) && count($_FILES['upd_file_image'])!= 0)
			{
				$file_array = $files->reArrayFiles($_FILES['upd_file_image']);
				
				foreach($file_array as $val)
				{
					if($files->check_file($val))
					{
						$x = $files->up_file($val,URL_PATH.'public/IMG/land/'.$fdata['id']);
					}
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			
			//update
			$this->db->update(DB_PREFEX.'land',$land,"l_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
		
		/**
		* function del_land
		* delete land
		*/
		public function del_land()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check ID
			$x = $this->db->select("SELECT l_id FROM ".DB_PREFEX."land
										WHERE l_id = :LID "
										,array(":LID"=>$fdata["id"]));
			if(count($x) != 1)
			{
				return array('Error'=>"In Field upd_no : Duplicate .. \n ");
			}
			
			//delete
			
			//$this->db->delete(DB_PREFEX.'chat',"ch_room IN (SELECT room_id FROM ".DB_PREFEX."chatroom WHERE room_land = ".$fdata['id']." )");
			//$this->db->delete(DB_PREFEX.'chatroom',"room_land = ".$fdata['id']);
			//$this->db->delete(DB_PREFEX.'land_package',"lp_land = ".$fdata['id']);
			
			
			//delete files
			$files	= new files(); 
			$files->del_file_list(URL_PATH.'public/IMG/land/'.$fdata['id']);
			
			//update
			$this->db->update(DB_PREFEX.'land',array('l_co'=>null),"l_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
		
		/**
		* function active
		* active / freez land
		*/
		public function active()
		{
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('Integer')
					
					->post('current',false,true) // Name
					->valid('In_Array',array('true','false'))
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>"err_id");
			}
			
			//check NO:
			$data = $this->db->select("SELECT l_status FROM ".DB_PREFEX."land 
									WHERE l_id = :ID"
									,array(":ID"=>$fdata['id']));
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على العقار");
			}
			
			$curr = ($data[0]['l_status']==1)?true:false;
			
			if(($fdata['current'] == "true" && !$curr)||($fdata['current']== "false" && $curr))
			{
				return array('Error'=>'حالة العقار الحالية هي  '.$curr.' - '.$fdata['current']);
			}	
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$arr = array();
			$arr['l_status'] = ($curr)?0:1;
			$arr['update_at']= $time;
			$arr['update_by']= session::get("user_id");
			
			$this->db->update(DB_PREFEX.'land',$arr,'l_id = '.$fdata['id']);
			return array('ok'=>'1');
		}
		
		/**
		* function vip
		* vip land
		*/
		public function vip()
		{
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('Integer')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>"err_id");
			}
			
			//check NO:
			$data = $this->db->select("SELECT l_id ,lp_start, lp_end, lp_comment
									FROM ".DB_PREFEX."land
									LEFT JOIN ".DB_PREFEX."land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
									WHERE l_id = :ID"
									,array(":ID"=>$fdata['id']));
			
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على العقار");
			}
			
			if($data[0]['lp_start'] != null && $data[0]['lp_start'] != "")
			{
				return array('Error'=>'باقة العقار الحالية من '.$data[0]['lp_start'].' - '.$data[0]['lp_end']);
			}	
			
			$time	= dates::convert_to_date('now');
			$timestr= dates::convert_to_string($time);
			
			$time_end = dates::add_days($time,session::get('VIP_PERIOD'));
			$timestr_end= dates::convert_to_string($time_end);
			
			$arr = array();
			$arr['lp_land'] 	= $fdata['id'];
			$arr['lp_start']	= substr($timestr,0,10);
			$arr['lp_end']		= substr($timestr_end,0,10);
			$arr['create_at']	= $timestr;
			$arr['create_by']	= session::get("user_id");
			
			$this->db->insert(DB_PREFEX.'land_package',$arr);
			return array('ok'=>$this->db->LastInsertedId());
		}
		
		/**
		* function upgrade
		* vip land with price
		*/
		public function upgrade_vip()
		{
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('Integer')
					
					->post('vip_range') // period
					->valid('Integer')
					
					->post('vip_price') // price
					->valid('Integer')
					
					->post('vip_card') // Card
					->valid('Min_Length',5)
					
					->post('vip_pass') // password
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>"err_id");
			}
			
			//check NO:
			$data = $this->db->select("SELECT l_id ,lp_start, lp_end, lp_comment
									FROM ".DB_PREFEX."land
									LEFT JOIN ".DB_PREFEX."land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
									WHERE l_id = :ID"
									,array(":ID"=>$fdata['id']));
			
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على العقار");
			}
			
			if($data[0]['lp_start'] != null && $data[0]['lp_start'] != "")
			{
				return array('Error'=>'باقة العقار الحالية من '.$data[0]['lp_start'].' - '.$data[0]['lp_end']);
			}	
			
			//Payments
			/*Payment Method Hear*/
			$pay_ok = payments::check($fdata['vip_card'],$fdata['vip_pass'],$fdata['vip_price']);
			if($pay_ok !== true)
			{
				return array('Error'=>'خطأ في بيانات البطاقة: '.$pay_ok);
			}
			/*_________________________*/
			
			$time	= dates::convert_to_date('now');
			$timestr= dates::convert_to_string($time);
			
			//add bill
			$bill = array();
			$bill['bi_land'] 		= $fdata['id'];
			$bill['bi_company']		= session::get("company");
			$bill['bi_period']		= $fdata["vip_range"];
			$bill['bi_amount']		= $fdata["vip_price"];
			$bill['create_at']		= $timestr;
			$bill['create_by']		= session::get("user_id");
			
			$this->db->insert(DB_PREFEX.'bill',$bill);
			$id = $this->db->LastInsertedId();
			
			$time_end = dates::add_days($time,$fdata["vip_range"]);
			$timestr_end= dates::convert_to_string($time_end);
			
			$arr = array();
			$arr['lp_land'] 	= $fdata['id'];
			$arr['lp_start']	= substr($timestr,0,10);
			$arr['lp_end']		= substr($timestr_end,0,10);
			$arr['create_at']	= $timestr;
			$arr['create_by']	= session::get("user_id");
			
			$this->db->insert(DB_PREFEX.'land_package',$arr);
			return array('id'=>$id);
		}
		
		/**
		* function land_bill
		* land with price
		*/
		public function land_bill()
		{
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('Integer')
					
					->post('vip_price') // price
					->valid('Integer')
					
					->post('vip_card') // Card
					->valid('Min_Length',5)
					
					->post('vip_pass') // password
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>"err_id");
			}
			
			//check NO:
			$data = $this->db->select("SELECT l_id ,lp_start, lp_end, lp_comment
									FROM ".DB_PREFEX."land
									LEFT JOIN ".DB_PREFEX."land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
									WHERE l_id = :ID"
									,array(":ID"=>$fdata['id']));
			
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على العقار");
			}
			
			//Payments
			/*Payment Method Hear*/
			$pay_ok = payments::check($fdata['vip_card'],$fdata['vip_pass'],$fdata['vip_price']);
			if($pay_ok !== true)
			{
				return array('Error'=>'خطأ في بيانات البطاقة: '.$pay_ok);
			}
			/*_________________________*/
			
			$time	= dates::convert_to_date('now');
			$timestr= dates::convert_to_string($time);
			
			//add bill
			$bill = array();
			$bill['bi_land'] 		= $fdata['id'];
			$bill['bi_company']		= session::get("company");
			$bill['bi_amount']		= $fdata["vip_price"];
			$bill['create_at']		= $timestr;
			$bill['create_by']		= session::get("user_id");
			
			$this->db->insert(DB_PREFEX.'bill',$bill);
			
			return array('id'=>$this->db->LastInsertedId());
		}
		
	}
?>