<?php
	class errors
	{
		///The Default Method Like Main in java
		
		function __construct()
		{
			
		}
		public function index()
		{
			print_r(array('error/index'));
		}
		public function file_404($file ="")
		{
			header("HTTP/1.0 404 Not Found");
		}
		public function method($file,$method)
		{
			$this->view->file_name = $file;
			$this->view->method = $method;
			print_r(array('error/method'));
		}
		public function no_permission()
		{
			header("HTTP/1.0 403 Access Denied");
		}
		public function error_data()
		{
			print_r(array('error/data_error'));
		}
		public function not_found($id)
		{
			print_r(array('error/data_error'));
		}
	}

?>