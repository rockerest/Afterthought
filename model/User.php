<?php
	require_once('connect.php');
	
	require_once('Authentication.php');

	class User extends Base
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM users WHERE userid=?";
			$values = array($id);
			$user = $db->qwv($sql, $values);
			
			return User::wrap($user);
		}
		
		public static function getByAuthenticationIdentity($ident)
		{
			global $db;
			$auth = Authentication::getByIdentity($ident);
			
			if( is_object($auth) )
			{
				return User::getByID($auth->userid);
			}
			else
			{
				return false;
			}
		}
		
		public static function add($fname, $lname, $email, $pass, $roleid)
		{
			$okay = Authentication::checkIdentity($email);
			
			if( $okay === 0 )
			{
				$user = new User(null, $fname, $lname, $email, null);
				$res = $user->save();
				
				if( $res )
				{
					$auth = Authentication::addForUser($res->userid, $email, $pass, $roleid);
					if( $auth )
					{
						return $res;
					}
					else
					{
						$status = User::delete($res->userid);
						
						if( $status )
						{
							return false;
						}
						else
						{
							//you are just totally screwed
						}
					}
				}
			}
			else
			{
				return false;
			}
		}
		
		public static function delete($userid)
		{
			global $db;
			$sql = "DELETE FROM users WHERE userid=?";
			$values = array($userid);
			$db->qwv($sql, $values);
			
			if( $db->stat() )
			{
				return Authentication::deleteByUserID($userid);
			}
			
			return $db->stat();
		}
		
		public static function wrap($users)
		{
			$userList = array();
			foreach( $users as $user )
			{
				array_push($userList, new User($user['userid'], $user['fname'], $user['lname'], $user['email'], $user['nickname']));
			}
			
			return User::sendback($userList);
		}

		
		private $userid;
		private $fname;
		private $lname;
		private $email;
		private $nickname;
		
		public function __construct($userid, $fname, $lname, $email, $nick)
		{
			$this->userid = $userid;
			$this->fname = $fname;
			$this->lname = $lname;
			$this->email = $email;
			$this->nickname = $nick;
		}
		
		public function __get($var)
		{
			if( strtolower($var) == 'authentication' )
			{
				return Authentication::getByUserID($this->userid);
			}
			else
			{
				return $this->$var;
			}
		}
		
		public function save()
		{
			global $db;
			if( !isset($this->userid) )
			{
				$sql = "INSERT INTO users (fname, lname, email, nickname) VALUES(?,?,?,?)";
				$values = array($this->fname, $this->lname, $this->email, $this->nickame);
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					$this->userid = $db->last();
					return $this;
				}
				else
				{
					return false;
				}
			}
			else
			{
				$userSQL = "UPDATE users SET fname=?, lname=?, email=?, nickname=? WHERE userid=?";
				$values = array ($this->fname, $this->lname, $this->email, $this->nickname, $this->userid);
				$db->qwv($userSQL, $values);
				
				return $db->stat();
			}
		}
	}
?>
