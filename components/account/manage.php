<?php
	set_include_path('../../backbone:../../global:../../jquery:../../components:../../content:../../images:../../model:../../render:../../scripts:../../styles');
	require_once('RedirectBrowserException.php');
	require_once('Authentication.php');
	require_once('User.php');
	require_once('Session.php');
	setSession(0, '/');
	
	if( !$_SESSION['active'] )
	{
		header('Location: ../../index.php?code=2');
	}
	
	$userid = isset($_GET['uid']) ? $_GET['uid'] : $_SESSION['userid'];
	$self = User::getByID($_SESSION['userid']);
	$attempt = User::getByID($userid);
	
	if( $userid != $_SESSION['userid'] && $_SESSION['roleid'] == 3 )
	{
		header('Location: ../../index.php?code=2');
	}
	
	if( $userid != $_SESSION['userid'] )
	{
		$addon = "&uid=" . $userid;
	}
	
	$cont = $attempt->contact;
	$auth = $attempt->authentication;
	
	$data['fname'] = isset($_POST['fname']) ? $_POST['fname'] : null;
	$data['lname'] = isset($_POST['lname']) ? $_POST['lname'] : null;
	$data['gender'] = isset($_POST['gender']) ? $_POST['gender'] : null;
	$data['email'] = isset($_POST['email']) ? $_POST['email'] : null;
	$data['phone'] = isset($_POST['phone']) ? $_POST['phone'] : null;
	
	$pass = isset($_POST['pass']) ? $_POST['pass'] : null;
	$passver = isset($_POST['passver']) ? $_POST['passver'] : null;
	$lemail = isset($_POST['lemail']) ? $_POST['lemail'] : null;
	$contact = isset($_POST['contact']) ? true : false;
	
	if( $data['fname'] )
	{	
		$attempt->fname = $data['fname'];
	}
	
	if( $data['lname'] )
	{	
		$attempt->lname = $data['lname'];
	}
	
	if( $data['gender'] )
	{	
		$attempt->gender = $data['gender'];
	}
	
	if( $data['email'] )
	{	
		$cont->email = $data['email'];
	}
	
	if( $data['phone'] )
	{	
		$cont->phone = preg_replace("/\D/","",$data['phone']);
	}
	
	if( $lemail )
	{
		$auth->identity = $lemail;
		if( $contact )
		{
			$cont->email = $lemail;
		}
	}
	
	if( $pass && ($pass == $passver) )
	{
		$auth->password = $pass;
		$auth->resetPassword = 0;
	}
	elseif( $pass )
	{
		header('Location: ../../account.php?a=login&code=4' . $addon);
		exit();
	}
	
	if( $attempt->save() && $cont->save() && $auth->save() )
	{
		header('Location: ../../account.php?code=0' . $addon);
	}
	else
	{
		//an error occurred during saving.  Probably bad input that wasn't caught above
		header('Location: ../../account.php?code=1' . $addon . "&" . http_build_query($data));
	}
?>