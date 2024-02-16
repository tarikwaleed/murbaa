<?php
	/**
	* payment Controller, 
	* This Called after admin
	*/
	class payment extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/payment/JS/payment.js');
		}
		
		//Display user window
		function index()
		{
			$this->view->bills = $this->model->payment();
			$this->view->render(array('payment/index'));
		}
		
		
	}
?>
