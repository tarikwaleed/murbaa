<?php
	/**
	* Model LOGIN, 
	*/
	class login_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function login
		* get login user date
		*/
		public function login()
		{
			$form = new form();
			
			$form	->post('usrname')
					->valid('Min_Length',1)
					->valid('Max_Length',90)
					
					->post('psw')
					->valid('Min_Length',2)
					->valid('Max_Length',90)
					
					->post('captcha')
					->valid('Int_max',9999)
					->valid('Int_min',1000)
					
					->submit();
						
			$req = $form->fetch();
			
			if($req['MSG']!= '')
			{
				return "INPUT_ERROR";
			}
			if(session::get("captcha") != $req['captcha'])
			{
				$err_log_time = (session::get("error_log_time")== false)?0:session::get("error_log_time");
				session::set("error_log_time",$err_log_time + 1);
				return "رمز التحقق خاطئ ";
			}
			
			$data = $this->db->select("SELECT staff_id, staff_name
									,staff_permission, staff_img
									,staff_email, staff_pass, staff_active
									,staff_company, co_name, co_img, co_active, co_package
									,if(co_package_end is null,356, DATEDIFF(`co_package_end`,now())) AS DIFF
									,pk_name, pk_stars, pk_users, pk_adv_area, pk_vip_area
									,per_name, per_type 
									,page_class, page
									FROM ".DB_PREFEX."staff 
									JOIN ".DB_PREFEX."permission_groups ON per_id = staff_permission
									JOIN ".DB_PREFEX."pages ON per_default_page = page_id
									LEFT JOIN ".DB_PREFEX."company ON co_id = staff_company
									LEFT JOIN ".DB_PREFEX."package ON pk_id = co_package
									WHERE 
									staff_email = :login" ,
									array(':login'=>$req['usrname'])
								);
			
			
			if(count($data) != 1 || $data[0]['staff_pass'] != Hash::create(HASH_FUN,$req['psw'],HASH_PASSWORD_KEY))
			{
				return "INPUT_ERROR";
			}
			if($data[0]['staff_active'] != 1)
			{
				return "UNACTIVE";
			}
			if($data[0]['co_active'] === 0)
			{
				return "UNACTIVE Company";
			}
			if($data[0]['DIFF']<= 0)
			{
				
				$em = $this->db->select("SELECT pk_id FROM ".DB_PREFEX."package WHERE pk_price = 0",array());
				if(count($em) == 0)
				{
					return "Your Package expierd Please Contact System Administrator";
				}
				$pk_upd = array("co_package"=>$em[0]['pk_id'],'co_package_end'=>null);
				
				$this->db->update(DB_PREFEX.'company',$pk_upd,"co_id = ".$data[0]['staff_company']);
			
			}
			
			$data = $data[0];
			
			$pages = $this->db->select("SELECT page_class,page
										FROM ".DB_PREFEX."per_group_pages 
										JOIN ".DB_PREFEX."pages ON per_group_page = page_id
										WHERE 
										per_group_permission = :GROUP" ,
									array(':GROUP'=>$data['staff_permission'])
								);
			
			
			$data['pages'] = array();
			foreach($pages as $key => $val)
			{
				if(empty($data['pages'][$val['page_class']]))
				{
					$data['pages'][$val['page_class']] = array();
				}
				array_push($data['pages'][$val['page_class']],$val['page']);
				
			}
			
			return $data;
		}
		
		/**
		* function forget_request
		* create Forget Password reset link
		*/
		public function forget_request()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form = new form();
			
			$form	->post('usrname')
					->valid('Min_Length',2)
					->valid('Max_Length',90)
					
					->submit();
						
			$req = $form->fetch();
			
			if($req['MSG']!= '')
			{
				return array('Error'=>$req['MSG']);
			}
			
			$data = $this->db->select("SELECT staff_id, staff_name 
									,staff_email
									FROM ".DB_PREFEX."staff 
									WHERE 
									staff_email = :login and staff_active = 1" ,
									array(':login'=>$req['usrname'])
								);
			
			if(count($data) != 1)
			{
				return array('Error'=>"In Field usrname: Not Found");
			}
			$data = $data[0];
			
			//insert
			$user_array = array('for_user'		=>$data['staff_id']
								,'create_at'	=>$time
								);
			$this->db->insert(DB_PREFEX.'forget',$user_array);
			$id = $this->db->LastInsertedId();
			
			//send Email:
			$email = new Email();
			
			$x = $email->forget($data['staff_name'],$data['staff_email'],$id,$time);
			return array('ok'=>$x);
		}
		
		/**
		* function resetpassword
		* check reset request
		*/
		public function resetpassword($id)
		{
			
			if(is_nan($id))
			{
				return "Error ID";
			}
			
			$data = $this->db->select("SELECT for_id, create_at, HOUR(TIMEDIFF(NOW(),create_at)) AS h
									FROM ".DB_PREFEX."forget 
									WHERE 
									for_id = :login " ,
									array(':login'=>$id)
								);
			
			if(count($data) != 1)
			{
				return "Your request Not Founded, Please Try again ".count($data);
			}
			if($data[0]['h'] > 24)
			{
				return "Your request expered, Please Try again";
			}
			return $data[0];
		}
				
		/**
		* update_res_password
		* to update password
		*/
		public function update_res_password()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form = new form();
			
			$form	->post('id')
					->valid('Integer')
						
					->post('psw')
					->valid('Min_Length',2)
					->valid('Max_Length',90)
						
					->post('psw2')
					->valid('Min_Length',2)
					->valid('Max_Length',90)
						
					->submit();
			$d = $form->fetch();
			
			if(!empty($d['MSG']))
			{
				return array('Error'=>$d['MSG']);
			}
			if($d['psw'] != $d['psw2'])
			{
				return array('Error'=>"In Field psw2 : Not match .. \n");
			}
			
			$data = $this->db->select("SELECT for_id, for_user, create_at, HOUR(TIMEDIFF(NOW(),create_at)) AS h
									FROM ".DB_PREFEX."forget 
									WHERE 
									for_id = :login 
									" ,
									array(':login'=>$d['id'])
								);
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على الطلب الرجاء المحاولة مرة اخرى");
			}
			if($data[0]['h'] > 24)
			{
				return array('Error'=>"لقد انتهت صلاحية رابط اعادة ضبط كلمة المرور, الرجاء المحاولة مرة اخرى");
			}
			
			if(!empty($data[0]['for_user']))
			{
				$this->db->update(DB_PREFEX.'staff',
									array('staff_pass'=>Hash::create(HASH_FUN,$d['psw'],HASH_PASSWORD_KEY)
									,'update_at'=>$time),
								'staff_id ='.$data[0]['for_user']);
			}
			session::destroy();
			return array("ok"=>1);
		}
		
		/**
		* function reg
		* reg user date
		*/
		public function reg()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form = new form();
			$form	->post('usrname')
					->valid('Min_Length',2)
					->valid('Max_Length',90)
					
					->post('email')
					->valid('Email')
					
					->post('phone')
					->valid('Phone')
					
					->post('psw')
					->valid('Min_Length',2)
					->valid('Max_Length',90)
					
					->post('psw2')
					->valid('Min_Length',2)
					->valid('Max_Length',90)
					
					->post('accept')
					->valid('Integer')
					
					->submit();
						
			$req = $form->fetch();
			
			if($req['MSG']!= '')
			{
				return array("Error"=>$req['MSG']);
			}
			
			//check Accept
			if($req['accept']!= 1)
			{
				return array('Error'=>"In Field accept : not match .. \n");
			}
			
			//check password
			if($req['psw']!= $req['psw2'])
			{
				return array('Error'=>"In Field reg_conf_pass : not match .. \n");
			}
			
			//check EMAIL ,phone ,card in reqest table
			$em = $this->db->select("SELECT staff_id, staff_email ,staff_phone
									FROM ".DB_PREFEX."staff 
									WHERE staff_email = :EMAIL 
										OR staff_phone = :PHONE"
									,array(':EMAIL'=>$req['email']
											,':PHONE'=>$req['phone']));
			if(count($em) != 0)
			{
				$err = "";
				foreach($em as $val)
				{
					if($val['staff_email'] == $req['email'])
					{
						$err .= "In Field email : Duplicate .. \n";
					}
					if($val['staff_phone'] == $req['phone'])
					{
						$err .= "In Field phone : Duplicate .. \n";
					}
				}
				return array('Error'=>$err);
			}
			
			//Get Free Package
			$em = $this->db->select("SELECT pk_id,pk_name, pk_stars, pk_users, pk_adv_area, pk_vip_area
									FROM ".DB_PREFEX."package WHERE pk_price = 0",array());
			if(count($em) == 0)
			{
				return array('Error'=>"Registration Not Available now Please Contact System Administrator");
			}
			
			//insert user
			$user_array = array('staff_name'		=>$req['usrname']
								,'staff_name_en'	=>$req['usrname']
								,'staff_phone'		=>$req['phone']
								,'staff_email'		=>$req['email']
								,'staff_pass'		=>Hash::create(HASH_FUN,$req['psw'],HASH_PASSWORD_KEY)
								,'create_at'		=>$time
								);
				
			$this->db->insert(DB_PREFEX.'staff',$user_array);
			$user_array = array_merge($user_array,$em[0]);
			
			$user_array['staff_id'] 	= $this->db->LastInsertedId();
			$user_array['staff_img'] 	= "logo1.png";
			$user_array['staff_active'] = 1;
			
			$MSG = "الاستاذ: ".$req['usrname']."<br/>مرحبا بك في ".TITLE." <br/>";
			
			//Insert co
			$co_array = array('co_name'		=>$req['usrname']
							,'co_name_en'	=>$req['usrname']
							,'co_package'	=>$user_array['pk_id']
							,'co_phone'		=>$req['phone']
							,'co_email'		=>$req['email']
							,'create_at'	=>$time
							,'create_by'	=>$user_array['staff_id']
							);
			$this->db->insert(DB_PREFEX.'company',$co_array);
			
			$user_array['staff_company']= $this->db->LastInsertedId();
			$user_array['co_name'] 		= $req['usrname'];
			$user_array['co_img'] 		= "logo1.png";
			$this->db->update(DB_PREFEX.'staff',array("staff_company"=>$user_array['staff_company']),"staff_id = ".$user_array['staff_id']);
			
			//get permission_groups
			$data = $this->db->select("SELECT per_name, per_type 
									,page_class, page
									FROM ".DB_PREFEX."permission_groups
									JOIN ".DB_PREFEX."pages ON per_default_page = page_id
									WHERE per_id = 2" ,
									array(':login'=>$req['usrname'])
								);
			$data = $data[0];
			
			$pages = $this->db->select("SELECT page_class,page
										FROM ".DB_PREFEX."per_group_pages 
										JOIN ".DB_PREFEX."pages ON per_group_page = page_id
										WHERE per_group_permission = 2" ,
									array()
								);
								
			$data['pages'] = array();
			foreach($pages as $key => $val)
			{
				if(empty($data['pages'][$val['page_class']]))
				{
					$data['pages'][$val['page_class']] = array();
				}
				array_push($data['pages'][$val['page_class']],$val['page']);
				
			}
			
			$user_array = array_merge($user_array,$data);
			
			$em = new Email();
			$em->send_email($req['email'],"انشاء حساب",$MSG);
			
			return $user_array;
		}
		
		
	}
?>