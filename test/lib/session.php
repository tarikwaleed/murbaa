<?php
	class session
	{
		/**The Default Method Like Main in java*/
		function __construct()
		{
		}
		
		public static function init()
		{
			session_start();
		}
		
		public static function set($key,$val)
		{
			$_SESSION[$key] = $val;
		}
		
		public static function get($key)
		{
			if(isset($_SESSION[$key]))
			{
				return $_SESSION[$key];
			}else
			{
				return false;
			}
		}
		
		public static function destroy()
		{
			unset($_SESSION);
			session_destroy();
		}
		
		public static function get_id()
		{
			return session_id();
		}
		
	}
?>