<?php
	require_once('connect.php');
	
	class Role extends Base
	{
		public static function getAll()
		{
			global $db;
			$sql = "SELECT * FROM roles";
			$res = $db->q($sql);
			
			return Role::wrap($res);
		}
	
		public static function getByID($id)
		{
			global $db;
			$roleSQL = "SELECT * FROM roles WHERE roleid=?";
			$values = array($id);
			$role = $db->qwv($roleSQL, $values);
			
			return Role::wrap($role);
		}
		
		public static function getByName($name)
		{
			global $db;
			$roleSQL = "SELECT * FROM roles WHERE name LIKE '%?%'";
			$values = array($name);
			$role = $db->qwv($roleSQL, $values);
			
			return Role::wrap($role);
		}
		
		public static function wrap($roles)
		{
			$roleList = array();
			foreach( $roles as $role )
			{
				array_push($roleList, new Role($role['roleid'], $role['description'], $role['name']));
			}
			
			return Role::sendback($roleList);
		}
		
		private $roleid;
		private $description;
		private $name;
		
		public function __construct($roleid, $description, $name)
		{
			$this->roleid = $roleid;
			$this->description = $description;
			$this->name = $name;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
