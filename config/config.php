<?php
		//General Vars:
	define('PAGING',3);
	define('TITLE','عقارات');
	define('DESCRIPTION','اول موقع لعرض العقارات');
	define('EMAIL_ADD','info@murbaa.com');
	define('PHONE_NUM','+966143544454');
	
	//Paths
	define("URL","//".$_SERVER['HTTP_HOST']."/");
	//define("URL","https://www.murbaa.com/");
	
	define("URL_PATH",$_SERVER['DOCUMENT_ROOT']."/");
	define('LIB','lib/');
	
	//Constants Dont Change This Hash !
	define('HASH_FUN','sha256');
	define('HASH_KEY','haftar');
	define('HASH_PASSWORD_KEY','haftar@JAK');
	define('TOKEN','MY_TOKEN_#12360_MAK');
	
	//Database
	define('DB_TYPE','mysql');
	//define('DB_HOST','murbaa_realstate');
	define('DB_HOST','localhost');
	define('DB_USER','murbaa_real');
	define('DB_PASS','yfNKZUYSgFP#');
	define('DB_NAME','murbaa_realstate');
	define('DB_charset','utf8');
	define('DB_PREFEX','real_');
	
	//Email Settings
	
	/*define('EMAIL_HOST',"cloud.murbaa.com");
	define('EMAIL_SMTP_AUTH',true);
	define('EMAIL_PORT',465);*/
	
	define('EMAIL_HOST',"mail.murbaa.com");
	define('EMAIL_SMTP_AUTH',true);
	define('EMAIL_PORT',587);
	
	define('EMAIL_SEND_ADD','info@murbaa.com');
	define('EMAIL_SEND_PASS','MuR@E2298#');
	
	//Realstate API:
	define('AD_LICENSE_NO',"7100011343");
	define('X_IBM_CLIENT_ID',"f8917a302830bae2c4a057a4e7c399eb");
	define('X_IBM_CLIENT_SEC',"3fc780c282671846207abb06bffdfc24");
	
	//LINKS
	define('FACE','#');
	define('LINKEDIN','#');
	define('TWITTER','#');
	define('INSTAGRAM','#');
	define('YOUTUBE','#');
	define('GOOGLE','#');
	
	//OTHER CONFIG
	define('MAX_FILE_SIZE',2097152);
	define('MAX_HOME_NO',1500);
	define('MIN_HOME_NO',1);
	define('MAC_ADDRESS','');
	
    //payment gatway
	define('P_JS_FILE','https://secure.paytabs.sa/payment/js/paylib.js'); //MAIN
	define('P_JS_KEY','CTKMHK-6PK96M-BQR7T7-T2DTR9'); //NAIN
	define('P_URL','https://secure.paytabs.sa/payment/request'); //NAIN
	define('P_URL_KEY','SZJNZWTM2T-J2LNRTGTW6-MT9ZHNLJRN'); //NAIN
	define('P_PROFILE_ID',83566);//main
    define('P_CURRENCY','SAR');
	
	/*define('P_JS_FILE','https://secure-global.paytabs.com/payment/js/paylib.js'); //TEST
	define('P_JS_KEY','CRKMP9-VKM76M-P9DRHK-HKQ62Q'); //TEST
	define('P_URL','https://secure-global.paytabs.com/payment/request'); //TEST
	define('P_URL_KEY','SMJNBJH6NN-J29ZDRHKKH-BNNDZKT6TG'); //TEST
	define('P_PROFILE_ID',85277);//test
	define('P_CURRENCY','USD');*/
?>