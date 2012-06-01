<?php
	$paths = array(
		'.', '../../backbone', '../../components', '../../content', '../../model', '../../render', '../../scripts', '../../styles', '../../images'
	);

	$includePath = implode( PATH_SEPARATOR, $paths );
	set_include_path( get_include_path() . PATH_SEPARATOR . $includePath );

	require_once('RedirectBrowserException.php');
	require_once('User.php');
	require_once('Session.php');
	setSession(0, '/');

	if( !$_SESSION['active'] )
	{
		header('Location: /index.php?code=2');
	}
	elseif( $_SESSION['roleid'] > 2 )
	{
		header('Location: /index.php?code=2');
	}

	$data['pass'] = isset($_POST['pass']) ? $_POST['pass'] : null;
	$data['fname'] = isset($_POST['fname']) ? $_POST['fname'] : null;
	$data['lname'] = isset($_POST['lname']) ? $_POST['lname'] : null;
	$data['email'] = isset($_POST['email']) ? $_POST['email'] : null;
	$data['gender'] = isset($_POST['gender']) ? $_POST['gender'] : null;
	$data['phone'] = isset($_POST['phone']) ? $_POST['phone'] : null;

	$roleid = isset($_POST['rid']) ? $_POST['rid'] : 3;

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

	if( !$data['fname'] || !$data['lname'] || !$data['email'] || !$data['pass'] )
	{
		array_shift($data);
		header('Location: /account.php?a=addnew&code=2&' . http_build_query($data));
	}
	else
	{
		$new = User::add($data['fname'], $data['lname'], $data['email'], $data['pass'], $roleid);
		if( $new )
		{
			header('Location: /' . $return . '.php?code=1');
		}
		else
		{
			header('Location: /account.php?a=addnew&code=3&' . http_build_query($data));
		}
	}
?>
