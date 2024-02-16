<?php
	/**
	* dashboard Controller, 
	* This Called after staff Loggin
	*/
	class dashboard extends controller
	{
		/**
		* The Default Method
		* No return (void)
		*/
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array('public/CSS/paging.css');
			$this->view->JS = array();
		}
		
		//Display dashboard window
		function index()
		{
			$this->view->cities = $this->model->city_list(); 
			$this->view->land_type = $this->model->land_type(); 
			
			array_push($this->view->JS,'views/dashboard/JS/dash.js');
			$this->view->render(array('dashboard/index'),'home');
		}
		
		//send Data By API
		function API($type="city")
		{
			$ret = array();
			switch($type)
			{
				case "types":
					$ret = lib::$land_type;
				break;
				case "search":
					$ret = $this->model->search();
				break;
				case "city":
				default:
					$ret = $this->model->city_list(); 
			}
			lib::api_headers();
			echo json_encode($ret);
		}
		
		//search
		function search()
		{
			echo json_encode($this->model->search());
		}
		
		//land window
		function land($id=0)
		{
			$_POST['csrf'] 	= session::get('csrf');
			$_POST['id'] 	= $id;
			$_POST['chat'] 	= true;
			
			array_push($this->view->JS,'public/JS/jquery/jquery.fullscreen.js');
			array_push($this->view->JS,'views/dashboard/JS/dash.js');
			$this->view->land 	= $this->model->search();
			$this->view->land_type = $this->model->land_type(); 
			$this->view->cities = $this->model->city_list(); 
			if(count($this->view->land) == 1)
			{
				$this->model->visits($this->view->land[0]);
				$this->view->suggest = $this->model->suggest($this->view->land[0]); 
				$this->view->render(array('dashboard/land_details'),'home');
			}else
			{
				$this->view->render(array('dashboard/index'),'home');
			}
		}
		
		function land_like($id=0)
		{
			echo $this->model->land_like($id);
		}
        
        //customer window
		function customer($id=0)
		{
			array_push($this->view->JS,'views/dashboard/JS/customer.js');
			$this->view->customer 	= $this->model->customer($id);
			if(empty($this->view->customer))
			{
				$this->index();
				die();
			}
			$this->view->land_type = $this->model->land_type(); 
			$this->view->render(array('dashboard/cus_details'),'home');
			
		}
		
		function adv_list($id=0)
		{
			echo $this->model->adv_list($id);
		}
		
		function qr($id=0)
		{
			$id = intval($id);
			$qr = new QrCode();
			$qr->URL(URL."dashboard/land/".$id);
			$qr->QRCODE(100);
		}
		
		//get Chat
		function chat_data($room =0,$last=0)
		{
			//lib::api_headers();
			echo json_encode($this->model->chat_data($room,$last));
		}
		
		//add Chat
		function addchat()
		{
			echo json_encode($this->model->addchat());
		}
		
		//New Chat
		function newChatRoom()
		{
			echo json_encode($this->model->newChatRoom());
		}
		
		//about
		function about()
		{
			$this->view->render(array('dashboard/about'),'home');
		}
		
		//terms
		function terms()
		{
			$this->view->terms = $this->model->terms(); 
			$this->view->render(array('dashboard/terms'),'home');
		}
		//policy
		function policy()
		{
			$this->view->terms = $this->model->policy(); 
			$this->view->render(array('dashboard/terms'),'home');
		}
		
		//contact
		function contact()
		{
			$this->view->render(array('dashboard/contact'),'home');
		}
		
		//send Contact message
		function cont()
		{
			echo json_encode($this->model->new_cont());
		}
		
		//save mail_list data
		function mail_list()
		{
			echo json_encode($this->model->mail_list());
		}
		
        //save report
		function report()
		{
			echo json_encode($this->model->report());
		}
		
	}
?>