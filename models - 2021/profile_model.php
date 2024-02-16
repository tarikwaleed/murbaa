<?php
	/**
	* profile MODEL, 
	*/
	class profile_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* sys_info get All profile
		*/
		public function sys_info()
		{
			$sql_arr = array(":ID"=>session::get("user_id"));
			
			$ret = array();
			
			//Profile
			$profile = $this->db->select("SELECT staff_id AS ID, staff_name AS NAME 
										,staff_nat_no AS NAT_NO, staff_phone AS PHONE
										,staff_img AS IMG, staff_address AS ADDRESS
										,staff_comm AS COMM, staff_email AS EMAIL
										
										FROM ".DB_PREFEX."staff
										WHERE staff_id = :ID"
										,$sql_arr);
			
			$ret 				= $profile[0];
			$ret["IMG"] 		= URL."public/IMG/user/".$ret['IMG'];
			
			$ret['ADMIN'] = session::get("user_type") == "admin";
			return $ret;
		}
		
		/**
		* lands get All lands
		*/
		
		public function lands()
		{
			$lands =  $this->db->select("SELECT l_id, l_block, l_location, l_type, l_img
										,l_desc, l_size, l_rooms, l_interface, l_halls, l_baths
										, l_cars, l_floor, l_price, l_currency, l_corner, l_for
										,l_active_date, l_bulid_year, l_no
										
										,nei_id, nei_name, nei_name_EN, c_id, c_name, c_name_EN
										,staff_id, staff_name, staff_name_en, staff_phone
										,staff_email,staff_conf_phone, staff_conf_email, staff_img
										
										,own_id, own_name, own_name_en, own_phone, own_email
										,lp_package, lp_start, lp_end, lp_comment
										,pk_name, pk_name_EN, pk_price, pk_days
										
										room_id, room_start
										
										FROM ".DB_PREFEX."land
										JOIN ".DB_PREFEX."neighborhood ON l_neighborhood = nei_id
										JOIN ".DB_PREFEX."city ON nei_city = c_id 
										JOIN ".DB_PREFEX."staff ON ".DB_PREFEX."land.create_by = staff_id 
										JOIN ".DB_PREFEX."owner ON l_owner = own_id 
										JOIN ".DB_PREFEX."land_package ON lp_land = l_id 
										JOIN ".DB_PREFEX."package ON pk_id = lp_package  
										JOIN ".DB_PREFEX."chatroom ON room_land = l_id
										WHERE room_customer = :ID
											AND l_status = 1  
											AND lp_start <= now() 
											AND (lp_end >= now() OR lp_end IS NULL)
										GROUP BY l_id
										ORDER BY l_id DESC
									" ,array(":ID"=>session::get("user_id")));
			$ret = array();
			
			foreach($lands as $val)
			{
				
				$sing = array();
				$sing["ID"] 			= $val["l_id"];
				$sing["NO"] 			= $val["l_no"];
				$sing["BLOCK"] 			= $val["l_block"];
				$sing["LOCATION"] 		= $val["l_location"];
				$sing["TYPE"] 			= $val["l_type"];
				$sing["TYPE_NAME"] 		= lib::$land_type[$val["l_type"]];
				$sing["IS_RES"] 		= in_array($val["l_type"],array("VILLA","FUR_APART","APART","OFFICE"));
				
				$sing["IMG"] 			= URL."public/IMG/land/".$val["l_id"]."/".$val["l_img"];
				$sing["FOR"] 			= $val["l_for"];
				$sing["FOR_NAME"] 		= lib::$land_for[$val["l_for"]];
				$sing["DESC"] 			= $val["l_desc"];
				$sing["SIZE"] 			= $val["l_size"];
				$sing["ROOMS"] 			= $val["l_rooms"];
				$sing["INTERFACE"] 		= $val["l_interface"];
				$sing["HALLS"] 			= $val["l_halls"];
				$sing["BATHS"] 			= $val["l_baths"];
				$sing["CARS"] 			= $val["l_cars"];
				$sing["FLOOR"] 			= $val["l_floor"];
				$sing["PRICE"] 			= $val["l_price"];
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
				
				$sing["IS_ADMIN"] 		= session::get('user_type') == "admin";
				$sing["IS_OW"] 			= $val["staff_id"] == session::get('user_id');
				
				$sing["OW_ID"] 			= $val["staff_id"];
				$sing["OW_NAME"] 		= $val["staff_name"];
				$sing["OW_NAME_EN"] 	= $val["staff_name_en"];
				$sing["OW_PHONE"] 		= $val["staff_phone"];
				$sing["OW_EMAIL"] 		= $val["staff_email"];
				$sing["OW_IMG"] 		= URL."public/IMG/user/".$val["staff_img"];
				
				$sing["OWNER_ID"] 		= $val["own_id"];
				$sing["OWNER_NAME"] 	= $val["own_name"];
				$sing["OWNER_PHONE"]	= $val["own_phone"];
				$sing["OWNER_EMAIL"]	= $val["own_email"];
				
				$sing["PACKAGE_ID"] 	= $val["lp_package"];
				$sing["PACKAGE_START"] 	= $val["lp_start"];
				$sing["PACKAGE_END"] 	= $val["lp_end"];
				$sing["PACKAGE_TY"] 	= $val["pk_name"];
				
				$dir 	= URL_PATH."public/IMG/land/".$val["l_id"]."/";
				$link 	= URL."public/IMG/land/".$val["l_id"]."/";
				$sing["OTHER_IMG"] 		= files::get_file_list($dir,$link);
				
				$sing['ROOM_ID'] 		= $val['room_id'];
				$sing['ROOM_START'] 	= $val['room_start'];
				
				array_push($ret,$sing);
			}
			
			return $ret;
			
		}
		
		
		
		/*
		* function upd_info
		* update profile
		*/
		public function upd_info()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form	= new form();
			
			$form	->post('new_staff_name') //name
					->valid('Min_Length',3)
			
					->post('new_staff_phone') //Phone
					->valid('Min_Length',10)
					
					->post('new_staff_nat') //Nat ID no
					->valid('Min_Length',3)
					
					->post('new_staff_address') //Address
					->valid('Min_Length',3)
					
					->post('curr_staff_pass') //Current Password
					->valid('Min_Length',2)
					
					->post('new_staff_pass',false,true) //New Password
					->valid('Min_Length',2)
					
					->post('new_staff_pass2',false,true) //Confirm New Password
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check user
			$data = $this->db->select("SELECT staff_id, staff_pass
										FROM ".DB_PREFEX."staff
										WHERE staff_id = :ID" 
										,array(":ID"=>session::get('user_id')));
			
			if(count($data) != 1 || $data[0]['staff_pass'] != Hash::create(HASH_FUN,$fdata['curr_staff_pass'],HASH_PASSWORD_KEY))
			{
				return array('Error'=>"Password Not Match!");
			}
			
			//check phone
			$em = $this->db->select("SELECT staff_id, staff_phone, staff_nat_no
									FROM ".DB_PREFEX."staff 
									WHERE staff_id != :ID AND
										(staff_phone = :PHO OR staff_nat_no = :NAT) "
									,array(':PHO'=>$fdata['new_staff_phone']
											,':NAT'=>$fdata['new_staff_nat']
											,':ID'=>session::get('user_id')
											));
			
			if(count($em) != 0)
			{
				$err = "";
				foreach($em as $val)
				{
					if($val['staff_phone'] == $fdata['new_staff_phone'])
					{
						$err .= "In Field new_staff_phone : Duplicate .. \n";
					}
					if($val['staff_nat_no'] == $fdata['new_staff_nat'])
					{
						$err .= "In Field new_staff_nat : Duplicate .. \n";
					}
				}
				if(!empty($err))
				{
					return array('Error'=>$err);
				}
			}
			
			$user_array = array('staff_name'		=>$fdata['new_staff_name']
								,'staff_phone'		=>$fdata['new_staff_phone']
								,'staff_address'	=>$fdata['new_staff_address']
								,'staff_nat_no'		=>$fdata['new_staff_nat']
								,'update_at'		=>$time
								,'update_by'		=>session::get('user_id')
								);
								
			if(!empty($fdata['new_staff_pass']))
			{
				if($fdata['new_staff_pass'] != $fdata['new_staff_pass2'])
				{
					return array('Error'=>"In Field new_staff_pass : Not Match .. \n ");
				}
				$user_array['staff_pass'] = $fdata['new_staff_pass'];
			}
			
			
			$files	= new files(); 
			
			if(!empty($_FILES['new_pro_image']) )
			{
				if($files->check_file($_FILES['new_pro_image']))
				{
					$user_array['staff_img'] = $files->up_file($_FILES['new_pro_image'],URL_PATH.'public/IMG/user/');
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			
			$this->db->update(DB_PREFEX.'staff',$user_array,"staff_id = ".session::get('user_id'));
			
			if(!empty($_FILES['upd_staff_file_image']) && count($_FILES['upd_staff_file_image'])!= 0)
			{
				$files	= new files(); 
				
				$file_array = $files->reArrayFiles($_FILES['upd_staff_file_image']);
				
				foreach($file_array as $val)
				{
					if($files->check_file($val))
					{
						$x = $files->up_file($val,URL_PATH.'public/IMG/user/'.session::get('user_id'));
					}
				}
				
				if(!empty($files->error_message))
				{
					return array('Error'=>"Other File ".$files->error_message);
				}
			}
			
			return array('id'=>1);
		}
		
		/**
		* function del_img
		* del_img
		* AJAX
		*/
		public function del_img()
		{
			$form	= new form();
			
			$form	->post('file') // Name
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			$url = URL_PATH."public/IMG/user/".session::get('user_id')."/".$fdata['file'];
			$x = unlink($url);
			if($x)
			{
				return "تم حذف الملف";
			}else{
				return "لم يتم حذف الملف";
			}
		}
	
		
	}
?>