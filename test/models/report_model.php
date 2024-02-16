<?php
	/**
	* report MODEL, 
	*/
	class report_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function types
		* get land_type data
		*/
		public function rep_list()
		{
			$list = $this->db->select("SELECT rep_id AS ID, rep_customer AS CUS_ID
									,rep_land AS LAND, rep_message AS MSG, rep_status AS STAT
									,REP.create_at AS REP_TIME, REP.create_by AS OWN
									,CUS_CO.co_name AS CUS_NAME
									,staff_name AS OWN_NAME, staff_company AS OWN_CO_ID
									,OWN_CO.co_name AS OWN_CO_NAME
									FROM ".DB_PREFEX."report AS REP
									JOIN ".DB_PREFEX."staff ON REP.create_by = staff_id
									JOIN ".DB_PREFEX."company AS CUS_CO ON rep_customer = CUS_CO.co_id
									JOIN ".DB_PREFEX."company AS OWN_CO ON staff_company = OWN_CO.co_id
									WHERE 1 = 1
									GROUP BY rep_id
                                    ORDER BY rep_id DESC"
										,array());
			$ret = array();
			foreach($list As $val)
			{
				$val['OWN_LINK'] = URL."dashboard/customer/".$val['OWN_CO_ID'];
				$val['CUS_LINK'] = URL."dashboard/customer/".$val['CUS_ID'];
				$val['LAND_LINK'] = URL."dashboard/land/".$val['LAND'];
				$ret[$val['ID']] = $val;
			}
			return $ret;
			
		}
		
		/**
		* function upd_type
		* update land_type
		* AJAX
		*/
		public function upd_type()
		{
			$form	= new form();
			
			$form	->post('id') // id
					->valid('numeric')
					
					->post('upd_name') // NAME
					->valid('Min_Length',3)
					
					->post('upd_stat') // status
					->valid('in_array',array_keys(lib::$land_stat))
					
					->post('upd_build',false,true) // building or not
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check type
			$ty = $this->db->select("SELECT ty_id , count(l_id) AS LANDS
									FROM ".DB_PREFEX."land_type
									LEFT JOIN ".DB_PREFEX."land ON l_type = ty_id
									WHERE ty_id = :ID GROUP BY ty_id"
									,array(":ID"=>$fdata["id"]));
			if(count($ty) != 1)
			{
				return array('Error'=>"Type Not Found");
			}
			if($ty[0]['LANDS'] != 0)
			{
				return array('Error'=>"هنالك عقارات مسجلة بهذا النوع");
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$ty_array = array('ty_name'		=>$fdata['upd_name']
							,'ty_name_en'	=>$fdata['upd_name']
							,'ty_type'		=>$fdata['upd_stat']
							,'ty_builed'	=>$fdata['upd_build']
							,'update_by'	=>session::get('user_id')
							,'update_at'	=>$time
							);
			$this->db->update(DB_PREFEX.'land_type',$ty_array,"ty_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
		
	}
?>