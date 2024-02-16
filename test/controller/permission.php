<?php
	/**
	* permission Controller, 
	* This Called after admin
	*/
	class permission extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/permission/JS/per.js');
		}
		
		//Display user window
		function index()
		{
			$this->view->group = $this->model->group();
			$this->view->pages = $this->model->pages();
			$this->view->render(array('permission/index'));
		}
		
		/**
		* new_group
		* save new group
		*/
		function new_group()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->new_group());
			}else
			{
				$this->view->type = "insert";
				$this->view->group = array("ID"			=>0
											,"NAME" 	=>""
											,"TYPE"		=>"CUSTOMER"
											,"DESCR"	=>""
											,"STAFF"	=>0
											,"DEF_PG_ID"=>0
											,"DEF_CLS"	=>""
											,"DEF_PG"	=>""
											,"DEF_DESC"	=>""
											,"LINK"		=>""
											,"PAGES"	=>array()
											);
				$this->view->pages = $this->model->pages();
				$this->view->render(array('permission/group'));
			}
		}
		
		//Display user window
		function upd_group($id=0)
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->upd_group());
			}else
			{
				$this->view->group = $this->model->group($id);
				$this->view->pages = $this->model->pages();
				
				if(count($this->view->group) == 1)
				{
					$this->view->type = "upd";
					$this->view->group = $this->view->group[0];
					$this->view->render(array('permission/group'));
				}else
				{
					$this->view->render(array('permission/index'));
				}
			}
		}
		
		//Delete group
		function del_group()
		{
			echo json_encode($this->model->del_group());
		}
		
		
	}
?>
