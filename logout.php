<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	
	session_destroy();
	setSession(0,"/");
	header('Location: index.php');
?>