<?php
	/**
	* customer MODEL, 
	*/
	class customer_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function package
		* get package list
		*/
		public function package()
		{
			$xx = $this->db->select("SELECT pk_id AS ID, pk_name AS NAME, pk_name_EN AS NAME_EN
										,pk_stars AS STARS, pk_users AS USERS, pk_adv_area AS ADV_AREA
										FROM ".DB_PREFEX."package
										WHERE 1=1
										" ,array());
			$ret = array();
			foreach($xx as $val)
			{
				$ret[$val['ID']] = $val;
			}
			return $ret;
		}
		
		/**
		* function user_list
		* get users list
		*/
		public function user_list()
		{
			$form	= new form();
			
			$form	->post('name',false,true) // Name
					->valid('Min_Length',3)
					
					->post('email',false,true) // email
					->valid('Email')
					
					->post('phone',false,true) // phone
					->valid('Phone')
					
					->post('package',false,true) // phone
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return 'Error: '.$fdata['MSG'];
			}
			
			$sea_arr = array();
			$sea_txt = "";
			
			if(!empty($fdata['name']))
			{
				$sea_arr[':NAME'] = "%".$fdata['name']."%";
				$sea_txt .= 'co_name like :NAME AND ';
			}
			if(!empty($fdata['phone']))
			{
				$sea_arr[':PH'] = $fdata['phone'];
				$sea_txt .= 'co_phone = :PH AND ';
			}
			if(!empty($fdata['email']))
			{
				$sea_arr[':ACA'] = $fdata['email'];
				$sea_txt .= 'co_email = :ACA AND ';
			}
			if(!empty($fdata['package']))
			{
				$sea_arr[':PK'] = $fdata['package'];
				$sea_txt .= 'co_package = :PK AND ';
			}
			$sea_txt .= ' 1=1 ';
			
			$customer = $this->db->select("SELECT co_id, co_name, co_name_en, co_phone, co_address
										,co_email, co_img, co_desc, co_active
										,count(staff_id) AS STAFF, co_package
										,comm_id, comm_accept, comm_exp_date
										FROM ".DB_PREFEX."company
										JOIN ".DB_PREFEX."staff ON co_id = staff_company
										LEFT JOIN ".DB_PREFEX."comm_reg AS COMM
											ON comm_co = co_id 
											AND (now() BETWEEN DATE(COMM.create_at) AND comm_exp_date OR comm_exp_date IS NULL )
										WHERE $sea_txt
										GROUP BY co_id
										ORDER BY co_name ASC
										" ,$sea_arr
								);
			$ret = array();
			foreach($customer as $val)
			{
				$r = array();
				$r["ID"] 		= $val['co_id'];
				$r["NAME"] 		= $val['co_name'];
				$r["PHONE"] 	= $val['co_phone'];
				$r["ADDRESS"] 	= $val['co_address'];
				$r["EMAIL"] 	= $val['co_email'];
				$r["DESC"] 		= $val['co_desc'];
				$r["ACTIVE"] 	= $val['co_active'];
				$r["STAFF"] 	= $val['STAFF'];
				$r["PK_ID"] 	= $val['co_package'];
				$r["ACCEPT"] 	= $val["comm_accept"];
				$r["REG_ID"]	= $val["comm_id"];
				
				$r["IMG"] 		= URL."public/IMG/co/".$val["co_img"];
				$r["LINK"]		= URL."dashboard/customer/".$val['co_id'];
				
				$lands = $this->db->select("SELECT count(l_id) AS LANDS
										FROM ".DB_PREFEX."land
										WHERE l_co = :ID
										" ,array(":ID"=>$val['co_id'])
									);
				
				$r["LANDS"] 	= $lands[0]['LANDS'];
				
				if(empty($r['REG_ID']))
				{
					$last_reg = $this->db->select("SELECT max(comm_exp_date) AS COMM_EXPERD
											FROM ".DB_PREFEX."comm_reg
											WHERE comm_co = :ID"
											,array(":ID"=>session::get("company")));
					if(count($last_reg) == 1)
					{
						$r['COMM_EXPERD'] = $last_reg[0]['COMM_EXPERD'];
					}
				}else
				{
					$r['COMM_EXPERD'] = $val['comm_exp_date'];
				}
				
				
				array_push($ret,$r);
			}
			return $ret;
		}
		
		/**
		* function msg_customer
		* msg_customer
		* AJAX
		*/
		public function msg_customer()
		{
			$form	= new form();
			
			$form	->post('msg_user') // users
					->valid_array('Integer')
					
					->post('msg_comm') // MSG
					->valid('Min_Length',5)
					
					->post('sms_msg',false,true) // SMS
					->valid('Integer')
					
					->post('email_msg',false,true) // Email
					->valid('Integer')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			$email 		= new Email();
			
			$sent_email = 0;
			$sent_sms 	= 0;
			$error 		= "";
			
			foreach($fdata['msg_user'] as $val)
			{
				//check NO:
				$data = $this->db->select("SELECT co_phone, co_email FROM ".DB_PREFEX."company 
										WHERE co_id = :ID"
										,array(":ID"=>$val));
				if(count($data) != 1)
				{
					$error .= "لم يتم العثور على العميل ".$val." \n ";
					continue;
				}
				$data = $data[0];
				if(!empty($fdata['email_msg']))
				{
					$em = $email->send_email($data['co_email'],"MSG",$fdata['msg_comm']);
					if($em === true)
					{
						$sent_email ++;
					}else
					{
						$error .= $em." d";
					}
				}
				if(!empty($fdata['sms_msg']))
				{
					$em = $email->send_SMS($data['co_phone'],$fdata['msg_comm']);
					if($em === true)
					{
						$sent_sms ++;
					}else
					{
						$error .= $em." d";
					}
				}
			}
			
			$ret = array();
			if(!empty($error))
			{
				$ret['Error'] = $error;
			}
			$ret['total'] 	= count($fdata['msg_user']);
			$ret['email'] 	= $sent_email;
			$ret['sms'] 	= $sent_sms;
			
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
					->valid('Integer')
					
					->post('current',false,true) // Name
					->valid('In_Array',array('true','false'))
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check NO:
			$data = $this->db->select("SELECT co_active 
									FROM ".DB_PREFEX."company 
									WHERE co_id = :ID"
									,array(":ID"=>$fdata['id']));
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على العميل");
			}
			
			$curr = ($data[0]['co_active']==1)?true:false;
			
			if(($fdata['current'] == "true" && !$curr)||($fdata['current']== "false" && $curr))
			{
				return array('Error'=>'حالة العميل الحالية هي  '.$curr.' - '.$fdata['current']);
			}
			
			
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$upd_array = array();
			$upd_array['co_active'] 	= ($curr)?0:1;
			$upd_array['update_at'] 	= $time;
			$upd_array['update_by'] 	= session::get('user_id');
			
			$this->db->update(DB_PREFEX.'company',$upd_array,'co_id = '.$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
			
		
	}
?>