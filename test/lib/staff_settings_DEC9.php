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
	}
?>