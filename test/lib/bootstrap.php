<?php
	/**
	* Lib bootstrap Init SYS, 
	*/
	class bootstrap extends controller
	{
		private $_URL = NULL;
		
		private $_controller_path = 'controller/';
		private $_model_path = 'models/';
		//private $_errorFile = 'error';
		
		private $permission = NULL;
		private $errors = NULL;
		
		/**
		* The Default Method Like Main in java
		*/
		function __construct()
		{
			//set init
			$this->_sys_init();
			//set url
			$this->_getURL();
			
			if(empty($this->_URL[0]))
			{
				$this->_URL[0] = $this->get_default_page();
			}
			if(empty($this->_URL[1]))
			{
				$this->_URL[1] = 'index';
			}
			
			$access = $this->staff_settings->get_acsses($this->_URL);
			
			if(!$access && session::get('user_id'))
			{
				$this->errors->no_permission();
			}elseif(!$access)
			{
				$x = false;
                if($this->_URL[1] == "ret" && !empty($this->_URL[2])&& cookies::get($this->_URL[2]) !== false)
				{
					$x = $this->staff_settings->ret_pay_session($this->_URL[2]);
				}elseif($this->_URL[1] == "pay_ret" && !empty($this->_URL[3])&& cookies::get($this->_URL[3]) !== false)
				{
					$x = $this->staff_settings->ret_pay_session($this->_URL[3]);
				}
				
				$this->save_data_for_check($x);
				
                if($x && $this->staff_settings->get_acsses($this->_URL))
				{
					$this->_setExsistController();
				}else
				{
                    header("location: ".URL."login/");
				}
                header("location: ".URL."login/");
			}else
			{
	        
				$this->_setExsistController();
			}
			
		}//__construct
		
		private function save_data_for_check($x=false)
		{
			$date=date_create();
			$myfile = fopen("config/newfile.csv", "a");
			if($myfile !== false)
			{
				$txt = "Start:".date_format($date,"Y/m/d H:i");
				$txt .="\n";
                if($x)
                {
                    $txt .="Has X True \n ";
                }else{
                    $txt .="Has X False \n ";
                }
				$txt .= "LINK:,";
				foreach($this->_URL as $val)
				{
					$txt .= $val.",";
				}
				$txt .="\n";
				$txt .="Time,Host,AGENT,ADDRESS,FILE,METHOD,URL,REF\n";
				$txt .=$_SERVER['REQUEST_TIME'].",";
				$txt .=$_SERVER['HTTP_HOST'].",";
				$txt .=$_SERVER['HTTP_USER_AGENT'].",";
				$txt .=$_SERVER['REMOTE_ADDR'].",";
				$txt .=$_SERVER['SCRIPT_FILENAME'].",";
				$txt .=$_SERVER['REQUEST_METHOD'].",";
				$txt .=$_SERVER['REQUEST_URI'].",";
				if(!empty($_SERVER['HTTP_REFERER']))
				{
					$txt .= $_SERVER['HTTP_REFERER'];
				}
				$txt .="\n";
				session_start();
				foreach($_SESSION as $key=>$val)
				{
					$txt .= $key.",".$val.",,";
				}
				
				$txt .="\n\n\n";
				fwrite($myfile, $txt);
				fclose($myfile);
			}
		}
		
		/**
		* _sys_init This For Set Ini variables
		*/
		private function get_default_page()
		{
			if(!empty(session::get('user_type')))
			{
				return $this->staff_settings->default_page(session::get('user_type'));
			}else
			{
				return "dashboard";
			}
		}
		
		
		/**
		* _sys_init This For Set Ini variables
		*/
		private function _sys_init()
		{
			date_default_timezone_set('Africa/Cairo');
			session::init();
			$this->staff_settings 	= new staff_settings;
			$this->errors			= new errors;
			
			// Language Settings
			if(!cookies::get('lang') && !session::get('lang'))
			{
				cookies::set('lang','AR');
				session::set('lang','AR');
			}else if(!cookies::get('lang'))
			{
				cookies::set('lang',session::get('lang'));
				$language = 0;
			}else if(!session::get('lang'))
			{
				session::set('lang', cookies::get('lang'));;
			}
		}
		
		/**
		* _getURL This for get and validate URL
		*/
		private function _getURL()
		{
			$this->_URL = (isset($_GET['url']))?$_GET['url']:$this->get_default_page().'/';
			
			$this->_URL = rtrim($this->_URL,'/');
			
			if(strpos($this->_URL,"get_file") === false)
			{
				$this->_URL = filter_var($this->_URL,FILTER_SANITIZE_URL);
			}
			
			$this->_URL = explode('/',$this->_URL);
			
			foreach($this->_URL as $val)
			{
				if(filter_var($val,FILTER_VALIDATE_IP,FILTER_VALIDATE_URL))
				{
					$this->errors->no_permission();
					return;
				}
			}
			
		}
		
		/**
		* set The called Controller And Its Method
		*/
		private function _setExsistController()
		{
			/**
			* url[0] = controller
			* url[1] = Method
			* url[2] = param 1
			* url[3] = param 2
			* url[4] = param 3
			* ......
			*/
			
			$file = $this->_controller_path.$this->_URL[0].".php";
			if(!file_exists($file))
			{
				$this->errors->file_404($file);
				return;
			}
			require($file);
			
			$this->controller = new $this->_URL[0]; //controller Class
			
			$this->controller->loadmodel($this->_URL[0],$this->_model_path);
			
			if(isset($this->_URL[1]))
			{
				if(method_exists($this->controller,$this->_URL[1]))
				{
					switch(count($this->_URL))
					{
						case 2:
							$this->controller->{$this->_URL[1]}();
						break;
						case 3:
							$this->controller->{$this->_URL[1]}($this->_URL[2]);
						break;
						case 4:
							$this->controller->{$this->_URL[1]}($this->_URL[2],$this->_URL[3]);
						break;
						case 5:
							$this->controller->{$this->_URL[1]}($this->_URL[2],$this->_URL[3],$this->_URL[4]);
						break;
						case 6:
							$this->controller->{$this->_URL[1]}($this->_URL[2],$this->_URL[3],$this->_URL[4],$this->_URL[5]);
						break;
						default:
							die('Something Is Error In Bootsrap');
					}
				}else
				{
					$this->controller->index();
				}
			}else
			{
				$this->controller->index();
			}			
		}
		
		/*function set_controller_path($path)
		{
			$this->_controller_path = $path;
		}
		
		function set_model_path($path)
		{
			$this->_model_path = $path;
		}
		
		function set_defaultFile($path)
		{
			$this->_defaultFile = $path;
		}
		
		function set_errorFile($path)
		{
			$this->_errorFile = $path;
		}
		**
		* to download public files
		*
		private function downloads()
		{
			$file = 'public';//URL.
			foreach($this->_URL as $k => $v)
			{
				if($k == 0)
				{
					continue;
				}else
				{
					$file.= '/';
				}
				$file .= $v;
			}
			
			if (file_exists($file)) 
			{
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
			}
		}*/
	}//class
?>