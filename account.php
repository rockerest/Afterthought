<?php
	set_include_path('backbone:components:content:model:render:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('User.php');
	require_once('Role.php');
	
	secure(3); //allow roles <=3
	
	$page = new Page("Account Management :: Afterthought");
	$tmpl = new Template();
	
	$page->run();
	$tmpl->self = $page->self;
	
	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;
	$userid = isset( $_GET['uid'] ) ? $_GET['uid'] : $_SESSION['userid'];
	$tmpl->action = isset( $_GET['a'] ) ? $_GET['a'] : 'manage';
	
	if( $userid == $_SESSION['userid'] )
	{
		$user = User::getByID($userid);
	}
	else
	{
		$attempt = User::getByID($userid);
		
		if( $tmpl->self->authentication->roleid == 1 )
		{
			$user = $attempt;
		}
		else
		{
			$user = $tmpl->self;
		}
	}
	
	//form fields
	$data['fname'] = isset($_GET['fname']) ? ($_GET['fname'] != null ? $_GET['fname'] : ($tmpl->action == 'manage' ? $user->fname : null)) : ($tmpl->action == 'manage' ? $user->fname : null);
	$data['lname'] = isset($_GET['lname']) ? ($_GET['lname'] != null ? $_GET['lname'] : ($tmpl->action == 'manage' ? $user->lname : null)) : ($tmpl->action == 'manage' ? $user->lname : null);
	$data['gender'] = isset($_GET['gender']) ? ($_GET['gender'] != null ? $_GET['gender'] : ($tmpl->action == 'manage' ? $user->gender : null)) : ($tmpl->action == 'manage' ? $user->gender : null);
	$data['email'] = isset($_GET['email']) ? ($_GET['email'] != null ? $_GET['email'] : ($tmpl->action == 'manage' ? $user->contact->email : ($tmpl->action == 'login' ? $user->authentication->identity : null))) : ($tmpl->action == 'manage' ? $user->contact->email : ($tmpl->action == 'login' ? $user->authentication->identity : null));
	$data['phone'] = isset($_GET['phone']) ? ($_GET['phone'] != null ? $_GET['phone'] : ($tmpl->action == 'manage' ? $user->contact->friendlyPhone : null)) : ($tmpl->action == 'manage' ? $user->contact->friendlyPhone : null);
	//end form fields
	
	//throwback
	$tmpl->tb = isset($_GET['tb']) ? $_GET['tb'] : null;
	
	switch( $tmpl->code )
	{
		case 0:
				// Account updated successfully
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The account was updated successfully.";
				break;
		case 1:
				// An unknown error occurred (account update)
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "An unknown error occurred while attempting to save the changes.";
				break;
		case 2:
				// The required fields for adding a new user weren't completed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "You must complete all of the fields in the \"Required Information\" set.";
				break;
		case 3:
				// An unknown error occurred (new account)
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "An unknown error occurred while attempting to create the account.";
				break;
		case 4:
				// The passwords (changing password) didn't match
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The \"New Password\" and \"Verify New Password\" fields must match.";
				break;
		case 5:
				// User is required to change their password
				$tmpl->alert['type'] = "alert";
				$tmpl->alert['message'] = "You must change your password before continuing.";
				break;
		case -1:
		default:
				break;
	}
	
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
	
	$tmpl->user = $user;
	$tmpl->data = $data;
	$tmpl->roles = Role::toArray(Role::getAll());
	
	$html = $tmpl->build('account.html');
	$css = $tmpl->build('account.css');
	$js = $tmpl->build('account.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'account'
											),
						'js' => array(	'code' => $js,
										'link' => 'account'
										)
						);

	print $page->build($appContent);
?>