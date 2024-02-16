<?php
	//This for pattern validation
	$email_pattern		= "[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[a-z]{2,4}$";
	$text_pattern		= "[A-Za-zا-ي]{2,}";
	$text_num_pattern	= "[A-Za-zا-ي0-9]{2,}";
	$text_pattern_en	= "[A-Za-z]{2,}";
	$text_pattern_ar	= "[^\x00-\x80]+";
	$num_pattern		= "[0-9]{1,}";
	$phone_pattern		= "[0-9]{10,15}";
	
	//for Date:
	$yearReg = '(196[0-9]|197[0-9]|198[0-9]|199[0-9]|200[0-9]|201[0-9]|202[0-9])';            ///< Allows a number between 2014 and 2029
	$monthReg = '(0[1-9]|1[0-2])';               ///< Allows a number between 00 and 12
	$dayReg = '(0[1-9]|1[0-9]|2[0-9]|3[0-1])';   ///< Allows a number between 00 and 31
	$hourReg = '([0-1][0-9]|2[0-3])';            ///< Allows a number between 00 and 24
	$minReg = '([0-5][0-9])';                    ///< Allows a number between 00 and 59
	
	$date_pattern = '^'.$yearReg.'-'.$monthReg.'-'.$dayReg.'$';

?>