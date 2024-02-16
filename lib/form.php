<?php
	/**
	* Form Validation Controller, 
	* its steps:
	* - Fill out a form
	* - POST to PHP
	* - Sanitize --(cleanup)
	* - validate
	* - Return data
	* - Write to database
	*/
	require('form/valid.php');
	class form
	{
		/** @var array $_postData Store The posted data*/
		private $_postData = array('MSG'=>'');
		
		/** @var array $_Data_permission Store The posted data Empty Permission */
		private $_Data_empty_permission = array();
		
		/** @var array $_Data_permission Store The posted data set Permission */
		private $_Data_isset_permission = array();
		
		
		/** @var $_currentItem The Immediately posted item*/
		private $_currentItem = NULL;
		
		/** @var object $_val The validator Object*/
		private $_val = array();
		
		/** @var object $_error The Error Array*/
		private $_error = array();

		/**
		* The Default Method Like Main in java
		*/
		function __construct()
		{
			$this->_val = new valid();
		}
		/**
		*function post: This is for get $_POST data
		*@param $filed: post id
		*@param $isset: Ignore or not ignore if not set
		*/
		public function post($filed,$isset = false,$impty = false,$JS = false)
		{
			if(!isset($_POST[$filed]))
			{
				$_POST[$filed] = NULL;
			}/*elseif(!is_array($_POST[$filed]))
			{
				$_POST[$filed] = htmlspecialchars($_POST[$filed], ENT_QUOTES, 'UTF-8');
			}*/
			
			$_POST[$filed] = $this->test_input($_POST[$filed]);
			if($JS)
			{
				$this->_postData[$filed] = json_decode($_POST[$filed]);
			}elseif(!is_array($_POST[$filed]))
			{
				try {
					$this->_postData[$filed] = htmlspecialchars($_POST[$filed], ENT_QUOTES, 'UTF-8');
				}
				catch (customException $e) {
					$this->_postData[$filed] = "";
				}
			}else
			{
				$this->_postData[$filed] = $_POST[$filed];
			}
			
			$this->_Data_empty_permission[$filed] = $impty;
			$this->_Data_isset_permission[$filed] = $isset;
			
			$this->_currentItem = $filed;
			return $this;
		}
		
		/**
		* this for test input before validation
		*/
		private function test_input($data) 
		{
			/*if(!isset($_POST[$filed]) && $isset)
			{
				$_POST[$filed] = NULL;
			}
			if(empty($_POST[$filed]) && $impty)
			{
				$_POST[$filed] = NULL;
			}*/
			
			if(empty($data) && $data != 0)
			{
				return "";
			}
			if(is_array($data))
			{
				$data = $this->test_array($data);
			}else
			{
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
			}
			return $data;
		}
		
		private function test_array($data) 
		{
			if(empty($data))
			{
				return "";
			}
			foreach($data As $key => $val)
			{
				if(is_array($val))
				{
					$data[$key] = $this->test_array($val);
				}else
				{
					$data[$key] = trim($val);
					$data[$key] = stripslashes($val);
					$data[$key] = htmlspecialchars($val);
				}
			}
			return $data;
		}
		
		/**
		* This For Validation
		* @param string $typeofValidation The Method from Form/val class
		* @param string $arg The property to validate against
		*/
		public function valid($typeofValidation,$arg = NULL)
		{
			if($typeofValidation == "Integer" && ($this->_postData[$this->_currentItem] === 0 || $this->_postData[$this->_currentItem] === "0"))
			{
				return $this;
			}
			if(empty($this->_postData[$this->_currentItem]))
			{
				if(!$this->_Data_isset_permission[$this->_currentItem]&&!$this->_Data_empty_permission[$this->_currentItem]&&!$this->_Data_empty_permission[$this->_currentItem])
				{
					
					$this->_error[$this->_currentItem] = "In ".$this->_currentItem.": Is Empty!..\n";
				}
				return $this;
			}
			
			if($arg === NULL)
			{
				$errors = $this->_val->{$typeofValidation}($this->_postData[$this->_currentItem]);
			}else
			{
				$errors = $this->_val->{$typeofValidation}($this->_postData[$this->_currentItem],$arg);
			}
			
			if($errors)
			{
				$this->_error[$this->_currentItem] = $errors;
			}elseif($typeofValidation == "Min_Length"||$typeofValidation == "Max_Length")
			{
				$this->_postData[$this->_currentItem] = ltrim($this->_postData[$this->_currentItem]);
				$this->_postData[$this->_currentItem] = rtrim($this->_postData[$this->_currentItem]);
			}
			return $this;
		}
		
		/**
		* This For Validation posted array
		* @param string $typeofValidation The Method from Form/val class
		* @param string $arg The property to validate against
		* @param boolean $empty: apply empty values
		*/
		public function valid_array($typeofValidation,$arg = NULL,$empty = false)
		{
			if($this->_postData[$this->_currentItem] == NULL)
			{
				return $this;
			}
			$errors = "";
			if(! is_array($this->_postData[$this->_currentItem]) && ! is_array(json_decode($this->_postData[$this->_currentItem])))
			{
				$errors = "The Type Of ".$this->_currentItem." Must Be An Array";
			}else
			{
				if(! is_array($this->_postData[$this->_currentItem]))
				{
					$this->_postData[$this->_currentItem] = json_decode($this->_postData[$this->_currentItem]);
				}
				foreach($this->_postData[$this->_currentItem] as $key => $val)
				{
					if(empty($val) && $empty)
					{
						continue;
					}
					if($arg == NULL)
					{
						$errors .= $this->_val->{$typeofValidation}($val);
					}else
					{
						$errors .= $this->_val->{$typeofValidation}($val,$arg);
					}
				}
			}
			
			if(!empty($error))
			{
				$this->_error[$this->_currentItem] = $error;
			}
			return $this;
		}
		
		/**
		* This For Single Validation
		* @param $var: the variable that need validation
		* @param string $typeofValidation The Method from Form/val class
		* @param string $arg The property to validate against
		*/
		public function single_valid($var,$typeofValidation,$arg = NULL)
		{
			if($var == NULL)
			{
				return false;
			}
			if($arg == NULL)
			{
				$errors = $this->_val->{$typeofValidation}($var);
			}else
			{
				$errors = $this->_val->{$typeofValidation}($var,$arg);
			}
			
			if($errors)
			{
				return $errors;
			}
			return true;
		}
		
		/**
		* fetch
		* This To get Data
		* @param Filed The filed Name
		*/
		public function fetch($filed = false)
		{
			if($filed)
			{
				if(isset($this->_postData[$filed]))
				{
					return $this->_postData[$filed];
				}else
				{
					return false;
				}
			}else
			{
				return $this->_postData;
			}	
		}
		
		/**
		* submit - Handlees the form and Throws an exception upon error
		*/
		public function submit()
		{
			$curr_csrf = (!empty(session::get('csrf')))?session::get('csrf'):TOKEN;
			
			if(empty($this->_error) && isset($_POST['csrf']) && $_POST['csrf'] == $curr_csrf)
			{
				return true;
			}else
			{
				$e = "";
				foreach($this->_error as $k =>$v)
				{
					$e.= "In Field $k : $v \n";
				}
				if(!isset($_POST['csrf']) || $_POST['csrf'] != session::get('csrf'))
				{
					$e.= "No Certificate";
				}
				$this->_postData['MSG'] .= $e;
				//throw new Exception($e);
			}
		}
		
		/**
		* arr_trim - cleanup array data
		*/
		public function arr_trim($arr) 
		{
			$ret = array();
		
			foreach($arr as $k => $v)
			{
				$ret[$k] = $this->test_input($v);
			}
			return $ret;
		}
		
		
	}
?>