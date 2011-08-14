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
			
			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			$js = $tmpl->build('header.js');
			
			$active = isset($_SESSION['active']);
			$script = $_SERVER['SCRIPT_NAME'];
			
			if( $script != '/errors.php' )
			{
				if( $script != '/index.php' && !$active )
				{
					header('Location: index.php?code=2');
				}
				elseif( $script == '/index.php' && $active )
				{
					header('Location: accepted.php');
				}
				elseif( $script == '/index.php' && !$active )
				{
					//allow to go to login or error handler page
				}
			}
			
			$content = array(
								'html' => $html,
								'css' => array(	'code' => $css,
												'link' => 'header'),
								'js' => $js
							);
			return $content;
		}
	}

?>