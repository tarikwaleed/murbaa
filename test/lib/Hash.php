<?php
	class Hash
	{
		public static function create($algo,$data,$salt)
		{
			/**
			* @param string $algo The algrithm (MD5,sha1,....)
			* @param string $data The data to encode
			* @param string $salt The Hash Key
			* @return string The Hased/salted data
			*/
			$context = hash_init($algo,HASH_HMAC,$salt);
			hash_update($context,$data);
			return hash_final($context);
		}
	}
?>