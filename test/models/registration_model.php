<?php
	/**
	* registration MODEL, 
	*/
	class registration_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		//get realsetate registration
		public function real_requests()
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
			
			$sea_txt .= ' comm_accept IS NULL ';
			
			return $this->db->select("SELECT comm_id AS ID, comm_no AS NO, comm_real_no AS REAL_NO
										,comm_co AS CO_ID, co_name AS C_NAME, co_phone AS C_PHONE
										,co_email AS C_EMAIL, co_img AS C_IMG
										,IF(comm_file IS NULL,'',CONCAT('".URL."registration/get_file/',comm_co,'/',comm_file)) AS FILE
										,comm_file AS FILE_NAME, comm_co_num AS CO_NUM
										,comm_exp_date AS EXP_DATE
										FROM ".DB_PREFEX."comm_reg
										JOIN ".DB_PREFEX."company ON comm_co = co_id
										WHERE $sea_txt
										GROUP BY comm_id
										ORDER BY comm_id DESC
										" ,$sea_arr
								);
			
		}
		
		//get Service registration
		public function ser_requests()
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
			$sea_txt .= ' reg_accept IS NULL ';
			
			return $this->db->select("SELECT reg_id AS ID, reg_type AS TYPE, reg_ser_type AS SER_TYPE
										,reg_name AS NAME, reg_no AS NO, reg_co_num AS NUM
										,reg_exp_date AS EXP_DATE, reg_file AS FILE_NAME
										,IF(reg_file IS NULL,'',CONCAT('".URL."registration/get_file/',reg_co,'/',reg_file)) AS FILE
										,reg_co AS CO_ID, co_name AS C_NAME, co_phone AS C_PHONE
										,co_email AS C_EMAIL, co_img AS C_IMG
										FROM ".DB_PREFEX."co_ser_reg
										JOIN ".DB_PREFEX."company ON reg_co = co_id
										WHERE $sea_txt
										GROUP BY reg_id
										ORDER BY reg_id DESC
										" ,$sea_arr
								);
			
		}
		
		//accept/deny registration request
		public function active()
		{
			$form	= new form();
			
			$form	->post('id') // no
					->valid('numeric')
					
					->post('status',false,true) // no
					->valid('In_Array',array(0,1))
					
					->post('new_date',false,true) // no
					->valid('Date')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG'].$fdata['status']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//add reg request
			$reg = array();
			$reg['update_at']		= $time;
			$reg['update_by']		= session::get("user_id");
			$reg['comm_accept']		= $fdata['status'];
			
			if($fdata['status'] == 1)
			{
				if(empty($fdata['new_date']))
				{
					return array("Error"=>"In new_date: is required");
				}
				$reg['comm_exp_date'] 	= $fdata['new_date'];
			}
			
			$this->db->update(DB_PREFEX.'comm_reg',$reg," comm_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
		
		//accept/deny service registration request
		public function active_ser()
		{
			$form	= new form();
			
			$form	->post('id') // no
					->valid('numeric')
					
					->post('status',false,true) // no
					->valid('In_Array',array(0,1))
					
					->post('new_date',false,true) // no
					->valid('Date')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG'].$fdata['status']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//add reg request
			$reg = array();
			$reg['update_at']		= $time;
			$reg['update_by']		= session::get("user_id");
			$reg['reg_accept']		= $fdata['status'];
			
			if($fdata['status'] == 1)
			{
				if(empty($fdata['new_date']))
				{
					return array("Error"=>"In new_date: is required");
				}
				$reg['reg_exp_date'] 	= $fdata['new_date'];
			}
			
			$this->db->update(DB_PREFEX.'co_ser_reg',$reg," reg_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
		
	}
?>