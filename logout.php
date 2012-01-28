<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	
	$fwd = isset($_GET['fwd']) ? urldecode($_GET['fwd']) : null;
	
	if( !$fwd )
	{
		$fwd = 'index.php?code=4';
	}
	
	session_destroy();
	setSession(0,"/");
	header('Location: ' . $fwd);
?>