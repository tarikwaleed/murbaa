<?php
	/**
	* staff MODEL, 
	*/
	class staff_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function conf
		* get package_config
		*/
		public function conf_list()
		{
			$ret = array();
			
			if(empty(session::get('company')))
			{
				//for Admin
				$ret['admin'] = true;
				$ret['no_user'] = null;
				$ret['msg']	= true;
			}else
			{
				//for Customer
				$ret['admin'] = false;
				$ret['no_user'] = 1;
				$ret['msg']	= false;
				
				$pk = $this->db->select("SELECT pk_id, pk_name, pk_stars, pk_users, pk_users_msg, pk_adv_area
										FROM ".DB_PREFEX."package
										JOIN ".DB_PREFEX."company ON pk_id = co_package
										WHERE co_id = :ID
										" ,array(':ID'=>session::get('company'))
								);
				if(count($pk) == 1)
				{
					$ret['no_user'] = $pk[0]['pk_users'];
					$ret['msg']		= ($pk[0]['pk_users_msg'] == 1);
				}
			}
			
			return $ret;
		}
		
		/**
		* function per_list
		* get permission list
		*/
		public function per_list()
		{
			$wh = "";
			$wh_array = array();
			if(empty(session::get('company')))
			{
				//for Admin
				$wh .= "per_type = 'ADMIN' AND ";
			}else
			{
				//for Customer
				$wh .= "per_type = 'CUSTOMER' AND (per_company = :CO OR per_id = 2) AND ";
				$wh_array[":CO"] = session::get('company');
			}
			
			
			$per = $this->db->select("SELECT per_id, per_name, count(per_group_page) AS per_no
										FROM ".DB_PREFEX."permission_groups
										JOIN ".DB_PREFEX."per_group_pages ON per_group_permission = per_id
										WHERE $wh 1=1
										GROUP BY per_id
										" ,$wh_array
								);
			$ret = array();
			foreach($per as $val)
			{
				$ret[$val["per_id"]] = array("NAME"=>$val['per_name'],"PER_NO" =>$val['per_no']);
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
				$sea_txt .= 'staff_name like :NAME AND ';
			}
			if(!empty($fdata['phone']))
			{
				$sea_arr[':PH'] = $fdata['phone'];
				$sea_txt .= 'staff_phone = :PH AND ';
			}
			if(!empty($fdata['email']))
			{
				$sea_arr[':ACA'] = $fdata['email'];
				$sea_txt .= 'staff_email = :ACA AND ';
			}
			
			if(empty(session::get('company')))
			{
				//for Admin
				$sea_txt .= "staff_company IS NULL AND ";
			}else
			{
				//for Customer
				$sea_txt .= "staff_company = :CO AND ";
				$sea_arr[":CO"] = session::get('company');
			}
			
			$staff = $this->db->select("SELECT staff_id, staff_name, staff_phone, staff_address
										,staff_email, staff_active, staff_img
										,staff_comm, staff_nat_no, staff_permission
										,(staff_id = 1 OR 
											staff_id IN (SELECT create_by FROM ".DB_PREFEX."company 
														WHERE co_id = staff_company) ) AS admin_us
										FROM ".DB_PREFEX."staff
										WHERE $sea_txt 1=1
										GROUP BY staff_id
										ORDER BY staff_name ASC
										" ,$sea_arr
								);
			$ret = array();
			foreach($staff as $val)
			{
				$r = array();
				$r["ID"] 		= $val['staff_id'];
				$r["NAME"] 		= $val['staff_name'];
				$r["PHONE"] 	= $val['staff_phone'];
				$r["NAT_NO"] 	= $val['staff_nat_no'];
				$r["ADDRESS"] 	= $val['staff_address'];
				$r["EMAIL"] 	= $val['staff_email'];
				$r["IMG"] 		= URL."public/IMG/user/".$val['staff_img'];
				$r["ACTIVE"] 	= $val['staff_active'];
				$r["COMM"] 		= $val['staff_comm'];
				$r["PER"] 		= $val['staff_permission'];
				$r["ADMIN"]		= ($val['staff_id'] == session::get('user_id') || $val['admin_us'] == 1)? 1:0;
				
				array_push($ret,$r);
			}
			return $ret;
		}
		
		/**
		* function new_Staff
		* create new staff
		*/
		public function new_Staff()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form	= new form();
			
			$form	->post('new_name') // Name
					->valid('Min_Length',3)
					
					->post('new_permission')
					->valid('numeric')
					
					->post('new_phone')
					->valid('Phone')
					
					->post('new_email')
					->valid('Email')
					
					->post('new_nat_no') // pass
					->valid('Min_Length',7)
					
					->post('new_pass') // pass
					->valid('Min_Length',3)
					
					->post('new_conf_pass') // pass
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check Email:
			$em = $this->db->select("SELECT staff_email, staff_phone, staff_nat_no 
									FROM ".DB_PREFEX."staff 
									WHERE staff_email = :AD 
										OR staff_phone = :PH 
										OR staff_nat_no = :NAT"
									,array(":AD"=>$fdata['new_email'],
											":PH"=>$fdata['new_phone']
											,":NAT"=>$fdata['new_nat_no']));
			if(count($em) != 0)
			{
				$err = "";
				foreach($em as $val)
				{
					if($val['staff_phone'] == $fdata['new_phone'])
					{
						$err .= "In Field new_phone : Duplicate .. \n";
					}
					if($val['staff_email'] == $fdata['new_email'])
					{
						$err .= "In Field new_email : Duplicate .. \n";
					}
					if($val['staff_nat_no'] == $fdata['new_nat_no'])
					{
						$err .= "In Field new_nat_no : Duplicate .. \n";
					}
				}
				if(!empty($err))
				{
					return array('Error'=>$err);
				}
			}
			
			//check password
			if($fdata['new_pass'] != $fdata['new_conf_pass'])
			{
				return array('Error'=>"In Field new_conf_pass : not match .. \n");
			}
			
			//insert
			$user_array = array('staff_name'		=> $fdata['new_name']
								,'staff_name_en'	=> $fdata['new_name']
								,'staff_nat_no'		=> $fdata['new_nat_no']
								,'staff_phone'		=> $fdata['new_phone']
								,'staff_permission'	=> $fdata['new_permission']
								,'staff_email'		=> $fdata['new_email']
								,'staff_pass'		=> Hash::create(HASH_FUN,$fdata['new_pass'],HASH_PASSWORD_KEY)
								,'create_at'		=> $time
								,'create_by'		=> session::get('user_id')
								);
			
			if(!empty(session::get('company')))
			{
				//check max staff
				$pk = $this->db->select("SELECT count(staff_id) AS curr_users,pk_users
											FROM ".DB_PREFEX."company
											JOIN ".DB_PREFEX."staff ON staff_company = co_id
											JOIN ".DB_PREFEX."package ON pk_id = co_package
											WHERE co_id = :ID
											GROUP BY co_id
											" ,array(":ID"=>session::get('company'))
											);
				if(count($pk)!= 1)
				{
					return array('Error'=>"لم يتم العثور على بيانات الحساب");
				}
				$pk = $pk[0];
				if($pk['curr_users'] >= $pk['pk_users'] && $pk['pk_users'] != null)
				{
					return array('Error'=>"لا يمكنك اضافة مستخدم لقد بلغت حد عدد المستخدمين, قم بتطوير الباقة");
				}
				
				$user_array['staff_company'] = session::get('company');
				
			}
			
			$this->db->insert(DB_PREFEX.'staff',$user_array);
			
			return array('id'=>$this->db->LastInsertedId());
		}
		
		/**
		* function upd_Staff
		* update staff
		*/
		public function upd_Staff()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form	= new form();
			
			$form	->post('upd_id') // id
					->valid('Integer')
					
					->post('upd_name') // Name
					->valid('Min_Length',3)
					
					->post('upd_permission')
					->valid('numeric')
					
					->post('upd_pass',false,true) // pass
					->valid('Min_Length',3)
					
					->post('upd_nat_no') // pass
					->valid('Min_Length',7)
					
					->post('upd_email') // Email
					->valid('Email')
					
					->post('upd_phone') // phone
					->valid('Phone')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check NO:
			$em = $this->db->select("SELECT staff_id FROM ".DB_PREFEX."staff 
									WHERE staff_id = :ID"
									,array(":ID"=>$fdata['upd_id']));
			if(count($em) != 1)
			{
				return array('Error'=>"لم يتم العثور على الموظف");
			}
			
			//check Email:
			$em = $this->db->select("SELECT staff_email, staff_phone, staff_nat_no 
									FROM ".DB_PREFEX."staff 
									WHERE (staff_email = :AD 
										OR staff_phone = :PH 
										OR staff_nat_no = :NAT) AND staff_id != :ID"
									,array(":ID"=>$fdata['upd_id']
											,":AD"=>$fdata['upd_email']
											,":PH"=>$fdata['upd_phone']
											,":NAT"=>$fdata['upd_nat_no']));
			if(count($em) != 0)
			{
				$err = "";
				foreach($em as $val)
				{
					if($val['staff_phone'] == $fdata['upd_phone'])
					{
						$err .= "In Field upd_phone : Duplicate .. \n";
					}
					if($val['staff_email'] == $fdata['upd_email'])
					{
						$err .= "In Field upd_email : Duplicate .. \n";
					}
					if($val['staff_nat_no'] == $fdata['upd_nat_no'])
					{
						$err .= "In Field upd_nat_no : Duplicate .. \n";
					}
				}
				if(!empty($err))
				{
					return array('Error'=>$err);
				}
			}
			
			//Update
			$user_array = array('staff_name'		=> $fdata['upd_name']
								,'staff_name_en'	=> $fdata['upd_name']
								,'staff_nat_no'		=> $fdata['upd_nat_no']
								,'staff_phone'		=> $fdata['upd_phone']
								,'staff_permission'	=> $fdata['upd_permission']
								,'staff_email'		=> $fdata['upd_email']
								,'update_at'		=> $time
								,'update_by'		=> session::get('user_id')
								);
			
			$this->db->update(DB_PREFEX.'staff',$user_array,'staff_id = '.$fdata['upd_id']);
			
			return array('id'=>$fdata['upd_id']);
		}
		
		/**
		* function del_Staff
		* delete staff
		*/
		public function del_Staff()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form	= new form();
			
			$form	->post('upd_id') // Admission
					->valid('Integer')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check NO:
			$em = $this->db->select("SELECT staff_id FROM ".DB_PREFEX."staff 
									WHERE staff_id = :ID"
									,array(":ID"=>$fdata['upd_id']));
			if(count($em) != 1)
			{
				return array('Error'=>"لم يتم العثور على الموظف");
			}
			
			$this->db->delete(DB_PREFEX.'staff','staff_id = '.$fdata['upd_id']);
			
			return array('id'=>$fdata['upd_id']);
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
			$data = $this->db->select("SELECT staff_active 
									FROM ".DB_PREFEX."staff 
									WHERE staff_id = :ID"
									,array(":ID"=>$fdata['id']));
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على الموظف");
			}
			
			$curr = ($data[0]['staff_active']==1)?true:false;
			
			if(($fdata['current'] == "true" && !$curr)||($fdata['current']== "false" && $curr))
			{
				return array('Error'=>'حالة الموظف الحالية هي  '.$curr.' - '.$fdata['current']);
			}
			
			
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$upd_array = array();
			$upd_array['staff_active'] 	= ($curr)?0:1;
			$upd_array['update_at'] 	= $time;
			$upd_array['update_by'] 	= session::get('user_id');
			
			$this->db->update(DB_PREFEX.'staff',$upd_array,'staff_id = '.$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
			
		/**
		* function msg_staff
		* msg_staff
		* AJAX
		*/
		public function msg_staff()
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
				$data = $this->db->select("SELECT staff_phone, staff_email FROM ".DB_PREFEX."staff 
										WHERE staff_id = :ID"
										,array(":ID"=>$val));
				if(count($data) != 1)
				{
					$error .= "لم يتم العثور على الموظف ".$val." \n ";
					continue;
				}
				$data = $data[0];
				if(!empty($fdata['email_msg']))
				{
					$em = $email->send_email($data['staff_email'],"MSG",$fdata['msg_comm']);
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
					$em = $email->send_SMS($data['staff_phone'],$fdata['msg_comm']);
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
			
		
	}
?>