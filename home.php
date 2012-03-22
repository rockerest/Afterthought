<?php
	set_include_path('backbone:components:content:model:render:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	
	require_once('User.php');
	$page = new Page("Home :: Afterthought", 'home');
	$tmpl = new Template();
	
	secure(4); //let anyone in who is logged in
	
	$page->run();
	$tmpl->self = $page->self;
	
	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;

	switch( $tmpl->code )
	{
		case 0:
				// user logged in successfully
				$tmpl->alert['type'] = "okay fadeout"; //style the messagebox AND target it to fade out.
				$tmpl->alert['message'] = 'Welcome, ' . $tmpl->self->fname;
				break;
		default:
				break;
	}

	$html = $tmpl->build('home.html');
	$css = $tmpl->build('home.css');
	$js = $tmpl->build('home.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'home'
											),
						'js' => array(	'code' => $js,
										'link' => 'home'
										)
						);

	print $page->build($appContent);
?>