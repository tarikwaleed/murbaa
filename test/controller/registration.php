<?php
	/**
	* registration Controller, 
	* This Called after admin
	*/
	class registration extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/registration/JS/registration.js');
		}
		
		//Display user window
		function index()
		{
			$this->view->REAL_requests = $this->model->real_requests();
			$this->view->SER_requests = $this->model->ser_requests();
			$this->view->render(array('registration/index'));
		}
		//Display user window
		function get_file($company,$file)
		{
			$form	= new form();
			
			if(empty($company) || !$form->single_valid($company,'Integer'))
			{
				echo "Check URL";
				return;
			}
			
			$dir = URL_PATH."reg_data/".$company."/".$file;
			
			if(!file_exists($dir) || is_dir($dir)) 
			{
				echo "File Not Found ";
				return;
			}
			
			//header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: inline; filename="'.basename($dir).'"');
				
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');
			echo file_get_contents($dir);
			
		}
		//accept Requist
		function active()
		{
			echo json_encode($this->model->active());
		}
		
		//accept service Requist
		function active_ser()
		{
			echo json_encode($this->model->active_ser());
		}
		
	}
?>
