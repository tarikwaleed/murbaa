<?php
	/**
	* my_co Controller, 
	*/
	class my_co extends controller
	{
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/my_co/JS/pro.js','public/JS/img.js');
			$this->view->curr_page = "my_co";
		}
		
		//Display my_co window
		function index()
		{
			$this->view->package = $this->model->package();
			$this->view->nat_type = $this->model->nat_type();
			$this->view->sys_info 	= json_encode($this->model->sys_info());
			if(session::get('user_type') == "public")
			{
				$this->view->lands 		= json_encode($this->model->lands());
				$this->view->render(array('my_co/public'));
			}else
			{
				$this->view->render(array('my_co/index'));
			}
		}
		
		//Update upd data
		function upd(){echo json_encode($this->model->upd_info());}
		
		//delete img file
		function del_img(){echo $this->model->del_img();}
		
		//check cobon
		function cobon($id="",$type=""){
            echo json_encode($this->model->cobon($id,$type));
        }
		
		//upgrade
		function upgrade()
		{
			$this->view->upgrade = $this->model->upgrade();
			$this->view->package = $this->model->package();
			$this->view->sys_info 	= json_encode($this->model->sys_info());
			$this->view->render(array('my_co/index'));
		}
		
		//ret
		function ret($id="")
		{
            if(empty($id))
            {
                $this->view->upgrade = array("error"=>"Not Valid ID");
            }else
            {
                $this->view->upgrade = payments::callback( $this->model->db,$id);
			}
            $this->view->package = $this->model->package();
			$this->view->sys_info 	= json_encode($this->model->sys_info());
			$this->view->render(array('my_co/index'));
		}
		
        
		//Display my_co window
		function registration()
		{
			$this->view->package = $this->model->package();
			$this->view->nat_type = $this->model->nat_type();
			$this->view->sys_info 	= json_encode($this->model->sys_info());
			$this->view->render(array('my_co/reg'));
			
		}
		
        //Display my_co window
		function invites()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->invites());
				return;
			}
			$this->view->comp_list 	= $this->model->company();
			$this->view->sys_info 	= json_encode($this->model->sys_info());
			$this->view->JS = array('views/my_co/JS/inv.js','public/JS/img.js');
			$this->view->render(array('my_co/inv'));
			
		}
        
		//send registration request
		function reg(){echo json_encode($this->model->reg());}
		
		//Update registration request
		function upd_reg(){echo json_encode($this->model->upd_reg());}
		
        //send service registration request
		function ser_reg(){echo json_encode($this->model->ser_reg());
		}
		
		//Update service registration request
		function upd_ser_reg(){echo json_encode($this->model->upd_ser_reg());}
		
	}