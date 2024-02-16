<?php
	/**
	* staff Controller, 
	* This Called after admin
	*/
	class staff extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/staff/JS/staff.js');
		}
		
		//Display user window
		function index()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->user_list());
				return;
			}
			$this->view->conf_list = $this->model->conf_list();
			$this->view->per_list = $this->model->per_list();
			$this->view->render(array('staff/index'));
		}
		
		/**
		* new_Staff
		* create New Staff
		* AJAX
		*/
		function new_staff()
		{
			echo json_encode($this->model->new_Staff());
		}
		
		/**
		* upd_Staff
		* update Staff
		* AJAX
		*/
		function upd_staff()
		{
			echo json_encode($this->model->upd_Staff());
		}
		
		/**
		* del_Staff
		* del Staff
		* AJAX
		*/
		function del_staff()
		{
			echo json_encode($this->model->del_Staff());
		}
		
		/**
		* trans_Staff
		* AJAX fun
		* transfire staff to next year
		*/
		function active()
		{
			echo json_encode($this->model->active());
		}
		
		/**
		* msg_staff
		* AJAX fun
		* send msg to staff
		*/
		function msg_staff()
		{
			echo json_encode($this->model->msg_staff());
		}
		
	}
?>
