<?php
	/**
	* city Controller, 
	* This For city Operations
	*/
	class city extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array('views/city/JS/city.js');
			$this->view->curr_page = "city";
		}
		
		/**
		* index 
		* show city list
		*/
		function index()//Main Page ........
		{
			$this->view->cities = $this->model->city_list();
			$this->view->render(array('city/index'));
		}
		
		/**
		* new_city
		* save new city
		*/
		function new_city()
		{
			echo json_encode($this->model->new_city());
		}
		
		/**
		* upd_city
		* update city
		*/
		function upd_city()
		{
			echo json_encode($this->model->upd_city());
		}
		
		/**
		* del_city
		* delete city
		*/
		function del_city()
		{
			echo json_encode($this->model->del_city());
		}
		
		/**
		* new_nei
		* save new neigborhood
		*/
		function new_nei()
		{
			echo json_encode($this->model->new_nei());
		}
		
		/**
		* upd_nei
		* update neigborhood
		*/
		function upd_nei()
		{
			echo json_encode($this->model->upd_nei());
		}
		
		/**
		* del_nei
		* delete neigborhood
		*/
		function del_nei()
		{
			echo json_encode($this->model->del_nei());
		}
		
	}
?>