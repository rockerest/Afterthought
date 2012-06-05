<?php
	$paths = array(
		'.', 'backbone', 'components', 'content', 'model', 'render', 'scripts', 'styles', 'images'
	);

	$includePath = implode( PATH_SEPARATOR, $paths );
	set_include_path( get_include_path() . PATH_SEPARATOR . $includePath );


	require_once('Page.php');

	$fwd = isset($_GET['fwd']) ? urldecode($_GET['fwd']) : null;

	if( !$fwd ){
		$fwd = 'index.php?code=4';
	}

	session_destroy();
	setSession(0,"/");
	header('Location: ' . $fwd);
?>
