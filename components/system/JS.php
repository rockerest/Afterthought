<?php
	header('Content-Type: text/javascript');

	$q = isset($_GET['q']) ? $_GET['q'] : null;
	preg_match_all('#[\w\.]+#', $q, $files);
	$content = '';
	
	foreach ($files[0] as $fn)
	{
		$filename = $fn . ".js";		
		$folders = array(
							"../../global/",
							"../../global/blackbird/",
							"../../jquery/"
						);
		foreach ( $folders as $folder )
		{
			if ( file_exists($folder . $filename) )
			{
				$content .= file_get_contents($folder . $filename);
			}
		}
		$content .= "\n\n";
	}
	
	print $content;
?>