<?php
	/**
	* tickets MODEL, 
	*/
	class tickets_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function user_list
		* get users list
		*/
		public function user_list()
		{
			$form	= new form();
			
			$form	->post('no',false,true) // phone
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error' => $fdata['MSG']);
			}
			
			$sea_arr = array();
			$sea_txt = "";
			$sum = "recived_by IS NULL AND ";
			
			if(!empty($fdata['no']))
			{
				$sea_arr[':NO'] = $fdata['no'];
				$sea_txt .= 'tk_id = :NO AND ';
			}
			if(empty(session::get('company')))
			{
				//for Admin
				$sea_txt .= "tk_status = 1 AND ";
				$sum .= "tk.create_by = ch.create_by";
			}else
			{
				//for Customer
				$sea_txt .= "tk.create_by = :USER AND ";
				$sea_arr[":USER"] = session::get('user_id');
				$sum .= "tk.create_by != ch.create_by";
			}
			$sea_txt .= ' 1=1 ';
			
			$tickets = $this->db->select("SELECT tk_id, tk_user_name, tk_user_email, tk_desc, tk_status
										,tk.create_at AS tk_create
										,staff_id, staff_name, staff_img, staff_email
										,co_id, co_name, co_name_en, co_phone
										,co_email, co_img, co_desc, co_active
										,SUM(CASE WHEN $sum then 1 else 0 end) AS NOT_READ
										FROM ".DB_PREFEX."ticket AS tk
										LEFT JOIN ".DB_PREFEX."staff ON tk.create_by = staff_id
										LEFT JOIN ".DB_PREFEX."company ON staff_company = co_id
										LEFT JOIN ".DB_PREFEX."ticket_msg as ch ON tk_id = msg_ticket 
										WHERE $sea_txt
										GROUP BY tk_id
										ORDER BY tk_id DESC
										" ,$sea_arr
								);
								/*
								IF(SUM(CASE WHEN $sum then 1 else 0 end) = 0, 0, 1) DESC, 
											tk_status DESC, tk.create_at
								*/
			
			$ret = array();
			foreach($tickets as $val)
			{
				
				$r = array();
				$r["ID"] 		= $val['tk_id'];
				$r["DESC"] 		= $val['tk_desc'];
				$r["NAME"] 		= (!empty($val['staff_name']))?$val['staff_name']:$val['tk_user_name'];
				$r["EMAIL"] 	= (!empty($val['staff_email']))?$val['staff_email']:$val['tk_user_email'];
				$r["CO"] 		= $val['co_name'];
				$r["DATE"] 		= $val['tk_create'];
				$r["STATUS"] 	= $val['tk_status'];
				$r["NOT_READ"] 	= $val['NOT_READ'];
				$r["CHAT_DATA"] = $this->chat_data($val['tk_id']);
				
				/*$r["IMG"] 		= URL."public/IMG/co/".$val["co_img"];
				$r["LINK"]		= URL."dashboard/tickets/".$val['co_id'];
				*/
				array_push($ret,$r);
			}
			return $ret;
		}
		
		/**
		* function chatData
		* get chat data
		*/
		public function chat_data($ticketID = 0,$last_msg = 0)
		{
			if(session::get("user_id") == false)
			{
				return array();
			}
			
			$form	= new form();
			if(!$form->single_valid($ticketID,'Integer'))
			{
				return array();
			}
			
			$sea_txt = "";
			$sea_arr = array(":ID"=>$ticketID);
			
			if(empty(session::get('company')))
			{
				//for Admin
				$sea_txt .= "msg_ticket = :ID AND ";
			}else
			{
				//for Customer
				$sea_txt .= "msg_ticket IN (SELECT tk_id FROM ".DB_PREFEX."ticket 
											WHERE tk_id=:ID AND create_by = :USER) AND ";
				$sea_arr[":USER"] = session::get('user_id');
			}
			
			if($form->single_valid($last_msg,'numeric'))
			{
				$sea_txt .= "msg_id > :LST AND ";
				$sea_arr[":LST"] = $last_msg;
			}
			
			$x = $this->db->select("SELECT msg_id, msg_text, ch.create_at  
										,staff_id, staff_name, staff_name_en, staff_img, staff_company
										FROM ".DB_PREFEX."ticket_msg as ch
										JOIN ".DB_PREFEX."staff ON ch.create_by = staff_id 
										WHERE $sea_txt 1 = 1
										ORDER BY msg_id"
										,$sea_arr);
			
			$ret = array();
			foreach($x as $val)
			{
				$x	= array();
				$x['ID']		= $val['msg_id'];
				$x['TEXT']		= str_replace("\n", "<br/>", $val['msg_text']);
				$x['FR_ID']		= $val['staff_id'];
				$x['FR_NAME']	= $val['staff_name'];
				$x['FR_IMG']	= URL."public/IMG/user/".$val["staff_img"];
				$x['DATE']		= $val['create_at'];
				
				if(empty(session::get('company')))
				{
					//for Admin
					$x['CLASS']		= ($val['staff_company'] == null)?"":"chat-left";
				}else
				{
					$x['CLASS']		= (session::get("user_id") == $val['staff_id'])?"":"chat-left";
				}
				array_push($ret,$x);
			}
			return $ret;
		}
		
		
		/**
		* function addChat
		* Add New Chat
		* AJAX
		*/
		public function addChat()
		{
			$form	= new form();
			
			$form	->post('ticket_id')
					->valid('Integer')
					
					->post('chat_msg')
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check chatRoom
			$sea_arr = array(':NO'=> $fdata['ticket_id']);
			$sea_txt = 'tk_id = :NO AND ';
			
			if(!empty(session::get('company')))
			{
				//for Customer
				$sea_txt .= "tk.create_by = :USER AND ";
				$sea_arr[":USER"] = session::get('user_id');
			}
			$sea_txt .= ' 1=1 ';
			
			$room = $this->db->select("SELECT tk_id, tk_status
										FROM ".DB_PREFEX."ticket AS tk
										WHERE $sea_txt
										" ,$sea_arr
								);
			
			
			
			if(count($room) != 1)
			{
				return array('Error'=>"Chat Room Not Defined");
			}
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			if($room[0]['tk_status'] == 0)
			{
				$this->db->update(DB_PREFEX.'ticket',array("tk_status"=>1),"tk_id = ".$fdata['ticket_id']);
			}
			
			//insert
			$user_array = array('msg_ticket'=>$fdata['ticket_id']
								,'msg_text'	=>$fdata["chat_msg"]
								,'create_by'=>session::get("user_id")
								,'create_at'=>$time
								);
				
			$this->db->insert(DB_PREFEX.'ticket_msg',$user_array);
				
			return array('ok'=>$this->db->LastInsertedId());
			
		}
		
		/**
		* function add_ticket
		* New ticket
		* AJAX
		*/
		public function add_ticket()
		{
			$form	= new form();
			
			$form	->post('msg_comm') // msg
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			if(empty(session::get("company")))
			{
				return array();
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$ty_array = array('tk_user_name'	=>session::get('user_name')
							,'tk_user_email'	=>session::get('user_email')
							,'tk_desc'			=>$fdata['msg_comm']
							,'tk_status'		=>1
							,'create_by'		=>session::get('user_id')
							,'create_at'		=>$time
							);
			$this->db->insert(DB_PREFEX.'ticket',$ty_array);
			$id = $this->db->LastInsertedId();
			
			return array('id'=>$id);
			
		}
		
		
	}
?>