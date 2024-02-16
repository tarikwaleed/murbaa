<?php
	class cookies
	{
		/**The Default Method Like Main in java*/
		
		function __construct()
		{
		}
		
		
		public static function set($key,$val,$time = 1)
		{
			$time = ($time ==1)? time() + 2592000:$time;
			setcookie ($key, $val, $time, "/" );
		}
		
		public static function get($key)
		{
			if(isset($_COOKIE[$key]))
			{
				return $_COOKIE[$key];
			}else
			{
				return false;
			}
		}
		
		public static function destroy($key)
		{
			setcookie ($key, '', time() - 3600);
		}
		
	}
?>