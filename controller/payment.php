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
			$this->view->services = $this->model->service();
			$this->view->render(array('payment/index'));
		}
		
        //Add new withdraw request
		function withdraw()
		{
			echo json_encode($this->model->withdraw());
		}
		
		//cancel withdraw request
		function dis_active()
		{
			echo json_encode($this->model->dis_active());
		}
		
		//withdraw compleate
		function active()
		{
			echo json_encode($this->model->active());
		}
		
	}
?>
