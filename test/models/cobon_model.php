<?php
	/**
	* cobon MODEL, 
	*/
	class cobon_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		//get cobon data
		public function cobon()
		{
			$form	= new form();
			
			$form	->post('name',false,true) // Name
					->valid('Min_Length',3)
					
					->post('package',false,true) // package
					->valid('In_Array',array_keys(lib::$cobon_type))
					
					->post('discount',false,true) // phone
					->valid('In_Array',array_keys(lib::$cobon_pay))
					
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
				$sea_txt .= 'cob_name like :NAME AND ';
			}
			if(!empty($fdata['package']))
			{
				$sea_arr[':PK'] = $fdata['package'];
				$sea_txt .= 'cob_type = :PK AND ';
			}
			if(!empty($fdata['discount']))
			{
				$sea_arr[':DIS'] = $fdata['discount'];
				$sea_txt .= 'cob_price_type = :DIS AND ';
			}
			
			$xx = $this->db->select("SELECT cob_id AS ID, cob_name AS NAME, cob_type AS TYPE
									,cob_price AS PRICE, cob_price_type AS PRICE_TYPE
									,cob_amount AS AMOUNT, cob_active AS ACTIVE
									,cob_expered AS EXP
									,count(bi_id) AS USED_BILL
									FROM ".DB_PREFEX."cobon
									LEFT JOIN ".DB_PREFEX."bill ON bi_cobon = cob_id
									WHERE $sea_txt 1=1
									GROUP BY cob_id" ,$sea_arr);
			$ret = array();
			foreach($xx as $val)
			{
				if($val['PRICE_TYPE'] == 'PER')
				{
					$val['V_PRICE'] = $val['PRICE']." % ";
				}else
				{
					$val['V_PRICE'] = $val['PRICE'];
				}
				array_push($ret,$val);
			}
			return $ret;
		}
		
		//New cobon
		public function add_cobon()
		{
			$form	= new form();
			
			$form	->post('new_name') // NAME
					->valid('Min_Length',3)
					
					->post('new_type') // package
					->valid('In_Array',array_keys(lib::$cobon_type))
					
					->post('new_discount') // discount type
					->valid('In_Array',array_keys(lib::$cobon_pay))
					
					->post('new_price') // price
					->valid('Int_min',1)
					
					->post('new_amount') // amount
					->valid('Int_min',1)
					
					->post('new_exp') // amount
					->valid('Date')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//check price if percentage
			if($fdata['new_discount'] == "PER" && $fdata['new_price'] > 100)
			{
				return array('Error'=>"Error In new_price: المبلغ لا بد ان يكون اقل من او يساوي 100");
			}
			
			//insert
			$ty_array = array('cob_name'		=>$fdata['new_name']
							,'cob_type'			=>$fdata['new_type']
							,'cob_price'		=>$fdata['new_price']
							,'cob_price_type'	=>$fdata['new_discount']
							,'cob_amount'		=>$fdata['new_amount']
							,'cob_expered'		=>$fdata['new_exp']
							,'create_by'		=>session::get('user_id')
							,'create_at'		=>$time
							);
			$this->db->insert(DB_PREFEX.'cobon',$ty_array);
			$id = $this->db->LastInsertedId();
			
			return array('id'=>$id);
			
		}
		
		//Update cobon
		public function upd_cobon()
		{
			$form	= new form();
			
			$form	->post('id') // id
					->valid('numeric')
					
					->post('upd_name') // NAME
					->valid('Min_Length',3)
					
					->post('upd_type') // package
					->valid('In_Array',array_keys(lib::$cobon_type))
					
					->post('upd_discount') // discount type
					->valid('In_Array',array_keys(lib::$cobon_pay))
					
					->post('upd_price') // price
					->valid('Int_min',1)
					
					->post('upd_amount') // amount
					->valid('Int_min',1)
					
					->post('upd_exp') // amount
					->valid('Date')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//check price if percentage
			if($fdata['upd_discount'] == "PER" && $fdata['upd_price'] > 100)
			{
				return array('Error'=>"Error In upd_price: المبلغ لا بد ان يكون اقل من او يساوي 100");
			}
			
			//check id
			$xx = $this->db->select("SELECT cob_id AS ID, cob_name AS NAME, cob_type AS TYPE
									,cob_price AS PRICE, cob_price_type AS PRICE_TYPE
									,cob_amount AS AMOUNT, cob_active AS ACTIVE
									,cob_expered AS EXP
									,count(bi_id) AS USED_BILL
									FROM ".DB_PREFEX."cobon
									LEFT JOIN ".DB_PREFEX."bill ON bi_cobon = cob_id
									WHERE cob_id = :ID
									GROUP BY cob_id" ,array(":ID"=>$fdata['id']));
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الكبون");
			}
			
			//update
			$ty_array = array('cob_name'		=>$fdata['upd_name']
							,'cob_type'			=>$fdata['upd_type']
							,'cob_price'		=>$fdata['upd_price']
							,'cob_price_type'	=>$fdata['upd_discount']
							,'cob_amount'		=>$fdata['upd_amount']
							,'cob_expered'		=>$fdata['upd_exp']
							,'update_by'		=>session::get('user_id')
							,'update_at'		=>$time
							);
			$this->db->update(DB_PREFEX.'cobon',$ty_array,'cob_id = '.$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
		//active / freez cobon
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
			
			//check id
			$xx = $this->db->select("SELECT cob_id AS ID, cob_name AS NAME, cob_type AS TYPE
									,cob_price AS PRICE, cob_price_type AS PRICE_TYPE
									,cob_amount AS AMOUNT, cob_active AS ACTIVE
									,cob_expered AS EXP
									,count(bi_id) AS USED_BILL
									FROM ".DB_PREFEX."cobon
									LEFT JOIN ".DB_PREFEX."bill ON bi_cobon = cob_id
									WHERE cob_id = :ID
									GROUP BY cob_id" ,array(":ID"=>$fdata['id']));
			if(count($xx) != 1)
			{
				return array('Error'=>"Error In id: لم يتم التعرف على الكبون");
			}
			
			$curr = ($xx[0]['ACTIVE']==1)?true:false;
			
			if(($fdata['current'] == "true" && !$curr)||($fdata['current']== "false" && $curr))
			{
				return array('Error'=>'حالة الكبون الحالية هي  '.$curr.' - '.$fdata['current']);
			}
			
			
			$time	= dates::convert_to_date('now');
			$time	= dates::convert_to_string($time);
			
			$upd_array = array();
			$upd_array['cob_active'] 	= ($curr)?0:1;
			$upd_array['update_at'] 	= $time;
			$upd_array['update_by'] 	= session::get('user_id');
			
			$this->db->update(DB_PREFEX.'cobon',$upd_array,'cob_id = '.$fdata['id']);
			
			return array('id'=>$fdata['id']);
		}
		
	}
?>