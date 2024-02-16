<?php
	/**
	* tickets Controller, 
	* This Called after admin
	*/
	class tickets extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/tickets/JS/tickets.js');
		}
		
		//Display user window
		function index($id='')
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->user_list());
				return;
			}
			$this->view->curr_no 	= (!empty($id))?$id:"";
			$this->view->curr_user 	= (empty(session::get('company')))?"ADMIN":"CUS";
			$this->view->render(array('tickets/index'));
		}
		
		//get Chat
		function get_chat($room =0,$last=0)
		{
			//lib::api_headers();
			echo json_encode($this->model->chat_data($room,$last));
		}
		
		/**
		* addchat
		* AJAX fun
		* addchat
		*/
		function addchat()
		{
			echo json_encode($this->model->addChat());
		}
		
		/**
		* add_ticket
		* AJAX fun
		* create new ticket
		*/
		function add_ticket()
		{
			echo json_encode($this->model->add_ticket());
		}
		
	}
?>
