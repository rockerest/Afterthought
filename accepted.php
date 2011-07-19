<?php
	set_include_path('backbone:components:content:model:render:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page("Afterthought Website Accelerator Logged In");
	$tmpl = new Template();
	$user = User::GetByID($_SESSION['userid']);
	
	$page->run();
	
	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;
	
	switch( $tmpl->code )
	{
		case 0:
				// user logged in successfully
				$tmpl->type = "okay";
				$tmpl->message = "You have logged in successfully, {$user->fname}!";
				break;
		case -1:
		default:
				break;
	}
	
	$html = $tmpl->build('accepted.html');
	$css = $tmpl->build('accepted.css');
	$js = $tmpl->build('accepted.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'accepted'
											),
						'js' => $js
						);

	print $page->build($appContent);

?>