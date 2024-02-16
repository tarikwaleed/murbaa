<?php
	/**
	* payment MODEL, 
	*/
	class payment_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function payment
		* get payment
		*/
		public function payment()
		{
			$sea_arr = array();
			$sea_txt = "";
			
			/*$form	= new form();
			
			$form	->post('land',false,true) // land ID
					->valid('numeric')
					
					->post('chatroom',false,true) // chatroom
					->valid('numeric')
					
					->post('bill',false,true) // bill
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error: '=>$fdata['MSG']);
			}
			
			if(!empty($fdata['land']))
			{
				$sea_arr[':LAND'] = "%".$fdata['land']."%";
				$sea_txt .= 'room_land :LAND AND ';
			}
			if(!empty($fdata['chatroom']))
			{
				$sea_arr[':ROOM'] = $fdata['chatroom'];
				$sea_txt .= 'room_id = :ROOM AND ';
			}
			if(!empty($fdata['bill']))
			{
				$sea_arr[':BILL'] = $fdata['bill'];
				$sea_txt .= 'bi_id = :BILL AND ';
			}
			*/
			if(!empty(session::get("company")))
			{
				$sea_txt .= "bi_company = :CO AND ";
				$sea_arr[':CO'] = session::get("company");
			}
			
			$sea_txt .= ' 1=1 ';
			
			return $this->db->select("SELECT bi_id AS ID ,bi_package AS BK_ID, bi_land AS LAND_ID
										,bi_period AS LAND_PERIOD, bi_amount AS AMOUNT
										,pk_name AS BK_NAME, bills.create_at BILL_DATE
										,co_name AS CO_NAME
										,l_block AS LAND_BLOCK, l_no AS LAND_NO, l_for AS LAND_FOR
										FROM ".DB_PREFEX."bill AS bills
										JOIN ".DB_PREFEX."company ON co_id = bi_company
										LEFT JOIN ".DB_PREFEX."land ON l_id = bi_land
										LEFT JOIN ".DB_PREFEX."package ON bi_package = pk_id
										WHERE $sea_txt
										GROUP BY bi_id
										ORDER BY bi_id DESC
										" ,$sea_arr
								);
			
		}
		
		
	}
?>