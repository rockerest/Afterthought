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
	require_once('Quick_Login.php');
	require_once('Mail.php');
	setSession(0, '/');

	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$vp = isset($_POST['vpass']) ? $_POST['vpass'] : null;
	$data['email'] = isset($_POST['email']) ? $_POST['email'] : null;
	$data['vemail'] = isset($_POST['vemail']) ? $_POST['vemail'] : null;
	$data['fname'] = isset($_POST['fname']) ? $_POST['fname'] : null;
	$data['lname'] = isset($_POST['lname']) ? $_POST['lname'] : null;

	if( ($password == $vp) && ($data['email'] == $data['vemail']) && ($password != null) && ($data['email'] != null) )
	{
		if( Authentication::checkIdentity($data['email']) == 0 )
		{
			//create user
			$user = User::add($data['fname'],$data['lname'],$data['email'],$password,3);
			if( $user )
			{
				$auth = $user->authentication;
				$auth->resetPassword = 0;
				$auth->disabled = 1;
				$auth->save();

				//create login hash
				$hash = hash('whirlpool', $user->authentication->identity . time() . (time() / 64));
				if( !Quick_Login::add($hash, $user->userid, time() + 3600, 0) )
				{
					// die
				}

				//load email template
				ob_start();
				include('templates/account_create.html');
				$body = ob_get_clean();

				if( Mail::sendMail($user->contact->email, 'no-reply-automator@afterthought.thomasrandolph.info', "Afterthought System Database Email Verification", $body) )
				{
					//redirect to login
					throw new RedirectBrowserException("/index.php?code=6");
				}
			}
			else
			{
				throw new RedirectBrowserException("/index.php?a=request&code=8&" . http_build_query($data));
			}
		}
		else
		{
			throw new RedirectBrowserException("/index.php?a=request&code=7&" . http_build_query($data));
		}
	}
	else
	{
		throw new RedirectBrowserException("/index.php?a=request&code=5&" . http_build_query($data));
	}
?>
