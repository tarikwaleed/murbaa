<?php
	/**
	* class lib
	* for contacting lib
	*/
	class lib
	{
		/**The Default Method Like Main in java*/
		function __construct()
		{
		}
		
		public static $contract_type 	= array("HUM"		=>"سكني"
												,"COMM"		=>"تجاري"
												,"BATIN"	=>"عن الباطن"
												);
		
		public static $service_status 	= array("NEW"		=>"مفتوح"
												,"WORK"		=>"قيد التنفيذ"
												,"SIM_END"	=>"طلب تسليم"
												,"END"		=>"مكتمل"
												,"FREEZ"	=>"ملغي"
												);
		
		public static $service_type 	= array("PUBLIC"	=>"عام"
												,"PRIVATE"	=>"خاص"
												);
		
		public static $cobon_type 		= array("PUBLIC"	=>"عامة"
												,"VIP"		=>"باقات"
												,"LAND"		=>"إعلان عقاري"
												);
		
		public static $cobon_pay 		= array("CASH"		=>"مبلغ"
												,"PER"		=>"نسبة"
												);
		
		public static $ser_reg_type 	= array("MARKET"	=>"مساعد وسيط عقاري"
												,"CREA"		=>"منشأة"
												);
		
		public static $house_live_type 	= array("OWNER"		=>"مالك"
												,"TENANT"	=>"مؤجر"
												,"OTHER"	=>"اخرى"
												);
		public static $company_type 	= array("OFFICE"	=>"مكتب"
												,"MARKET"	=>"مسوق عقاري"
												,"FREE"		=>"حاصل على وثيقة عمل حر"
												,"OTHER"	=>"اخرى"
												);
		
		public static $land_for 		= array("RENT_D"	=>array("NAME"=>"للإيجار اليومي","S_NAME"=>"اليوم")
												,"RENT_M"	=>array("NAME"=>"للإيجار الشهري","S_NAME"=>"الشهر")
												,"RENT_Y"	=>array("NAME"=>"للإيجار السنوي","S_NAME"=>"السنة")
												,"SALE"		=>array("NAME"=>"للبيع","S_NAME"=>"للبيع")
												,"INVEST"	=>array("NAME"=>"للاستثمار","S_NAME"=>"للاستثمار")
												);
		
		public static $land_stat 		= array("HUM"		=>"سكني"
												,"COMM"		=>"تجاري"
												,"FARM"		=>"صناعي"
												,"TREE"		=>"زراعي"
												);
		
		public static $currency 		= array("SAR"		=>"SAR"
												,"USD"		=>"USD"
												);
		
		public static $adv_type 		= array("ADV"		=>"إعلان"
												,"REQ"		=>"طلب"
												);
		
		public static $land_relation 	= array("OWNER"		=>array("NAME"=>"مالك","del"=>false)
												,"CO_REL"	=>array("NAME"=>"وكيل","del"=>false)
												,"SAIL"		=>array("NAME"=>"مسوق","del"=>true)
												,"CREA"		=>array("NAME"=>"منشأة","del"=>true)
												);
		public static $land_interface 	= array("N"			=>"شمالية"
												,"E"		=>"شرقية"
												,"W"		=>"غربية"
												,"S"		=>"جنوبية"
												,"NE"		=>"شمالية شرقية"
												,"NW"		=>"شمالية غربية"
												,"SE"		=>"جنوبية شرقية"
												,"SW"		=>"جنوبية غربية"
												,"3D"		=>"ثلاثة شوارع"
												,"4D"		=>"اربعة شوارع"
												);
									
		
		public static $letters 			= array("ا","ب","ت","ث","ج","ح","خ"
												,"د","ذ","ر","ز","س","ش","ص"
												,"ض","ط","ظ","ع","غ","ف","ق"
												,"ك","ل","م","ن","ه","و","ي"
												);
		
		public static function api_headers()
		{
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			header('HTTP/1.1 200 OK');
			
		}
		
		public static function api_land($adLicenseNumber,$advertiserId,$idType="1")
		{
		    $apiUrl = "https://integration-gw.nhc.sa/nhc/prod/v1/brokerage/AdvertisementValidator?adLicenseNumber={$adLicenseNumber}&advertiserId={$advertiserId}&idType={$idType}";
		    
            $headers = array(
                'X-IBM-Client-Id: '.X_IBM_CLIENT_ID,
                'X-IBM-Client-Secret: '.X_IBM_CLIENT_SEC,
                'RefId: 1'
            );
            
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            $response = curl_exec($ch);

            if (curl_errno($ch)) 
            {
                return 'Error: ' . curl_error($ch);
            }
            
            curl_close($ch);
            return json_decode($response, true);
            

		}
		
		public static function get_CSRF()
		{
			if(!empty(session::get("csrf")))
			{
				return session::get("csrf"); 
			}
			return TOKEN;
		}
		
        //get land info
		public static function get_land_info($lat,$lng,$lang='AR')
		{
			$code_api_link = 'https://maps.googleapis.com/maps/api/geocode/json?sensor=true&key=AIzaSyC9LAtPj0KJDr5l621IbMZcQinoYO-7-4g&language='.$lang.'&latlng='.$lat.','.$lng;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $code_api_link);
			$result = curl_exec($ch);
			curl_close($ch);

			$obj = json_decode($result);
			
			$data = array();
			
			foreach($obj->results as $res)
			{
				foreach($res->address_components as $info)
				{
					$data['long'][$info->types[0]] = $info->long_name;
					$data['short'][$info->types[0]]= $info->short_name;
				}
			}
			return $data;
		}
		
		//save notification
		public static function save_notification($db, $data = array(),$type)
		{
			/*
			Type:
			1: Email (admin)
			2: New Registration
			
			3: transfire Booking (spec_dr)
			4: New DR (Group Admin)
			5: New Group (admin)
			6: Patient Booking actions
			*/
			
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$ans_array = array('noti_user'		=>1
								,'noti_type'	=>$type
								,'noti_title'	=>""
								,'noti_url'		=>""
								,'create_at'	=>$time
								);
			$send_noti = array();
			switch($type)
			{
				case 1:
					/*
					Email
					For Admin
					data: array('con_subject'	=>$fdata['subject'] // MSG subject
								,'con_name'		=>$fdata['name'] 	//Who Send MSG
								,'con_email'	=>$fdata['email']	//Who Send MSG Email
								,'con_msg'		=>$fdata['message'] // MSG
								,'con_date'		=>$time				// MSG Time
								);
					Pages:
					dashboard_model 	-> new_cont
					*/
					
					$ans_array['noti_title'] = "رسالة من ".$data['con_name']." راجع الايميل";
					array_push($send_noti,$ans_array);
				break;
				case 2:
					/*
					New Registration
					For Admin AND Statistics
					data: array('id'		=> ID
								,'req_name'	=> NAME
								,'req_land'	=> LAND NO
								,'req_card'	=> CARD NO
								,'req_email'=> Email
								,'req_phone'=> PHONE
								,'create_at'=> time
								);
					pages:
					login_model		->reg
					*/
					
					$ans_array['noti_title']= "طلب تسجيل جديد من ".$data['req_name']." ";
					$ans_array['noti_url'] 	= "reg/".$data['id'];
					array_push($send_noti,$ans_array);
				break;
				case 3:
					
				break;
				case 4:
					
				break;
				case 5:
					
				break;
				case 6:
					
				break;
				
			}
			
			//send_noti
			foreach($send_noti as $val)
			{
				$db->insert(DB_PREFEX.'notification',$val);
			}
			
		}
		
		//save notification
		public static function notification_read($db, $id ,$type)
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form = new form();
			
			if(empty($id) || empty($type))
			{
				$form	->post('id')
						->valid('Integer')
							
						->post('type')
						->valid('Integer')
							
						->submit();
				$d = $form->fetch();
				
				if(!empty($d['MSG']))
				{
					return array('Error'=>$d['MSG']);
				}
				
				$id = $d['id'];
				$type = $d['type'];
			}
			
			$table_name = "notification";
			$where = "1 != 1";
			switch($type)
			{
				case 1:
					/*
					Admin noti
					id: noti ID
					notificate	->read_noti() 
					*/
					$where = "noti_id = ".$id." AND noti_user = ".session::get('user_id');
				break;
				
				case 2:
				case 3:
					/*
					Booking noti
					New Booking
					transfire Booking
					id: book_id
					//actions		->booking()
					*/
					$where = "noti_book = ".$id;
					
				break;
				case 4:
					/*
					DR noti
					New DR
					id: DR_ID
					//staff		->index()
					*/
					$where = "noti_dr = ".$id;
					
				break;
				case 5:
					/*
					Group noti
					New Group
					id: Group_ID
					//group		->index()
					*/
					$where = "noti_gr = ".$id;
					
				break;
				case 6:
					/*
					Patient noti
					patient		->index()
					ID: Booking no
					*/
					
					$table_name = "pa_notification";
					$where = "noti_book = ".$id." 
							AND noti_book IN (SELECT bo_id FROM ".DB_PREFEX."booking
											WHERE bo_patient = ".session::get('user_id').")";
					
				break;
			}
			$db->update(DB_PREFEX.$table_name,array("noti_status"=>1),$where);
			return array('ok'=>1);
		}
		
	}
?>