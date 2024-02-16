<?php
	/**
	* pkg_types MODEL, 
	*/
	class pkg_types_model extends model
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
		public function types()
		{
			return $this->db->select("SELECT pk_id AS ID, pk_name AS NAME
									,pk_name_EN AS NAME_EN, pk_stars AS STARS
									,pk_price AS PRICE, pk_users AS USERS
									,pk_users_msg AS MSG, pk_adv_area AS ADV, pk_adv_pay AS ADV_PAY
									,pk_vip_area AS VIP , count(co_id) AS CO_NO
									FROM ".DB_PREFEX."package
									LEFT JOIN ".DB_PREFEX."company ON co_package = pk_id
									WHERE 1 = 1
									GROUP BY pk_id"
										,array());
			
		}
		
		/**
		* function add_type
		* New land_type
		* AJAX
		*/
		public function add_type()
		{
			$form	= new form();
			
			$form	->post('new_name') // NAME
					->valid('Min_Length',3)
					
					->post('new_user') // users
					->valid('numeric')
					
					->post('new_msg',false,true) // msg
					->valid('numeric')
					
					->post('new_price') // price
					->valid('numeric')
					
					->post('new_stars') // stars
					->valid('Int_max',5)
					->valid('Int_min',1)
					
					->post('new_adv') // adv area no
					->valid('Int_min',1)
					
					->post('new_adv_pay',false,true) // pay or not
					->valid('numeric')
					
					->post('new_vip',false,true) // building or not
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$ty_array = array('pk_name'		=>$fdata['new_name']
							,'pk_name_EN'	=>$fdata['new_name']
							,'pk_stars'		=>$fdata['new_stars']
							,'pk_price'		=>$fdata['new_price']
							,'pk_users'		=>$fdata['new_user']
							,'pk_users_msg'	=>(empty($fdata['new_msg']))?0:1
							,'pk_adv_pay'	=>(empty($fdata['new_adv_pay']))?0:1
							,'pk_adv_area'	=>$fdata['new_adv']
							,'pk_vip_area'	=>$fdata['new_vip']
							,'create_by'	=>session::get('user_id')
							,'create_at'	=>$time
							);
			$this->db->insert(DB_PREFEX.'package',$ty_array);
			
			return array('id'=>$this->db->LastInsertedId());
			
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
					
					->post('upd_user') // users
					->valid('numeric')
					
					->post('upd_msg',false,true) // msg
					->valid('numeric')
					
					->post('upd_price',false,true) // price
					->valid('numeric')
					
					->post('upd_stars') // stars
					->valid('Int_max',5)
					->valid('Int_min',1)
					
					->post('upd_adv') // adv area no
					->valid('Int_min',1)
					
					->post('upd_adv_pay',false,true) // building or not
					->valid('numeric')
					
					->post('upd_vip',false,true) // building or not
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check type
			$ty = $this->db->select("SELECT pk_id , count(co_id) AS CO_NO
									FROM ".DB_PREFEX."package
									LEFT JOIN ".DB_PREFEX."company ON co_package = pk_id
									WHERE pk_id = :ID
									GROUP BY pk_id"
									,array(":ID"=>$fdata["id"]));
			if(count($ty) != 1)
			{
				return array('Error'=>"Pakage Not Found");
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			$ty_array = array('pk_name'		=>$fdata['upd_name']
							,'pk_name_EN'	=>$fdata['upd_name']
							,'pk_stars'		=>$fdata['upd_stars']
							,'pk_price'		=>(empty($fdata['upd_price']))? 0: $fdata['upd_price']
							,'pk_users'		=>$fdata['upd_user']
							,'pk_users_msg'	=>(empty($fdata['upd_msg']))?0:1
							,'pk_adv_area'	=>$fdata['upd_adv']
							,'pk_adv_pay'	=>(empty($fdata['upd_adv_pay']))?0:1
							,'pk_vip_area'	=>$fdata['upd_vip']
							,'update_by'	=>session::get('user_id')
							,'update_at'	=>$time
							);
			
			$this->db->update(DB_PREFEX.'package',$ty_array,"pk_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
		/**
		* function del_type
		* delete land_type
		* AJAX
		*/
		public function del_type()
		{
			$form	= new form();
			
			$form	->post('id') // id
					->valid('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check type
			$ty = $this->db->select("SELECT pk_id , count(co_id) AS CO_NO
									FROM ".DB_PREFEX."package
									LEFT JOIN ".DB_PREFEX."company ON co_package = pk_id
									WHERE pk_id = :ID
									GROUP BY pk_id"
									,array(":ID"=>$fdata["id"]));
			if(count($ty) != 1)
			{
				return array('Error'=>"Pakage Not Found");
			}
			if($ty[0]['CO_NO'] != 0)
			{
				return array('Error'=>"هنالك عملاء مسجلين بهذا النوع");
			}
			
			$this->db->delete(DB_PREFEX.'package',"pk_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
	}
?>