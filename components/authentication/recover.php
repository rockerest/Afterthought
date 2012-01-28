<?php
	set_include_path('../../backbone:../../global:../../jquery:../../components:../../content:../../images:../../model:../../render:../../scripts:../../styles');
	require_once('RedirectBrowserException.php');
	require_once('User.php');
	require_once('Session.php');
	require_once('Quick_Login.php');
	setSession(0, '/');
	
	$email = isset($_GET['email']) ? $_GET['email'] : null;
	
	if( $email )
	{
		$auth = Authentication::getByIdentity($email);
		if( $auth )
		{
			$user = $auth->user;
			if( $user )
			{
				Authentication::forcePasswordChangeByUserID($user->userid);
				
				//create login hash
				$hash = hash('whirlpool', $user->authentication->password . time() . $user->authentication->salt);
				if( !Quick_Login::add($hash, $user->userid, time() + 3600, 0) )
				{
					// die
				}
				
				//send email
				$body = <<<HEREDOCend
<html>
<body>
	<div style="width: 600px; border: 2px solid #E9EBF6; margin: auto; font-size: 16px; color: #555555;">
		<h1 style="margin: 0; padding: 8px; background-color: #E9EBF6; text-align: center;">
			Hello, {$user->fname}!
		</h1>
		<div style="overflow: hidden; padding: 8px; padding-top: 0; background-color: #F5F6FB;">
			<p>You are receiving this email because you (or someone pretending to be you!) requested that your account be recovered.</p>
			<p>It's possible you forgot your password, or you forgot you even had an account.</p>
			<p>That's okay.</p>
			<p>To reset your password and log in, please <a href="http://afterthought.thomasrandolph.info/components/authentication/verify.php?code={$hash}">click this link</a>.</p>
			<p>If you don't know what this is about, or you don't want the account, simply do nothing.</p>
			<p>The quick login link above is a one-time access pass to your account.  Please use it to reset your password and log in this time.</p>
			<br />
			<p>Thanks!</p>
			<p>-Afterthought</p>
		</div>
	</div>
</body>
</html>
HEREDOCend;
				$subject = "Aferthought System Account Recovery";
				$to = $user->contact->email;
				$headers =	'From: no-reply-automator@afterthought.thomasrandolph.info' . "\r\n" .
							'Content-Type: text/html; charset=iso-8859-1' . "\r\n" . 
							'X-Mailer: PHP/' . phpversion() . "\r\n" . 
							'MIME-Version: 1.0' . "\r\n";
				
				if( mail($to, $subject, $body, $headers) )
				{
					//redirect to login			
					throw new RedirectBrowserException("/index.php?code=6");
				}
			}
		}
	}
?>