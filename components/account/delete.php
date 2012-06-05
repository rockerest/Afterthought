<?php
	$paths = array(
		'.', '../../backbone', '../../components', '../../content', '../../model', '../../render', '../../scripts', '../../styles', '../../images'
	);

	$includePath = implode( PATH_SEPARATOR, $paths );
	set_include_path( get_include_path() . PATH_SEPARATOR . $includePath );

	require_once('RedirectBrowserException.php');
	require_once('Authentication.php');
	require_once('User.php');
	require_once('Session.php');
	setSession(0, '/');

	if( !$_SESSION['active'] ){
		header('Location: /index.php?code=2');
	}
	elseif( $_SESSION['roleid'] > 2 ){
		header('Location: /index.php?code=2');
	}

	$self = User::getByID($_SESSION['userid']);
	$uid = isset($_GET['uid']) ? $_GET['uid'] : null;
	$tb = isset($_GET['tb']) ? $_GET['tb'] : null;

	//determine return script
	switch( $tb ){
		case 'u':
			$return = 'users';
			break;
		default:
			$return = 'home';
			break;
	}

	if( $uid ){
		$user = User::getByID($uid);
	}
	else{
		$user = false;
	}

	if( $_SESSION['roleid'] == 1 ){
		if( User::deleteByID($user->userid) ){
			header('Location: /' . $return . '.php?code=3');
		}
		else{
			header('Location: /' . $return . '.php?code=4&ec=CASCADE_DELETE_FAIL--' . $user->userid);
		}
	}
	else{
		header('Location: /index.php?code=2');
	}
?>
