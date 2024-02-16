<?php
	//report Controller
	class report extends controller
	{
		//The Default Method (void)
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/report/JS/report.js');
			$this->view->curr_page = "report";
		}
		
		/**
		* index 
		* show report list
		*/
		function index()//Main Page ........
		{
			$this->view->rep_list = $this->model->rep_list();
			$this->view->render(array('report/index'));
		}
		
	}
?>