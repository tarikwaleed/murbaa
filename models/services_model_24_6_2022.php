<?php
	/**
	* services MODEL, 
	*/
	class services_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		//get config
		public function config()
		{
			$x = $this->db->select("SELECT co_id AS ID, reg_id AS SER_REG_ID ,reg_type AS SER_REG_TYPE 
										,reg_ser_type AS SER_TYPE, reg_name AS SER_REG_NAME
										,reg_no AS SER_REG_NO ,reg_co_num AS SER_REG_NUM
										,reg_exp_date AS SER_REG_EXPERD
										FROM ".DB_PREFEX."company AS COMP
										LEFT JOIN ".DB_PREFEX."co_ser_reg ON reg_co = co_id 
												AND reg_accept = 1
												AND now() < reg_exp_date
										WHERE co_id = :ID
										GROUP BY co_id"
										,array(":ID"=>session::get("company")));
			$x = $x[0];
			
			$conf = $this->db->select("SELECT conf_name AS ID, conf_val AS VAL
										FROM ".DB_PREFEX."config
										WHERE conf_name IN ('SERVICE_MIN_PRICE','SERVICE_PERCENTAGE')
										",array());
			foreach($conf as $val)
			{
				$x[$val['ID']] = $val['VAL'];
			}
			return $x;
		}
		
		//get services data
		public function services()
		{
			$form	= new form();
			
			$form	->post('id',false,true) // ID
					->valid('numeric')
					
					->post('name',false,true) // Name
					->valid('Min_Length',2)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				$fdata = array();
			}
			$sea_arr = array();
			$sea_txt = "";
			
			$sea_arr[':CO'] = session::get('company');
			if(!empty($fdata['id']))
			{
				$sea_arr[':ID'] = $fdata['id'];
				$sea_txt .= 'ser_id = :ID AND ';
			}else
			{
				$sea_txt .= "(ser_status LIKE 'NEW' 
								OR ser_co = :CO
								OR ser_offer = off_id
								) AND ";
			}
			if(!empty($fdata['name']))
			{
				$sea_arr[':NAME'] = "%".$fdata['name']."%";
				$sea_txt .= 'ser_title like :NAME AND ';
			}
			
			$xx = $this->db->select("SELECT ser_id AS ID, ser_type AS TYPE, ser_price_from AS PRICE_FROM
									,ser_price_to AS PRICE_TO, ser_title AS TITLE, ser_sm_desc AS SM_DESC
									,ser_desc AS DESCR, ser_status AS STATUS, SER.create_at AS CREATE_TIME
									,ser_co AS CO_ID, co_name AS CO_NAME, co_img AS IMG
									,ser_period AS PERIOD, ser_contract_type AS CONTRACT_TYPE
									,off_id AS MY_OFFER, ser_offer AS CURR_OFF
									FROM ".DB_PREFEX."service AS SER
									JOIN ".DB_PREFEX."company ON co_id = ser_co
									LEFT JOIN ".DB_PREFEX."service_offer 
										ON off_service = ser_id AND off_co = :CO
									WHERE $sea_txt co_active = 1
									GROUP BY ser_id" ,$sea_arr);
			
			$ret = array();
			foreach($xx as $val)
			{
				$val['IMG'] 		= URL."public/IMG/co/".$val['IMG'];
				$val['CO_LINK'] 	= URL."dashboard/customer/".$val["CO_ID"];
				$val['D_SM_DESC'] 	= str_replace("\n"," <br/> ",$val['SM_DESC']);
				$val['D_DESCR'] 	= str_replace("\n"," <br/> ",$val['DESCR']);
				
				$dir 				= URL_PATH."services_files/".$val["ID"]."/";
				$link 				= URL."services_files/".$val["ID"]."/";
				$val["FILES"] 		= files::get_file_list($dir,$link);
				
				$val['OFFERS'] 		= array();
				$off = $this->db->select("SELECT off_id AS ID, off_price AS PRICE, off_desc AS DESCR
										,off_co AS CO_ID, co_name AS CO_NAME, co_img AS IMG
										,off_percentage AS PER, off_period AS PERIOD
										FROM ".DB_PREFEX."service_offer AS SER
										JOIN ".DB_PREFEX."company ON co_id = off_co
										WHERE off_service = :SER AND co_active = 1
										ORDER BY off_id ASC
										" ,array(':SER'=>$val['ID']));
				foreach($off as $offer)
				{
					$offer['CO_LINK'] 	= URL."dashboard/customer/".$offer["CO_ID"];
					$offer['IMG'] 		= URL."public/IMG/co/".$offer['IMG'];
					$offer['D_DESCR'] 	= str_replace("\n"," <br/> ",$offer['DESCR']);
					$offer['CUS_AMOUNT']= $offer['PRICE'] - ($offer['PRICE'] * ($offer['PER']/100));
					
					$dir 				= URL_PATH."services_files/".$val["ID"]."/".$offer['ID']."/";
					$link 				= URL."services_files/".$val["ID"]."/".$offer['ID']."/";
					$offer["FILES"] 	= files::get_file_list($dir,$link);
					
					$val['OFFERS'][$offer['ID']] = $offer;
				}
				array_push($ret,$val);
			}
			return $ret;
		}
		
		//New services
		public function new_service()
		{
			$config = $this->config();
			
            $form	= new form();
			$form	->post('new_title') // NAME
					->valid('Min_Length',3)
					
					->post('new_price_from') // price from
					->valid('Int_min',$config['SERVICE_MIN_PRICE'])
					
					->post('new_price_to') // price to
					->valid('Int_min',$config['SERVICE_MIN_PRICE'])
					
					->post('new_period') // period
					->valid('numeric')
					
					->post('new_cont_type') // period
					->valid('In_Array',array_keys(lib::$contract_type))
					
					->post('new_sm_desc') // Desc
					->valid('Min_Length',3)
					
					->post('new_desc') // Desc
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//check price
			if($fdata['new_price_to'] <= $fdata['new_price_from'])
			{
				return array('Error'=>"Error In new_price_to: المبلغ لا بد ان يكون اكثر من المبلغ المحدد في بداية المدي");
			}
			//insert
			$ty_array = array('ser_co'			=>session::get('company')
							,'ser_price_from'	=>$fdata['new_price_from']
							,'ser_price_to'		=>$fdata['new_price_to']
							,'ser_period'		=>$fdata['new_period']
							,'ser_contract_type'=>$fdata['new_cont_type']
							,'ser_title'		=>$fdata['new_title']
							,'ser_sm_desc'		=>$fdata['new_sm_desc']
							,'ser_desc'			=>$fdata['new_desc']
							,'create_by'		=>session::get('user_id')
							,'create_at'		=>$time
							);
			$this->db->insert(DB_PREFEX.'service',$ty_array);
			$id = $this->db->LastInsertedId();
			
			if(!empty($_FILES['new_ser_files']) && count($_FILES['new_ser_files'])!= 0)
			{
				$files	= new files(); 
				$file_array = $files->reArrayFiles($_FILES['new_ser_files']);
					
				foreach($file_array as $val)
				{
					if($files->check_file($val))
					{
						$x = $files->up_file($val,URL_PATH.'services_files/'.$id);
					}
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			
			return array('id'=>$id);
			
		}
		
		//Update service
		public function upd_service()
		{
			$config = $this->config();
			
			$form	= new form();
			
			$form	->post('id') // id
					->valid('numeric')
					
					->post('upd_title') // NAME
					->valid('Min_Length',3)
					
					->post('upd_price_from') // price from
					->valid('Int_min',$config['SERVICE_MIN_PRICE'])
					
					->post('upd_price_to') // price to
					->valid('Int_min',$config['SERVICE_MIN_PRICE'])
					
					->post('upd_period') // period
					->valid('numeric')
					
					->post('upd_cont_type') // period
					->valid('In_Array',array_keys(lib::$contract_type))
					
					->post('upd_sm_desc') // Desc
					->valid('Min_Length',3)
					
					->post('upd_desc') // Desc
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check id
			$xx = $this->db->select("SELECT ser_id AS ID, count(off_id) AS OFFERS
									FROM ".DB_PREFEX."service
									LEFT JOIN ".DB_PREFEX."service_offer ON off_service = ser_id 
									WHERE ser_id = :ID" ,array(":ID"=>$fdata['id']));
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الطلب");
			}
			$xx = $xx[0];
			if($xx['OFFERS'] != 0)
			{
				return array('Error'=>"لا يمكنك تعديل الطلب بعد تقديم العروض");
			}
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//check price
			if($fdata['upd_price_to'] <= $fdata['upd_price_from'])
			{
				return array('Error'=>"Error In new_price_to: المبلغ لا بد ان يكون اكثر من المبلغ المحدد في بداية المدي");
			}
			//insert
			$ty_array = array('ser_price_from'	=>$fdata['upd_price_from']
							,'ser_price_to'		=>$fdata['upd_price_to']
							,'ser_period'		=>$fdata['upd_period']
							,'ser_contract_type'=>$fdata['upd_cont_type']
							,'ser_title'		=>$fdata['upd_title']
							,'ser_sm_desc'		=>$fdata['upd_sm_desc']
							,'ser_desc'			=>$fdata['upd_desc']
							,'update_by'		=>session::get('user_id')
							,'update_at'		=>$time
							);
			$this->db->update(DB_PREFEX.'service',$ty_array,'ser_id = '.$fdata['id']);
			if(!empty($_FILES['upd_ser_files']) && count($_FILES['upd_ser_files'])!= 0)
			{
				$files	= new files(); 
				$file_array = $files->reArrayFiles($_FILES['upd_ser_files']);
					
				foreach($file_array as $val)
				{
					if($files->check_file($val))
					{
						$x = $files->up_file($val,URL_PATH.'services_files/'.$fdata['id']);
					}
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			return array('id'=>$fdata['id']);
			
		}
		
		//active / freez services
		public function active()
		{
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('Integer')
					
					->post('current',false,true) // Name
					->valid('In_Array',array('NEW','FREEZ'))
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check id
			$xx = $this->db->select("SELECT ser_id AS ID, count(off_id) AS OFFERS,ser_status AS STATUS
									FROM ".DB_PREFEX."service
									LEFT JOIN ".DB_PREFEX."service_offer ON off_service = ser_id 
									WHERE ser_id = :ID" ,array(":ID"=>$fdata['id']));
			
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الكبون");
			}
			
			
			if($fdata['current'] != $xx[0]['STATUS'])
			{
				return array('Error'=>'حالة الكبون الحالية هي  '.$xx[0]['STATUS'].' - '.$fdata['current']);
			}
			
			
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$upd_array = array();
			$upd_array['ser_status'] 	= ($xx[0]['STATUS'] == 'NEW')?'FREEZ':'NEW';
			$upd_array['update_at'] 	= $time;
			$upd_array['update_by'] 	= session::get('user_id');
			
			$this->db->update(DB_PREFEX.'service',$upd_array,'ser_id = '.$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
		
		//New Offer
		public function new_offer()
		{
			$config = $this->config();
			
			$form	= new form();
			$form	->post('ser_id') // ID
					->valid('numeric')
					
					->post('off_percentage') // Percentage
					->valid('Int_min',$config['SERVICE_PERCENTAGE'])
					->valid('Int_max',$config['SERVICE_PERCENTAGE'])
					
					->post('off_price') // price
					->valid('Int_min',$config['SERVICE_MIN_PRICE'])
					
					->post('off_period') // period
					->valid('numeric')
					
					->post('off_desc') // Desc
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//check id
			$xx = $this->db->select("SELECT ser_id AS ID, ser_status AS STATUS, ser_offer AS OFFER
									FROM ".DB_PREFEX."service
									WHERE ser_id = :ID" ,array(":ID"=>$fdata['ser_id']));
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الطلب");
			}
			$xx = $xx[0];
			if(!empty($xx['OFFER']))
			{
				return array('Error'=>"لا يمكنك تقديم عرض بعد قبول عرض شخص اخر");
			}
			if($xx['STATUS'] != "NEW")
			{
				return array('Error'=>"لقد تم اقفال تقديم العروض");
			}
			
			//insert
			$ty_array = array('off_co'			=>session::get('company')
							,'off_service'		=>$fdata['ser_id']
							,'off_price'		=>$fdata['off_price']
							,'off_percentage'	=>$fdata['off_percentage']
							,'off_period'		=>$fdata['off_period']
							,'off_desc'			=>$fdata['off_desc']
							,'create_by'		=>session::get('user_id')
							,'create_at'		=>$time
							);
			$this->db->insert(DB_PREFEX.'service_offer',$ty_array);
			$id = $this->db->LastInsertedId();
			
			if(!empty($_FILES['new_off_files']) && count($_FILES['new_off_files'])!= 0)
			{
				$files	= new files(); 
				$file_array = $files->reArrayFiles($_FILES['new_off_files']);
				if(!is_dir(URL_PATH.'services_files/'.$fdata['ser_id']))
				{
					mkdir(URL_PATH.'services_files/'.$fdata['ser_id']);
				}
				foreach($file_array as $val)
				{
					if($files->check_file($val))
					{
						$x = $files->up_file($val,URL_PATH.'services_files/'.$fdata['ser_id'].'/'.$id);
					}
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			return array('id'=>$id);
			
		}
		
		//update Offer
		public function upd_offer()
		{
			$config = $this->config();
			
			$form	= new form();
			$form	->post('id') // ID
					->valid('numeric')
					
					->post('off_percentage') // Percentage
					->valid('Int_min',$config['SERVICE_PERCENTAGE'])
					->valid('Int_max',$config['SERVICE_PERCENTAGE'])
					
					->post('off_price') // price
					->valid('Int_min',$config['SERVICE_MIN_PRICE'])
					
					->post('off_period') // period
					->valid('numeric')
					
					->post('off_desc') // Desc
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//check id
			$xx = $this->db->select("SELECT off_id AS ID, off_co AS CO_ID
									,ser_id, ser_status AS STATUS, ser_offer AS OFFER
									FROM ".DB_PREFEX."service_offer AS SER
									JOIN ".DB_PREFEX."service ON off_service = ser_id
									WHERE off_id = :OFF AND off_co = :CO
									" ,array(':OFF'=>$fdata['id'],':CO'=>session::get('company')));
										
										
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الطلب");
			}
			$xx = $xx[0];
			if(!empty($xx['OFFER']))
			{
				return array('Error'=>"لا يمكنك  تعديل عرض بعد قبول عرض");
			}
			if($xx['STATUS'] != "NEW")
			{
				return array('Error'=>"لقد تم اقفال تقديم  ونعديل العروض");
			}
			
			//update
			$ty_array = array('off_price'		=>$fdata['off_price']
							,'off_percentage'	=>$fdata['off_percentage']
							,'off_period'		=>$fdata['off_period']
							,'off_desc'			=>$fdata['off_desc']
							,'update_by'		=>session::get('user_id')
							,'update_at'		=>$time
							);
			$this->db->update(DB_PREFEX.'service_offer',$ty_array,' off_id = '.$fdata['id']);
			
			if(!empty($_FILES['upd_off_files']) && count($_FILES['upd_off_files'])!= 0)
			{
				$files	= new files(); 
				$file_array = $files->reArrayFiles($_FILES['upd_off_files']);
				if(!is_dir(URL_PATH.'services_files/'.$xx['ser_id']))
				{
					mkdir(URL_PATH.'services_files/'.$xx['ser_id']);
				}
				foreach($file_array as $val)
				{
					if($files->check_file($val))
					{
						$x = $files->up_file($val,URL_PATH.'services_files/'.$xx['ser_id'].'/'.$fdata['id']);
					}
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			return array('id'=>$fdata['id']);
			
		}
		
		//get services with chat data
		public function service_chat($chat_id=0)
		{
			$form	= new form();
			
			if(!$form->single_valid($chat_id,'numeric'))
			{
				return array();
			}
			
			$xx = $this->db->select("SELECT ser_id AS ID, ser_type AS TYPE, ser_price_from AS PRICE_FROM
									,ser_price_to AS PRICE_TO, ser_title AS TITLE, ser_sm_desc AS SM_DESC
									,ser_desc AS DESCR, ser_status AS STATUS, SER.create_at AS CREATE_TIME
									,ser_period AS PERIOD, ser_contract_type AS CONTRACT_TYPE
									,ser_offer AS CURR_OFF
									
									,ser_co AS CO_ID, OWN.co_name AS CO_NAME, OWN.co_img AS CO_IMG
									,off_id AS OFFER_ID, off_price AS OFFER_PRICE, off_percentage AS OFFER_PER
									,off_period AS OFFER_PERIOD, off_desc AS OFFER_DESC
									,off_co AS OFF_CO_ID, OFF_OWN.co_name AS OFF_CO_NAME, OFF_OWN.co_img AS OFF_IMG
									
									FROM ".DB_PREFEX."service AS SER
									JOIN ".DB_PREFEX."service_offer ON off_service = ser_id 
									JOIN ".DB_PREFEX."company AS OWN ON OWN.co_id = ser_co
									JOIN ".DB_PREFEX."company AS OFF_OWN ON OFF_OWN.co_id = off_co
									WHERE OWN.co_active = 1 AND OFF_OWN.co_active = 1 AND off_id = :ID
									GROUP BY ser_id" ,array(":ID"=>$chat_id));
			
			
			if(count($xx) != 1)
			{
				return array();
			}
			$ret = $xx[0];
			$ret['CO_IMG'] 		= URL."public/IMG/co/".$ret['CO_IMG'];
			$ret['OFF_IMG'] 	= URL."public/IMG/co/".$ret['OFF_IMG'];
			$ret['CO_LINK'] 	= URL."dashboard/customer/".$ret["CO_ID"];
			$ret['OFF_LINK'] 	= URL."dashboard/customer/".$ret["OFF_CO_ID"];
			$ret['D_SM_DESC'] 	= str_replace("\n"," <br/> ",$ret['SM_DESC']);
			$ret['D_DESCR'] 	= str_replace("\n"," <br/> ",$ret['DESCR']);
			
			$dir 				= URL_PATH."services_files/".$ret["ID"]."/";
			$link 				= URL."services_files/".$ret["ID"]."/";
			$ret["SER_FILES"] 	= files::get_file_list($dir,$link);
			
			$dir 				= URL_PATH."services_files/".$ret["ID"]."/".$ret['OFFER_ID']."/";
			$link 				= URL."services_files/".$ret["ID"]."/".$ret['OFFER_ID']."/";
			$ret["OFF_FILES"] 	= files::get_file_list($dir,$link);
					
			$ret['OFFER_D_DESC']= str_replace("\n"," <br/> ",$ret['OFFER_DESC']);
			$ret['CUS_AMOUNT']	= $ret['OFFER_PRICE'] - ($ret['OFFER_PRICE'] * ($ret['OFFER_PER']/100));
			$ret['CHAT']		= $this->chat($ret['OFFER_ID']);
			
			return $ret;
		}
		
		//get chat data
		public function chat($off_id=0,$last=0)
		{
			$form = new form();
			if(!$form->single_valid($off_id,'numeric'))
			{
				return array();
			}
			$sql_last = 1;
			if(!empty($last) && $form->single_valid($last,'numeric'))
			{
				$sql_last = $last; 
			}
			//get offer info
			$xx = $this->db->select("SELECT ser_id AS ID,ser_offer AS CURR_OFF
									,off_id AS OFFER_ID
									FROM ".DB_PREFEX."service AS SER
									JOIN ".DB_PREFEX."service_offer ON off_service = ser_id 
									WHERE off_id = :ID" ,array(":ID"=>$off_id));
			if(count($xx) != 1)
			{
				return array();
			}
			$xx = $xx[0];
			
			$chat = $this->db->select("SELECT ch_id AS ID, ch_type AS TYPE, ch_txt AS TXT
										,CH.create_at AS DATE
										,staff_name AS NAME, staff_company AS CO
										,staff_img AS IMG
										FROM ".DB_PREFEX."offer_chat AS CH
										JOIN ".DB_PREFEX."staff ON staff_id = CH.create_by
										WHERE ch_offer = :ID AND ch_id >= :CH
										ORDER BY ch_id ASC
										" ,array(':ID'=>$off_id,':CH'=>$sql_last));
			$ret = array();
			foreach($chat as $val)
			{
				$dir 		= URL_PATH."services_files/".$xx["ID"]."/".$xx['OFFER_ID']."/".$val['ID']."/";
				$link 		= URL."services_files/".$xx["ID"]."/".$xx['OFFER_ID']."/".$val['ID']."/";
				$val["FILES"]= files::get_file_list($dir,$link);
				$val['TEXT']= str_replace("\n"," <br/> ",$val['TXT']);
			
				$ret[$val['ID']] = $val;
			}
			return $ret;
		}
	
		//add chat
		public function add_chat()
		{
			$form	= new form();
			$form	->post('chatroom') // ID
					->valid('numeric')
					
					->post('chat_msg') // MSG
					->valid('Min_Length',3)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//check id
			$xx = $this->db->select("SELECT off_id AS ID, off_co AS CO_ID, ser_co AS SER_CO
									,ser_id, ser_status AS STATUS, ser_offer AS OFFER
									FROM ".DB_PREFEX."service_offer AS SER
									JOIN ".DB_PREFEX."service ON off_service = ser_id
									WHERE off_id = :OFF
									" ,array(':OFF'=>$fdata['chatroom']));
										
										
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الطلب");
			}
			$xx = $xx[0];
			if($xx['SER_CO'] != session::get('company') && $xx['CO_ID'] != session::get('company'))
			{
				return array('Error'=>"لا يمكنك  ارسال رسالة داخل عرض لا علاقة لك به");
			}
			if($xx['STATUS'] != "NEW" && $xx['OFFER'] != $xx['off_id'])
			{
				return array('Error'=>"لقد تم اقفال المراسلات في هذا المشروع");
			}
			
			//insert
			$ty_array = array('ch_offer'	=>$fdata['chatroom']
							,'ch_type'		=>($xx['STATUS'] == "NEW")?"OFFER":"WORK"
							,'ch_txt'		=>$fdata['chat_msg']
							,'create_by'	=>session::get('user_id')
							,'create_at'	=>$time
							);
			$this->db->insert(DB_PREFEX.'offer_chat',$ty_array);
			
			$id = $this->db->LastInsertedId();
			
			if(!empty($_FILES['chat_files']) && count($_FILES['chat_files'])!= 0)
			{
				$files	= new files(); 
				$file_array = $files->reArrayFiles($_FILES['chat_files']);
				if(!is_dir(URL_PATH.'services_files/'.$xx['ser_id']))
				{
					mkdir(URL_PATH.'services_files/'.$xx['ser_id']);
				}
				if(!is_dir(URL_PATH.'services_files/'.$xx['ser_id']."/".$xx['ID']))
				{
					mkdir(URL_PATH.'services_files/'.$xx['ser_id']."/".$xx['ID']);
				}
				foreach($file_array as $val)
				{
					if($files->check_file($val))
					{
						$x = $files->up_file($val,URL_PATH.'services_files/'.$xx['ser_id']."/".$xx['ID'].'/'.$id);
					}
				}
				if(!empty($files->error_message))
				{
					return array('Error'=>$files->error_message);
				}
			}
			return array('id'=>$id);
			
		}
		
		//offer accept
		public function accept_offer()
		{
			$form	= new form();
			
			$form	->post('offer_id') // ID
					->valid('Integer')
					
					->post('price') // price
					->valid('Integer')
					
					->post('token') // Pay token
					->valid('Min_Length',5)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG'],'OFFER'=>$fdata['offer_id']);
			}
			
			//check id
			$xx = $this->db->select("SELECT off_id AS ID, off_price AS PRICE, off_co AS CO_ID
									,ser_co AS SER_CO, ser_id, ser_status AS STATUS, ser_offer AS OFFER
									,co_email AS EMAIL
									FROM ".DB_PREFEX."service_offer AS SER
									JOIN ".DB_PREFEX."service ON off_service = ser_id
									JOIN ".DB_PREFEX."company ON co_id = ser_co
									WHERE off_id = :OFF AND ser_co = :CO
									" ,array(':OFF'=>$fdata['offer_id'],':CO'=>session::get('company')));
			
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الطلب",'OFFER'=>$fdata['offer_id']);
			}
			$xx = $xx[0];
			if($xx['STATUS'] != "NEW" || !empty($xx['OFFER']))
			{
				return array('Error'=>"لا يمكنك اعادة دفع وحجز خدمة محجوزة من قبل",'OFFER'=>$fdata['offer_id']);
			}
			if($xx['PRICE'] != $fdata['price'])
			{
				return array('Error'=>"المبلغ المحدد عير مطابق للمبلغ المسجل في العرض",'OFFER'=>$fdata['offer_id']);
			}
			
			//Payments
			/*Payment Method Hear*/
			
			$pay_code = "SER_REQ_".time();
			$pay_ok = payments::pay($this->db,$fdata['token'],$fdata['price'],$pay_code,"Service Request",'services/pay_ret/'.$fdata['offer_id']);
			
			if(!empty($pay_ok['error']))
			{
				return array('error'=>"call Error","error_data"=>$pay_ok,'OFFER'=>$fdata['offer_id']);
			}
			if(!empty($pay_ok['payment_result']) && $pay_ok['payment_result']['response_status'] != 'A')
			{
				return array('Error'=>'بم تتم عملية التحويل بالخطا:  '.$pay_ok['payment_result']['response_message'],'OFFER'=>$fdata['offer_id']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			$ty_array = array('ser_offer'	=>$xx['ID']
							,'ser_status'	=>"WORK"
							,'update_by'	=>session::get('user_id')
							,'update_at'	=>$time
							);
			
			//Bill
			$bill = array();
			
			if(!empty($pay_ok['payment_result']))
			{
				//don , no action need
				$this->db->update(DB_PREFEX.'service',$ty_array,'ser_id = '.$xx['ser_id']);
				$bill['bi_status']= "A";
			}else
			{
				$bill['bi_status']= "PEND";
				$bill['bi_upd_data']= json_encode(array('table'=>DB_PREFEX.'service'
														,'data'=>$ty_array
														,'where'=>'ser_id = '.$xx['ser_id']));
				
			}
			
			
			$ty_array = array('ser_offer'	=>$xx['ID']
							,'update_by'	=>session::get('user_id')
							,'update_at'	=>$time
							);
			
			$bill['bi_company']		= session::get("company");
			$bill['bi_code']		= $pay_code;
			$bill['bi_ref']			= $pay_ok['tran_ref'];
			$bill['bi_service_offer']= $xx['ID'];
			$bill['bi_amount']		= $fdata["price"];
			$bill['create_at']		= $time;
			$bill['create_by']		= session::get("user_id");
			
			$this->db->insert(DB_PREFEX.'bill',$bill);
			$id = $this->db->LastInsertedId();
			
			$ret = array();
			$ret['id'] 		= $id;
			$ret['sql'] 	= $this->db->errordata();
			$ret['OFFER'] 	= $xx['ID'];
			
			$E_MSG = "";
			if($bill['bi_status']== "A")
			{
				$E_MSG = "معاملة مالية ناجحة \n 
						رقم الايصال: ".$ret['id']." \n 
						نوع المعاملة: قبول عرض طلب خدمة\n 
						مرجع المعاملة: ".$pay_ok['tran_ref']." \n
						المبلغ: ".$fdata["price"]." \n
						التاريخ: ".$time."
						";
			}else
			{
				$E_MSG = "معاملة مالية تحت الاختبار \n 
						رقم الايصال: ".$ret['id']." \n 
						نوع المعاملة: قبول عرض طلب خدمة\n 
						مرجع المعاملة: ".$pay_ok['tran_ref']." \n
						المبلغ: ".$fdata["price"]." \n
						التاريخ: ".$time."
						";
			}
			
			$email 		= new Email();
			
			$re = $email->send_email($xx['EMAIL'],'معاملة مالية',$E_MSG);
			if(!empty($pay_ok['redirect_url']))
			{
				$ret['url'] = $pay_ok['redirect_url'];
				header('Location: '.$ret['url']);
				die();
			}
			
			return $ret;
			
		}
		
		//finish service
		public function finish()
		{
			$form	= new form();
			
			$form	->post('id') // ID
					->valid('Integer')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check id
			$xx = $this->db->select("SELECT ser_id AS ID, ser_status AS STATUS, ser_co AS CO
									,off_id AS OFFER_ID, off_co AS OFF_CO
									,off_price AS OFFER_PRICE, off_percentage AS OFFER_PER
									FROM ".DB_PREFEX."service AS SER
									JOIN ".DB_PREFEX."service_offer ON off_service = ser_id 
									WHERE ser_id = :ID" ,array(":ID"=>$fdata['id']));
			
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الطلب");
			}
			
			$xx = $xx[0];
			if($xx['CO'] != session::get('company') && $xx['OFF_CO'] != session::get('company'))
			{
				return array('Error'=>"لا يمكنك تعديل عرض لا علاقة لك به");
			}
			$upd = "";
			if($xx['STATUS'] == "WORK" && $xx['OFF_CO'] == session::get('company'))
			{
				$upd = "SIM_END";
			}elseif($xx['STATUS'] == "SIM_END" && $xx['CO'] == session::get('company'))
			{
				$upd = "END";
			}else
			{
				return array();
			}
			
			
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$upd_array = array();
			$upd_array['ser_status'] 	= $upd;
			$upd_array['update_at'] 	= $time;
			$upd_array['update_by'] 	= session::get('user_id');
			$this->db->update(DB_PREFEX.'service',$upd_array,'ser_id = '.$fdata['id']);
			
			if($upd == "END")
			{
				//add price to offer
				$AMOUNT	= $xx['OFFER_PRICE'] - ($xx['OFFER_PRICE'] * ($xx['OFFER_PER']/100));
				$pr_array = array('pr_co'		=>$xx['OFF_CO']
								,'pr_offer'		=>$xx['OFFER_ID']
								,'pr_amount'	=>$AMOUNT
								,'create_by'	=>session::get('user_id')
								,'create_at'	=>$time
								);
				$this->db->insert(DB_PREFEX.'offer_price',$pr_array);
			
			}
			
			
			return array('id'=>$fdata['id']);
		}
		
		
		
	}
?>