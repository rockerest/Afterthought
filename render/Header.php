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
			
			if( ($_SERVER['SCRIPT_NAME'] != '/index.php' && !isset($_SESSION['active'])) )
			{
				header('Location: index.php?code=2');
			}
			elseif( ($_SERVER['SCRIPT_NAME'] == '/index.php' && isset($_SESSION['active'])) )
			{
				header('Location: accepted.php');
			}
			elseif( ($_SERVER['SCRIPT_NAME'] == '/index.php' && !isset($_SESSION['active'])) )
			{
				//allow to go to login page
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