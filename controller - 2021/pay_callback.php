<?php
	/**
	* pay_callback Controller
	*/
	class pay_callback extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			
			if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != 'secure.paytabs.sa')
			{
				echo parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
				die();
			}
		}
		
		//Display user window
		function index($id=0)
		{
			if(!empty($_POST))
			{
				echo json_encode($this->model->callback($id));
				return;
			}
			//header('Location: '.URL);
		}
	}
?>
