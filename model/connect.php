<?php
	require_once('Database.php');
	require_once('afterthought.db');
	
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	
	require_once('Base.php');
?>
