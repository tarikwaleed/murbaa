<?php
	/**
	* configuration Controller, 
	* This For configuration Operations
	*/
	class configuration extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/configuration/JS/editor.js'
									,'views/configuration/JS/config.js'
									,'public/JS/img.js');
			$this->view->curr_page = "configuration";
		}
		
		/**
		* index 
		* show configuration list
		*/
		function index()//Main Page ........
		{
			$x = "";
			if(!empty($_POST['csrf']))
			{
				$a = $this->model->upd_info();
				if(!empty($a["Error"]))
				{
					$x = $a["Error"];
				}else
				{
					$x = "تم تحديث البيانات";
				}
			}
			$this->view->config_item = $this->model->config_item();
			$this->view->config_item["Error"] = $x;
			$this->view->render(array('configuration/index'));
			
		}
		
	}
?>