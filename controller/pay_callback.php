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
			$link = URL;
			if(!empty($_POST))
			{
				$link = $this->model->callback($id);
				if(is_array($link))
				{
					echo json_encode($link);
					return;
				}elseif(filter_var($link,FILTER_VALIDATE_URL))
				{
					header('Location: '.$link);
				}
			}
			header('Location: '.URL);
			
		}
	}
?>
