<?php
	$paths = array(
		'.', '../../backbone', '../../components', '../../content', '../../model', '../../render', '../../scripts', '../../styles', '../../images'
	);

	$includePath = implode( PATH_SEPARATOR, $paths );
	set_include_path( get_include_path() . PATH_SEPARATOR . $includePath );

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
