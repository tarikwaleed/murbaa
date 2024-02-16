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
		public function callback($db,$id)
		{
			$form	= new form();
			
			if(!$form->single_valid($id,'Min_Length',5))
			{
				return array('Error'=>"Error In Key ");;
			}
				
			//check NO:
			$data = $this->db->select("SELECT bi_id, bi_upd_data, bi_sql
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
			
			return "";
		}
			
		
	}
?>