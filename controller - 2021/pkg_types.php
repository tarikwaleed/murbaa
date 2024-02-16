<?php
	/**
	* pkg_types Controller, 
	* This Called after admin
	*/
	class pkg_types extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/pkg_types/JS/pkg_types.js');
		}
		
		//Display user window
		function index()
		{
			$this->view->types = $this->model->types();
			$this->view->render(array('pkg_types/index'));
		}
		
		/**
		* add_type
		* AJAX fun
		* Add new pkg_types
		*/
		function add_type()
		{
			echo json_encode($this->model->add_type());
		}
		
		/**
		* upd_type
		* AJAX fun
		* update pkg_types
		*/
		function upd_type()
		{
			echo json_encode($this->model->upd_type());
		}
		
		/**
		* del_type
		* AJAX fun
		* delete pkg_types
		*/
		function del_type()
		{
			echo json_encode($this->model->del_type());
		}
		
	}
?>
