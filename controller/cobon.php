<?php
	/**
	* cobon Controller, 
	* This Called after admin
	*/
	class cobon extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/cobon/JS/cobon.js');
		}
		
		//Display user window
		function index()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->cobon());
				return;
			}
			$this->view->render(array('cobon/index'));
		}
		
		//Add new cobon
		function add_cobon()
		{
			echo json_encode($this->model->add_cobon());
		}
		
		//update cobon
		function upd_cobon()
		{
			echo json_encode($this->model->upd_cobon());
		}
		
		//active - freez cobon
		function active()
		{
			echo json_encode($this->model->active());
		}
		
	}
?>
