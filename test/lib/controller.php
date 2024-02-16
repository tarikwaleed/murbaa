<?php
	/**
	* Main Controller, 
	*/
	class controller
	{
		/**
		* The Default Method Like Main in java
		*/
		function __construct()
		{
			$this->view = new view();
		}
		public function loadModel($name,$model_path)
		{
			// To Load Model File
			$path = $model_path.$name.'_model.php';
			
			if(file_exists($path))
			{
			    require($path);
				$model_name = $name.'_model';
				
				$this->model = new $model_name();
			}
		}
		
	}
?>