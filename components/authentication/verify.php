<?php
	set_include_path('../../backbone:../../global:../../jquery:../../components:../../content:../../images:../../model:../../render:../../scripts:../../styles');
	require_once('RedirectBrowserException.php');
	require_once('User.php');
	require_once('Session.php');
	require_once('Quick_Login.php');
	setSession(0, '/');
	
	$code = isset($_GET['code']) ? $_GET['code'] : null;
	
	if( $code )
	{
		$ql = Quick_Login::getByHash($code);
		if( $ql )
		{
			$user = User::getByID($ql->userid);
			$user->disabled = 0;
			$user->save();
			
			setSessionVar('active', true);
			setSessionVar('roleid', $user->authentication->role->roleid);
			setSessionVar('userid', $user->userid);
			
			$ql->used = 1;
			$ql->save();
			
			throw new RedirectBrowserException("/home.php?code=0");
		}
		else
		{
			throw new RedirectBrowserException('/index.php?code=9');
		}
	}
	else
	{
		throw new RedirectBrowserException('/index.php?code=9');
	}
?>