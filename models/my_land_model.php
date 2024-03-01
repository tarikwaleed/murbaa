<?php

/** my_land MODEL, */
class my_land_model extends model
{
	/** The Default Method Like Main in java*/
	function __construct()
	{
		parent::__construct();
	}

	//package_config
	public function conf_list()
	{
		$ret = array();

		//for Customer
		$ret['AREA'] 	= 1;
		$ret['VIP']		= 0;
		$ret['CURR_VIP'] = 0;
		$ret['PAK_PRICE'] = 0;
		$pk = $this->db->select(
			"SELECT pk_id, pk_name, pk_stars, pk_users
									,pk_users_msg, pk_adv_area, pk_vip_area, pk_adv_pay
									,count(lp_id) AS vip
									,co_id_type, co_id_no
									FROM " . DB_PREFEX . "package
									JOIN " . DB_PREFEX . "company ON pk_id = co_package
									LEFT JOIN " . DB_PREFEX . "land_package ON 
										lp_land IN (SELECT l_id FROM " . DB_PREFEX . "land WHERE l_co = co_id)
									WHERE co_id = :ID
									",
			array(':ID' => session::get('company'))
		);
		$all_lands = $this->db->select(
			"SELECT count(l_id) AS lands FROM " . DB_PREFEX . "land 
											WHERE l_co = :ID AND l_adv like 'ADV'
											",
			array(':ID' => session::get('company'))
		);
		$ret['ALL_LANDS'] = $all_lands[0]['lands'];
		if (count($pk) == 1) {
			$ret['PK_PRICE'] = $pk[0]['pk_adv_pay'];
			$ret['AREA'] 	= $pk[0]['pk_adv_area'];
			$ret['VIP']		= $pk[0]['pk_vip_area'];
			$ret['CURR_VIP'] = $pk[0]['vip'];;
			$ret['ID_TYPE']	= $pk[0]['co_id_type'];
			$ret['ID_NO']	= $pk[0]['co_id_no'];
		}
		return $ret;
	}

	//get other company data
	public function company()
	{
		$customer = $this->db->select(
			"SELECT co_id AS ID, co_name AS NAME
									,co_name_en AS NAME_EN, co_phone AS PHONE
									,co_email AS EMAIL, co_img
									FROM " . DB_PREFEX . "company
									JOIN " . DB_PREFEX . "co_adv ON adv_co = co_id
									WHERE adv_my_co = :CO AND co_id != :CO AND co_active = 1",
			array(':CO' => session::get('company'))
		);
		$ret = array();
		foreach ($customer as $val) {
			$val["IMG"] 		= URL . "public/IMG/co/" . $val["co_img"];
			$val["LINK"]		= URL . "dashboard/customer/" . $val['ID'];
			array_push($ret, $val);
		}
		return $ret;
	}

	//get land_type data
	public function land_type()
	{
		$ty = $this->db->select("SELECT ty_id AS ID, ty_name AS NAME
									,ty_name_en AS NAME_EN, ty_builed AS BUILD 
									FROM " . DB_PREFEX . "land_type
									WHERE 1 = 1", array());
		$ret = array();
		foreach ($ty as $val) {
			$ret[$val['ID']] = $val;
		}
		return $ret;
	}

	//get city data
	public function city_list()
	{
		$x = $this->db->select(
			"SELECT c_id, c_name,c_name_EN  
										FROM " . DB_PREFEX . "city
										WHERE 1 = 1",
			array()
		);
		$ret = array();
		foreach ($x as $val) {
			$x = array(
				'ID'		=> $val['c_id'], 'NAME'		=> $val['c_name'], 'NAME_EN'	=> $val['c_name_EN'], 'NEIGHBOR'	=> $this->neighborhood($val['c_id'])
			);
			array_push($ret, $x);
		}
		return $ret;
	}

	//get neighborhood data
	public function neighborhood($city_id = 0)
	{
		$wh = "";
		$wh_array = array();
		if (!empty($city_id)) {
			$form	= new form();
			if ($form->single_valid($city_id, 'Integer')) {
				$wh = "nei_city = :ID AND ";
				$wh_array[':ID'] = $city_id;
			}
		}

		$x = $this->db->select(
			"SELECT nei_id, nei_name,nei_name_EN,nei_city  
										FROM " . DB_PREFEX . "neighborhood
										WHERE $wh 1 = 1",
			$wh_array
		);
		$ret = array();
		foreach ($x as $val) {
			$ret[$val['nei_id']] = array(
				'ID' => $val['nei_id'], 'NAME' => $val['nei_name'], 'NAME_EN' => $val['nei_name_EN'], 'CITY' => $val['nei_city']
			);
		}
		return array_values($ret);
	}

	//search lands
	public function search()
	{
		$form	= new form();

		$form->post('chat', false, true)

			->post('id', false, true)
			->valid('numeric')

			->post('city', false, true)
			->valid('numeric')

			->post('neighborhood', false, true)
			->valid('numeric')

			->post('block', false, true)
			->valid('numeric')

			->post('types', false, true)
			->valid('numeric')

			->post('adv', false, true)
			->valid('In_Array', array_keys(lib::$adv_type))

			->post('land_for', false, true)
			->valid('In_Array', array_keys(lib::$land_for))

			->post('rooms', false, true)
			->valid('numeric')
			->post('rooms_min', false, true)
			->valid('numeric')
			->post('rooms_max', false, true)
			->valid('numeric')

			->post('baths', false, true)
			->valid('numeric')
			->post('baths_max', false, true)
			->valid('numeric')
			->post('baths_min', false, true)
			->valid('numeric')

			->post('size', false, true)
			->valid('numeric')
			->post('size_max', false, true)
			->valid('numeric')
			->post('size_min', false, true)
			->valid('numeric')

			->post('floor', false, true)
			->valid('numeric')
			->post('floor_max', false, true)
			->valid('numeric')
			->post('floor_min', false, true)
			->valid('numeric')

			->post('interface', false, true)
			->valid('numeric')

			->post('corner', false, true)
			->valid('numeric')

			->post('price', false, true)
			->valid('numeric')
			->post('price_max', false, true)
			->valid('numeric')
			->post('price_min', false, true)
			->valid('numeric')

			->post('road_max', false, true)
			->valid('numeric')
			->post('road_min', false, true)
			->valid('numeric')

			->post('limit', false, true)
			->valid('numeric')

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG']);
		}

		$txt = "l_co = :CO_ID AND ";
		$limit = "";
		$arr = array(":CO_ID" => session::get('company'));

		if (!empty($fdata['id'])) {
			$txt .= "l_id = :ID AND ";
			$arr[':ID'] = $fdata['id'];
		} else {
			if (!empty($fdata['city'])) {
				$txt .= "c_id = :CITY AND ";
				$arr[':CITY'] = $fdata['city'];
			}
			//_________________________NEIGHBORHOOD
			if (!empty($fdata['neighborhood']) && !empty($fdata['neighbor_letter'])) {
				$txt .= "(l_neighborhood = :NEIGHBOR OR nei_letter = :LETT ) AND ";
				$arr[':NEIGHBOR'] = $fdata['neighborhood'];
				$arr[':LETT'] = $fdata['neighbor_letter'];
			} elseif (!empty($fdata['neighborhood'])) {
				$txt .= "l_neighborhood = :NEIGHBOR AND ";
				$arr[':NEIGHBOR'] = $fdata['neighborhood'];
			} elseif (!empty($fdata['neighbor_letter'])) {
				$txt .= "nei_letter = :LETT AND ";
				$arr[':LETT'] = $fdata['neighbor_letter'];
			}

			if (!empty($fdata['block'])) {
				$txt .= "l_block = :BLOCK AND ";
				$arr[':BLOCK'] = $fdata['block'];
			}
			if (!empty($fdata['adv'])) {
				$txt .= "l_adv LIKE :ADV AND ";
				$arr[':ADV'] = $fdata['adv'];
			}
			if (!empty($fdata['types'])) {
				$txt .= "l_type LIKE :TYPE AND ";
				$arr[':TYPE'] = $fdata['types'];
			}
			if (!empty($fdata['land_for'])) {
				$txt .= "l_for LIKE :LFOR AND ";
				$arr[':LFOR'] = $fdata['land_for'];
			}
			//_________________________ROOMS
			if (!empty($fdata['rooms'])) {
				$txt .= "l_rooms = :ROOMS AND ";
				$arr[':ROOMS'] = $fdata['rooms'];
			}
			if (!empty($fdata['rooms_min'])) {
				$txt .= "l_rooms >= :ROOMS_MIN AND ";
				$arr[':ROOMS_MIN'] = $fdata['rooms'];
			}
			if (!empty($fdata['rooms_max'])) {
				$txt .= "l_rooms <= :ROOMS_MAX AND ";
				$arr[':ROOMS_MAX'] = $fdata['rooms'];
			}
			//_________________________BATHS
			if (!empty($fdata['baths'])) {
				$txt .= "l_baths = :BATHS AND ";
				$arr[':BATHS'] = $fdata['baths'];
			}
			if (!empty($fdata['baths_min'])) {
				$txt .= "l_baths >= :BATHS_MIN AND ";
				$arr[':BATHS_MIN'] = $fdata['baths_min'];
			}
			if (!empty($fdata['baths_max'])) {
				$txt .= "l_baths <= :BATHS_MAX AND ";
				$arr[':BATHS_MAX'] = $fdata['baths_max'];
			}
			//_________________________SIZE
			if (!empty($fdata['size'])) {
				$txt .= "l_size = :SIZE AND ";
				$arr[':SIZE'] = $fdata['size'];
			}
			if (!empty($fdata['size_min'])) {
				$txt .= "l_size >= :SIZE_MIN AND ";
				$arr[':SIZE_MIN'] = $fdata['size_min'];
			}
			if (!empty($fdata['size_max'])) {
				$txt .= "l_size <= :SIZE_MAX AND ";
				$arr[':SIZE_MAX'] = $fdata['size_max'];
			}
			//_________________________FLOOR
			if (!empty($fdata['floor'])) {
				$txt .= "l_floor = :FLOOR AND ";
				$arr[':FLOOR'] = $fdata['floor'];
			}
			if (!empty($fdata['floor_min'])) {
				$txt .= "l_floor >= :FLOOR_MIN AND ";
				$arr[':FLOOR_MIN'] = $fdata['floor_min'];
			}
			if (!empty($fdata['floor_max'])) {
				$txt .= "l_floor <= :FLOOR_MAX AND ";
				$arr[':FLOOR_MAX'] = $fdata['floor_max'];
			}
			//_________________________PRICE
			if (!empty($fdata['price'])) {
				$txt .= "l_price = :PRICE AND ";
				$arr[':PRICE'] = $fdata['price'];
			}
			if (!empty($fdata['price_min'])) {
				$txt .= "l_price >= :PRICE_MIN AND ";
				$arr[':PRICE_MIN'] = $fdata['price_min'];
			}
			if (!empty($fdata['price_max'])) {
				$txt .= "l_price <= :PRICE_MAX AND ";
				$arr[':PRICE_MAX'] = $fdata['price_max'];
			}
			//_________________________ROAD
			if (!empty($fdata['road_min'])) {
				$txt .= "l_road >= :ROAD_MIN AND ";
				$arr[':ROAD_MIN'] = $fdata['road_min'];
			}
			if (!empty($fdata['road_max'])) {
				$txt .= "l_road <= :ROAD_MAX AND ";
				$arr[':ROAD_MAX'] = $fdata['road_max'];
			}
			//_________________________interface
			if (!empty($fdata["interface"])) {
				$txt .= "l_interface IS NOT NULL AND ";
			}
			//_________________________corner
			if (!empty($fdata["corner"])) {
				$txt .= "l_corner IS NOT NULL AND ";
			}
		}

		if (!empty($fdata["limit"])) {
			//$limit = "LIMIT ".$fdata["limit"];
		}

		//l_status = 1

		$lands =  $this->db->select("SELECT l_id, l_block, l_location
										,l_img, l_desc, l_size, l_baths ,l_adv, l_adv_no
										,l_rooms, l_interface, l_halls, " . DB_PREFEX . "land.create_at AS add_date
										,l_cars, l_floor, l_price, l_currency, l_corner
										,l_bulid_year, l_no, l_road, l_status, l_visit
										,l_tree, l_well, l_mushub, l_type, l_for, l_unit_num, l_unit_no
										,l_mortgage, l_law, l_info, l_disputes, l_condition
										,l_expered, l_delegate_file
										,l_duplex, l_append, l_monster, l_swim, l_kitchen
										,l_basement, l_elevator, l_server_room, l_dr_room
										,l_des_n, l_des_e, l_des_w, l_des_s
										,nei_id, nei_name, nei_name_EN, c_id, c_name, c_name_EN
										,co_id, co_name, co_name_en, co_package, co_phone, co_email, co_img
										,l_co_relation, l_co_delegat
										,lp_start, lp_end, lp_comment
										,count(bi_id) AS BILL
										FROM " . DB_PREFEX . "land
										JOIN " . DB_PREFEX . "company ON co_id = l_co AND co_active = 1 
										JOIN " . DB_PREFEX . "package ON co_package = pk_id  
										LEFT JOIN " . DB_PREFEX . "neighborhood ON l_neighborhood = nei_id
										LEFT JOIN " . DB_PREFEX . "city ON nei_city = c_id 
										LEFT JOIN " . DB_PREFEX . "land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
										LEFT JOIN " . DB_PREFEX . "bill ON bi_land = l_id
										WHERE $txt 1=1  
										GROUP BY l_id
										ORDER BY IF(lp_start IS NOT NULL,1,2) ASC
												,pk_stars DESC
												,l_id DESC
										$limit
									", $arr);
		$ret = array();

		foreach ($lands as $val) {
			$sing = array();
			$sing["ID"] 			= $val["l_id"];
			$sing["ADV"] 			= $val["l_adv"];
			$sing["ADV_NO"] 		= $val["l_adv_no"];
			$sing["ADV_NAME"] 		= lib::$adv_type[$val["l_adv"]];
			$sing["ACTIVE"] 		= $val["l_status"];
			$sing["NO"] 			= $val["l_no"];
			$sing["BLOCK"] 			= $val["l_block"];

			$sing["LOCATION"] 		= str_replace("&quot;", '"', $val["l_location"]);
			$sing["LOCATION"] 		= str_replace("quot;", '"', $sing["LOCATION"]);
			$sing["LOCATION"] 		= str_replace("&amp;", '', $sing["LOCATION"]);

			$sing["VISIT"] 			= $val["l_visit"];

			$sing["UNIT_NUM"] 		= $val["l_unit_num"];
			$sing["UNIT_NO"] 		= $val["l_unit_no"];

			$sing["MORTGAGE"] 		= $val["l_mortgage"]; // الرهن
			$sing["LAW"] 			= $val["l_law"]; // الحقوق والالترامات
			$sing["INFO"] 			= $val["l_info"]; // المعلومات التي تؤثر على العقار
			$sing["DISPUTES"] 		= $val["l_disputes"]; // النزاعات
			$sing["AIR_COND"] 		= $val["l_condition"]; // التكييف
			$sing["EXPERED"] 		= $val["l_expered"]; // انتهاء الصلاحية

			$sing["DES_N"] 			= $val["l_des_n"];
			$sing["DES_E"] 			= $val["l_des_e"];
			$sing["DES_W"] 			= $val["l_des_w"];
			$sing["DES_S"] 			= $val["l_des_s"];

			$sing["RELATION"] 		= $val["l_co_relation"];
			$sing["DELEGATION"] 	= $val["l_co_delegat"];

			$sing["BILL"] 			= $val["BILL"];

			$sing["TYPE"] 			= $val["l_type"];
			$sing["FOR"] 			= $val["l_for"];

			$sing["IMG"] 			= URL . "public/IMG/land/" . $val["l_id"] . "/" . $val["l_img"];
			$sing["DESC"] 			= $val["l_desc"];
			$sing["LIM_DESC"] 		= (strlen($val["l_desc"]) > 2000) ? substr($val["l_desc"], 0, 2000) . "..." : $val["l_desc"];

			if (strpos($val["l_size"], '-')) {
				$a = explode("-", $val["l_size"]);
				$sing["SIZE"] 			= $a[0];
				$sing["SIZE_M"] 		= $a[1];
			} else {
				$sing["SIZE"] 			= $val["l_size"];
			}
			$sing["M_SIZE"] 		= $val["l_size"];

			if (strpos($val["l_rooms"], '-')) {
				$a = explode("-", $val["l_rooms"]);
				$sing["ROOMS"] 			= $a[0];
				$sing["ROOMS_M"] 		= $a[1];
			} else {
				$sing["ROOMS"] 			= $val["l_rooms"];
			}

			$sing["INTERFACE"] 		= $val["l_interface"];
			$sing["HALLS"] 			= $val["l_halls"];

			if (strpos($val["l_baths"], '-')) {
				$a = explode("-", $val["l_baths"]);
				$sing["BATHS"] 			= $a[0];
				$sing["BATHS_M"] 		= $a[1];
			} else {
				$sing["BATHS"] 			= $val["l_baths"];
			}

			$sing["CARS"] 			= $val["l_cars"];
			$sing["FLOOR"] 			= $val["l_floor"];

			if (strpos($val["l_price"], '-')) {
				$a = explode("-", $val["l_price"]);
				$sing["PRICE"] 			= number_format($a[0], 2, '.', ',');
				$sing["PRICE_M"] 		= number_format($a[1], 2, '.', ',');
				$sing["M_PRICE"] 		= $a[0];
				$sing["M_PRICE_M"] 		= $a[1];
			} else {
				$sing["PRICE"] 			= number_format($val["l_price"], 2, '.', ',');
				$sing["UPD_PRICE"] 		= $val["l_price"];
			}

			$sing["CURRENCY"] 		= $val["l_currency"];
			$sing["CORNER"] 		= $val["l_corner"];
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

			if (strpos($val["l_tree"], '-')) {
				$a = explode("-", $val["l_tree"]);
				$sing["TREES"] 			= $a[0];
				$sing["TREES_M"] 		= $a[1];
			} else {
				$sing["TREES"] 			= $val["l_tree"];
			}

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
			$sing["OW_IMG"] 		= URL . "public/IMG/co/" . $val["co_img"];
			$sing["OW_LINK"] 		= URL . "dashboard/customer/" . $val["co_id"];

			if (!empty($val['l_delegate_file'])) {
				$sing['DELEGATE_FILE'] = URL . "public/IMG/land/" . $val["l_id"] . "/delegate/" . $val['l_delegate_file'];
				$sing['DELEGATE_FILE_NAME'] = $val['l_delegate_file'];
			} else {
				$sing['DELEGATE_FILE'] = "";
			}
			$dir 	= URL_PATH . "public/IMG/land/" . $val["l_id"] . "/";
			$link 	= URL . "public/IMG/land/" . $val["l_id"] . "/";
			$sing["OTHER_IMG"] 		= files::get_file_list($dir, $link);


			if ($fdata['chat']) {
				$sing['CHAT'] = $this->chatRoom($val["l_id"]);
			} //if chat

			$comp = $this->db->select(
				"SELECT adv_co AS ID FROM " . DB_PREFEX . "land_adv
														WHERE adv_land = :LAND",
				array(':LAND' => $val["l_id"])
			);
			$sing['COMPANY'] = array();
			foreach ($comp as $v) {
				array_push($sing['COMPANY'], $v['ID']);
			}
			array_push($ret, $sing);
		}

		return $ret;
	}

	//create new land REQ
	public function new_request()
	{
		$form	= new form();
		$form->post('request_map_data') 	// LOCATION
			->valid('Min_Length', 5)

			->post('request_desc') 		// DESC
			->valid('Min_Length', 5)

			->post('request_space_from', false, true) // Space from
			->valid('numeric')

			->post('request_space_to', false, true)	// Space to
			->valid('numeric')

			->post('request_type') 		// TYPE
			->valid('numeric')

			->post('request_for') 		// For
			->valid('In_Array', array_keys(lib::$land_for))

			->post('request_price_from') // PRICE From
			->valid('numeric')

			->post('request_price_to', false, true)	// PRICE TO
			->valid('numeric')

			->post('request_currency') 	// CURRENCY
			->valid('In_Array', array_keys(lib::$currency))

			->post('request_interface', false, true)	// INTERFACE
			->valid('In_Array', array_keys(lib::$land_interface))

			->post('request_corner', false, true)	// CORNER
			->valid('numeric')

			->post('request_road', false, true)	 	// ROAD
			->valid('numeric')

			->post('request_rooms_from', false, true)	 	// ROOMS
			->valid('numeric')

			->post('request_rooms_to', false, true)	 	// ROOMS
			->valid('numeric')

			->post('request_bath_from', false, true)	 	// BATHS
			->valid('numeric')

			->post('request_bath_to', false, true)	 	// BATHS
			->valid('numeric')

			->post('request_hall', false, true)	 	// HALLS
			->valid('numeric')

			->post('request_floor', false, true)	 	// FLOOR
			->valid('numeric')

			->post('request_unit_nun', false, true)	 // unit_num
			->valid('numeric')

			->post('request_car', false, true)	 	// CARS
			->valid('numeric')

			->post('request_duplex', false, true)
			->valid('numeric')

			->post('request_append', false, true)
			->valid('numeric')

			->post('request_basment', false, true)
			->valid('numeric')

			->post('request_monsters', false, true)
			->valid('numeric')

			->post('request_swim', false, true)
			->valid('numeric')

			->post('request_kitchen', false, true)
			->valid('numeric')

			->post('request_elevator', false, true)
			->valid('numeric')

			->post('request_ser_room', false, true)
			->valid('numeric')

			->post('request_dr_room', false, true)
			->valid('numeric')

			->post('request_air_cond', false, true)
			->valid('numeric')

			->post('request_well', false, true)
			->valid('numeric')

			->post('request_tree_from', false, true)
			->valid('numeric')

			->post('request_tree_to', false, true)
			->valid('numeric')

			->post('request_mushub', false, true)
			->valid('numeric')

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG'] . ":: " . $_POST['request_for']);
		}

		$ty = $this->db->select(
			"SELECT ty_id AS ID, ty_name AS NAME
									,ty_name_en AS NAME_EN ,ty_builed AS BUILD 
										FROM " . DB_PREFEX . "land_type
										WHERE ty_id = :ID",
			array(":ID" => $fdata['request_type'])
		);
		if (count($ty) != 1) {
			return array('Error' => "In Field new_type : لم يتم التعرف على نوع العقار ");
		}
		$ty = $ty[0];

		$conf = $this->db->select("SELECT conf_name , conf_val, update_at
									FROM " . DB_PREFEX . "config 
									WHERE conf_name like 'ADV_DAYS'
									", array());

		if (count($conf) != 1) {
			return array('Error' => "خطأ بالاعدادات الرجاء التواصل مع الادارة");
		}

		$m_time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($m_time);
		$exp	= dates::add_days($m_time, $conf[0]['conf_val']);
		$exp 	= dates::convert_to_string($exp);

		$pkg 	= array();
		$land 	= array();

		$land['l_adv'] 		= "REQ";
		$land['l_location'] = $fdata['request_map_data'];
		$land['l_desc'] 	= $fdata['request_desc'];
		$land['l_size'] 	= (empty($fdata['request_space_to'])) ? $fdata['request_space_from'] : $fdata['request_space_from'] . "-" . $fdata['request_space_to'];
		$land['l_co'] 		= session::get('company');
		$land['l_type'] 	= $fdata['request_type'];
		$land['l_for'] 		= $fdata['request_for'];
		$land['l_price'] 	= (empty($fdata['request_price_to'])) ? $fdata['request_price_from'] : $fdata['request_price_from'] . "-" . $fdata['request_price_to'];
		$land['l_currency'] = $fdata['request_currency'];
		$land['l_interface'] = $fdata['request_interface'];

		$land['l_corner']	= (!empty($fdata['request_corner'])) ? $fdata['request_corner'] : null;
		$land['l_road']		= (!empty($fdata['request_road'])) ? $fdata['request_road'] : null;

		$land['l_expered'] 		= $exp;
		$land['create_at'] 		= $time;
		$land['create_by'] 		= session::get('user_id');


		switch ($ty['BUILD']) {
			case 1: //building
				$land['l_rooms'] 		= (empty($fdata['request_rooms_to'])) ? $fdata['request_rooms_from'] : $fdata['request_rooms_from'] . "-" . $fdata['request_rooms_to'];
				$land['l_baths'] 		= (empty($fdata['request_bath_to'])) ? $fdata['request_bath_from'] : $fdata['request_bath_from'] . "-" . $fdata['request_bath_to'];
				$land['l_halls'] 		= (!empty($fdata['request_hall'])) ? $fdata['request_hall'] : null;
				$land['l_floor'] 		= (!empty($fdata['request_floor'])) ? $fdata['request_floor'] : null;
				$land['l_unit_num'] 	= (!empty($fdata['request_unit_nun'])) ? $fdata['request_unit_nun'] : 1;
				$land['l_cars'] 		= (!empty($fdata['request_car'])) ? $fdata['request_car'] : null;
				$land['l_duplex'] 		= (!empty($fdata['request_duplex'])) ? $fdata['request_duplex'] : null;
				$land['l_append'] 		= (!empty($fdata['request_append'])) ? $fdata['request_append'] : null;
				$land['l_basement'] 	= (!empty($fdata['request_basment'])) ? $fdata['request_basment'] : null;
				$land['l_monster'] 		= (!empty($fdata['request_monsters'])) ? $fdata['request_monsters'] : null;
				$land['l_swim'] 		= (!empty($fdata['request_swim'])) ? $fdata['request_swim'] : null;
				$land['l_kitchen'] 		= (!empty($fdata['request_kitchen'])) ? $fdata['request_kitchen'] : null;
				$land['l_elevator'] 	= (!empty($fdata['request_elevator'])) ? $fdata['request_elevator'] : null;
				$land['l_server_room'] 	= (!empty($fdata['request_ser_room'])) ? $fdata['request_ser_room'] : null;
				$land['l_dr_room'] 		= (!empty($fdata['request_dr_room'])) ? $fdata['request_dr_room'] : null;
				$land['l_condition'] 	= (!empty($fdata['request_air_cond'])) ? $fdata['request_air_cond'] : null;

				break;
			case 2: //Farm
				$land['l_well'] 		= (!empty($fdata['request_well'])) ? $fdata['request_well'] : null;
				$land['l_tree'] 		= (empty($fdata['request_tree_to'])) ? $fdata['request_tree_from'] : $fdata['request_tree_from'] . "" . $fdata['request_tree_to'];
				$land['l_mushub'] 		= (!empty($fdata['request_mushub'])) ? $fdata['request_mushub'] : null;

				break;
		}

		//insert
		$this->db->insert(DB_PREFEX . 'land', $land);
		$id = $this->db->LastInsertedId();

		$files	= new files();
		$main_img = 'default.png';

		if (!empty($id)) {
			if (!empty($_FILES['new_land_img'])) {
				if ($files->check_file($_FILES['new_land_img'])) {
					$main_img = $files->up_file($_FILES['new_land_img'], URL_PATH . 'public/IMG/land/' . $id);
					$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);
				}
				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			} else {
				$files->copy_file(URL_PATH . 'public/IMG/land/default.png', URL_PATH . 'public/IMG/land/' . $id, 'default.png');
			}

			$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);

			if (!empty($_FILES['new_file_image']) && count($_FILES['new_file_image']) != 0) {
				$file_array = $files->reArrayFiles($_FILES['new_file_image']);

				foreach ($file_array as $val) {
					if ($files->check_file($val)) {
						$x = $files->up_file($val, URL_PATH . 'public/IMG/land/' . $id);
					}
				}

				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			}
		}

		return array('id' => $id);
	}

	//Get land With API
	public function get_api_land()
	{
		$form	= new form();
		$form->post('new_id_type') 				// ID Type
			->valid('In_Array', array(1, 2))

			->post('new_id') 				    // ID No
			->valid('Min_Length', 5)

			->post('new_delegate') 				// ID /////////////////////////////////////////////////////
			->valid('Min_Length', 5)

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG'] . ":: " . $_POST['new_for']);
		}

		//Get API Data
		$result = lib::api_land($fdata['new_delegate'], $fdata["new_id"], $fdata["new_id_type"]);

		if (!empty($result["Header"]['Status']['Code']) && $result["Header"]['Status']['Code'] != 200) {

			return array('Error' => "Error Status Code");
		}
		$result = $result["Body"]["result"];

		if ($result["isValid"] != true && $result["isValid"] != "true") {
			return array('Error' => "Error Valid");
		}
		$result["advertisement"]["location"]["latitude"] =  floatval($result["advertisement"]["location"]["latitude"]) + 0.00000000000000010;
		$result["advertisement"]["location"]["longitude"] = floatval($result["advertisement"]["location"]["longitude"]) + 0.00000000000000010;
		//{"LATLNG":[{"LAT":24.113211069382405,"":38.01971928891122}]}
		$result["advertisement"]["LOCATION_A"] = array("LATLNG" => array(array("LAT" => $result["advertisement"]["location"]["latitude"], "LNG" => $result["advertisement"]["location"]["longitude"])));

		return $result["advertisement"];
	}

	//create new land API ADV
	public function new_api_land()
	{
		$form	= new form();
		$form->post('new_id_type') 				// ID Type
			->valid('In_Array', array(1, 2))

			->post('new_id') 				    // ID No
			->valid('Min_Length', 5)

			->post('new_delegate') 				// ID /////////////////////////////////////////////////////
			->valid('Min_Length', 5)

			->post('new_desc') 					// DESC
			->valid('Min_Length', 5)

			->post('new_relation') 				// Relation
			->valid('In_Array', array_keys(lib::$land_relation))

			->post('company', false, true)
			->valid_array('numeric')

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG'] . ":: " . $_POST['new_for']);
		}

		//Get AD_LINC
		$co = $this->db->select(
			"SELECT co_id AS ID, co_name AS NAME
									,co_id_type AS ID_TYPE, co_id_no AS ID_NO
									FROM " . DB_PREFEX . "company
									WHERE co_id = :CO",
			array(':CO' => session::get('company'))
		);
		if (count($co) != 1) {
			return array('Error' => "لم يتم العثور على بيانات الترخيص" . count($co));
		}
		$co = $co[0];

		//update ID Type and No
		if ($co['ID_TYPE'] != $fdata["new_id_type"] || $co['ID_NO'] != $fdata["new_id"]) {
			$upd = array("co_id_type" => $fdata["new_id_type"], "co_id_no" => $fdata["new_id"]);
			$this->db->update(DB_PREFEX . 'company', $upd, "co_id = " . session::get('company'));
			$co['ID_TYPE'] = $fdata["new_id_type"];
			$co['ID_NO'] = $fdata["new_id"];
		}

		$conf = $this->db->select("SELECT conf_name , conf_val, update_at
									FROM " . DB_PREFEX . "config 
									WHERE conf_name like 'ADV_DAYS'
									", array());

		if (count($conf) != 1) {
			return array('Error' => "خطأ بالاعدادات الرجاء التواصل مع الادارة");
		}

		//Get API Data
		$result = lib::api_land($fdata['new_delegate'], $co["ID_NO"], $co["ID_TYPE"]);

		if (!empty($result["Header"]['Status']['Code']) && $result["Header"]['Status']['Code'] != 200) {

			return array('Error' => "Error Status Code");
		}
		$result = $result["Body"]["result"];

		if ($result["isValid"] != true && $result["isValid"] != "true") {
			return array('Error' => "Error Valid");
		}

		$result = $result["advertisement"];

		$m_time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($m_time);
		$exp	= dates::add_days($m_time, $conf[0]['conf_val']);
		$exp 	= dates::convert_to_string($exp);

		$land = array();

		//check Regon
		$reg = $this->db->select("SELECT reg_id, reg_name
										FROM " . DB_PREFEX . "region
										WHERE reg_id = :ID 
									", array(":ID" => $result['location']['regionCode']));
		if (empty($reg)) {
			$region = array(
				"reg_id" => $result['location']['regionCode'], "reg_name" => $result['location']['region'], "reg_name_en" => $result['location']['region'], "create_at" => $time
			);
			$this->db->insert(DB_PREFEX . 'region', $region);
		}

		//check City
		$city = $this->db->select("SELECT c_id, c_region
										FROM " . DB_PREFEX . "city
										WHERE c_id = :ID 
									", array(":ID" => $result['location']['cityCode']));
		if (empty($city)) {
			$city = array(
				"c_id" => $result['location']['cityCode'], "c_name" => $result['location']['city'], "c_name_en" => $result['location']['city'], "c_region" => $result['location']['regionCode'], "create_at" => $time
			);
			$this->db->insert(DB_PREFEX . 'city', $city);
		}

		//check Neighborhood
		$nei = $this->db->select("SELECT nei_id, nei_city
										FROM " . DB_PREFEX . "neighborhood
										WHERE nei_id = :ID 
									", array(":ID" => $result['location']['cityCode']));
		if (empty($nei)) {
			$nei = array(
				"nei_id" => $result['location']['districtCode'], "nei_name" => $result['location']['district'], "nei_name_EN" => $result['location']['district'], "nei_city" => $result['location']['cityCode'], "create_at" => $time
			);
			$this->db->insert(DB_PREFEX . 'neighborhood', $nei);
		}

		//check type
		$ty = $this->db->select("SELECT ty_id, ty_name
										FROM " . DB_PREFEX . "land_type
										WHERE ty_name = :ID 
									", array(":ID" => $result['propertyType']));
		if (empty($ty)) {
			$ty = array(
				"ty_name" => $result['propertyType'], "ty_name_en" => $result['propertyType'], "ty_type" => "HUM", "create_at" => $time
			);
			$this->db->insert(DB_PREFEX . 'land_type', $ty);
			$type = $this->db->LastInsertedId();
		} else {
			$type = $ty[0]['ty_id'];
		}

		//Add Land
		$land['l_adv']          = "ADV";
		$land['l_neighborhood'] = $result['location']['districtCode'];
		$land['l_loc_lat']      = $result['location']['latitude'];
		$land['l_loc_lng']      = $result['location']['longitude'];
		$land['l_street']       = $result['location']['street'];
		$land['l_unit_no']      = $result['location']['additionalNumber'];
		$land['l_no']           = $result['location']['buildingNumber'];
		$land['l_block']        = $result['planNumber'];
		$land['l_street_width'] = $result['streetWidth'];

		$land['l_mortgage'] 	= (!empty($result['isPawned']) && $result['isPawned'] != 'false' && $result['isPawned'] != false) ? $result['isPawned'] : null;
		$land['l_law'] 	        = (!empty($result['isConstrained']) && $result['isConstrained'] != 'false' && $result['isConstrained'] != false) ? $result['isConstrained'] : null;
		$land['l_disputes'] 	= (!empty($result['obligationsOnTheProperty']) && $result['obligationsOnTheProperty'] != 'false' && $result['obligationsOnTheProperty'] != false) ? $result['obligationsOnTheProperty'] : null;



		$land['l_size']         = $result['propertyArea'];
		$land['l_price']        = $result['propertyPrice'];
		$land['l_m_price']      = $result['propertyPrice'];
		$land['l_currency']     = "SAR";
		$land['l_rooms']        = $result['numberOfRooms'];

		$land['l_type']         = $type;
		$land['l_co_relation'] 	= $fdata['new_relation'];
		$land['l_co'] 			= session::get('company');
		$land['l_adv_no']    	= $fdata['new_delegate'];
		$land['l_desc']    	    = $fdata['new_desc'];

		$land['l_for']          = ($result['advertisementType'] == "بيغ") ? "SALE" : "RENT_Y";
		$land['l_interface']    = array_search($result['propertyFace'], lib::$land_interface);
		$land['l_expered']      = $exp;
		$land['create_at'] 		= $time;
		$land['create_by'] 		= session::get('user_id');

		/*
			SELECT `l_id`, `l_block`,`l_unit_num`, ``, `l_visit`, `l_like`, `l_co_delegat`, `l_delegate_file`, 
			`l_img`, `l_size`, `l_halls`, `l_baths`, `l_cars`, `l_duplex`, `l_append`, `l_monster`, 
			`l_swim`, `l_corner`, `l_kitchen`, `l_basement`, `l_elevator`, `l_server_room`, `l_dr_room`, `l_floor`, `l_road`, `l_tree`, 
			`l_well`, `l_mushub`, ``, ``, `l_mortgage`, `l_law`, `l_info`, ``, `l_disputes`, `l_condition`, ``,
			`l_status`, `l_bulid_year`, `l_des_n`, `l_des_e`, `l_des_w`, `l_des_s` 
			FROM `real_land` WHERE 1
			*/

		//insert
		$this->db->insert(DB_PREFEX . 'land', $land);
		$id = $this->db->LastInsertedId();

		$files	= new files();
		$main_img = 'default.png';

		if (!empty($id)) {
			if (!empty($_FILES['new_land_img'])) {
				if ($files->check_file($_FILES['new_land_img'])) {
					$main_img = $files->up_file($_FILES['new_land_img'], URL_PATH . 'public/IMG/land/' . $id);
					$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);
				}
				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			} else {
				$files->copy_file(URL_PATH . 'public/IMG/land/default.png', URL_PATH . 'public/IMG/land/' . $id, 'default.png');
			}

			$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);

			if (!empty($_FILES['new_delegate_file'])) {
				if ($files->check_file($_FILES['new_delegate_file'])) {
					$main_img = $files->up_file($_FILES['new_delegate_file'], URL_PATH . 'public/IMG/land/' . $id . '/delegate');
					$this->db->update(DB_PREFEX . 'land', array("l_delegate_file" => $main_img), "l_id = " . $id);
				}
				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			}

			if (!empty($_FILES['new_file_image']) && count($_FILES['new_file_image']) != 0) {
				$file_array = $files->reArrayFiles($_FILES['new_file_image']);

				foreach ($file_array as $val) {
					if ($files->check_file($val)) {
						$x = $files->up_file($val, URL_PATH . 'public/IMG/land/' . $id);
					}
				}

				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			}

			//VIP adv
			if (!empty($fdata['company'])) {
				foreach ($fdata['company'] as $val) {
					$this->db->insert(DB_PREFEX . 'land_adv', array('adv_land' => $id, 'adv_co' => $val));
				}
			}
		}

		return array('id' => $id);
	}


	public function new_land2()
	{
		$form	= new form();
		$form->post('advertiser-id')->valid('numeric') // رقم هوية المعلن
			->post('ad-license-number')->valid('numeric') // رقم ترخيص الاعلان
			->post('deed-number')->valid('numeric') // رقم صك الملكية
			->post('advertiser-name')->valid('Min_Length', 5) // أسم المعلن
			->post('phone-number')->valid('numeric') // رقم الهاتف
			->post('brokerage-and-marketing-license-number')->valid('numeric') // رقم رخصة الوساطة والتسويق العقاري
			->post('is-constrained')->valid('numeric') // وجود قيد؟
			->post('is-pawned')->valid('numeric') // وجود رهن؟
			->post('street-width')->valid('numeric') // عرض الشارع
			->post('propertyArea')->valid('numeric') // المساحة
			->post('property-price')->valid('numeric') // سعر العقار
			->post('number-of-rooms')->valid('numeric') // عدد الغرف
			->post('property-type')->valid('numeric') // نوع العقار
			->post('property-age')->valid('numeric') // عمر العقار
			->post('advertisement-type')->valid('numeric') // نوع الإعلان
			->post('region')->valid('numeric') // المنطقة
			->post('region-code')->valid('numeric') // كود المنطقة
			->post('city')->valid('numeric') // المدينة
			->post('city-code')->valid('numeric') // كود المدينة
			->post('district')->valid('numeric') // الحي
			->post('district-code')->valid('numeric') // كود الحي
			->post('street')->valid('numeric') // الشارع
			->post('postal-code')->valid('numeric') // الرمز البريدي
			->post('building-number')->valid('numeric') // رقم المبنى
			->post('additional-number')->valid('numeric') // الرقم الإضافي
			->post('longitude')->valid('numeric') // خط الطول
			->post('latitude')->valid('numeric') // خط العرض
			->post('property-face')->valid('numeric') // واجهة العقار
			->post('plan-number')->valid('numeric') // رقم المخطط
			->post('obligations-on-the-property')->valid('numeric') // الالتزامات على العقار
			->post('guarantees-and-their-duration')->valid('numeric') // الضمانات ومدتها
			->post('the-borders-and-lengths-of-the-property')->valid('numeric') // الحدود والأطوال للعقار
			->post('compliance-with-the-saudi-building-code')->valid('numeric') // مطابقة كود البناء السعودي
			->post('property-utilities')->valid('array')
			->post('channels')->valid('array')
			->post('property-usages')->valid('array');

		$form->submit();


		$fdata	= $form->fetch();

		//check for errors
		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG'] . ":: " . $_POST['new_for']);
		}

		$conf = $this->db->select("SELECT conf_name , conf_val, update_at
									FROM " . DB_PREFEX . "config 
									WHERE conf_name like 'ADV_DAYS'
									", array());

		if (count($conf) != 1) {
			return array('Error' => "خطأ بالاعدادات الرجاء التواصل مع الادارة");
		}

		$m_time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($m_time);
		$exp	= dates::add_days($m_time, $conf[0]['conf_val']);
		$exp 	= dates::convert_to_string($exp);

		$pkg 	= array();
		$land 	= array();

		$land['l_co'] 			= session::get('company');
		$land['l_adv'] 			= 'ADV';
		$land['create_at'] 		= $time;
		$land['create_by'] 		= session::get('user_id');
		$land['l_expered'] 		= $exp;
		$land['advertiser-id'] = (!empty($fdata['advertiser-id'])) ? $fdata['advertiser-id'] : null;
		$land['ad-license-number'] = (!empty($fdata['ad-license-number'])) ? $fdata['ad-license-number'] : null;
		$land['deed-number'] = (!empty($fdata['deed-number'])) ? $fdata['deed-number'] : null;
		$land['advertiser-name'] = (!empty($fdata['advertiser-name']) && strlen($fdata['advertiser-name']) >= 5) ? $fdata['advertiser-name'] : null;
		$land['phone-number'] = (!empty($fdata['phone-number'])) ? $fdata['phone-number'] : null;
		$land['brokerage-and-marketing-license-number'] = (!empty($fdata['brokerage-and-marketing-license-number'])) ? $fdata['brokerage-and-marketing-license-number'] : null;
		$land['is-constrained'] = (!empty($fdata['is-constrained'])) ? $fdata['is-constrained'] : null;
		$land['is-pawned'] = (!empty($fdata['is-pawned'])) ? $fdata['is-pawned'] : null;
		$land['street-width'] = (!empty($fdata['street-width'])) ? $fdata['street-width'] : null;
		$land['propertyArea'] = (!empty($fdata['propertyArea'])) ? $fdata['propertyArea'] : null;
		$land['property-price'] = (!empty($fdata['property-price'])) ? $fdata['property-price'] : null;
		$land['number-of-rooms'] = (!empty($fdata['number-of-rooms'])) ? $fdata['number-of-rooms'] : null;
		$land['property-type'] = (!empty($fdata['property-type'])) ? $fdata['property-type'] : null;
		$land['property-age'] = (!empty($fdata['property-age'])) ? $fdata['property-age'] : null;
		$land['advertisement-type'] = (!empty($fdata['advertisement-type'])) ? $fdata['advertisement-type'] : null;
		$land['region'] = (!empty($fdata['region'])) ? $fdata['region'] : null;
		$land['region-code'] = (!empty($fdata['region-code'])) ? $fdata['region-code'] : null;
		$land['city'] = (!empty($fdata['city'])) ? $fdata['city'] : null;
		$land['city-code'] = (!empty($fdata['city-code'])) ? $fdata['city-code'] : null;
		$land['district'] = (!empty($fdata['district'])) ? $fdata['district'] : null;
		$land['district-code'] = (!empty($fdata['district-code'])) ? $fdata['district-code'] : null;
		$land['street'] = (!empty($fdata['street'])) ? $fdata['street'] : null;
		$land['postal-code'] = (!empty($fdata['postal-code'])) ? $fdata['postal-code'] : null;
		$land['building-number'] = (!empty($fdata['building-number'])) ? $fdata['building-number'] : null;
		$land['additional-number'] = (!empty($fdata['additional-number'])) ? $fdata['additional-number'] : null;
		$land['longitude'] = (!empty($fdata['longitude'])) ? $fdata['longitude'] : null;
		$land['latitude'] = (!empty($fdata['latitude'])) ? $fdata['latitude'] : null;
		$land['property-face'] = (!empty($fdata['property-face'])) ? $fdata['property-face'] : null;
		$land['plan-number'] = (!empty($fdata['plan-number'])) ? $fdata['plan-number'] : null;
		$land['obligations-on-the-property'] = (!empty($fdata['obligations-on-the-property'])) ? $fdata['obligations-on-the-property'] : null;
		$land['guarantees-and-their-duration'] = (!empty($fdata['guarantees-and-their-duration'])) ? $fdata['guarantees-and-their-duration'] : null;
		$land['the-borders-and-lengths-of-the-property'] = (!empty($fdata['the-borders-and-lengths-of-the-property'])) ? $fdata['the-borders-and-lengths-of-the-property'] : null;
		$land['compliance-with-the-saudi-building-code'] = (!empty($fdata['compliance-with-the-saudi-building-code'])) ? $fdata['compliance-with-the-saudi-building-code'] : null;
		$land['property-utilities'] = (!empty($fdata['property-utilities']) && is_array($fdata['property-utilities'])) ? $fdata['property-utilities'] : null;
		$land['channels'] = (!empty($fdata['channels']) && is_array($fdata['channels'])) ? $fdata['channels'] : null;
		$land['property-usages'] = (!empty($fdata['property-usages']) && is_array($fdata['property-usages'])) ? $fdata['property-usages'] : null;


		//insert
		$this->db->insert(DB_PREFEX . 'land2', $land);
		$id = $this->db->LastInsertedId();

		$files	= new files();
		$main_img = 'default.png';

		if (!empty($id)) {
			if (!empty($_FILES['new_land_img'])) {
				if ($files->check_file($_FILES['new_land_img'])) {
					$main_img = $files->up_file($_FILES['new_land_img'], URL_PATH . 'public/IMG/land/' . $id);
					$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);
				}
				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			} else {
				$files->copy_file(URL_PATH . 'public/IMG/land/default.png', URL_PATH . 'public/IMG/land/' . $id, 'default.png');
			}

			$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);

			if (!empty($_FILES['new_delegate_file'])) {
				if ($files->check_file($_FILES['new_delegate_file'])) {
					$main_img = $files->up_file($_FILES['new_delegate_file'], URL_PATH . 'public/IMG/land/' . $id . '/delegate');
					$this->db->update(DB_PREFEX . 'land', array("l_delegate_file" => $main_img), "l_id = " . $id);
				}
				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			}

			if (!empty($_FILES['new_file_image']) && count($_FILES['new_file_image']) != 0) {
				$file_array = $files->reArrayFiles($_FILES['new_file_image']);

				foreach ($file_array as $val) {
					if ($files->check_file($val)) {
						$x = $files->up_file($val, URL_PATH . 'public/IMG/land/' . $id);
					}
				}

				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			}

			//VIP adv
			if (!empty($fdata['company'])) {
				foreach ($fdata['company'] as $val) {
					$this->db->insert(DB_PREFEX . 'land_adv', array('adv_land' => $id, 'adv_co' => $val));
				}
			}
		}

		return array('id' => $id);
	}
	//create new land ADV
	public function new_land()
	{
		$form	= new form();
		$form->post('advertiser-id')->valid('numeric') // رقم هوية المعلن
			->post('ad-license-number')->valid('numeric') // رقم ترخيص الاعلان
			->post('deed-number')->valid('numeric') // رقم صك الملكية
			->post('advertiser-name')->valid('Min_Length', 5) // أسم المعلن
			->post('phone-number')->valid('numeric') // رقم الهاتف
			->post('brokerage-and-marketing-license-number')->valid('numeric') // رقم رخصة الوساطة والتسويق العقاري
			->post('is-constrained')->valid('numeric') // وجود قيد؟
			->post('is-pawned')->valid('numeric') // وجود رهن؟
			->post('street-width')->valid('numeric') // عرض الشارع
			->post('propertyArea')->valid('numeric') // المساحة
			->post('property-price')->valid('numeric') // سعر العقار
			->post('number-of-rooms')->valid('numeric') // عدد الغرف
			->post('property-type')->valid('numeric') // نوع العقار
			->post('property-age')->valid('numeric') // عمر العقار
			->post('advertisement-type')->valid('numeric') // نوع الإعلان
			->post('region')->valid('numeric') // المنطقة
			->post('region-code')->valid('numeric') // كود المنطقة
			->post('city')->valid('numeric') // المدينة
			->post('city-code')->valid('numeric') // كود المدينة
			->post('district')->valid('numeric') // الحي
			->post('district-code')->valid('numeric') // كود الحي
			->post('street')->valid('numeric') // الشارع
			->post('postal-code')->valid('numeric') // الرمز البريدي
			->post('building-number')->valid('numeric') // رقم المبنى
			->post('additional-number')->valid('numeric') // الرقم الإضافي
			->post('longitude')->valid('numeric') // خط الطول
			->post('latitude')->valid('numeric') // خط العرض
			->post('property-face')->valid('numeric') // واجهة العقار
			->post('plan-number')->valid('numeric') // رقم المخطط
			->post('obligations-on-the-property')->valid('numeric') // الالتزامات على العقار
			->post('guarantees-and-their-duration')->valid('numeric') // الضمانات ومدتها
			->post('the-borders-and-lengths-of-the-property')->valid('numeric') // الحدود والأطوال للعقار
			->post('compliance-with-the-saudi-building-code')->valid('numeric') // مطابقة كود البناء السعودي
			->post('property-utilities')->valid('array')
			->post('channels')->valid('array')
			->post('property-usages')->valid('array');

		$form->submit();


		$fdata	= $form->fetch();

		//check for errors
		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG'] . ":: " . $_POST['new_for']);
		}

		$conf = $this->db->select("SELECT conf_name , conf_val, update_at
									FROM " . DB_PREFEX . "config 
									WHERE conf_name like 'ADV_DAYS'
									", array());

		if (count($conf) != 1) {
			return array('Error' => "خطأ بالاعدادات الرجاء التواصل مع الادارة");
		}

		$m_time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($m_time);
		$exp	= dates::add_days($m_time, $conf[0]['conf_val']);
		$exp 	= dates::convert_to_string($exp);

		$pkg 	= array();
		$land 	= array();

		$land['l_co'] 			= session::get('company');
		$land['l_adv'] 			= 'ADV';
		$land['create_at'] 		= $time;
		$land['create_by'] 		= session::get('user_id');
		$land['l_expered'] 		= $exp;
		$land['advertiser-id'] = (!empty($fdata['advertiser-id'])) ? $fdata['advertiser-id'] : null;
		$land['ad-license-number'] = (!empty($fdata['ad-license-number'])) ? $fdata['ad-license-number'] : null;
		$land['deed-number'] = (!empty($fdata['deed-number'])) ? $fdata['deed-number'] : null;
		$land['advertiser-name'] = (!empty($fdata['advertiser-name']) && strlen($fdata['advertiser-name']) >= 5) ? $fdata['advertiser-name'] : null;
		$land['phone-number'] = (!empty($fdata['phone-number'])) ? $fdata['phone-number'] : null;
		$land['brokerage-and-marketing-license-number'] = (!empty($fdata['brokerage-and-marketing-license-number'])) ? $fdata['brokerage-and-marketing-license-number'] : null;
		$land['is-constrained'] = (!empty($fdata['is-constrained'])) ? $fdata['is-constrained'] : null;
		$land['is-pawned'] = (!empty($fdata['is-pawned'])) ? $fdata['is-pawned'] : null;
		$land['street-width'] = (!empty($fdata['street-width'])) ? $fdata['street-width'] : null;
		$land['propertyArea'] = (!empty($fdata['propertyArea'])) ? $fdata['propertyArea'] : null;
		$land['property-price'] = (!empty($fdata['property-price'])) ? $fdata['property-price'] : null;
		$land['number-of-rooms'] = (!empty($fdata['number-of-rooms'])) ? $fdata['number-of-rooms'] : null;
		$land['property-type'] = (!empty($fdata['property-type'])) ? $fdata['property-type'] : null;
		$land['property-age'] = (!empty($fdata['property-age'])) ? $fdata['property-age'] : null;
		$land['advertisement-type'] = (!empty($fdata['advertisement-type'])) ? $fdata['advertisement-type'] : null;
		$land['region'] = (!empty($fdata['region'])) ? $fdata['region'] : null;
		$land['region-code'] = (!empty($fdata['region-code'])) ? $fdata['region-code'] : null;
		$land['city'] = (!empty($fdata['city'])) ? $fdata['city'] : null;
		$land['city-code'] = (!empty($fdata['city-code'])) ? $fdata['city-code'] : null;
		$land['district'] = (!empty($fdata['district'])) ? $fdata['district'] : null;
		$land['district-code'] = (!empty($fdata['district-code'])) ? $fdata['district-code'] : null;
		$land['street'] = (!empty($fdata['street'])) ? $fdata['street'] : null;
		$land['postal-code'] = (!empty($fdata['postal-code'])) ? $fdata['postal-code'] : null;
		$land['building-number'] = (!empty($fdata['building-number'])) ? $fdata['building-number'] : null;
		$land['additional-number'] = (!empty($fdata['additional-number'])) ? $fdata['additional-number'] : null;
		$land['longitude'] = (!empty($fdata['longitude'])) ? $fdata['longitude'] : null;
		$land['latitude'] = (!empty($fdata['latitude'])) ? $fdata['latitude'] : null;
		$land['property-face'] = (!empty($fdata['property-face'])) ? $fdata['property-face'] : null;
		$land['plan-number'] = (!empty($fdata['plan-number'])) ? $fdata['plan-number'] : null;
		$land['obligations-on-the-property'] = (!empty($fdata['obligations-on-the-property'])) ? $fdata['obligations-on-the-property'] : null;
		$land['guarantees-and-their-duration'] = (!empty($fdata['guarantees-and-their-duration'])) ? $fdata['guarantees-and-their-duration'] : null;
		$land['the-borders-and-lengths-of-the-property'] = (!empty($fdata['the-borders-and-lengths-of-the-property'])) ? $fdata['the-borders-and-lengths-of-the-property'] : null;
		$land['compliance-with-the-saudi-building-code'] = (!empty($fdata['compliance-with-the-saudi-building-code'])) ? $fdata['compliance-with-the-saudi-building-code'] : null;
		$land['property-utilities'] = (!empty($fdata['property-utilities']) && is_array($fdata['property-utilities'])) ? $fdata['property-utilities'] : null;
		$land['channels'] = (!empty($fdata['channels']) && is_array($fdata['channels'])) ? $fdata['channels'] : null;
		$land['property-usages'] = (!empty($fdata['property-usages']) && is_array($fdata['property-usages'])) ? $fdata['property-usages'] : null;


		//insert
		$this->db->insert(DB_PREFEX . 'land', $land);
		$id = $this->db->LastInsertedId();

		$files	= new files();
		$main_img = 'default.png';

		if (!empty($id)) {
			if (!empty($_FILES['new_land_img'])) {
				if ($files->check_file($_FILES['new_land_img'])) {
					$main_img = $files->up_file($_FILES['new_land_img'], URL_PATH . 'public/IMG/land/' . $id);
					$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);
				}
				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			} else {
				$files->copy_file(URL_PATH . 'public/IMG/land/default.png', URL_PATH . 'public/IMG/land/' . $id, 'default.png');
			}

			$this->db->update(DB_PREFEX . 'land', array("l_img" => $main_img), "l_id = " . $id);

			if (!empty($_FILES['new_delegate_file'])) {
				if ($files->check_file($_FILES['new_delegate_file'])) {
					$main_img = $files->up_file($_FILES['new_delegate_file'], URL_PATH . 'public/IMG/land/' . $id . '/delegate');
					$this->db->update(DB_PREFEX . 'land', array("l_delegate_file" => $main_img), "l_id = " . $id);
				}
				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			}

			if (!empty($_FILES['new_file_image']) && count($_FILES['new_file_image']) != 0) {
				$file_array = $files->reArrayFiles($_FILES['new_file_image']);

				foreach ($file_array as $val) {
					if ($files->check_file($val)) {
						$x = $files->up_file($val, URL_PATH . 'public/IMG/land/' . $id);
					}
				}

				if (!empty($files->error_message)) {
					return array('Error' => $files->error_message);
				}
			}

			//VIP adv
			if (!empty($fdata['company'])) {
				foreach ($fdata['company'] as $val) {
					$this->db->insert(DB_PREFEX . 'land_adv', array('adv_land' => $id, 'adv_co' => $val));
				}
			}
		}

		return array('id' => $id);
	}

	//delete land image
	public function del_img()
	{
		$form	= new form();

		$form->post('id') // id
			->valid('numeric')

			->post('img') // img
			->valid('Min_Length', 3)

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return 'Error: ' . $fdata['MSG'];
		}

		$files	= new files();
		$file_path = URL_PATH . "public/IMG/land/" . $fdata['id'] . "/" . $fdata['img'];
		$files->del_file($file_path);

		return array("ok" => 1);
	}

	//update upd_request
	public function upd_request()
	{
		$form	= new form();

		$form->post('id') 						// ID
			->valid('numeric')

			->post('upd_request_map_data') 		// LOCATION
			//->valid('Min_Length',5)

			->post('upd_request_type') 			// TYPE
			->valid('numeric')

			->post('upd_request_for') 			// For
			->valid('In_Array', array_keys(lib::$land_for))

			->post('upd_request_desc') 			// DESC
			->valid('Min_Length', 5)

			->post('upd_request_space_from')	// Space
			->valid('numeric')

			->post('upd_request_space_to', false, true)	// Space
			->valid('numeric')

			->post('upd_request_price_from')			// PRICE
			->valid('numeric')

			->post('upd_request_price_to')				// PRICE
			->valid('numeric')

			->post('upd_request_currency') 				// CURRENCY
			->valid('In_Array', array_keys(lib::$currency))

			->post('upd_request_interface', false, true)	// INTERFACE
			->valid('In_Array', array_keys(lib::$land_interface))

			->post('upd_request_rooms_from', false, true)	 	// ROOMS
			->valid('numeric')

			->post('upd_request_rooms_to', false, true)	 	// ROOMS
			->valid('numeric')

			->post('upd_request_well', false, true)
			->valid('numeric')

			->post('upd_request_mushub', false, true)
			->valid('numeric')

			->post('upd_request_tree_from', false, true)
			->valid('numeric')

			->post('upd_request_tree_to', false, true)
			->valid('numeric')

			->post('upd_request_hall', false, true)	 	// HALLS
			->valid('numeric')

			->post('upd_request_bath_from', false, true)	 	// BATHS
			->valid('numeric')

			->post('upd_request_bath_to', false, true)	 	// BATHS
			->valid('numeric')

			->post('upd_request_floor', false, true)	 	// FLOOR
			->valid('numeric')

			->post('upd_request_road', false, true)	 	// ROAD
			->valid('numeric')

			->post('upd_request_unit_nun', false, true)	 // unit_num
			->valid('numeric')

			->post('upd_request_car', false, true)	 	// CARS
			->valid('numeric')

			->post('upd_request_duplex', false, true)
			->valid('numeric')

			->post('upd_request_corner', false, true)		// CORNER
			->valid('numeric')

			->post('upd_request_append', false, true)
			->valid('numeric')

			->post('upd_request_basment', false, true)
			->valid('numeric')

			->post('upd_request_monsters', false, true)
			->valid('numeric')

			->post('upd_request_swim', false, true)
			->valid('numeric')

			->post('upd_request_kitchen', false, true)
			->valid('numeric')

			->post('upd_request_elevator', false, true)
			->valid('numeric')

			->post('upd_request_ser_room', false, true)
			->valid('numeric')

			->post('upd_request_dr_room', false, true)
			->valid('numeric')

			->post('upd_request_air_cond', false, true)
			->valid('numeric')



			->post('upd_mortage', false, true) 	// الرهن
			->valid('Min_Length', 5)

			->post('upd_law', false, true) 	// الحقوق والالترامات
			->valid('Min_Length', 5)

			->post('upd_info', false, true) 	// المعلومات التي تؤثر على العقار
			->valid('Min_Length', 5)

			->post('upd_dispute', false, true) 	// النزاعات
			->valid('Min_Length', 5)

			->post('upd_des_n', false, true)
			->valid('numeric')

			->post('upd_des_e', false, true)
			->valid('numeric')

			->post('upd_des_w', false, true)
			->valid('numeric')

			->post('upd_des_s', false, true)
			->valid('numeric')

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG']);
		}

		$conf = $this->db->select("SELECT conf_name , conf_val, update_at
									FROM " . DB_PREFEX . "config 
									WHERE conf_name like 'ADV_DAYS'
									", array());

		if (count($conf) != 1) {
			return array('Error' => "خطأ بالاعدادات الرجاء التواصل مع الادارة");
		}

		$m_time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($m_time);
		$exp	= dates::add_days($m_time, $conf[0]['conf_val']);
		$exp 	= dates::convert_to_string($exp);

		$land 	= array();

		$land['l_location'] = $fdata['upd_request_map_data'];
		$land['l_type'] 	= $fdata['upd_request_type'];
		$land['l_for'] 		= $fdata['upd_request_for'];
		$land['l_desc'] 	= $fdata['upd_request_desc'];
		$land['l_size'] 	= (empty($fdata['upd_request_space_to'])) ? $fdata['upd_request_space_from'] : $fdata['upd_request_space_from'] . "-" . $fdata['upd_request_space_to'];;
		$land['l_price'] 	= (empty($fdata['upd_request_price_to'])) ? $fdata['upd_request_price_from'] : $fdata['upd_request_price_from'] . "-" . $fdata['upd_request_price_to'];;
		$land['l_currency'] = $fdata['upd_request_currency'];
		$land['l_expered'] 	= $exp;
		$land['update_at'] 	= $time;
		$land['update_by'] 	= session::get('user_id');
		$land['l_interface'] = (!empty($fdata['upd_request_interface'])) ? $fdata['upd_request_interface'] : null;
		$land['l_rooms'] 	= (empty($fdata['upd_request_rooms_to'])) ? $fdata['upd_request_rooms_from'] : $fdata['upd_request_rooms_from'] . "-" . $fdata['upd_request_rooms_to'];;
		$land['l_well'] 	= (!empty($fdata['upd_request_well'])) ? $fdata['upd_request_well'] : null;
		$land['l_mushub'] 	= (!empty($fdata['upd_request_mushub'])) ? $fdata['upd_request_mushub'] : null;
		$land['l_tree'] 	= (empty($fdata['upd_request_tree_to'])) ? $fdata['upd_request_tree_from'] : $fdata['upd_request_tree_from'] . "-" . $fdata['upd_request_tree_to'];;
		$land['l_halls'] 	= (!empty($fdata['upd_request_hall'])) ? $fdata['upd_request_hall'] : null;
		$land['l_baths'] 	= (empty($fdata['upd_request_bath_to'])) ? $fdata['upd_request_bath_from'] : $fdata['upd_request_bath_from'] . "-" . $fdata['upd_request_bath_to'];;
		$land['l_floor'] 	= (!empty($fdata['upd_request_floor'])) ? $fdata['upd_request_floor'] : null;
		$land['l_road'] 	= (!empty($fdata['upd_request_road'])) ? $fdata['upd_request_road'] : null;
		$land['l_unit_num'] = (!empty($fdata['upd_request_unit_nun'])) ? $fdata['upd_request_unit_nun'] : 1;
		$land['l_cars'] 	= (!empty($fdata['upd_request_car'])) ? $fdata['upd_request_car'] : null;
		$land['l_duplex'] 	= (!empty($fdata['upd_request_duplex'])) ? $fdata['upd_request_duplex'] : null;
		$land['l_corner'] 	= (!empty($fdata['upd_request_corner'])) ? $fdata['upd_request_corner'] : null;
		$land['l_append'] 	= (!empty($fdata['upd_request_append'])) ? $fdata['upd_request_append'] : null;
		$land['l_basement'] = (!empty($fdata['upd_request_basment'])) ? $fdata['upd_request_basment'] : null;
		$land['l_monster'] 	= (!empty($fdata['upd_request_monsters'])) ? $fdata['upd_request_monsters'] : null;
		$land['l_swim'] 	= (!empty($fdata['upd_request_swim'])) ? $fdata['upd_request_swim'] : null;
		$land['l_kitchen'] 	= (!empty($fdata['upd_request_kitchen'])) ? $fdata['upd_request_kitchen'] : null;
		$land['l_elevator'] = (!empty($fdata['upd_request_elevator'])) ? $fdata['upd_request_elevator'] : null;
		$land['l_server_room'] = (!empty($fdata['upd_request_ser_room'])) ? $fdata['upd_request_ser_room'] : null;
		$land['l_dr_room'] 	= (!empty($fdata['upd_request_dr_room'])) ? $fdata['upd_request_dr_room'] : null;
		$land['l_condition'] = (!empty($fdata['upd_request_air_cond'])) ? $fdata['upd_request_air_cond'] : null;

		//update
		$this->db->update(DB_PREFEX . 'land', $land, "l_id = " . $fdata['id']);

		return array('id' => $fdata['id']);
	}

	//update land
	public function upd_land()
	{
		$form	= new form();

		$form->post('id') 						// ID
			->valid('numeric')

			->post('upd_block') 				// BLOCK
			->valid('numeric')

			->post('upd_location') 		// LOCATION
			//->valid('Min_Length',5)

			->post('upd_relation') 				// Relation
			->valid('In_Array', array_keys(lib::$land_relation))

			->post('upd_delegate', false, true) 	// Delegation
			->valid('Min_Length', 5)

			->post('upd_type') 					// TYPE
			->valid('numeric')

			->post('upd_for') 					// For
			->valid('In_Array', array_keys(lib::$land_for))

			->post('upd_desc') 					// DESC
			->valid('Min_Length', 5)

			->post('upd_no') 					// NO
			->valid('numeric')

			->post('upd_space')	 				// Space
			->valid('numeric')

			->post('upd_rooms', false, true)	 	// ROOMS
			->valid('numeric')

			->post('upd_bath', false, true)	 	// BATHS
			->valid('numeric')

			->post('upd_hall', false, true)	 	// HALLS
			->valid('numeric')

			->post('upd_floor', false, true)	 	// FLOOR
			->valid('numeric')

			->post('upd_road', false, true)	 	// ROAD
			->valid('numeric')

			->post('upd_car', false, true)	 	// CARS
			->valid('numeric')

			->post('upd_price')					// PRICE
			->valid('numeric')

			->post('upd_unit_no', false, true)	 // UNit_no
			->valid('numeric')

			->post('upd_unit_nun', false, true)	 // unit_num
			->valid('numeric')

			->post('upd_currency') 				// CURRENCY
			->valid('In_Array', array_keys(lib::$currency))

			->post('upd_year', false, true)		// YEAR
			->valid('Date')

			->post('upd_corner', false, true)		// CORNER
			->valid('numeric')

			->post('upd_interface', false, true)	// INTERFACE
			->valid('In_Array', array_keys(lib::$land_interface))

			->post('upd_duplex', false, true)
			->valid('numeric')

			->post('upd_append', false, true)
			->valid('numeric')

			->post('upd_basment', false, true)
			->valid('numeric')

			->post('upd_monsters', false, true)
			->valid('numeric')

			->post('upd_swim', false, true)
			->valid('numeric')

			->post('upd_kitchen', false, true)
			->valid('numeric')

			->post('upd_elevator', false, true)
			->valid('numeric')

			->post('upd_ser_room', false, true)
			->valid('numeric')

			->post('upd_dr_room', false, true)
			->valid('numeric')

			->post('upd_well', false, true)
			->valid('numeric')

			->post('upd_tree', false, true)
			->valid('numeric')

			->post('upd_mushub', false, true)
			->valid('numeric')

			->post('upd_air_cond', false, true)
			->valid('numeric')

			->post('upd_mortage', false, true) 	// الرهن
			->valid('Min_Length', 5)

			->post('upd_law', false, true) 	// الحقوق والالترامات
			->valid('Min_Length', 5)

			->post('upd_info', false, true) 	// المعلومات التي تؤثر على العقار
			->valid('Min_Length', 5)

			->post('upd_dispute', false, true) 	// النزاعات
			->valid('Min_Length', 5)

			->post('upd_des_n', false, true)
			->valid('numeric')

			->post('upd_des_e', false, true)
			->valid('numeric')

			->post('upd_des_w', false, true)
			->valid('numeric')

			->post('upd_des_s', false, true)
			->valid('numeric')

			->post('company', false, true)
			->valid_array('numeric')

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG']);
		}

		//$fdata['upd_location'] = $_POST['upd_location'];

		$f_location = str_replace('quot;', '"', $fdata['upd_location']);
		$f_location  = str_replace('&', '', $f_location);
		$f_location  = str_replace('amp;', '', $f_location);
		$f_location  = str_replace("'", '"', $f_location);
		$f_location  = json_decode($f_location, true);

		$area_info = $this->location_info($f_location['LATLNG'][0]['LAT'], $f_location['LATLNG'][0]['LNG']);

		$neighborhood = $area_info['NEIG'];

		//check ID
		$x = $this->db->select(
			"SELECT l_id FROM " . DB_PREFEX . "land
										WHERE l_id!= :LID AND l_no = :NO AND l_neighborhood = :ID AND l_block = :BLOCK",
			array(
				":NO" => $fdata["upd_no"], ":LID" => $fdata["id"], ":ID" => $neighborhood, ":BLOCK" => $fdata["upd_block"]
			)
		);
		if (count($x) != 0) {
			return array('Error' => "In Field upd_no : Duplicate .. \n ");
		}

		$conf = $this->db->select("SELECT conf_name , conf_val, update_at
									FROM " . DB_PREFEX . "config 
									WHERE conf_name like 'ADV_DAYS'
									", array());

		if (count($conf) != 1) {
			return array('Error' => "خطأ بالاعدادات الرجاء التواصل مع الادارة");
		}

		$m_time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($m_time);
		$exp	= dates::add_days($m_time, $conf[0]['conf_val']);
		$exp 	= dates::convert_to_string($exp);

		$land 	= array();

		//$land['l_neighborhood'] = $neighborhood;;
		//$land['l_street'] 		= (!empty($area_info['STREET']))?$area_info['STREET']:null;
		$land['l_block'] 		= $fdata['upd_block'];
		$land['l_no'] 			= $fdata['upd_no'];
		$land['l_type'] 		= $fdata['upd_type'];
		$land['l_for'] 			= $fdata['upd_for'];
		$land['l_size'] 		= $fdata['upd_space'];
		$land['l_desc'] 		= $fdata['upd_desc'];
		$land['l_price'] 		= $fdata['upd_price'];
		$land['l_currency'] 	= $fdata['upd_currency'];
		$land['update_at'] 		= $time;
		$land['update_by'] 		= session::get('user_id');

		$land['l_unit_no'] 		= (!empty($fdata['upd_unit_no'])) ? $fdata['upd_unit_no'] : 1;
		$land['l_unit_num'] 	= (!empty($fdata['upd_unit_nun'])) ? $fdata['upd_unit_nun'] : 1;

		$land['l_mortgage'] 	= (!empty($fdata['upd_mortage']) && $fdata['upd_mortage'] != 'لا يوجد') ? $fdata['upd_mortage'] : null;
		$land['l_law'] 			= (!empty($fdata['upd_law']) && $fdata['upd_law'] != 'لا يوجد') ? $fdata['upd_law'] : null;
		$land['l_info'] 		= (!empty($fdata['upd_info']) && $fdata['upd_info'] != 'لا يوجد') ? $fdata['upd_info'] : null;
		$land['l_disputes'] 	= (!empty($fdata['upd_dispute']) && $fdata['upd_dispute'] != 'لا يوجد') ? $fdata['upd_dispute'] : null;
		$land['l_expered'] 		= $exp;

		$land['l_des_n'] 		= (!empty($fdata['upd_des_n'])) ? $fdata['upd_des_n'] : null;
		$land['l_des_e'] 		= (!empty($fdata['upd_des_e'])) ? $fdata['upd_des_e'] : null;
		$land['l_des_w'] 		= (!empty($fdata['upd_des_w'])) ? $fdata['upd_des_w'] : null;
		$land['l_des_s'] 		= (!empty($fdata['upd_des_s'])) ? $fdata['upd_des_s'] : null;
		///////////////////////////////////////////////////////////////////////////////////////
		$land['l_co_relation'] 	= $fdata['upd_relation'];
		$land['l_co_delegat'] 	= (!empty($fdata['upd_delegate'])) ? $fdata['upd_delegate'] : null;

		$land['l_location'] 	= (!empty($fdata['upd_location'])) ? $fdata['upd_location'] : null;
		$land['l_rooms'] 		= (!empty($fdata['upd_rooms'])) ? $fdata['upd_rooms'] : null;
		$land['l_baths'] 		= (!empty($fdata['upd_bath'])) ? $fdata['upd_bath'] : null;
		$land['l_halls'] 		= (!empty($fdata['upd_hall'])) ? $fdata['upd_hall'] : null;
		$land['l_floor'] 		= (!empty($fdata['upd_floor'])) ? $fdata['upd_floor'] : null;
		$land['l_cars'] 		= (!empty($fdata['upd_car'])) ? $fdata['upd_car'] : null;
		//$land['l_bulid_year'] 	= (!empty($fdata['upd_year']))?$fdata['upd_year']:null;
		$land['l_corner'] 		= (!empty($fdata['upd_corner'])) ? $fdata['upd_corner'] : null;
		$land['l_interface'] 	= (!empty($fdata['upd_interface'])) ? $fdata['upd_interface'] : null;
		$land['l_road'] 		= (!empty($fdata['upd_road'])) ? $fdata['upd_road'] : null;
		$land['l_condition'] 	= (!empty($fdata['upd_air_cond'])) ? $fdata['upd_air_cond'] : null;
		$land['l_basement'] 	= (!empty($fdata['upd_basment'])) ? $fdata['upd_basment'] : null;
		$land['l_duplex'] 		= (!empty($fdata['upd_duplex'])) ? $fdata['upd_duplex'] : null;
		$land['l_append'] 		= (!empty($fdata['upd_append'])) ? $fdata['upd_append'] : null;
		$land['l_monster'] 		= (!empty($fdata['upd_monsters'])) ? $fdata['upd_monsters'] : null;
		$land['l_swim'] 		= (!empty($fdata['upd_swim'])) ? $fdata['upd_swim'] : null;
		$land['l_kitchen'] 		= (!empty($fdata['upd_kitchen'])) ? $fdata['upd_kitchen'] : null;
		$land['l_elevator'] 	= (!empty($fdata['upd_elevator'])) ? $fdata['upd_elevator'] : null;
		$land['l_server_room'] 	= (!empty($fdata['upd_ser_room'])) ? $fdata['upd_ser_room'] : null;
		$land['l_dr_room'] 		= (!empty($fdata['upd_dr_room'])) ? $fdata['upd_dr_room'] : null;

		$land['l_well'] 		= (!empty($fdata['upd_well'])) ? $fdata['upd_well'] : null;
		$land['l_tree'] 		= (!empty($fdata['upd_tree'])) ? $fdata['upd_tree'] : null;
		$land['l_mushub'] 		= (!empty($fdata['upd_mushub'])) ? $fdata['upd_mushub'] : null;

		$files	= new files();
		if (!empty($_FILES['upd_land_img'])) {
			if ($files->check_file($_FILES['upd_land_img'])) {
				$land['l_img'] = $files->up_file($_FILES['upd_land_img'], URL_PATH . 'public/IMG/land/' . $fdata['id']);
			}
			if (!empty($files->error_message)) {
				return array('Error' => $files->error_message);
			}
		}
		if (!empty($_FILES['upd_file_image']) && count($_FILES['upd_file_image']) != 0) {
			$file_array = $files->reArrayFiles($_FILES['upd_file_image']);

			foreach ($file_array as $val) {
				if ($files->check_file($val)) {
					$x = $files->up_file($val, URL_PATH . 'public/IMG/land/' . $fdata['id']);
				}
			}
			if (!empty($files->error_message)) {
				return array('Error' => $files->error_message);
			}
		}
		if (!empty($_FILES['upd_delegate_file'])) {
			if ($files->check_file($_FILES['upd_delegate_file'])) {
				$land['l_delegate_file'] = $files->up_file($_FILES['upd_delegate_file'], URL_PATH . 'public/IMG/land/' . $fdata['id'] . '/delegate');
			}

			if (!empty($files->error_message)) {
				return array('Error' => $files->error_message);
			}
		}

		//update
		$this->db->update(DB_PREFEX . 'land', $land, "l_id = " . $fdata['id']);

		//print_r($land);
		$this->db->delete(DB_PREFEX . 'land_adv', "adv_land = " . $fdata['id']);
		//VIP adv
		if (!empty($fdata['company'])) {
			foreach ($fdata['company'] as $val) {
				$this->db->insert(DB_PREFEX . 'land_adv', array('adv_land' => $fdata['id'], 'adv_co' => $val));
			}
		}

		return array('id' => $fdata['id']);
	}

	//delete land
	public function del_land()
	{
		$time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($time);

		$form	= new form();

		$form->post('id') // ID
			->valid('numeric')

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => $fdata['MSG']);
		}

		//check ID
		$x = $this->db->select(
			"SELECT l_id FROM " . DB_PREFEX . "land
										WHERE l_id = :LID ",
			array(":LID" => $fdata["id"])
		);
		if (count($x) != 1) {
			return array('Error' => "In Field upd_no : Duplicate .. \n ");
		}

		//delete

		//$this->db->delete(DB_PREFEX.'chat',"ch_room IN (SELECT room_id FROM ".DB_PREFEX."chatroom WHERE room_land = ".$fdata['id']." )");
		//$this->db->delete(DB_PREFEX.'chatroom',"room_land = ".$fdata['id']);
		//$this->db->delete(DB_PREFEX.'land_package',"lp_land = ".$fdata['id']);


		//delete files
		$files	= new files();
		$files->del_file_list(URL_PATH . 'public/IMG/land/' . $fdata['id']);

		//update
		$this->db->update(DB_PREFEX . 'land', array('l_co' => null), "l_id = " . $fdata['id']);

		return array('id' => $fdata['id']);
	}

	//active / freez land
	public function active()
	{
		$form	= new form();

		$form->post('id') // ID
			->valid('Integer')

			->post('current', false, true) // Name
			->valid('In_Array', array('true', 'false'))

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => "err_id");
		}

		//check NO:
		$data = $this->db->select(
			"SELECT l_status FROM " . DB_PREFEX . "land 
									WHERE l_id = :ID",
			array(":ID" => $fdata['id'])
		);
		if (count($data) != 1) {
			return array('Error' => "لم يتم العثور على العقار");
		}

		$curr = ($data[0]['l_status'] == 1) ? true : false;

		if (($fdata['current'] == "true" && !$curr) || ($fdata['current'] == "false" && $curr)) {
			return array('Error' => 'حالة العقار الحالية هي  ' . $curr . ' - ' . $fdata['current']);
		}
		$time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($time);

		$arr = array();
		$arr['l_status'] = ($curr) ? 0 : 1;
		$arr['update_at'] = $time;
		$arr['update_by'] = session::get("user_id");

		$this->db->update(DB_PREFEX . 'land', $arr, 'l_id = ' . $fdata['id']);
		return array('ok' => '1');
	}

	//vip land
	public function vip()
	{
		$form	= new form();

		$form->post('id') // ID
			->valid('Integer')

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => "err_id");
		}

		//check NO:
		$data = $this->db->select(
			"SELECT l_id ,lp_start, lp_end, lp_comment
									FROM " . DB_PREFEX . "land
									LEFT JOIN " . DB_PREFEX . "land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
									WHERE l_id = :ID",
			array(":ID" => $fdata['id'])
		);

		if (count($data) != 1) {
			return array('Error' => "لم يتم العثور على العقار");
		}

		if ($data[0]['lp_start'] != null && $data[0]['lp_start'] != "") {
			return array('Error' => 'باقة العقار الحالية من ' . $data[0]['lp_start'] . ' - ' . $data[0]['lp_end']);
		}

		$time	= dates::convert_to_date('now');
		$timestr = dates::convert_to_string($time);

		$time_end = dates::add_days($time, session::get('VIP_PERIOD'));
		$timestr_end = dates::convert_to_string($time_end);

		$arr = array();
		$arr['lp_land'] 	= $fdata['id'];
		$arr['lp_start']	= substr($timestr, 0, 10);
		$arr['lp_end']		= substr($timestr_end, 0, 10);
		$arr['create_at']	= $timestr;
		$arr['create_by']	= session::get("user_id");

		$this->db->insert(DB_PREFEX . 'land_package', $arr);
		return array('ok' => $this->db->LastInsertedId());
	}

	//vip land with price
	public function upgrade_vip()
	{
		$form	= new form();

		$form->post('id') // ID
			->valid('Integer')

			->post('vip_range') // period
			->valid('Integer')

			->post('vip_price') // price
			->valid('Integer')

			->post('token') // Pay token
			->valid('Min_Length', 5)

			->post('vip_cobon', false, true) // Cobon
			->valid('Min_Length', 3)

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => "err_id");
		}

		//check NO:
		$data = $this->db->select(
			"SELECT l_id ,lp_start, lp_end, lp_comment
									FROM " . DB_PREFEX . "land
									LEFT JOIN " . DB_PREFEX . "land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
									WHERE l_id = :ID",
			array(":ID" => $fdata['id'])
		);

		if (count($data) != 1) {
			return array('Error' => "لم يتم العثور على العقار");
		}

		if ($data[0]['lp_start'] != null && $data[0]['lp_start'] != "") {
			return array('Error' => 'باقة العقار الحالية من ' . $data[0]['lp_start'] . ' - ' . $data[0]['lp_end']);
		}

		//check discount
		$discount = 0.0;
		$cobon_id = null;
		if (!empty($fdata['vip_cobon'])) {
			$xx = $this->db->select("SELECT cob_id AS ID, cob_name AS NAME, cob_type AS TYPE
										,cob_price AS PRICE, cob_price_type AS PRICE_TYPE
										,cob_amount AS AMOUNT, cob_active AS ACTIVE
										,IF(now() <= cob_expered,0,1) AS IS_EXP
										,count(bi_id) AS USED_BILL
										FROM " . DB_PREFEX . "cobon
										LEFT JOIN " . DB_PREFEX . "bill ON bi_cobon = cob_id
										WHERE cob_name LIKE :NAME 
											AND (cob_type LIKE 'VIP' OR cob_type LIKE 'PUBLIC')
										GROUP BY cob_id
										", array(':NAME' => $fdata['vip_cobon']));
			if (count($xx) != 1) {
				return array('Error' => "رقم الكبون غير صحيح ");
			}
			$xx = $xx[0];
			if ($xx['IS_EXP'] == 1 || $xx['USED_BILL'] >= $xx['AMOUNT'] || $xx['ACTIVE'] != 1) {
				return array('Error' => "لقد انتهت صلاحية الكبون ");
			}
			$cobon_id = $xx['ID'];
			if ($xx['PRICE_TYPE'] == "PER") {
				$discount = $xx['PRICE'] / 100;
			} else {
				$discount = $xx['PRICE'];
			}
		}
		/*$db_total = $fdata['vip_price']
            if($fdata['vip_price'] != $db_total)
			{
				return array('Error'=>'المبلغ المحدد غير مطابق للمبلغ المسجل وهو '.$db_total);
			}*/
		//Payments Method Hear
		$pay_code = "UPG_LAND_" . $fdata['id'] . "_PAY_" . time();

		$pay_ok = payments::pay(
			$this->db,
			$fdata['token'],
			$fdata['vip_price'],
			$pay_code,
			"land Payment",
			'my_land/ret/'
		);

		if (!empty($pay_ok['error'])) {
			return array('error' => "call Error", "error_data" => $pay_ok);
		}
		if (!empty($pay_ok['payment_result']) && $pay_ok['payment_result']['response_status'] != 'A') {
			return array('Error' => 'بم تتم عملية التحويل بالخطا:  ' . $pay_ok['payment_result']['response_message']);
		}

		$time = dates::convert_to_date('now');
		$timestr = dates::convert_to_string($time);

		$bill = array();

		$time_end = dates::add_days($time, $fdata["vip_range"]);
		$timestr_end = dates::convert_to_string($time_end);

		$arr = array();
		$arr['lp_land'] 	= $fdata['id'];
		$arr['lp_start']	= substr($timestr, 0, 10);
		$arr['lp_end']		= substr($timestr_end, 0, 10);
		$arr['create_at']	= $timestr;
		$arr['create_by']	= session::get("user_id");

		if (!empty($pay_ok['payment_result'])) {
			//don , no action need
			$this->db->insert(DB_PREFEX . 'land_package', $arr);

			$bill['bi_status'] = "A";
		} else {
			$bill['bi_status'] = "PEND";
			$bill['bi_ins_data'] = json_encode(array(
				'table' => DB_PREFEX . 'company', 'data' => $arr
			));
		}

		$bill['bi_land'] 		= $fdata['id'];
		$bill['bi_company']		= session::get("company");
		$bill['bi_code']		= $pay_code;
		$bill['bi_ref']			= $pay_ok['tran_ref'];
		$bill['bi_period']		= $fdata["vip_range"];
		$bill['bi_cobon']		= $cobon_id;
		$bill['bi_amount']		= $fdata["vip_price"];
		$bill['create_at']		= $timestr;
		$bill['create_by']		= session::get("user_id");


		$this->db->insert(DB_PREFEX . 'bill', $bill);

		$ret = array();
		$ret['id'] =  $this->db->LastInsertedId();
		$ret['sql'] =  $this->db->errordata();

		$E_MSG = "";
		if ($bill['bi_status'] == "A") {
			$E_MSG = "معاملة مالية ناجحة <br/> 
						رقم الايصال: " . $ret['id'] . " <br/> 
						نوع المعاملة: ترقية عقار <br/> 
						مرجع المعاملة: " . $pay_ok['tran_ref'] . " <br/>
						المبلغ: " . $fdata["vip_price"] . " <br/>
						التاريخ: " . $timestr . "
						";
		} else {
			$E_MSG = "معاملة مالية تحت الاختبار <br/> 
						رقم الايصال: " . $ret['id'] . " <br/> 
						نوع المعاملة: ترقية عقار <br/> 
						مرجع المعاملة: " . $pay_ok['tran_ref'] . " <br/>
						المبلغ: " . $fdata["vip_price"] . " <br/>
						التاريخ: " . $timestr . "
						";
		}
		if ($cobon_id != null) {
			$E_MSG .= "\n التخفيض: " . $discount;
		}
		$my_co = $this->db->select(
			"SELECT co_package AS PKG, co_package_end AS BK_END
										,create_by AS AD_ID, co_email AS EMAIL
										FROM " . DB_PREFEX . "company
										WHERE co_id = :ID",
			array(":ID" => session::get("company"))
		);
		$my_co = $my_co[0];
		$email 		= new Email();
		$re = $email->send_email($my_co['EMAIL'], 'معاملة مالية', $E_MSG);

		if (!empty($pay_ok['redirect_url'])) {
			$ret['url'] = $pay_ok['redirect_url'];
			header('Location: ' . $ret['url']);
			die();
		}

		return $ret;
	}

	//land with price
	public function land_bill()
	{
		$form	= new form();

		$form->post('id') // ID
			->valid('Integer')

			->post('vip_price') // price
			->valid('Integer')

			->post('token') // Pay token
			->valid('Min_Length', 5)

			->submit();
		$fdata	= $form->fetch();

		if (!empty($fdata['MSG'])) {
			return array('Error' => "err_id");
		}

		//check NO:
		$data = $this->db->select(
			"SELECT l_id ,lp_start, lp_end, lp_comment
									FROM " . DB_PREFEX . "land
									LEFT JOIN " . DB_PREFEX . "land_package ON 
														lp_land = l_id AND lp_start <= now() 
														AND (lp_end >= now() OR lp_end IS NULL)
									WHERE l_id = :ID",
			array(":ID" => $fdata['id'])
		);

		if (count($data) != 1) {
			return array('Error' => "لم يتم العثور على العقار");
		}

		//Payments Method Hear
		$pay_code = "LAND_" . $fdata['id'] . "_PAY_" . time();

		$pay_ok = payments::pay(
			$this->db,
			$fdata['token'],
			$fdata['vip_price'],
			$pay_code,
			"land Payment",
			'my_land/ret/'
		);

		if (!empty($pay_ok['error'])) {
			return array('error' => "call Error", "error_data" => $pay_ok);
		}
		if (!empty($pay_ok['payment_result']) && $pay_ok['payment_result']['response_status'] != 'A') {
			return array('Error' => 'لم تتم عملية التحويل بالخطا:  ' . $pay_ok['payment_result']['response_message']);
		}

		$time = dates::convert_to_date('now');
		$time = dates::convert_to_string($time);

		$bill = array();

		if (!empty($pay_ok['payment_result'])) {
			$bill['bi_status'] = "A";
		} else {
			$bill['bi_status'] = "PEND";
		}

		//add bill
		$bill['bi_company']		= session::get("company");
		$bill['bi_land'] 		= $fdata['id'];
		$bill['bi_code']		= $pay_code;
		$bill['bi_ref']			= $pay_ok['tran_ref'];
		$bill['bi_period']		= $fdata['vip_range'];
		$bill['bi_amount']		= $fdata["vip_price"];
		$bill['create_at']		= $time;
		$bill['create_by']		= session::get("user_id");

		$this->db->insert(DB_PREFEX . 'bill', $bill);
		$id = $this->db->LastInsertedId();

		$ret = array();
		$ret['id'] = $id;
		$ret['sql'] =  $this->db->errordata();

		$E_MSG = "";
		if ($bill['bi_status'] == "A") {
			$E_MSG = "معاملة مالية ناجحة \n 
						رقم الايصال: " . $ret['id'] . " \n 
						نوع المعاملة: ترقية عقار \n 
						مرجع المعاملة: " . $pay_ok['tran_ref'] . " \n
						المبلغ: " . $fdata["vip_price"] . " \n
						التاريخ: " . $time . "
						";
		} else {
			$E_MSG = "معاملة مالية تحت الاختبار \n 
						رقم الايصال: " . $ret['id'] . " \n 
						نوع المعاملة: ترقية عقار \n 
						مرجع المعاملة: " . $pay_ok['tran_ref'] . " \n
						المبلغ: " . $fdata["vip_price"] . " \n
						التاريخ: " . $time . "
						";
		}

		$my_co = $this->db->select(
			"SELECT co_package AS PKG, co_package_end AS BK_END
										,create_by AS AD_ID, co_email AS EMAIL
										FROM " . DB_PREFEX . "company
										WHERE co_id = :ID",
			array(":ID" => session::get("company"))
		);
		$my_co = $my_co[0];
		$email 		= new Email();
		$re = $email->send_email($my_co['EMAIL'], 'معاملة مالية', $E_MSG);

		if (!empty($pay_ok['redirect_url'])) {
			$ret['url'] = $pay_ok['redirect_url'];
			header('Location: ' . $ret['url']);
			die();
		}

		return $ret;
	}

	//get location details and update info
	private function location_info($lat, $lng)
	{
		$m_time	= dates::convert_to_date('now');
		$time	= dates::convert_to_string($m_time);
		//return array('NEIG'=>1,'STREET'=>""); /// DElete IT////////////////////////////
		$location_data = lib::get_land_info($lat, $lng);
		//print_r($location_data);
		//echo " \n\n\n ";
		//check country
		$cou = $this->db->select(
			"SELECT cou_id FROM " . DB_PREFEX . "country
									WHERE cou_code = :COU ",
			array(":COU" => $location_data['short']["country"])
		);

		if (count($cou) != 1) {
			//country not found
			$cou_data = array();
			$cou_data['cou_name'] 	= $location_data['long']['country'];
			$cou_data['cou_name_EN'] = $location_data['long']['country'];
			$cou_data['cou_code']	= $location_data['short']['country'];
			$cou_data['create_at'] 	= $time;
			$cou_data['create_by'] 	= session::get('user_id');

			$this->db->insert(DB_PREFEX . 'country', $cou_data);
			$country = $this->db->LastInsertedId();
		} else {
			$country = $cou[0]['cou_id'];
		}

		//check city
		$cou = $this->db->select(
			"SELECT c_id FROM " . DB_PREFEX . "city
									WHERE (c_name like :NAM OR c_name_EN like :NAM2) AND c_country = :COU",
			array(
				":NAM" => $location_data['short']["administrative_area_level_2"], ":NAM2" => $location_data['short']["administrative_area_level_2"], ":COU" => $country
			)
		);
		if (count($cou) != 1) {
			//city not found
			$cou_data = array();
			$cou_data['c_name'] 	= $location_data['short']['administrative_area_level_2'];
			$cou_data['c_name_EN']	= $location_data['long']['administrative_area_level_2'];
			$cou_data['c_area_name'] 	= $location_data['short']['administrative_area_level_1'];
			$cou_data['c_area_name_EN']	= $location_data['long']['administrative_area_level_1'];
			$cou_data['c_country']	= $country;
			$cou_data['create_at'] 	= $time;
			$cou_data['create_by'] 	= session::get('user_id');

			$this->db->insert(DB_PREFEX . 'city', $cou_data);
			$city = $this->db->LastInsertedId();
		} else {
			$city = $cou[0]['c_id'];
		}

		//check neighborhood
		$neig_search = "";
		if (!empty($location_data['short']['neighborhood'])) {
			$neig_search = $location_data['short']['neighborhood'];
		} elseif (!empty($location_data['short']['political'])) {
			$neig_search = $location_data['short']['political'];
		} elseif (!empty($location_data['short']['locality'])) {
			$neig_search = $location_data['short']['locality'];
		} else {
			$neig_search = $location_data['short']['administrative_area_level_1'];
		}


		$cou = $this->db->select(
			"SELECT nei_id  
										FROM " . DB_PREFEX . "neighborhood
										WHERE nei_city = :CITY AND nei_name like :NAME",
			array(":CITY" => $city, ":NAME" => $neig_search)
		);

		if (count($cou) != 1) {
			//city not found
			$cou_data = array();
			$cou_data['nei_name'] 	= $neig_search;
			$cou_data['nei_name_EN'] = $neig_search;
			$cou_data['nei_city']	= $city;
			$cou_data['create_at'] 	= $time;
			$cou_data['create_by'] 	= session::get('user_id');

			$this->db->insert(DB_PREFEX . 'neighborhood', $cou_data);
			$neigbor = $this->db->LastInsertedId();
		} else {
			$neigbor = $cou[0]['nei_id'];
		}
		$street = (!empty($location_data['short']['route'])) ? $location_data['short']['route'] : "";
		return array('NEIG' => $neigbor, 'STREET' => $street);
	}

	function refrech()
	{
		$date = date("Y-m-d h:i");
		$form	= new form();

		$form

			->post('id_land', false, true)
			->valid('numeric')

			->submit();
		$fdata	= $form->fetch();
		$this->db->update(DB_PREFEX . 'land', array("update_at" => $date), "l_id = " . $fdata['id_land']);
		return array("ok" => 1);
	}
}
