<?php
	/**
	* customer Controller, 
	* This Called after admin
	*/
	class customer extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/customer/JS/customer.js');
		}
		
		//Display user window
		function index()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->user_list());
				return;
			}

			if(!empty($_POST['edit_package']))
			{
				 
				echo json_encode($this->model->edit_package());
			}
			$this->view->package = $this->model->package();
			$this->view->render(array('customer/index'));
		}

 
		
		/**
		* trans_customer
		* AJAX fun
		* transfire customer to next year
		*/
		function active()
		{
			echo json_encode($this->model->active());
		}
		
		/**
		* msg_customer
		* AJAX fun
		* send msg to customer
		*/
		function msg_customer()
		{
			echo json_encode($this->model->msg_customer());
		}
		
	}
?>
