<?php
	/**
	* mail_list Controller, 
	* This Called after admin
	*/
	class mail_list extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/mail_list/JS/mail_list.js');
		}
		
		//Display user window
		function index()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->user_list());
				return;
			}
			$this->view->render(array('mail_list/index'));
		}
		
		/**
		* trans_mail_list
		* AJAX fun
		* transfire mail_list to next year
		*/
		function active()
		{
			echo json_encode($this->model->active());
		}
		
		/**
		* msg_mail
		* AJAX fun
		* send msg to mail_list
		*/
		function msg_mail()
		{
			echo json_encode($this->model->msg_mail_list());
		}
		
	}
?>
