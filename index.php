<?php
	set_include_path('backbone:components:content:model:render:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page("Afterthought");
	$tmpl = new Template();
	
	$page->run();
	
	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;
	$tmpl->email = isset( $_GET['email'] ) ? $_GET['email'] : null;
		$tmpl->ve= isset( $_GET['vemail'] ) ? $_GET['vemail'] : null;
	$tmpl->fname = isset( $_GET['fname'] ) ? $_GET['fname'] : null;
	$tmpl->lname = isset( $_GET['lname'] ) ? $_GET['lname'] : null;
	
	$tmpl->a = isset( $_GET['a'] ) ? $_GET['a'] : 'login';
	
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
				$tmpl->message = "Your account doesn't have the proper credentials to view that page.";
				break;
		case 4:
				//user logged out
				$tmpl->type = "okay fadeout";
				$tmpl->message = "You have been logged out successfully!";
				break;
		case 5:
				//user didn't fill out all of the fields for a new account
				$tmpl->type="error";
				$tmpl->message = "You must complete all of the fields.";
				break;
		case 6:
				//account was created, email sent
				$tmpl->type="alert";
				$tmpl->message = "An email was sent to your address.<br />Please complete your request by clicking the link provided in the email.";
				break;
		case 7:
				//identity unavailable
				$tmpl->type="error";
				$tmpl->message = 'That email address is unavailable for use.  If this is your email address, <a href="components/authentication/recover.php?email=' . $tmpl->email . '">click here to send a recovery email</a>.';
				break;
		case 8:
				//failed to create user
				$tmpl->type="error";
				$tmpl->message = "An error was encountered while attempting to create the new user.  Please try again.";
				break;
		case 9:
				//invalid quick_login code
				$tmpl->type="error";
				$tmpl->message = 'That verification code was invalid.  Please try again, or <a href="index.php?a=request">click here to create a new account</a>.';
				break;
		case -1:
		default:
				break;
	}
	
	$html = $tmpl->build('index.html');
	$css = $tmpl->build('index.css');
	$js = $tmpl->build('index.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'index'
											),
						'js' => array(	'code' => $js,
										'link' => 'index'
										)
						);

	print $page->build($appContent);

?>