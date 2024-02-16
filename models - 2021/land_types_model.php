<?php
	/**
	* land_types MODEL, 
	*/
	class land_types_model extends model
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
			return $this->db->select("SELECT ty_id AS ID, ty_name AS NAME
									,ty_name_en AS NAME_EN, ty_for AS TY_FOR
									,ty_builed AS BUILD , count(l_id) AS LANDS
									FROM ".DB_PREFEX."land_type
									LEFT JOIN ".DB_PREFEX."land ON l_type = ty_id
									WHERE 1 = 1
									GROUP BY ty_id"
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
					
					->post('new_for') // Type
					->valid('in_array',array_keys(lib::$land_for))
					
					->post('new_build',false,true) // building or not
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
			$ty_array = array('ty_name'		=>$fdata['new_name']
							,'ty_name_en'	=>$fdata['new_name']
							,'ty_for'		=>$fdata['new_for']
							,'ty_builed'	=>$fdata['new_build']
							,'create_by'	=>session::get('user_id')
							,'create_at'	=>$time
							);
			$this->db->insert(DB_PREFEX.'land_type',$ty_array);
			$id = $this->db->LastInsertedId();
			
			return array('id'=>$id);
			
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
					
					->post('upd_for') // Type
					->valid('in_array',array_keys(lib::$land_for))
					
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
							,'ty_for'		=>$fdata['upd_for']
							,'ty_builed'	=>$fdata['upd_build']
							,'update_by'	=>session::get('user_id')
							,'update_at'	=>$time
							);
			$this->db->update(DB_PREFEX.'land_type',$ty_array,"ty_id = ".$fdata['id']);
			
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
			
			$this->db->delete(DB_PREFEX.'land_type',"ty_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
	}
?>