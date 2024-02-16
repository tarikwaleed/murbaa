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
			$this->view->JS = array('views/my_land/JS/dash.js','views/my_land/JS/drawing_map.js','public/JS/img.js');
		}
		
		//Display user window
		function index()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->search());
				return;
			}
			if($_POST['refrech'])
			{
				echo json_encode($this->model->refrech());
			}

			$this->view->conf_list 	= $this->model->conf_list();
			$this->view->comp_list 	= $this->model->company();
			$this->view->land_type 	= $this->model->land_type(); 
			$this->view->cities 	= $this->model->city_list(); 
			$this->view->render(array('my_land/index'));
		}
		
		//create New land REQ
		function new_request()
		{
			echo json_encode($this->model->new_request());
		}
		
        // create New land
		function new_land()
		{
		    if(!empty($_POST["get_info"]))
		    {
		        echo json_encode($this->model->get_api_land());
			    return;
		    }
		    if(empty($_POST["new_land_type"]))
		    {
			    echo json_encode($this->model->new_land());
			    return;
		    }
		    echo json_encode($this->model->new_api_land());
		}
		
		//Update upd_request
		function upd_request()
		{
			echo json_encode($this->model->upd_request());
		}
		
        //Update land
		function upd_land()
		{
			echo json_encode($this->model->upd_land());
		}
		
		//delete land 
		function del_land()
		{
			echo json_encode($this->model->del_land());
		}
		
		//delete land img
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
			if(empty($_POST['type']))
			{
				$this->view->upgrade 	= array('Error'=>"لم يتم التعرف على الاجراء");
			}elseif($_POST['type']=="VIP_BILL")
			{
				$this->view->upgrade 	= $this->model->upgrade_vip();
			}elseif($_POST['type']=="LAND_BILL")
			{
				$this->view->upgrade 	= $this->model->land_bill();
			}
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
			$this->view->comp_list 	= $this->model->company();
			$this->view->land_type 	= $this->model->land_type(); 
			$this->view->cities 	= $this->model->city_list(); 
			$this->view->render(array('my_land/index'));
		}


		
	}
?>
