<?php
	class database extends PDO
	{
		/**The Default Method Like Main in java*/
		function __construct()
		{
			/**
			* This For Connecting To Database Using PDO
			* PDO Can Be Used For Many Database
			* Now We Use MSQL
			* Its Class Implemented In apach
			*/
			parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_charset,DB_USER,DB_PASS);
			
			//this for display error
			$this->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
			
			$this->get_config(); 
		}
		
		/**
		* Get Config:
		* this to get configration from DB
		*/
		private function get_config()
		{
			//Get visits
			$x = $this->select("SELECT * FROM ".DB_PREFEX."config WHERE conf_user = 0",array());
			foreach($x as $val)
			{
				if(session::get($val['conf_name'])!= $val['conf_val'])
				{
					session::set($val['conf_name'],$val['conf_val']);
				}
			}
			
			/**check visit
			cookies: visit : last visit - end in 1 houre
			cookies: main_visit: from first visit will end in 1 year 
			*/
			if(!cookies::get('main_visit'))
			{
				//New Visit from new PC
				$new_visit = session::get('NEW_VISIT') + 1;
				session::set('NEW_VISIT',$new_visit);
				$this->update('config',array('conf_val'=>$new_visit),'conf_name = "NEW_VISIT"');
				
				$visit = session::get('VISIT') + 1;
				session::set('VISIT',$visit);
				$this->update(DB_PREFEX.'config',array('conf_val'=>$visit),'conf_name = "VISIT"');
				
				cookies::set('main_visit',session::get_id(),time()+60*60*24*365);
				cookies::set('visit',session::get_id(),time()+60*60);
			}elseif(!cookies::get('visit'))
			{
				//new visit from old PC
				cookies::set('visit',session::get_id(),time()+60*60);
				$visit = session::get('VISIT') + 1;
				session::set('VISIT',$visit);
				$this->update(DB_PREFEX.'config',array('conf_val'=>$visit),'conf_name = "VISIT"');
			}
			
		}
		
		/**
		* Insert Action
		* save database insert,update, and delete actions after 
		* @param string $table The name of table To Insaet data into it
		* @param string $dara The Associative array
		* @param bool $fun The result of executed action
		*/
		private function db_action($table,$data,$type,$fun)
		{
			if(!$fun)
			{
				/*
				0 	SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
				1 	Driver-specific error code.
				2 	Driver-specific error message
				*/
				return $this->errorInfo();
			}
			
			$user = session::get('user_id');
			
			$ins_data = json_encode($data);
			
			$sth = $this->prepare("INSERT INTO ".DB_PREFEX."staff_event 
								(eve_user,eve_table,eve_action,eve_data, eve_time) 
								VALUES 
								($user,'$table','$type','$ins_data',now())");
			
			$as = $sth->execute();
		}
		
		/**
		* Insert
		* @param string $table The name of table To Insaet data into it
		* @param string $data The Associative array
		*/
		public function insert($table,$data)
		{
			ksort($data);
			$filed_name = implode(',',array_keys($data));
			$filed_values = ':'.implode(',:',array_keys($data));
			$sth = $this->prepare("INSERT INTO $table ($filed_name) VALUES ($filed_values)");
			foreach($data as $key => $val)
			{
				$sth->bindValue("$key",$val);
			}
			$as = $sth->execute();
			return $this->db_action($table,$data,'insert',$as);
		}
		
		/**
		* select
		* @param string $sql The Sql Statment For select
		* @param string $array The Associative array
		* @param string $fetchMode PDO::FETCHMODE
		*/
		public function select($sql,$data,$fetchMode = PDO::FETCH_ASSOC)
		{
			$sth = $this->prepare($sql);
			if(count($data)>0)
			{
				foreach($data as $key => $val)
				{
					$sth->bindValue("$key",$val);
				}
			}
			$sth->execute();
			return $sth->fetchAll($fetchMode);
		}
		/**
		* update
		* @param string $table The Table Name For updating
		* @param string $data The Associative array of updates data
		* @param string $where The where sql data
		*/
		public function update($table,$data,$where)
		{
			ksort($data);
			$field = NULL;
			foreach($data as $key =>$val)
			{
				$field .= "$key = :$key,";
			}
			$field = rtrim($field,',');
			
			$sth = $this->prepare("UPDATE $table SET $field WHERE $where");
			foreach($data as $key => $val)
			{
				echo $key." -- ".$val."\n";
				$x = $sth->bindValue(":$key",$val);
			}
			$as = $sth->execute();
			$data['WHERE'] = $where;
			return $this->db_action($table,$data,'update',$as);
		}
		
		/**
		* Insert OR update on duplicate
		* @param string $table The name of table To Insert data into it
		* @param string $data The Associative array
		*/
		public function insert_updata($table,$data)
		{
			ksort($data);
			$filed_name = implode(',',array_keys($data));
			$filed_values = ':'.implode(',:',array_keys($data));
			///
			$field = NULL;
			foreach($data as $key =>$val)
			{
				$field .= "$key = :$key,";
			}
			$field = rtrim($field,',');
			///
			
			$sth = $this->prepare("INSERT INTO $table ($filed_name) VALUES ($filed_values) ON DUPLICATE KEY UPDATE SET $field");
			foreach($data as $key => $val)
			{
				$sth->bindValue("$key",$val);
			}
			$as = $sth->execute();
			$data['WHERE'] = $where;
			return $this->db_action($table,$data,'insert_update',$as);
			
		}
		
		/**
		* delete
		* @param string $table The Table Name For deleting
		* @param string $where The where sql data
		*/
		public function delete($table,$where)
		{
			$st = $this->exec("DELETE FROM $table WHERE $where");
			$data = array('WHERE'=>$where);
			return $this->db_action($table,$data,'delete',$as);
			
		}
		/**
		* Last Inserted ID
		*/
		public function LastInsertedId()
		{
			return $this->lastInsertId();
		}
		
		/**
		* auto increment no update
		*/
		public function auto_increment($table)
		{
			$sth = $this->prepare('ALTER TABLE '.$table.' auto_increment = 1');
		}
	}
?>