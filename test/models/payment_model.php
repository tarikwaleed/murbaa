<?php
	/**
	* payment MODEL, 
	*/
	class payment_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function payment
		* get payment
		*/
		public function payment()
		{
			$sea_arr = array();
			$sea_txt = "";
			
			/*$form	= new form();
			
			$form	->post('land',false,true) // land ID
					->valid('numeric')
					
					->post('chatroom',false,true) // chatroom
					->valid('numeric')
					
					->post('bill',false,true) // bill
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error: '=>$fdata['MSG']);
			}
			
			if(!empty($fdata['land']))
			{
				$sea_arr[':LAND'] = "%".$fdata['land']."%";
				$sea_txt .= 'room_land :LAND AND ';
			}
			if(!empty($fdata['chatroom']))
			{
				$sea_arr[':ROOM'] = $fdata['chatroom'];
				$sea_txt .= 'room_id = :ROOM AND ';
			}
			if(!empty($fdata['bill']))
			{
				$sea_arr[':BILL'] = $fdata['bill'];
				$sea_txt .= 'bi_id = :BILL AND ';
			}
			*/
			if(!empty(session::get("company")))
			{
				$sea_txt .= "bi_company = :CO AND ";
				$sea_arr[':CO'] = session::get("company");
			}
			
			$sea_txt .= ' 1=1 ';
			
			return $this->db->select("SELECT bi_id AS ID ,bi_package AS BK_ID, bi_land AS LAND_ID
										,bi_period AS LAND_PERIOD, bi_amount AS AMOUNT, bi_status AS STATUS
										,pk_name AS BK_NAME, bills.create_at BILL_DATE
										,co_name AS CO_NAME
										,l_block AS LAND_BLOCK, l_no AS LAND_NO, l_for AS LAND_FOR
										,ser_id AS SER_ID, ser_title AS TITLE
										FROM ".DB_PREFEX."bill AS bills
										JOIN ".DB_PREFEX."company ON co_id = bi_company
										LEFT JOIN ".DB_PREFEX."land ON l_id = bi_land
										LEFT JOIN ".DB_PREFEX."package ON bi_package = pk_id
										LEFT JOIN ".DB_PREFEX."service_offer ON  bi_service_offer = off_id
										LEFT JOIN ".DB_PREFEX."service ON off_service = ser_id 
										WHERE $sea_txt
										GROUP BY bi_id
										ORDER BY bi_id DESC
										" ,$sea_arr
								);
			
		}
		
		//get service payment
		public function service()
		{
			$sea_arr = array();
			$sea_txt = "";
			
			/*$form	= new form();
			
			$form	->post('land',false,true) // land ID
					->valid('numeric')
					
					->post('chatroom',false,true) // chatroom
					->valid('numeric')
					
					->post('bill',false,true) // bill
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error: '=>$fdata['MSG']);
			}
			
			if(!empty($fdata['land']))
			{
				$sea_arr[':LAND'] = "%".$fdata['land']."%";
				$sea_txt .= 'room_land :LAND AND ';
			}
			if(!empty($fdata['chatroom']))
			{
				$sea_arr[':ROOM'] = $fdata['chatroom'];
				$sea_txt .= 'room_id = :ROOM AND ';
			}
			if(!empty($fdata['bill']))
			{
				$sea_arr[':BILL'] = $fdata['bill'];
				$sea_txt .= 'bi_id = :BILL AND ';
			}
			SELECT ``, ``, ``, ``, ``, ``, 
			 `create_at`, `create_by`, `update_at`, `update_by` FROM `real_offer_price` WHERE 1
			*/
			if(!empty(session::get("company")))
			{
				$sea_txt .= "pr_co = :CO AND ";
				$sea_arr[':CO'] = session::get("company");
			}
			
			$sea_txt .= ' 1=1 ';
			
			$xx = $this->db->select("SELECT pr_id AS ID ,pr_offer AS OFF_ID, pr_amount AS AMOUNT
									,date(bills.create_at) AS BILL_DATE, pr_type AS TYPE, pr_status AS STATUS
									,pr_comment AS COMM, co_id AS CO, co_name AS CO_NAME
									,ser_id AS SER_ID, ser_title AS TITLE
									FROM ".DB_PREFEX."offer_price AS bills
									JOIN ".DB_PREFEX."company ON co_id = pr_co
									LEFT JOIN ".DB_PREFEX."service_offer ON pr_offer = off_id
									LEFT JOIN ".DB_PREFEX."service ON off_service = ser_id 
									WHERE $sea_txt
									GROUP BY pr_id ORDER BY pr_id DESC
									" ,$sea_arr
								);
			
			$ret = array();
			foreach($xx as $val)
			{
				if(empty($ret[$val['CO']]))
				{
					$ret[$val['CO']] = array('total'=>0,'pend'=>0,'data'=>array(),'name'=>$val['CO_NAME']);
				}
				if($val['STATUS'] == 'A')
				{
					if($val['TYPE'] == 'IN')
					{
						$ret[$val['CO']]['total'] += $val['AMOUNT'];
					}else
					{
						$ret[$val['CO']]['total'] -= $val['AMOUNT'];
					}
				}else
				{
					if($val['TYPE'] == 'IN')
					{
						$ret[$val['CO']]['pend'] += $val['AMOUNT'];
					}else
					{
						$ret[$val['CO']]['pend'] -= $val['AMOUNT'];
					}
				}
				array_push($ret[$val['CO']]['data'],$val);
			}
			return $ret;
		}
		
		//Add new withdraw request
		public function withdraw()
		{
			$form	= new form();
			
			$form	->post('new_account') // NAME
					->valid('Min_Length',3)
					
					->post('new_price') // price
					->valid('Int_min',1)
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check price
			$xx = $this->db->select("SELECT 
									 SUM(CASE WHEN pr_type = 'IN' AND pr_status = 'A' 
											THEN pr_amount ELSE 0 END ) AS IN_OK
									,SUM(CASE WHEN pr_type = 'IN' AND pr_status != 'A' 
											THEN pr_amount ELSE 0 END ) AS IN_PEND
									,SUM(CASE WHEN pr_type != 'IN' AND pr_status = 'A' 
											THEN pr_amount ELSE 0 END ) AS OUT_OK
									,SUM(CASE WHEN pr_type != 'IN' AND pr_status != 'A' 
											THEN pr_amount ELSE 0 END ) AS OUT_PEND
									FROM ".DB_PREFEX."offer_price AS bills
									WHERE pr_co = :CO
									
									" ,array(':CO'=>session::get("company"))
								);
			if(count($xx) != 1)
			{
				return array('Error'=>"Error in data");
			}
			$xx = $xx[0];
			$available = $xx['IN_OK'] - $xx['OUT_OK'] - $xx['OUT_PEND'];
			
			if($available < $fdata['new_price'])
			{
				return array('Error'=>"المبلغ المراد سحبه اكبر من المبلغ المتاح");
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$ty_array = array('pr_co'		=>session::get("company")
							,'pr_amount'	=>$fdata['new_price']
							,'pr_type'		=>"OUT"
							,'pr_status'	=>"D"
							,'pr_comment'	=>$fdata['new_account']
							,'create_by'	=>session::get('user_id')
							,'create_at'	=>$time
							);
			$this->db->insert(DB_PREFEX.'offer_price',$ty_array);
			$id = $this->db->LastInsertedId();
			
			return array('id'=>$id);
			
		}
		
		//cancel withdraw request
		public function dis_active()
		{
			$form	= new form();
			
			$form	->post('id') // NAME
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check price
			$xx = $this->db->select("SELECT pr_id , pr_status, pr_type
									FROM ".DB_PREFEX."offer_price
									WHERE pr_co = :CO AND pr_id = :ID
									
									" ,array(':ID'=>$fdata['id'],':CO'=>session::get("company"))
								);
			if(count($xx) != 1)
			{
				return array('Error'=>"Error in data");
			}
			$this->db->delete(DB_PREFEX.'offer_price','pr_id = '.$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
		//withdraw compleate
		public function active()
		{
			$form	= new form();
			
			$form	->post('id') // NAME
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check price
			$xx = $this->db->select("SELECT pr_id , pr_status, pr_type
									FROM ".DB_PREFEX."offer_price
									WHERE pr_id = :ID
									" ,array(':ID'=>$fdata['id'])
								);
			if(count($xx) != 1)
			{
				return array('Error'=>"Error in data");
			}
			$this->db->update(DB_PREFEX.'offer_price',array('pr_status'=>'A'),'pr_id = '.$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
		
	}
?>