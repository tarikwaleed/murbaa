<?php
	/**
	* services Controller
	*/
	class services extends controller
	{
		//The Default Method
		function __construct()
		{
			parent::__construct();
			$this->view->CSS = array();
			$this->view->JS = array();
		}
		
		//Display service window
		function index($my_pro="")
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->services());
				return;
			}
			$this->view->JS = array('views/services/JS/services.js');
			$this->view->config = $this->model->config();
			if(!empty($my_pro))
			{
				$this->view->render(array('services/my_project'));
			}else
			{
				$this->view->render(array('services/index'));
			}
		}
		
		//Add new services
		function new_service()
		{
			echo json_encode($this->model->new_service());
		}
		
		//update services
		function upd_service()
		{
			echo json_encode($this->model->upd_service());
		}
		
		//active - freez services
		function active()
		{
			echo json_encode($this->model->active());
		}
		
		//Display details window
		function details($id=0)
		{
			$_POST['csrf'] 	= lib::get_CSRF();
			$_POST['id']	= $id;
			$this->view->service = $this->model->services();
			
			if(count($this->view->service) != 1)
			{
				$_POST = array();
				$this->index();
				return;
			}
			$this->view->JS = array('views/services/JS/details.js');
			$this->view->config = $this->model->config();
			$this->view->service = $this->view->service[0];
			
			$this->view->render(array('services/details'));
			
		}
		
		//Add new offer
		function new_offer()
		{
			echo json_encode($this->model->new_offer());
		}
		
		//update offer
		function upd_offer()
		{
			echo json_encode($this->model->upd_offer());
		}
		
		//Display details with chat window
		function chat($chat_id=0)
		{
			$this->view->service = $this->model->service_chat($chat_id);
			
			if(empty($this->view->service))
			{
				$_POST = array();
				$this->index();
				return;
			}
			if($this->view->service['CO_ID'] != session::get('company') && $this->view->service['OFF_CO_ID'] != session::get('company'))
			{
				$this->details($this->view->service['ID']);
				return;
			}
			$this->view->JS = array('views/services/JS/details.js');
			$this->view->config = $this->model->config();
			$this->view->render(array('services/chat_details'));
			
		}
		
		//get chat
		function get_chat($off_id=0,$last = 0)
		{
			echo json_encode($this->model->chat($off_id,$last));
		}
		
		//add chat
		function add_chat()
		{
			echo json_encode($this->model->add_chat());
		}
		
		//accept offer
		function accept_offer()
		{
			$this->view->upgrade = $this->model->accept_offer();
			$this->view->service = $this->model->service_chat($this->view->upgrade['OFFER']);
			
			if(empty($this->view->service))
			{
				$this->index();
				return;
			}
			if($this->view->service['CO_ID'] != session::get('company') && $this->view->service['OFF_CO_ID'] != session::get('company'))
			{
				$this->details($this->view->service['ID']);
				return;
			}
			$this->view->JS = array('views/services/JS/details.js');
			$this->view->config = $this->model->config();
			$this->view->render(array('services/chat_details'));
			
		}
		
		//accept offer
		function pay_ret($off,$id)
		{
			$this->view->upgrade = payments::callback( $this->model->db,$id);
			$this->view->service = $this->model->service_chat($off);
			
			if(empty($this->view->service))
			{
				$this->index();
				return;
			}
			if($this->view->service['CO_ID'] != session::get('company') && $this->view->service['OFF_CO_ID'] != session::get('company'))
			{
				$this->details($this->view->service['ID']);
				return;
			}
			$this->view->JS = array('views/services/JS/details.js');
			$this->view->config = $this->model->config();
			$this->view->render(array('services/chat_details'));
		}
		
		//finish service
		function finish()
		{
			echo json_encode($this->model->finish());
		}

        //Display service users window
		function users()
		{
			if(!empty($_POST['csrf']))
			{
				echo json_encode($this->model->service_users());
				return;
			}
			$this->view->JS = array('views/services/JS/services.js');
			$this->view->config = $this->model->config();
			$this->view->render(array('services/users'));
		}
		
	}
?>
