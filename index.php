<?php
	require("config/config.php");
	
	spl_autoload_register(function($class) {
        //This Function Will load Classes Function 
		require(LIB.$class.".php");
    });
	
	$app = new bootstrap();
	
?>