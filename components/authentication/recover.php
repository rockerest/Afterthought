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
	require_once('Mail.php');
	setSession(0, '/');

	$email = isset($_GET['email']) ? $_GET['email'] : null;

	if( $email ){
		$auth = Authentication::getByIdentity($email);
		if( $auth ){
			$user = $auth->user;
			if( $user ){
				Authentication::forcePasswordChangeByUserID($user->userid);

				//create login hash
				$hash = hash('whirlpool', $user->authentication->password . time() . $user->authentication->salt);
				if( !Quick_Login::add($hash, $user->userid, time() + 3600, 0) ){
					// die
				}

				//load email template
				ob_start();
				include('templates/account_recover.html');
				$body = ob_get_clean();

				if( Mail::sendMail($user->contact->email, 'no-reply-automator@afterthought.thomasrandolph.info', "Afterthought System Account Recovery", $body) ){
					//redirect to login
					throw new RedirectBrowserException("/index.php?code=6");
				}
			}
		}
	}
?>
