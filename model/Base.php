<?php
	require_once('Database.php');
	
	class Base
	{
		public static function connect()
		{
			return new Base();
		}
		
		private $dbname;
		private $user;
		private $pass;
		private $host;
		
		protected $db;
		
		public function __construct()
		{
			$options = file('afterthought.conf', FILE_USE_INCLUDE_PATH);
			foreach( $options as $conf_entry )
			{
				eval($conf_entry);
			}
			
			$this->dbname = $config['db']['dbname'];
			$this->user = $config['db']['user'];
			$this->pass = $config['db']['pass'];
			$this->host = $config['db']['host'];
			
			$this->db = new Database($this->user, $this->pass, $this->dbname, $this->host, 'mysql');
		}
		
		public function __get($var)
		{
			if( strtolower($var) == 'db' )
			{
				return $this->db;
			}
		}
		
		public function sendback($objects)
		{
			if( count( $objects ) > 1 )
			{
				return $objects;
			}
			elseif( count( $objects ) == 1 )
			{
				return $objects[0];
			}
			else
			{
				return false;
			}
		}
		
		public function toArray($objects)
		{
			if( is_array($objects) )
			{
				return $objects;
			}
			elseif( is_object($objects) )
			{
				return array($objects);
			}
			else
			{
				return array();
			}
		}
	}
?>