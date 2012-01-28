<?php
	set_include_path('../../backbone:../../global:../../jquery:../../components:../../content:../../images:../../model:../../render:../../scripts:../../styles');
	require_once('User.php');
	require_once('Authentication.php');
	require_once('Session.php');
	setSession(0, '/');
	
	if( !$_SESSION['active'] )
	{
		header('Location: /index.php?code=2');
	}
	
	$self = User::getByID($_SESSION['userid']);
	$uid = isset($_GET['uid']) ? $_GET['uid'] : null;
	$tb = isset($_GET['tb']) ? $_GET['tb'] : null;
	
	//determine return script
	switch( $tb )
	{
		case 'u':
			$return = 'users';
			break;
		default:
			$return = 'home';
			break;
	}
	
	if( $uid )
	{
		$user = User::getByID($uid);
	}
	else
	{
		$user = false;
	}
	
	if( $self == $user || $_SESSION['roleid'] < 3 )
	{
		if( $user->authentication->disabled )
		{
			if( enable($user->userid) )
			{
				header('Location: /' . $return . '.php?code=6');
			}
			else
			{
				header('Location: /' . $return . '.php?code=8');
			}
		}
		else
		{
			if( disable($user->userid) )
			{
				header('Location: /' . $return . '.php?code=5');
			}
			else
			{
				header('Location: /' . $return . '.php?code=7');
			}
		}
	}
	else
	{
		header('Location: /index.php?code=2');
	}
	
	function disable($id)
	{
		return Authentication::disableByUserID($id);
	}
	
	function enable($id)
	{
		return Authentication::enableByUserID($id);
	}
?>
