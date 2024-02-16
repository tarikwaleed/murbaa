<?php
	/**
	* ser_admin MODEL, 
	*/
	class ser_admin_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		//get services data
		public function services()
		{
			$form	= new form();
			
			$form	->post('id',false,true) // ID
					->valid('numeric')
					
					->post('name',false,true) // Name
					->valid('Min_Length',2)
					
					->post('cont_type',false,true) // cont_type
					->valid('In_Array',array_keys(lib::$contract_type))
					
					->post('cont_status',false,true) // cont_status
					->valid('In_Array',array_keys(lib::$service_status))
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				$fdata = array();
			}
			$sea_arr = array();
			$sea_txt = "";
			
			if(!empty($fdata['id']))
			{
				$sea_arr[':ID'] = $fdata['id'];
				$sea_txt .= 'ser_id = :ID AND ';
			}
			if(!empty($fdata['name']))
			{
				$sea_arr[':NAME'] = "%".$fdata['name']."%";
				$sea_txt .= 'ser_title like :NAME AND ';
			}
			if(!empty($fdata['cont_type']))
			{
				$sea_arr[':CONT_TYPE'] = $fdata['cont_type'];
				$sea_txt .= 'ser_contract_type = :CONT_TYPE AND ';
			}
			if(!empty($fdata['cont_status']))
			{
				$sea_arr[':CONT_STATUS'] = $fdata['cont_status'];
				$sea_txt .= 'ser_status = :CONT_STATUS AND ';
			}
			
			$xx = $this->db->select("SELECT ser_id AS ID, ser_type AS TYPE, ser_price_from AS PRICE_FROM
									,ser_price_to AS PRICE_TO, ser_title AS TITLE, ser_sm_desc AS SM_DESC
									,ser_desc AS DESCR, ser_status AS STATUS, SER.create_at AS CREATE_TIME
									,ser_period AS PERIOD, ser_contract_type AS CONTRACT_TYPE
									,ser_city AS CITY, ser_offer AS CURR_OFF ,ser_sel_type AS SEL_TYPE
									
									,ser_co AS CO_ID, OWN.co_name AS CO_NAME, OWN.co_img AS CO_IMG
									,off_id AS OFFER_ID, off_price AS OFFER_PRICE, off_percentage AS OFFER_PER
									,off_period AS OFFER_PERIOD, off_desc AS OFFER_DESC
									,off_co AS OFF_CO_ID, OFF_OWN.co_name AS OFF_CO_NAME, OFF_OWN.co_img AS OFF_IMG
									
									FROM ".DB_PREFEX."service AS SER
									JOIN ".DB_PREFEX."company AS OWN ON OWN.co_id = ser_co
									LEFT JOIN ".DB_PREFEX."service_offer ON off_service = ser_id 
									LEFT JOIN ".DB_PREFEX."company AS OFF_OWN ON OFF_OWN.co_id = off_co
									WHERE $sea_txt OWN.co_active = 1
									GROUP BY ser_id" ,$sea_arr);
			
			$ret = array();
			foreach($xx as $val)
			{
				$val['CO_IMG'] 		= URL."public/IMG/co/".$val['CO_IMG'];
				$val['CO_LINK'] 	= URL."dashboard/customer/".$val["CO_ID"];
				$val['D_SM_DESC'] 	= str_replace("\n"," <br/> ",$val['SM_DESC']);
				$val['D_DESCR'] 	= str_replace("\n"," <br/> ",$val['DESCR']);
				$dir 				= URL_PATH."services_files/".$val["ID"]."/";
				$link 				= URL."services_files/".$val["ID"]."/";
				$val["SER_FILES"] 	= files::get_file_list($dir,$link);
				
				$val['OFF_IMG'] 	= URL."public/IMG/co/".$val['OFF_IMG'];
				$val['OFF_LINK'] 	= URL."dashboard/customer/".$val["OFF_CO_ID"];
				$val['OFFER_D_DESC']= str_replace("\n"," <br/> ",$val['OFFER_DESC']);
				$val['CUS_AMOUNT']	= $val['OFFER_PRICE'] - ($val['OFFER_PRICE'] * ($val['OFFER_PER']/100));
				$dir 				= URL_PATH."services_files/".$val["ID"]."/".$val['OFFER_ID']."/";
				$link 				= URL."services_files/".$val["ID"]."/".$val['OFFER_ID']."/";
				$val["OFF_FILES"] 	= files::get_file_list($dir,$link);
			
				array_push($ret,$val);
			}
			return $ret;
		}
		
	}
?>