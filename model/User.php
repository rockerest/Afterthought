<?php
	require_once('Base.php');
	
	require_once('Authentication.php');
	require_once('Contact.php');

	class User extends Base{
		public static function getByID($id){
			$base = new Base();
			
			$sql = "SELECT * FROM users WHERE userid=?";
			$values = array($id);
			$user = $base->db->qwv($sql, $values);
			
			return User::wrap($user);
		}
		
		public static function getAll(){
			$base = new Base();
			
			$sql = "SELECT * FROM users";
			return User::wrap($base->db->q($sql));
			
		}
		
		public static function getByAuthenticationIdentity($ident){
			$base = new Base();
			
			$auth = Authentication::getByIdentity($ident);
			
			if( is_object($auth) ){
				return User::getByID($auth->userid);
			}
			else{
				return false;
			}
		}
		
		public static function getAllWithRestrictions(){
			$base = new Base();
			
			$sql = "SELECT * FROM users WHERE userid IN
					(
						SELECT userid FROM authentication
						WHERE resetPassword=1
						OR disabled=1						
					)";
			return User::wrap($base->db->q($sql));
			
		}
		
		public static function search($term = null){
			$base = new Base();
			
			if( !$term ){
				$sql = "SELECT * FROM users ORDER BY lname ASC, fname ASC";
				$res = $base->db->q($sql);
			}
			else{
				$sql = "SELECT * FROM users WHERE lname LIKE ? OR fname LIKE ? OR CONCAT(fname, ' ', lname) LIKE ? ORDER BY lname ASC, fname ASC";
				$values = array('%' . $term . '%', '%' . $term . '%', '%' . $term . '%');
				$res = $base->db->qwv($sql, $values);
			}
			
			return User::wrap($res);
		}
		
		public static function add($fname, $lname, $identity, $pass, $roleid){
			$okay = Authentication::checkIdentity($identity);
			
			if( $okay === 0 ){
				$user = new User(null, $fname, $lname, null);
				$res = $user->save();

				if( $res ){
					$auth = Authentication::addForUser($res->userid, $identity, $pass, $roleid);
					$cont = Contact::addForUserID($res->userid, $identity);
					
					if( $auth && $cont ){
						return $res;
					}
					else{
						if( User::deleteByID($res->userid) ){
							return false;
						}
						else{
							//you are just totally screwed
						}
					}
				}
			}
			else{
				return false;
			}
		}
		
		public static function deleteByID($userid){
			$base = new Base();
			
			//save everything in case we need to put them back in.
			$contact = Contact::toArray(Contact::getByUserID($userid));
			$auth = Authentication::toArray(Authentication::getByUserID($userid));
			
			//get the user object
			$user = User::getByID($userid);
			
			//Delete user
			$sql = "DELETE FROM users WHERE userid=?";
			$values = array($userid);
			$base->db->qwv($sql, $values);
			
			if( $base->db->stat() ){
				return $base->db->stat();
			}
			else{
				foreach( $contact as $con ){
					$con->contactid = null;
					$con->save();
				}
				
				$auth->authenticationid = null;
				$auth->save();
				
				return false;
			}
		}
		
		public static function wrap($users){
			$userList = array();
			foreach( $users as $user ){
				array_push($userList, new User($user['userid'], $user['fname'], $user['lname'], $user['gender']));
			}
			
			return User::sendback($userList);
		}

		private $userid;
		private $fname;
		private $lname;
		private $gender;
		
		public function __construct($userid, $fname, $lname, $gender){
			//initialize the database connection variables
			parent::__construct();
			
			$this->userid = $userid;
			$this->fname = $fname;
			$this->lname = $lname;
			$this->gender = $gender;
		}
		
		public function __get($var){
			if( strtolower($var) == 'authentication' ){
				return Authentication::getByUserID($this->userid);
			}
			elseif( strtolower($var) == 'contact' ){
				return Contact::getByUserID($this->userid);
			}
			elseif( strtolower($var) == 'fullname' ){
				return $this->fname . " " . $this->lname;
			}
			else{
				return $this->$var;
			}
		}
		
		public function __set($var, $val){
			$this->$var = $val;
		}
		
		public function save(){
			if( !isset($this->userid) ){
				$sql = "INSERT INTO users (fname, lname, gender) VALUES(?,?,?)";
				$values = array($this->fname, $this->lname, $this->gender);
				$this->db->qwv($sql, $values);
				
				if( $this->db->stat() ){
					$this->userid = $this->db->last();
					return $this;
				}
				else{
					return false;
				}
			}
			else{
				$userSQL = "UPDATE users SET fname=?, lname=?, gender=? WHERE userid=?";
				$values = array ($this->fname, $this->lname, $this->gender, $this->userid);
				$this->db->qwv($userSQL, $values);

				return $this->db->stat();
			}
			
			
		}
	}
?>
