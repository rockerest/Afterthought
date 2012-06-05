<?php
	require_once('Template.php');

	class Footer{
		public function __construct(){
		}
		
		public function run(){
		}
		
		public function generate(){
			$tmpl = new Template();
			
			$css = $tmpl->build('footer.css');
			$html = $tmpl->build('footer.html');
			$js = $tmpl->build('footer.js');
			
			$content = array(	'html' => $html,
								'css' => array(	'code' => $css,
												'link' => 'footer'
											),
								'js' => array(	'code' => $js,
												'link' => 'footer'
											)
							);
			return $content;
		}
	}

?>