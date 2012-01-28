<?php
	set_include_path('../../backbone:../../global:../../jquery:../../components:../../content:../../images:../../model:../../render:../../scripts:../../styles');
	require_once('RedirectBrowserException.php');
	require_once('Authentication.php');
	require_once('User.php');
	require_once('Session.php');
	setSession(0, '/');
	
	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;
	
	if( $password != null && $identity != null )
	{
		$tmp = Authentication::validate($identity, $password);
		
		if( $tmp )
		{
			setSessionVar('active', true);
			setSessionVar('roleid', $tmp->authentication->role->roleid);
			setSessionVar('userid', $tmp->userid);
			
			throw new RedirectBrowserException("/home.php?code=0");
		}
		else
		{
			throw new RedirectBrowserException("/index.php?code=1&email=" . $identity);
		}
	}
	else
	{
		throw new RedirectBrowserException("/index.php?code=0&email=" . $identity);
	}
?>