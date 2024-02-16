<?php
	/**
	* ser_admin Controller, 
	* This Called after admin
	*/
	class ser_admin extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/ser_admin/JS/ser_admin.js');
		}
		
		//Display user window
		function index()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->services());
				return;
			}
			$this->view->render(array('ser_admin/index'));
		}
		
		
	}
?>
