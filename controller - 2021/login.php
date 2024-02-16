<?php
	// Controller For LOGIN
	class login extends controller
	{
		function __construct()
		{
			if(session::get("error_log_time") > 3)
			{
				die("Sorry ....");
			}
			parent::__construct();
			$this->view->MSG = '';
			$this->view->CSS = array();
			$this->view->JS = array('views/login/JS/login.js');
		}
		
		public function index()
		{
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			{
				echo "Your session Expired, please LOGIN again";
			}elseif(session::get('user_id'))
			{
				header('location:'.URL.session::get('default_page'));
			}else
			{
				$this->view->render(array('login/index'),'login');
			}
		}
		
		public function login()
		{
			$data = $this->model->login();
			
			if($data != null && is_array($data))
			{
				$ok = false;
				if(!empty($data['staff_id']))
				{
					session::set('user_id'			,$data['staff_id']);
					session::set('user_name'		,$data['staff_name']);
					session::set('user_email'		,$data['staff_email']);
					session::set('user_type'		,$data['per_type']);
					session::set('user_img'			,$data['staff_img']);
					session::set('user_per_name'	,$data['per_name']);
					session::set('user_pages'		,$data['pages']);
					
					session::set('default_page'		,$data['page_class']."/".$data['page']);
					
					session::set('company'			,$data['staff_company']);
					session::set('com_name'			,$data['co_name']);
					session::set('com_img'			,$data['co_img']);
					
					//package
					session::set('PK_STARS'			,$data['pk_stars']);
					session::set('PK_NAME'			,$data['pk_name']);
					session::set('PK_USERS'			,$data['pk_users']);
					session::set('PK_ADV'			,$data['pk_adv_area']);
					
					$e = staff_settings::generateRandomString();
					session::set('csrf'	,Hash::create(HASH_FUN,$e,HASH_PASSWORD_KEY));
					session::set('CREATED'		,time());
					header('location:'.URL.$data['page_class']."/".$data['page']);
					die();
				}
			}
			
			$this->view->no = (!empty($_POST['MSG']))?intval($_POST['MSG'])+1:1;
			$this->view->MSG = $data;
			$this->view->render(array('login/index'),'login');
			
		}
		
		public function logout()
		{
			session::destroy();
			header('location:'.URL.'');
		}
		
		/**
		* Forget Password request #1  
		* Open Forget Password Window
		*/
		public function forget()
		{
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
			{
				echo "Your session Expired, please LOGIN again";
			}elseif(session::get('user_id'))
			{
				$default_page = staff_settings::default_page(session::get('user_type'));
				header('location:'.URL.$default_page);
			}else
			{
				$this->view->render(array('login/forget'),'home');
			}
			
		}
		
		/**
		* Forget Password request #2 
		* if Email sent successfully will print 1 or Error message
		*/
		public function forget_request()
		{
			echo json_encode($this->model->forget_request());
		}
		
		/**
		* Forget Password request #3 
		* Open reset Password Window if request is true
		*/
		public function resetpassword($id)
		{
			$x = $this->model->resetpassword($id);
			
			if(!is_array($x))
			{
				echo $x;
			}else
			{
				$this->view->id = $x['for_id'];
				$this->view->render(array('login/reset'),'login');
			}
		}
		
		/**
		* Forget Password request #4 
		* Update Password
		* if Update complected successfully will print 1 or Error message
		*/
		public function update_res_password()
		{
			echo json_encode($this->model->update_res_password());
		}
		
		//display register page
		function register()
		{
			$this->view->render(array('login/register'),'login');
		}
		
		/**
		* Registration
		* Request Registration From Home Page
		*/
		public function reg()
		{
			$data = $this->model->reg();
			
			if($data != null && is_array($data))
			{
				if(!empty($data['staff_id']))
				{
					session::set('user_id'			,$data['staff_id']);
					session::set('user_name'		,$data['staff_name']);
					session::set('user_email'		,$data['staff_email']);
					session::set('user_type'		,$data['per_type']);
					session::set('user_img'			,$data['staff_img']);
					session::set('user_per_name'	,$data['per_name']);
					session::set('user_pages'		,$data['pages']);
					
					session::set('default_page'		,$data['page_class']."/".$data['page']);
					
					session::set('company'			,$data['staff_company']);
					session::set('com_name'			,$data['co_name']);
					session::set('com_img'			,$data['co_img']);
					
					//package
					session::set('PK_STARS'			,$data['pk_stars']);
					session::set('PK_NAME'			,$data['pk_name']);
					session::set('PK_USERS'			,$data['pk_users']);
					session::set('PK_ADV'			,$data['pk_adv_area']);
					
					$e = staff_settings::generateRandomString();
					session::set('csrf'	,Hash::create(HASH_FUN,$e,HASH_PASSWORD_KEY));
					session::set('CREATED'		,time());
					header('location:'.URL."profile/");
					die();
				}
			}
			$this->view->no = (!empty($_POST['MSG']))?intval($_POST['MSG'])+1:1;
			$this->view->MSG = $data;
			$this->view->render(array('login/register'),'home');
		}
		
		public function img()
		{
			try{
				session::set("captcha",rand(1000, 9999)) ;
				$im = @imagecreate(55, 25) or die("Cannot Initialize new GD image stream");
				$background_color = imagecolorallocate($im, 192, 192, 192);
				$text_color = imagecolorallocate($im, 233, 14, 91);

				header("Content-Type: image/png");

				imagestring($im, 50, 10, 5,  session::get("captcha"), $text_color);
				imagepng($im);
				imagedestroy($im);
			}catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
	
	}
?>