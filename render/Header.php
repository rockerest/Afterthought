<?php
	require_once('Template.php');
	require_once('User.php');
	
	class Header
	{
		public function __construct()
		{
		}
		
		public function run()
		{
		}
		
		public function generate()
		{
			$tmpl = new Template();
			
			$tmpl->active = $active = isset($_SESSION['active']);
			$tmpl->resetPassword = $rp = 1;
			
			if( $active )
			{
				$user = User::getByID($_SESSION['userid']);
				
				switch( strtolower($user->gender) )
				{
					case 'm':
						$tmpl->icon = 'user';
						break;
					case 'f':
						$tmpl->icon = 'user-female';
						break;
					default:
						$tmpl->icon = 'user-silhouette';
						break;
				}
				
				$tmpl->account = $_SESSION['userid'];
				$tmpl->roleid = $_SESSION['roleid'];
				$tmpl->name = $user->fullname;
				$tmpl->resetPassword = $rp = $user->authentication->resetPassword;
			}
			
			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			$js = $tmpl->build('header.js');
			
			/*
			 * force SSL
			 *
			 * if($_SERVER["HTTPS"] != "on")
			 * {
			 * 	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			 * 	exit();
			 * }
			 *
			 * /force SSL
			 */
			
			$uri = $_SERVER['REQUEST_URI'];
			$script = $_SERVER['SCRIPT_NAME'];
			
			if( $rp && $active )
			{
				if( $uri != '/graphic/account.php?a=login&code=5' )
				{
					header('Location: account.php?a=login&code=5');
				}
			}
			else
			{
				if( $script != '/graphic/errors.php' )
				{
					if( $script != '/graphic/index.php' && !$active )
					{
						header('Location: index.php?code=2');
					}
					elseif( $script == '/graphic/index.php' && $active )
					{
						header('Location: home.php');
					}
					elseif( $script == '/graphic/index.php' && !$active )
					{
						//allow to go to login or error handler page
					}
				}
			}
			
			$content = array(
								'html' => $html,
								'css' => array(	'code' => $css,
												'link' => 'header'),
								'js' => array(	'code' => $js,
												'link' => 'header')
							);
			return $content;
		}
	}

?>