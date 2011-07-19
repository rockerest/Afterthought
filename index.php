<?php
	set_include_path('backbone:components:content:model:render:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page("Randolph Web Development Project Management");
	$tmpl = new Template();
	
	$page->run();
	
	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;
	$tmpl->email = isset( $_GET['email'] ) ? $_GET['email'] : null;
	
	switch( $tmpl->code )
	{
		case 0:
				// user didn't enter one of the two fields
				$tmpl->type = "error";
				$tmpl->message = "You must enter both an email address and a password.";
				break;
		case 1:
				//user didn't authenticate to a valid account
				$tmpl->type = "error";
				$tmpl->message = "The email + password combination you entered didn't match an active account.";
				break;
		case 2:
				//user isn't logged in at all.
				$tmpl->type = "alert";
				$tmpl->message = "You'll need to log in before you can view that area of the site.";
				break;
		case 3:
				//fringe case: user types in a page they are not authorized to see
				$tmpl->type = "error";
				$tmpl->message = "Your account does have have the proper credentials to view that page.";
		case -1:
		default:
				break;
	}
	
	$html = $tmpl->build('index.html');
	$css = $tmpl->build('index.css');
	//$js = $tmpl->build('index.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'index'
											),
						'js' => $js
						);

	print $page->build($appContent);

?>