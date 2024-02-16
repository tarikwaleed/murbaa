<?php
	/**
	* my_co MODEL, 
	*/
	class my_co_model extends model
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
										, pk_adv_pay AS ADV_PAY
										,pk_price AS PRICE, pk_users_msg AS MSG, pk_vip_area AS VIP
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
		* sys_info get All my_co
		*/
		public function sys_info()
		{
			$ret = array();
			
			//my_co
			$my_co = $this->db->select("SELECT co_id AS ID, co_name AS NAME 
										,co_name_en AS NAME_EN, co_phone AS PHONE
										,co_img AS IMG, co_address AS ADDRESS
										,co_desc AS DESCR, co_email AS EMAIL
										,co_package AS PKG, co_package_end AS BK_END
										,COMP.create_by AS AD_ID
										,comm_id AS REG_ID, comm_exp_date AS COMM_EXPERD
										,comm_accept AS CO_ACCEPT, comm_real_no AS CO_REAL_NO
										,comm_no AS CO_REG_NO, comm_co_num AS CO_WORK_NO
										,comm_file AS CO_REG_FILE
										FROM ".DB_PREFEX."company AS COMP
										LEFT JOIN ".DB_PREFEX."comm_reg AS COMM
											ON comm_co = co_id 
											AND now() BETWEEN DATE(COMM.create_at) AND comm_exp_date
										WHERE co_id = :ID"
										,array(":ID"=>session::get("company")));
			
			$ret 				= $my_co[0];
			$ret["IMG"] 		= URL."public/IMG/co/".$ret['IMG'];
			if(empty($ret['REG_ID']))
			{
				$last_reg = $this->db->select("SELECT max(comm_exp_date) AS COMM_EXPERD
										FROM ".DB_PREFEX."comm_reg
										WHERE comm_co = :ID"
										,array(":ID"=>session::get("company")));
				if(count($last_reg) == 1)
				{
					$ret['COMM_EXPERD'] = $last_reg[0]['COMM_EXPERD'];
				}
			}
			
			$ret['ADMIN'] = $ret['AD_ID'] == session::get("user_id");
			return $ret;
		}
		
		/*
		* function upd_info
		* update my_co
		*/
		public function upd_info()
		{
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$form	= new form();
			
			$form	->post('new_co_name') //name
					->valid('Min_Length',3)
			
					->post('new_co_phone') //Phone
					->valid('Min_Length',10)
					
					->post('new_co_email') //EMAIL
					->valid('Min_Length',3)
					
					->post('new_co_address') //Address
					->valid('Min_Length',3)
					
					->post('new_co_desc') //Description
					->valid('Min_Length',2)
					
					->post('new_staff_pass',false,true) //New Password
					->valid('Min_Length',2)
					
					->post('new_staff_pass2',false,true) //Confirm New Password
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check phone
			$em = $this->db->select("SELECT co_id, co_phone, co_email
									FROM ".DB_PREFEX."company 
									WHERE co_id != :ID AND
										(co_phone = :PHO OR co_email = :NAT) "
									,array(':PHO'=>$fdata['new_co_phone']
											,':NAT'=>$fdata['new_co_email']
											,':ID'=>session::get('company')
											));
			
			if(count($em) != 0)
			{
				$err = "";
				foreach($em as $val)
				{
					if($val['co_phone'] == $fdata['new_co_phone'])
					{
						$err .= "In Field new_co_phone : Duplicate .. \n";
					}
					if($val['co_email'] == $fdata['new_co_email'])
					{
						$err .= "In Field new_co_email : Duplicate .. \n";
					}
				}
				if(!empty($err))
				{
					return array('Error'=>$err);
				}
			}
			
			$user_array = array('co_name'		=>$fdata['new_co_name']
								,'co_phone'		=>$fdata['new_co_phone']
								,'co_address'	=>$fdata['new_co_address']
								,'co_desc'		=>$fdata['new_co_desc']
								,'co_email'		=>$fdata['new_co_email']
								,'update_at'	=>$time
								,'update_by'	=>session::get('user_id')
								);
			
			$files	= new files(); 
			
			if(!empty($_FILES['new_pro_image']) )
			{
				if($files->check_file($_FILES['new_pro_image']))
				{
					$user_array['co_img'] = $files->up_file($_FILES['new_pro_image'],URL_PATH.'public/IMG/co/');
					session::set('com_img',$user_array['co_img']);
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			
			$this->db->update(DB_PREFEX.'company',$user_array,"co_id = ".session::get('company'));
			session::set('com_name',$fdata['new_co_name']);
			
			return array('id'=>1);
		}
		
		/**
		* function del_img
		* del_img
		* AJAX
		*/
		public function del_img()
		{
			$form	= new form();
			
			$form	->post('file') // Name
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			$url = URL_PATH."public/IMG/user/".session::get('user_id')."/".$fdata['file'];
			$x = unlink($url);
			if($x)
			{
				return "تم حذف الملف";
			}else{
				return "لم يتم حذف الملف";
			}
		}
	
		/**
		* function upgrade
		* vip with price
		*/
		public function upgrade()
		{
			$form	= new form();
			
			$form	->post('new_pkg') // ID
					->valid('Integer')
					
					->post('vip_range') // period -- years
					->valid('Integer')
					
					->post('vip_price') // price
					->valid('Integer')
					
					->post('token') // Pay token
					->valid('Min_Length',5)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check Package:
			
			$data = $this->db->select("SELECT pk_id AS ID, pk_name AS NAME, pk_name_EN AS NAME_EN
										,pk_stars AS STARS, pk_users AS USERS, pk_adv_area AS ADV_AREA
										,pk_price AS PRICE, pk_users_msg AS MSG, pk_vip_area AS VIP
									FROM ".DB_PREFEX."package
									WHERE pk_id = :ID"
									,array(":ID"=>$fdata['new_pkg']));
			
			if(count($data) != 1)
			{
				return array('Error'=>"لم يتم العثور على الباقة");
			}
			$data = $data[0];
			
			$db_total = $data['PRICE'] * $fdata['vip_range'];
			
			if($fdata['vip_price'] != $db_total)
			{
				return array('Error'=>'المبلغ المحدد غير مطابق للمبلغ المسجل وهو '.$db_total);
			}	
			
			//Payments
			/*Payment Method Hear*/
			
			$pay_code = "PK_UPG_".time();
			$pay_ok = payments::pay($this->db,$fdata['token'],$fdata['vip_price'],$pay_code,"package upgrade",'my_co/ret/');
			
			if(!empty($pay_ok['error']))
			{
				return array('error'=>"call Error","error_data"=>$pay_ok);
			}
			if(!empty($pay_ok['payment_result']) && $pay_ok['payment_result']['response_status'] != 'A')
			{
				return array('Error'=>'بم تتم عملية التحويل بالخطا:  '.$pay_ok['payment_result']['response_message']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			$user_array = array('update_at'=>$time,'update_by'=>session::get('user_id'));
			
			$my_co = $this->db->select("SELECT co_package AS PKG, co_package_end AS BK_END
										,create_by AS AD_ID, co_email AS EMAIL
										FROM ".DB_PREFEX."company
										WHERE co_id = :ID"
										,array(":ID"=>session::get("company")));
			$my_co = $my_co[0];
			$sql = "";
			if($my_co['PKG'] == $fdata['new_pkg'])
			{
				//update package date
				$sql = "if(co_package_end is null,now(), co_package_end)";
			}else
			{
				$user_array['co_package'] = $fdata['new_pkg'];
				$sql = "now()";
			}
			
			$sql_query = "UPDATE ".DB_PREFEX."company SET 
								co_package_end = DATE_ADD($sql,INTERVAL ".$fdata["vip_range"]." YEAR)
								WHERE co_id = ".session::get('company');
			$bill = array();
			
			if(!empty($pay_ok['payment_result']))
			{
				//don , no action need
				$this->db->update(DB_PREFEX.'company',$user_array,"co_id = ".session::get('company'));
				$this->db->sql_quer($sql_query);
				
				//update package
				session::set('PK_STARS'			,$data['STARS']);
				session::set('PK_NAME'			,$data['NAME']);
				session::set('PK_USERS'			,$data['USERS']);
				session::set('PK_ADV'			,$data['ADV_AREA']);
				
				$bill['bi_status']= "A";
			}else
			{
				$bill['bi_status']= "PEND";
				$bill['bi_upd_data']= json_encode(array('table'=>DB_PREFEX.'company'
														,'data'=>$user_array
														,'where'=>"co_id = ".session::get('company')));
				$bill['bi_sql']= $sql_query;
				
			}
			
			//add bill
			//$bill = array();
			$bill['bi_package'] 	= $fdata['new_pkg'];
			$bill['bi_company']		= session::get("company");
			$bill['bi_code']		= $pay_code;
			$bill['bi_ref']			= $pay_ok['tran_ref'];
			$bill['bi_period']		= $fdata['vip_range'];
			$bill['bi_amount']		= $fdata["vip_price"];
			$bill['create_at']		= $time;
			$bill['create_by']		= session::get("user_id");
			
			$this->db->insert(DB_PREFEX.'bill',$bill);
			$id = $this->db->LastInsertedId();
			
			$ret = array();
			$ret['id'] = $id;
			$ret['sql'] =  $this->db->errordata();
			
            $E_MSG = "";
			if($bill['bi_status']== "A")
			{
				$E_MSG = "معاملة مالية ناجحة <br/>
						رقم الايصال: ".$ret['id']." <br/> 
						نوع المعاملة: ترقية باقة <br/>
						مرجع المعاملة: ".$pay_ok['tran_ref']." <br/>
						المبلغ: ".$fdata["vip_price"]." <br/>
						التاريخ: ".$time."
						";
			}else
			{
				$E_MSG = "معاملة مالية تحت الاختبار <br/>
						رقم الايصال: ".$ret['id']." <br/> 
						نوع المعاملة: ترقية باقة <br/>
						مرجع المعاملة: ".$pay_ok['tran_ref']." <br/>
						المبلغ: ".$fdata["vip_price"]." <br/>
						التاريخ: ".$time."
						";
			}
			
			$email 		= new Email();
			$re = $email->send_email($my_co['EMAIL'],'معاملة مالية',$E_MSG);
			if(!empty($pay_ok['redirect_url']))
			{
				$ret['url'] = $pay_ok['redirect_url'];
				header('Location: '.$ret['url']);
				die();
			}
			
			return $ret;
		}
		
		/**
		* function reg
		* send registration request
		*/
		public function reg()
		{
			$form	= new form();
			
			$form	->post('reg_co_no') // no
					->valid('Min_Length',2)
					
					//->post('reg_no') // no
					//->valid('Min_Length',2)
					
					->post('reg_real_no',false,true) // no
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//add reg request
			$reg = array();
			$reg['comm_no'] 	= $fdata['reg_co_no'];
			$reg['comm_real_no']= (!empty($fdata['reg_real_no']))?$fdata['reg_real_no']:null;
			//$reg['comm_co_num']	= $fdata[''];
			$reg['comm_co']		= session::get("company");
			$reg['create_at']	= $time;
			$reg['create_by']	= session::get("user_id");
			
			if(empty($_FILES['new_reg_file']) )
			{
				return array('Error'=>"In new_reg_file: File required");
			}
			$files	= new files(); 
			if($files->check_file($_FILES['new_reg_file']))
			{
				$reg['comm_file'] = $files->up_file($_FILES['new_reg_file'],URL_PATH.'reg_data/'.session::get("company").'/');
				
			}
			if(!empty($files->error_message))
			{
				return array('Error'=>"In new_reg_file: ".$files->error_message);
			}
			
			$this->db->insert(DB_PREFEX.'comm_reg',$reg);
			
			return array('id'=>$this->db->LastInsertedId());
		}
		
		/**
		* function upd_reg
		* send registration request
		*/
		public function upd_reg()
		{
			$form	= new form();
			
			$form	->post('id') // no
					->valid('numeric')
					
					//->post('upd_reg_no') // no
					//->valid('Min_Length',2)
					
					->post('upd_reg_real_no',false,true) // no
					->valid('Min_Length',2)
					
					->post('upd_reg_co_no') // no
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//add reg request
			$reg = array();
			$reg['comm_no'] 	= $fdata['upd_reg_co_no'];
			$reg['comm_real_no']= (!empty($fdata['upd_reg_real_no']))?$fdata['upd_reg_real_no']:null;
			//$reg['comm_co_num']	= (!empty($fdata['upd_reg_co_no']))?$fdata['upd_reg_co_no']:null;
			$reg['comm_accept']	= null;
			$reg['update_at']	= $time;
			$reg['update_by']	= session::get("user_id");
			
			if(!empty($_FILES['upd_reg_file']) )
			{
				
				$files	= new files(); 
				if($files->check_file($_FILES['upd_reg_file']))
				{
					$reg['comm_file'] = $files->up_file($_FILES['upd_reg_file'],URL_PATH.'reg_data/'.session::get("company").'/');
					
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>"In upd_reg_file: ".$files->error_message);
				}
			}
			$this->db->update(DB_PREFEX.'comm_reg',$reg,"comm_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
		
	}
?>