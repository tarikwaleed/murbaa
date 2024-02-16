<?php
	/**
	* my_land Controller, 
	* This Called after owner
	*/
	class my_land extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/my_land/JS/dash.js','public/JS/img.js');
		}
		
		//Display user window
		function index()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->search());
				return;
			}
			$this->view->conf_list 	= $this->model->conf_list();
			$this->view->land_type 	= $this->model->land_type(); 
			$this->view->cities 	= $this->model->city_list(); 
			$this->view->render(array('my_land/index'));
		}
		
		/* new_land
		* create New land
		* AJAX
		*/
		function new_land()
		{
			echo json_encode($this->model->new_land());
		}
		
		/* upd_land
		* Update land
		* AJAX
		*/
		function upd_land()
		{
			echo json_encode($this->model->upd_land());
		}
		
		/* del_land
		* delete land 
		* AJAX
		*/
		function del_land()
		{
			echo json_encode($this->model->del_land());
		}
		
		/* del_img
		* delete land img
		* AJAX
		*/
		function del_img()
		{
			echo json_encode($this->model->del_img());
		}
		
		/* active
		* active / Freez land
		* AJAX
		*/
		function active()
		{
			echo json_encode($this->model->active());
		}
		
		/* vip
		* vip land From Package
		* AJAX
		*/
		function vip()
		{
			echo json_encode($this->model->vip());
		}
		
		/* upgrade
		* vip land with Price
		* AJAX
		*/
		function upgrade()
		{
			$this->view->upgrade 	= $this->model->upgrade_vip();
			$this->view->conf_list 	= $this->model->conf_list();
			$this->view->land_type 	= $this->model->land_type(); 
			$this->view->cities 	= $this->model->city_list(); 
			$this->view->render(array('my_land/index'));
		}
		
		/* land_bill
		* land with Price
		* AJAX
		*/
		function land_bill()
		{
			$this->view->upgrade 	= $this->model->land_bill();
			$this->view->conf_list 	= $this->model->conf_list();
			$this->view->land_type 	= $this->model->land_type(); 
			$this->view->cities 	= $this->model->city_list(); 
			$this->view->render(array('my_land/index'));
		}
		
		function ret($id='')
		{
			$this->view->upgrade 	= payments::callback( $this->model->db,$id);
			$this->view->conf_list 	= $this->model->conf_list();
			$this->view->land_type 	= $this->model->land_type(); 
			$this->view->cities 	= $this->model->city_list(); 
			$this->view->render(array('my_land/index'));
		}
		
	}
?>
