<?php
	/**
	* city MODEL, 
	*/
	class city_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* city_list get All city
		*/
		public function city_list()
		{
			$city = $this->db->select("SELECT c_id, c_name, c_country, c_area_name
									FROM ".DB_PREFEX."city 
									WHERE 1=1
									" ,array());
			$ret = array();
			foreach($city as $val)
			{
				$r = array();
				$r["ID"] 		= $val['c_id'];
				$r["NAME"] 		= $val['c_name'];
				$r["AREA"] 		= $val['c_area_name'];
				$r["COUNTRY"] 	= $val['c_country'];
				$r["NEIGHBOR"] 	= $this->neighbor($val['c_id']);
				array_push($ret,$r);
			}
			return $ret;
		}
		
		/**
		* neighbor get All neighborhood
		*/
		public function neighbor($id=0)
		{
			$form = new form();
			
			if(!$form->single_valid($id,'Integer'))
			{
				return array();
			}
			
			return $this->db->select("SELECT nei_id AS ID , nei_name AS NAME, nei_letter AS LETTER
									,count(l_id) AS LANDS
									FROM ".DB_PREFEX."neighborhood 
									LEFT JOIN ".DB_PREFEX."land ON nei_id = l_neighborhood
									WHERE nei_city = :ID
									GROUP BY nei_id
									" ,array(":ID"=>$id));
			
		}
		
		/**
		* new_city
		* save New city
		*/
		public function new_city()
		{
			$form = new form();
			$form	->post('new_name')
					->valid('Min_Length',2)
					
					->post('new_area')
					->valid('Min_Length',2)
					
					->submit();
			$fdata = $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$gr_array = array('c_name'		=>$fdata['new_name']
							,'c_name_EN'	=>$fdata['new_name']
							,'c_area_name'	=>$fdata['new_area']
							,'c_area_name_EN'=>$fdata['new_area']
							,'create_by'	=>session::get('user_id')
							,'create_at'	=>$time
							);
			$this->db->insert(DB_PREFEX.'city',$gr_array);
			return array('id'=>$this->db->LastInsertedId());
		}
		
		/**
		* upd_city
		* update city
		*/
		public function upd_city()
		{
			$form = new form();
			
			$form	->post('id')
					->valid('Integer')
					
					->post('upd_name')
					->valid('Min_Length',2)
					
					->post('upd_area')
					->valid('Min_Length',2)
					
					->submit();
			$fdata = $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check city
			$cat = $this->db->select("SELECT c_id, c_name
									FROM ".DB_PREFEX."city
									WHERE c_id = :ID
									" ,array(":ID"=>$fdata['id']));
			if(count($cat)!= 1)
			{
				return array('Error'=>"In Field id: city Not Found");
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			$gr_array = array('c_name'		=>$fdata['upd_name']
							,'c_name_EN'	=>$fdata['upd_name']
							,'c_area_name'	=>$fdata['upd_area']
							,'c_area_name_EN'=>$fdata['upd_area']
							,'update_by'	=>session::get('user_id')
							,'update_at'	=>$time
							);
				
			$this->db->update(DB_PREFEX.'city',$gr_array,"c_id = ".$fdata['id']);
			return array('id'=>$fdata['id']);
			
		}
		
		/**
		* del_city
		* delete city
		*/
		public function del_city()
		{
			$form = new form();
			
			$form	->post('id')
					->valid('Integer')
					
					->submit();
						
			$fdata = $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check city
			$city = $this->db->select("SELECT c_id, c_name , count(nei_id) AS NEI
									FROM ".DB_PREFEX."city
									LEFT JOIN ".DB_PREFEX."neighborhood ON nei_city = c_id
									WHERE c_id = :ID
									" ,array(":ID"=>$fdata['id']));
			if(count($city)!= 1)
			{
				return array('Error'=>"In Field id: city Not Found");
			}
			if($city[0]['NEI']!= 0)
			{
				return array('Error'=>"In Field id: city Not Empty");
			}
			
			$this->db->delete(DB_PREFEX.'city',"c_id = ".$fdata['id']);
			return array('id'=>$fdata['id']);
			
		}
		
		/**
		* new_nei
		* save New neighborhood
		*/
		public function new_nei()
		{
			$form = new form();
			$form	->post('new_nei_name')
					->valid('Min_Length',2)
					
					->post('new_letter')
					->valid('In_Array',array_keys(lib::$letters))
					
					->post('new_nei_city')
					->valid('numeric')
					
					->submit();
			$fdata = $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$gr_array = array('nei_name'	=>$fdata['new_nei_name']
							,'nei_name_EN'	=>$fdata['new_nei_name']
							,'nei_letter'	=>$fdata['new_letter']
							,'nei_city'		=>$fdata['new_nei_city']
							,'create_by'	=>session::get('user_id')
							,'create_at'	=>$time
							);
			$this->db->insert(DB_PREFEX.'neighborhood',$gr_array);
			return array('id'=>$this->db->LastInsertedId());
		}
		
		/**
		* upd_nei
		* update neighborhood
		*/
		public function upd_nei()
		{
			$form = new form();
			$form	->post('id')
					->valid('Integer')
					
					->post('upd_nei_name')
					->valid('Min_Length',2)
					
					->post('upd_letter')
					->valid('In_Array',array_keys(lib::$letters))
					
					->post('upd_nei_city')
					->valid('numeric')
					
					->submit();
			$fdata = $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check city
			$cat = $this->db->select("SELECT nei_id
									FROM ".DB_PREFEX."neighborhood
									WHERE nei_id = :ID 
									" ,array(":ID"=>$fdata['id']));
									
			if(count($cat)!= 1)
			{
				return array('Error'=>"In Field id: neighborhood Not Found");
			}
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$gr_array = array('nei_name'	=>$fdata['upd_nei_name']
							,'nei_name_EN'	=>$fdata['upd_nei_name']
							,'nei_letter'	=>$fdata['upd_letter']
							,'nei_city'		=>$fdata['upd_nei_city']
							,'update_by'	=>session::get('user_id')
							,'update_at'	=>$time
							);
			$this->db->update(DB_PREFEX.'neighborhood',$gr_array," nei_id = ".$fdata['id']);
			return array('id'=>$fdata['id']);
		}
		
		/**
		* del_nei
		* delete neighborhood
		*/
		public function del_nei()
		{
			$form = new form();
			
			$form	->post('id')
					->valid('Integer')
					
					->submit();
						
			$fdata = $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check city
			$nei = $this->db->select("SELECT nei_id ,count(l_id) AS LANDS
									FROM ".DB_PREFEX."neighborhood 
									LEFT JOIN ".DB_PREFEX."land ON nei_id = l_neighborhood
									WHERE nei_id = :ID
									GROUP BY nei_id
									" ,array(":ID"=>$fdata['id']));
			if(count($nei)!= 1)
			{
				return array('Error'=>"In Field id: city Not Found");
			}
			if($nei[0]['LANDS']!= 0)
			{
				return array('Error'=>"In Field id: NEIGHBOR Not Empty");
			}
			
			$this->db->delete(DB_PREFEX.'neighborhood',"nei_id = ".$fdata['id']);
			return array('id'=>$fdata['id']);
			
		}
		
		
		
	}
?>