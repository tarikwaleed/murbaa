<?php
	/**
	* VIEW, 
	*/
	class view
	{
		/**
		* The Default Method Like Main in java
		*/
		function __construct()
		{
			
		}
		
		/**
		* user data
		*/
		public $user_name	= '';
		public $user_img	= '';
		public $user_type	= '';
		public $curr_page	= '';
		public $curr_title	= TITLE;
		
		
		/**
		* fun render
		*/
		public function render($files,$class="")
		{
			/**
			* check if file array is OK
			*/
			if(!is_array($files))
			{
				require("views/error/data_error.php");
				return;
			}
			
			//header
			require("views/header.php");
			
			//menu
			//if($class != "login")
			//{
				if(empty(session::get('user_type')))
				{
					require("views/menus/menu.php");
				}elseif(empty(session::get('company')))
				{
					require("views/menus/admin.php");
				}else
				{
					require("views/menus/owner.php");
				}
			//}
			
			//content files
			foreach($files as $k => $name)
			{
				$file_name = "views/".$name.".php";
				if(file_exists($file_name))
				{
					require($file_name);
				}else
				{
					$this->file_name = $name;
					require("views/error/err404.php");
				}
			}
			//footer
			require("views/footer.php");
		}
		
		/**
		* fun js_render
		* get pages that called by AJAX
		* @parm $name: The file name
		*/
		function js_render($name)
		{
			if(!is_array($name))
			{
				if(file_exists("views/$name.php"))
				{
					require("views/".$name.".php");
				}else
				{
					$this->file_name = $name;
					require("views/error/err404.php");
				}
			}else{
				foreach($name as $k => $na)
				{
					if(file_exists("views/$na.php"))
					{
						require("views/$na.php");
					}else
					{
						$this->file_name .= $na;
						require("views/error/err404.php");
					}
				}
			}
		}
		
		/**
		* fun render
		*/
		public function report_render($files,$title)
		{
			/**
			* check if file array is OK
			*/
			if(!is_array($files))
			{
				require("views/error/data_error.php");
				return;
			}
			
			/**
			* get user data if loggin
			*/
			if(session::get('user_type'))
			{
				$this->user_name	= session::get('user_name');
				$this->user_type	= session::get('user_type');
				$this->user_msg		= session::get('user_MSG');
			}
			$this->html = "";
			
			
			//content files
			foreach($files as $k => $name)
			{
				if(file_exists("views/$name.php"))
				{
					require("views/$name.php");
				}
			}
			
			require(URL_PATH.'adds/pdf.php');
			$mode = [
					'margin_left' => 25,
					'margin_right' => 25,
					'margin_top' => 40,
					'margin_bottom' => 20,
					'margin_header' => 10,
					'margin_footer' => 10
					];
			$last_update = null;
			
			$pdf = pdf::create(true,$title,"fullpage",$mode,'');
			$pdf->WriteHTML($this->html);
			pdf::close($pdf,$title.".pdf");
			
		}
	}
?>