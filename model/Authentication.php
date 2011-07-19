<?php
	require_once('connect.php');
	
	require_once('Role.php');
	require_once('User.php');
	
	class Authentication extends Base
	{
		public static function validate($identity, $password)
		{
			global $db;
			
			$sql = "SELECT salt FROM authentication WHERE identity=?";
			$values = array($identity);
			$res = $db->qwv($sql, $values);
			
			$saltPass = hash('whirlpool', $res[0]['salt'].$password);
			
			$sql = "SELECT * FROM authentication WHERE identity=? AND password=?";
			$values = array($identity, $saltPass);
			$res = $db->qwv($sql, $values);
			
			if( count($res) != 1 )
			{
				return false;
			}
			else
			{
				$user = User::getByID($res[0]['userid']);
				
				if( !$user->Authentication->disabled )
				{
					return $user;
				}
				else
				{
					return false;
				}
			}
		}
		
		public static function checkIdentity($ident)
		{
			global $db;
			$sql = "SELECT authenticationid FROM authentication WHERE identity=?";
			$values = array($ident);
			$res = $db->qwv($sql, $values);
			
			return count($res);
		}
		
		public static function addForUser($id, $ident, $pass, $roleid)
		{
			$vals = Authentication::hash($pass);

			$auth = new Authentication(null, $ident, $vals[0], $vals[1], $id, $roleid, 1, 0);
			$save = $auth->save();
			if( $save )
			{
				return $auth;
			}
			else
			{
				return false;
			}
		}
		
		public static function getByIdentity($ident)
		{
			global $db;
			$sql = "SELECT * FROM authentication WHERE identity=?";
			$values = array($ident);
			$res = $db->qwv($sql, $values);
			
			return Authentication::wrap($res);
		}
		
		public static function getByUserID($id)
		{
			global $db;
			$authSQL = "SELECT * FROM authentication WHERE userid=?";
			$values = array($id);
			$auth = $db->qwv($authSQL, $values);
			
			return Authentication::wrap($auth);
		}
		
		public static function deleteByUserID($id)
		{
			global $db;
			$delSQL = "DELETE FROM authentication WHERE userid=?";
			$values = array($id);
			$del = $db->qwv($delSQL, $values);
			
			return $db->stat();
		}
	
		public static function wrap($auths)
		{
			$authList = array();
			foreach( $auths as $auth )
			{
				array_push($authList, new Authentication($auth['authenticationid'], $auth['identity'], $auth['salt'], $auth['password'], $auth['userid'], $auth['roleid'], $auth['resetPassword'], $auth['disabled']));
			}
			
			return Authentication::sendback($authList);
		}
	
		private $authenticationid;
		private $identity;
		private $password;
		private $salt;
		private $resetPassword;
		private $disabled;
		
		private $roleid;
		private $userid;
	
		public function __construct($id, $ident, $salt, $pass, $userid, $roleid, $reset, $disabled)
		{
			$this->roleid = $roleid;
			$this->userid = $userid;
			
			$this->authenticationid = $id;
			$this->identity = $ident;
			$this->salt = $salt;
			$this->password = $pass;
			$this->resetPassword = $reset;
			$this->disabled = $disabled;
		}
		
		public function __get($var)
		{
			if( strtolower($var) == 'role' )
			{
				return Role::getByID($this->roleid);
			}
			elseif( strtolower($var) == 'user' )
			{
				return User::getByID($this->userid);
			}
			else
			{
				return $this->$var;
			}
		}
		
		public function __set($name, $value)
		{
			if( $name == 'salt' )
			{
				return false;
			}
			elseif( $name == 'password' )
			{
				$vals = Authentication::hash($value);
				$this->salt = $vals[0];
				$this->password = $vals[1];
			}
			else
			{
				$this->$name = $value;
			}
			return $this->save();
		}
		
		public function save()
		{
			global $db;
			if( isset($this->authenticationid) )
			{
				if( $this->allSet() )
				{
					$authSQL = "UPDATE authentication SET identity=?, salt=?, password=?, roleid=?, resetPassword=?, disabled=? WHERE authenticationid=? AND userid=?";
					$values = array($this->identity, $this->salt, $this->password, $this->roleid, $this->authenticationid, $this->userid, $this->resetPassword, $this->disabled);
					$db->qwv($authSQL, $values);
					
					if( $db->stat() )
					{
						return $this;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				if( $this->allSet() )
				{
					$authSQL = "INSERT INTO authentication (identity, salt, password, userid, roleid, resetPassword, disabled) VALUES (?,?,?,?,?,?,?)";
					$values = array($this->identity, $this->salt, $this->password, $this->userid, $this->roleid, $this->resetPassword, $this->disabled);
					$db->qwv($authSQL, $values);
					
					if( $db->stat() )
					{
						$this->authenticationid = $db->last();
						return $this;
					}
					else
					{
						return false;
					}					
				}
				else
				{
					return false;
				}
			}
		}
		
		private function allSet()
		{
			return isset($this->identity, $this->salt, $this->password, $this->roleid);
		}
		
		private function hash($pass)
		{
			$salt = substr(hash('whirlpool',rand(100000000000, 999999999999)), 0, 64);
			$real_pass = hash('whirlpool', $salt.$pass);
			
			return array($salt, $real_pass);
		}
	}
?>
