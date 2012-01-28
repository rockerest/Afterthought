<?php
	set_include_path('../../backbone:../../global:../../jquery:../../components:../../content:../../images:../../model:../../render:../../scripts:../../styles');
	require_once('RedirectBrowserException.php');
	require_once('Authentication.php');
	require_once('User.php');
	require_once('Session.php');
	require_once('Quick_Login.php');
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
				$user->resetPassword = 0;
				$user->disabled = 1;
				$user->save();
				
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
			<p>You are receiving this email because you (or someone pretending to be you!) has signed up for a new account on the Afterthought System.</p>
			<p>If you would like to verify this email account (and you must in order to use the system), please <a href="http://afterthought.thomasrandolph.info/components/authentication/verify.php?code={$hash}">click this link</a>.</p>
			<p>If you don't know what this is about, or you don't want the account, simply do nothing.</p>
			<p>The quick login link above is a one-time access pass to your account.  Please use the link to verify your email address and complete your account signup.</p>
			<br />
			<p>Thanks!</p>
			<p>-Afterthought</p>
		</div>
	</div>
</body>
</html>
HEREDOCend;
				$subject = "Afterthought System Email Verification";
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