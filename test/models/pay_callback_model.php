<?php
	/**
	* pay_callback MODEL, 
	*/
	class pay_callback_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function callback
		*/
		public function callback($id)
		{
			$form	= new form();
			
			if(!$form->single_valid($id,'Min_Length',5))
			{
				return array('Error'=>"Error In Key ");;
			}
				
			//check NO:
			$data = $this->db->select("SELECT bi_id, bi_upd_data, bi_sql
									,bi_package , bi_land,bi_service_offer
									FROM ".DB_PREFEX."bill
									WHERE bi_code LIKE :ID AND bi_status LIKE 'PEND' "
									,array(":ID"=>$id));
			if(count($data) != 1)
			{
				return array('Error'=>"Error In Key ");;
			}
			$data = $data[0];
			
			$db->update(DB_PREFEX.'bill',array('bi_status'=>'A'),"bi_id = ".$data['bi_id']);
			
			$upd = json_decode($data['bi_upd_data'],true);
			$db->update($data['table'],$upd,$data['where']);
			$db->sql_quer($data['bi_sql']);
			
			if(!empty($data['bi_package']))
			{
				return URL."my_co/";
			}
			if(!empty($data['bi_land']))
			{
				return URL."my_land/";
			}
			if(!empty($data['bi_service_offer']))
			{
				return URL."services/details/".$data['bi_service_offer'];
			}
			return URL;
			
			
		}
			
		
	}
?>