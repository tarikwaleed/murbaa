<?php
	class staff_settings
	{
		public static $staf_asso_type	= array('admin' 	=> 'المدير'
												,'staff'	=> 'موظف'
												,'co_admin' => 'مدير شركة'
												,'co_staff'	=> 'موظف شركة'
												);
		
		public static $permission_type	= array('ADMIN' 	=> 'الادارة'
												,'CUSTOMER'	=> 'العملاء'
												);
		
		private $public_pages 			= array('login'			/*Login */
												,'dashboard'	/*Public Page*/
												,'pay_callback'	/*for payment*/
												);
		private $public_login_pages 	= array('profile'		/*User Profile */
												);
		
		/**The Default Method Like Main in java*/
		function __construct()
		{
			if(empty(session::get('public_pages')))
			{
				session::set('public_pages',$this->public_pages);
				session::set('public_login_pages',$this->public_login_pages);
			}
		}
		
		public function get_acsses($url)
		{
			if(!empty(MAC_ADDRESS))
			{
				ob_start(); // Turn on output buffering
				system('ipconfig /all'); //Execute external program to display output
				
				$mycom=ob_get_contents(); // Capture the output into a variable
				
				ob_clean(); // Clean (erase) the output buffer
				ob_end_flush();
				if (stripos($mycom, MAC_ADDRESS) === false) 
				{
					return false;
				}
			}
			
			//public Pages
			if(array_search($url[0],$this->public_pages)!== false)
			{
				return true;
			}
			
			if(!empty(session::get('user_pages')))
			{
				$pages = session::get('user_pages');
				//public Pages
				if(count($pages) > 1 && array_search($url[0],$this->public_login_pages)!== false)
				{
					return true;
				}
				
				//other pages
				if(!empty($pages[$url[0]]) && array_search($url[1],$pages[$url[0]]) !== false )
				{
					return true;
				}
			}
			return false;
		}
		
		public static function generateRandomString($length = 10)
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#@^%()-';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			//return $randomString;
			return "123456";
		}
		
		public static function default_page($staff_type)
		{
			return "dashboard";
		}
		
		public static function get_public_page()
		{
			return self::public_pages;
		}
		public static function get_public_login_page()
		{
			return $this->public_login_pages;
		}

        public static function ret_pay_session($id)
		{
		    $nn = explode("_",$id);
			$req_time = $nn[count($nn)-1];
                
			if(!is_numeric($req_time))
			{
			    return false;
			}
			$time	= dates::convert_to_date('now');
			$diff = $time - $req_time;
			if($diff > 700)
			{
                return false;
		    }

			$db = new database();
			$no = cookies::get($id);
			
			$data = $db->select("SELECT staff_id, staff_name
								,staff_permission, staff_img
								,staff_email, staff_pass, staff_active
								,staff_company, co_name, co_img, co_active, co_package
								,if(co_package_end is null,356, DATEDIFF(`co_package_end`,now())) AS DIFF
								,pk_name, pk_stars, pk_users, pk_adv_area, pk_vip_area
								,per_name, per_type 
								,page_class, page
								FROM ".DB_PREFEX."staff 
								JOIN ".DB_PREFEX."permission_groups ON per_id = staff_permission
								JOIN ".DB_PREFEX."pages ON per_default_page = page_id
								LEFT JOIN ".DB_PREFEX."company ON co_id = staff_company
								LEFT JOIN ".DB_PREFEX."package ON pk_id = co_package
								WHERE staff_id = :ID" ,
								array(':ID'=>$no)
							);
			
			if(count($data) != 1)
			{
				return false;
			}
			$data = $data[0];
			
			if($data['staff_active'] != 1 || $data['co_active'] === 0)
			{
				return false;
			}
			
			$pages = $db->select("SELECT page_class,page
								FROM ".DB_PREFEX."per_group_pages 
								JOIN ".DB_PREFEX."pages ON per_group_page = page_id
								WHERE per_group_permission = :GROUP" ,
								array(':GROUP'=>$data['staff_permission'])
							);
			
			
			$data['pages'] = array();
			foreach($pages as $key => $val)
			{
				if(empty($data['pages'][$val['page_class']]))
				{
					$data['pages'][$val['page_class']] = array();
				}
				array_push($data['pages'][$val['page_class']],$val['page']);
				
			}
			
			session::set('user_id'			,$data['staff_id']);
			session::set('user_name'		,$data['staff_name']);
			session::set('user_email'		,$data['staff_email']);
			session::set('user_type'		,$data['per_type']);
			session::set('user_img'			,$data['staff_img']);
			session::set('user_per_name'	,$data['per_name']);
			session::set('user_pages'		,$data['pages']);
			
			session::set('default_page'		,$data['page_class']."/".$data['page']);
			
			session::set('company'			,$data['staff_company']);
			session::set('com_name'			,$data['co_name']);
			session::set('com_img'			,$data['co_img']);
			
			//package
			session::set('PK_STARS'			,$data['pk_stars']);
			session::set('PK_NAME'			,$data['pk_name']);
			session::set('PK_USERS'			,$data['pk_users']);
			session::set('PK_ADV'			,$data['pk_adv_area']);
			
			$e = staff_settings::generateRandomString();
			session::set('csrf'	,Hash::create(HASH_FUN,$e,HASH_PASSWORD_KEY));
			session::set('CREATED'		,time());
			
			return true;
		}

	}
?>