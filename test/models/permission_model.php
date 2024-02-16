<?php
	/**
	* permission MODEL, 
	*/
	class permission_model extends model
	{
		/** The Default Method Like Main in java*/
		function __construct()
		{
			parent::__construct();
		}
		
		/**
		* function group
		* get group list
		*/
		public function group($id=0)
		{
			$wh = "";
			$wh_array = array();
			
			$form = new form();
			if(!empty($id) && $form->single_valid($id,'numeric'))
			{
				$wh = "per_id = :ID AND ";
				$wh_array[':ID'] = $id;
			}
			if(empty(session::get('company')))
			{
				//for Admin
				$wh .= "(per_type = 'ADMIN' OR per_id = 2) AND ";
			}else
			{
				//for Customer
				$wh .= "per_type = 'CUSTOMER' AND (per_company = :CO OR per_id = 2) AND ";
				$wh_array[":CO"] = session::get('company');
			}
			
			$gr = $this->db->select("SELECT per_id AS ID, per_name AS NAME
									,per_desc AS DESCR, count(staff_id) AS STAFF
									,per_default_page AS DEF_PG_ID, page_class AS DEF_CLS, page AS DEF_PG
									,page_description AS DEF_DESC, per_type AS TYPE
									,concat('".URL."permission/upd_group/',per_id) AS LINK
									FROM ".DB_PREFEX."permission_groups
									LEFT JOIN ".DB_PREFEX."staff ON per_id = staff_permission
									JOIN ".DB_PREFEX."pages ON page_id = per_default_page
									WHERE $wh 1=1
									GROUP BY per_id
										" ,$wh_array
								);
			if(count($gr) == 1)
			{
				$gr[0]['PAGES'] = array();
				$pg = $this->db->select("SELECT per_group_page 
										FROM ".DB_PREFEX."per_group_pages 
										WHERE per_group_permission = :ID",
										array(":ID"=>$gr[0]['ID']));
				foreach($pg as $val)
				{
					array_push($gr[0]['PAGES'],$val['per_group_page']);
				}
			}
			return $gr;
			
		}
		
		/**
		* function pages
		* get pages list
		*/
		public function pages()
		{
			if(empty(session::get('company')))
			{
				//for Admin
				$wh = "ADMIN";
			}else
			{
				//for Customer
				$wh = "CUSTOMER";
			}
			$x = $this->db->select("SELECT page_id AS ID, page_class AS CL_NAME
									,page_name AS NAME
									,page AS PG, page_description AS DEF_DESC
									,page_per_type AS PER_TYPE
									FROM ".DB_PREFEX."pages
									WHERE page_type IN (:TY,'ADMIN_CUS')
									" ,array(":TY"=>$wh)
								);
			$ret = array();
			foreach($x as $val)
			{
				if(empty($ret[$val['CL_NAME']]))
				{
					$ret[$val['CL_NAME']] = array();
				}
				$ret[$val['CL_NAME']][$val['PG']] = $val;
			}
			return $ret;
		}
		
		/**
		* function new_group
		* New Group
		* AJAX
		*/
		public function new_group()
		{
			$form	= new form();
			
			$form	->post('name') // NAME
					->valid('Min_Length',3)
					
					->post('desc',false,true) // Desc
					->valid('Min_Length',10)
					
					->post('def_page') // default page
					->valid('numeric')
					
					->post('pages') // pages
					->valid_array('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//insert
			$gr_array = array('per_name'		=>$fdata['name']
							,'per_desc'			=>$fdata['desc']
							,'per_default_page'	=>$fdata['def_page']
							,'create_by'		=>session::get('user_id')
							,'create_at'		=>$time
							);
							
			if(empty(session::get('company')))
			{
				//for Admin
				$gr_array["per_type"] = 'ADMIN';
			}else
			{				
				//for Customer
				$gr_array["per_type"] = 'CUSTOMER';
				$gr_array["per_company"] = session::get('company');
			}
			
			$this->db->insert(DB_PREFEX.'permission_groups',$gr_array);
			$id = $this->db->LastInsertedId();
			
			$pag_array = array('per_group_permission'	=>$id
							,'per_group_page'			=>0
							,'create_by'				=>session::get('user_id')
							,'create_at'				=>$time
							);
			
			foreach($fdata['pages'] as $val)
			{
				$pag_array['per_group_page'] = $val;
				$this->db->insert(DB_PREFEX.'per_group_pages',$pag_array);
			}
			
			return array('id'=>$id);
			
		}
		
		/**
		* function upd_group
		* Update Group
		* AJAX
		*/
		public function upd_group()
		{
			$form	= new form();
			
			$form	->post('id') // id
					->valid('numeric')
					
					->post('name') // NAME
					->valid('Min_Length',3)
					
					->post('desc',false,true) // Desc
					->valid('Min_Length',10)
					
					->post('def_page') // default page
					->valid('numeric')
					
					->post('pages') // pages
					->valid_array('numeric')
					
					->submit();
			$fdata	= $form->fetch();
			
			if(!empty($fdata['MSG']))
			{
				return array('Error'=>$fdata['MSG']);
			}
			
			//check group
			$wh = "per_id = :ID AND ";
			$wh_array = array(":ID" =>$fdata['id']);
			if(empty(session::get('company')))
			{
				//for Admin
				$wh .= "per_type = 'ADMIN' AND per_id != 1 AND ";
			}else
			{
				//for Customer
				$wh .= "per_type = 'CUSTOMER' AND per_company = :CO AND per_id != 2 AND ";
				$wh_array[":CO"] = session::get('company');
			}
			$gr = $this->db->select("SELECT per_id
									FROM ".DB_PREFEX."permission_groups
									WHERE $wh 1=1
									" ,$wh_array
								);
			if(count($gr) != 1)
			{
				return array("Error"=>"Group Not Found");
			}
			
			$time = dates::convert_to_date('now');
			$time = dates::convert_to_string($time);
			
			//update
			$gr_array = array('per_name'		=>$fdata['name']
							,'per_desc'			=>$fdata['desc']
							,'per_default_page'	=>$fdata['def_page']
							,'update_by'		=>session::get('user_id')
							,'update_at'		=>$time
							);
			$this->db->update(DB_PREFEX.'permission_groups',$gr_array,"per_id = ".$fdata['id']);
			
			//delete old pages
			$old_pages = array();
			$pg = $this->db->select("SELECT per_group_page AS OLD_ID
										FROM ".DB_PREFEX."per_group_pages 
										WHERE per_group_permission = :ID",
										array(":ID"=>$fdata['id']));
			
			foreach($pg as $val)
			{
				$as = array_search($val['OLD_ID'],$fdata['pages']); 
				if($as === false)
				{
					//delete page
					$this->db->delete(DB_PREFEX.'per_group_pages'
									,"per_group_permission = ".$fdata['id']." 
										AND per_group_page = ".$val['OLD_ID']);
				}else
				{
					array_splice($fdata['pages'],$as,1);
				}
				
			}
			
			//add new pages
			$pag_array = array('per_group_permission'	=>$fdata['id']
							,'per_group_page'			=>0
							,'create_by'				=>session::get('user_id')
							,'create_at'				=>$time
							);
			
			foreach($fdata['pages'] as $val)
			{
				$pag_array['per_group_page'] = $val;
				$this->db->insert(DB_PREFEX.'per_group_pages',$pag_array);
			}
			
			return array('id'=>$fdata['id']);
			
		}
		
		/**
		* function del_group
		* Delete Group
		* AJAX
		*/
		public function del_group()
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
			
			//check group
			$wh = "per_id = :ID AND ";
			$wh_array = array(":ID" =>$fdata['id']);
			if(empty(session::get('company')))
			{
				//for Admin
				$wh .= "per_type = 'ADMIN' AND per_id != 1 AND ";
			}else
			{
				//for Customer
				$wh .= "per_type = 'CUSTOMER' AND per_company = :CO AND per_id != 2 AND ";
				$wh_array[":CO"] = session::get('company');
			}
			
			$gr = $this->db->select("SELECT per_id, count(staff_id) AS STAFF
									FROM ".DB_PREFEX."permission_groups
									LEFT JOIN ".DB_PREFEX."staff ON per_id = staff_permission
									WHERE $wh 1=1
									GROUP BY per_id
									" ,$wh_array
								);
			if(count($gr) != 1)
			{
				return array("Error"=>"Group Not Found");
			}
			if($gr[0]['STAFF'] != 0)
			{
				return array("Error"=>"Staff In This Group ".$gr[0]['STAFF']);
			}
			
			//delete pages
			$this->db->delete(DB_PREFEX.'per_group_pages',"per_group_permission = ".$fdata['id']);
			
			$this->db->delete(DB_PREFEX.'permission_groups',"per_id = ".$fdata['id']);
			
			return array('id'=>$fdata['id']);
			
		}
		
		
	}
?>