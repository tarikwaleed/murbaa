<?php
	/**
	* profile Controller, 
	* This Called after staff Loggin
	*/
	class profile extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array('');
			$this->view->JS = array('views/profile/JS/pro.js','public/JS/img.js');
			$this->view->curr_page = "profile";
		}
		
		//Display profile window
		function index()
		{
			$this->view->sys_info 	= json_encode($this->model->sys_info());
			if(session::get('user_type') == "public")
			{
				$this->view->lands 		= json_encode($this->model->lands());
				$this->view->render(array('profile/public'));
			}else
			{
				$this->view->render(array('profile/index'));
			}
			
		}
		
		//Display profile window
		function holy_cov()
		{
			$this->view->sys_info = json_encode($this->model->sys_info());
			$this->view->render(array('profile/holy_cov'));
		}
		
		//Update profile data
		function upd_info()
		{
			echo json_encode($this->model->upd_info());
		}
		
		/**
		* del_img
		* AJAX fun
		* delete img file
		*/
		function del_img()
		{
			echo $this->model->del_img();
		}
		
	}