<?php
	class dates extends DateTime
	{
		/**The Default Method Like Main in java*/
		
		function __construct()
		{
			
		}
		
		public static function convert_to_date($da)
		{
			if($da == '0000-00-00 00:00:00')
			{
				$da = '2000-01-01 01:01:01';
			}
			return new DateTime($da);
		}
		
		public static function convert_to_string($date)
		{
			return $date->format('Y-m-d H:i:s');
		}
		
		public static function difrent($date1,$date2)
		{
			if(is_string($date1)) $date1 = self::convert_to_date($date1);
			if(is_string($date2)) $date2 = self::convert_to_date($date2);
			
			return $date1->diff($date2);
			
			/*
			Properties :
				public integer $y ;			Number of years
				public integer $m ;			Number of months
				public integer $d ;			Number of days
				public integer $h ;			Number of hours.
				public integer $i ;			Number of minutes.
				public integer $s ;			Number of seconds.
				public integer $invert ;	1 if the interval represents a negative time : 0 otherwise
				public mixed $days ;
			Methods :
				public __construct ( string $interval_spec )
				public static DateInterval createFromDateString ( string $time )
				public string format ( string $format )
			*/	
		}
		
		public static function get_data_details($date,$type)
		{
			/*
			* the Type Must be on of:
			* d : Day: 01 - 31
			* D : Day: Mon through Sun
			* j : Day: 1 - 31
			* l : Day: Sunday through Saturday
			* N : Day: 1 (for Monday) through 7 (for Sunday)
			* S : Day: st, nd, rd or th. Works well with j
			* w : Day: 0 (for Sunday) through 6 (for Saturday)
			* z : Day: 0 through 365
			
			* W : Week: (examlpe: the 42nd week in the year)
			
			* F : Month: January through December
			* m : Month: 01 through 12
			* M : Month: Jan through Dec
			* n : Month: 1 through 12
			* t : No of Days in Month 28 through 31
			
			* L : Whether it's a leap year 1 if it is a leap year, 0 otherwise.
			* o : Year: 1999 or 2016
			* Y : Year: 1999 or 2016
			* y : Year: 99 or 03
			
			* a : TIME: am or pm
			* A : TIME: AM or PM
			* B : Time:	Swatch Internet time 	000 through 999
			
			* g : Houre: 1 through 12
			* G : Houre: 0 through 23
			* h : Houre: 01 through 12 
			* H : Houre: 00 through 23
			
			* i : Minits: 00 to 59 
			
			* s : Secound: 00 to 59
			*continue....
			*
			*
			*
			*
			*/
			
			if(is_string($date))
			{
				$date = self::convert_to_date($date);
			}
			return $date->format($type);//"Y"
		}
		
		public static function add_days($date,$days =0)
		{
			date_add($date,date_interval_create_from_date_string($days." days"));
			return $date ;
		}
	}
?>