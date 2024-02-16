<?php
	/**
	* mail_list MODEL, 
	*/
	class mail_list_model extends model
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
			
			$form	->post('email',false,true) // email
					->valid('Email')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return 'Error: '.$fdata['MSG'];
			}
			
			$sea_arr = array();
			$sea_txt = "";
			
			if(!empty($fdata['email']))
			{
				$sea_arr[':ACA'] = $fdata['email'];
				$sea_txt .= 'mail_name = :ACA AND ';
			}
			$sea_txt .= ' 1=1 ';
			
			$mail_list = $this->db->select("SELECT mail_name, mail_active, create_at
										FROM ".DB_PREFEX."mail_list
										WHERE $sea_txt
										ORDER BY create_at ASC
										" ,$sea_arr
								);
			$ret = array();
			foreach($mail_list as $val)
			{
				$r = array();
				$r["EMAIL"] 	= $val['mail_name'];
				$r["ACTIVE"] 	= $val['mail_active'];
				$r["CREATE"] 	= $val['create_at'];
				array_push($ret,$r);
			}
			return $ret;
		}
		
		/**
		* function msg_mail_list
		* msg_mail_list
		* AJAX
		*/
		public function msg_mail_list()
		{
			$form	= new form();
			
			$form	->post('msg_user') // users
					->valid_array('Integer')
					
					->post('msg_comm') // MSG
					->valid('Min_Length',5)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			$email 		= new Email();
			
			$sent_email = 0;
			$error 		= "";
			
			$em = $email->send_email($fdata['msg_user'],"MSG",$fdata['msg_comm']);
			
			if($em === true)
			{
				$sent_email = count($fdata['msg_user']);
			}else
			{
				$error .= $em." d";
			}
			
			$ret = array();
			if(!empty($error))
			{
				$ret['Error'] = $error;
			}
			$ret['total'] 	= count($fdata['msg_user']);
			$ret['email'] 	= $sent_email;
			
			return $ret;
		}
			
		/**
		* function active
		* active / freez agent
		* AJAX
		*/
		public function active()
		{
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('Email')
					
					->post('current',false,true) // Name
					->valid('In_Array',array('true','false'))
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check NO:
			$data = $this->db->select("SELECT mail_name, mail_active, create_at
									FROM ".DB_PREFEX."mail_list
									WHERE mail_name LIKE :ID"
									,array(":ID"=>$fdata['id']));
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على العميل ".$fdata['id']);
			}
			
			$curr = ($data[0]['mail_active']==1)?true:false;
			
			if(($fdata['current'] == "true" && !$curr)||($fdata['current']== "false" && $curr))
			{
				return array('Error'=>'حالة العميل الحالية هي  '.$curr.' - '.$fdata['current']);
			}
			
			$this->db->delete(DB_PREFEX.'mail_list',"mail_name LIKE '".$fdata['id']."'");
			
			return array('id'=>$fdata['id']);
		}
			
		
	}
?>