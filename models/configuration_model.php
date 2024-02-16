<?php
	/**
	* configuration MODEL, 
	*/
	class configuration_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* config_item get All configuration
		*/
		public function config_item()
		{
			$conf = $this->db->select("SELECT conf_name , conf_val, update_at
									FROM ".DB_PREFEX."config 
									WHERE 1=1
									" ,array());
			$ret = array();
			foreach($conf as $val)
			{
				$ret[$val['conf_name']] = array();
				$ret[$val['conf_name']]['NAME'] = $val['conf_val'];
				$ret[$val['conf_name']]['DATE'] = $val['update_at'];
			}
			return $ret;
		}
		
		/**
		* upd_info
		* update configuration
		*/
		public function upd_info()
		{
			$form = new form();
			
			$form	->post('title')
					->valid('Min_Length',2)
					
					->post('desc')
					->valid('Min_Length',2)
					
					->post('face')
					->valid('URL')
					
					->post('twitter')
					->valid('URL')
					
					->post('instagram')
					->valid('URL')
					
					->post('vip')
					->valid('Int_min',1)
					
					->post('vip_price')
					->valid('Int_min',1)
					
					->post('rent_day')
					->valid('Int_min',1)
					
					->post('rent_month')
					->valid('Int_min',1)
					
					->post('rent_year')
					->valid('Int_min',1)
					
					->post('sale')
					->valid('Int_min',1)
					
					->post('invest')
					->valid('Int_min',1)
					
					->post('paging')
					->valid('Int_min',2)
					->valid('Int_max',50)
					
					->post('days')
					->valid('Int_min',2)
					
					->post('terms')
					->valid('Min_Length',2)
					
					->post('policy')
					->valid('Min_Length',2)
					
					->post('SER_MIN_PRICE')
					->valid('Int_min',1)
					
					->post('SER_PERC')
					->valid('Int_min',1)
					->valid('Int_max',99)
					
					->submit();
			$fdata = $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			$gr_array = array('conf_val'	=>$fdata['title']
							,'update_at'	=>$time
							);
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'TITLE'");
			session::set("TITLE",$fdata['title']);
			
			$gr_array['conf_val'] = $fdata['desc'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'DESC_INFO'");
			session::set("DESC_INFO",$fdata['desc']);
			
			$gr_array['conf_val'] = $fdata['face'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'FACEBOOK'");
			session::set("FACEBOOK",$fdata['face']);
			
			$gr_array['conf_val'] = $fdata['twitter'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'TWITTER'");
			session::set("TWITTER",$fdata['twitter']);
			
			$gr_array['conf_val'] = $fdata['instagram'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'INSTAGRAM'");
			session::set("INSTAGRAM",$fdata['instagram']);
			
			$gr_array['conf_val'] = $fdata['vip'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'VIP_PERIOD'");
			session::set("VIP_PERIOD",$fdata['vip']);
			
			$gr_array['conf_val'] = $fdata['vip_price'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'VIP_PRICE'");
			session::set("VIP_PRICE",$fdata['vip_price']);
			
			$gr_array['conf_val'] = $fdata['rent_day'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'RENT_DAY'");
			session::set("RENT_DAY",$fdata['rent_day']);
			
			$gr_array['conf_val'] = $fdata['rent_month'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'RENT_MONTH'");
			session::set("RENT_MONTH",$fdata['rent_month']);
			
			$gr_array['conf_val'] = $fdata['rent_year'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'RENT_YEAR'");
			session::set("RENT_YEAR",$fdata['rent_year']);
			
			$gr_array['conf_val'] = $fdata['sale'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'SALE'");
			session::set("SALE",$fdata['sale']);
			
			$gr_array['conf_val'] = $fdata['invest'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'INVESTMENT'");
			session::set("INVESTMENT",$fdata['invest']);
			
			$gr_array['conf_val'] = $fdata['paging'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'PAGING'");
			session::set("PAGING",$fdata['paging']);
			
			$gr_array['conf_val'] = html_entity_decode($fdata['terms']);
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 1 AND conf_name = 'TERMS'");
			
			$gr_array['conf_val'] = html_entity_decode($fdata['policy']);
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 1 AND conf_name = 'POLICY'");
			
			$gr_array['conf_val'] = html_entity_decode($fdata['days']);
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 1 AND conf_name = 'ADV_DAYS'");
			
			$gr_array['conf_val'] = $fdata['SER_MIN_PRICE'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 1 AND conf_name = 'SERVICE_MIN_PRICE'");
			
			$gr_array['conf_val'] = $fdata['SER_PERC'];
			$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 1 AND conf_name = 'SERVICE_PERCENTAGE'");
			
            $files	= new files(); 
			
			//Logo
			if(!empty($_FILES['new_pro_image']) )
			{
				if($files->check_file($_FILES['new_pro_image']))
				{
					$gr_array['conf_val'] = $files->up_file($_FILES['new_pro_image'],URL_PATH.'public/IMG/');
					$this->db->update(DB_PREFEX.'config',$gr_array,"conf_user = 0 AND conf_name = 'LOGO'");
					session::set("LOGO",$gr_array['conf_val']);
				}
				if(!empty($files->error_message) && $files->error_message != "No file was uploaded")
				{
					return array('Error'=>$files->error_message);
				}
			}
			
			return array("ok"=>1);
			
		}
		
		
	}
?>