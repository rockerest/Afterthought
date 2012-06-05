<?php
	header('Content-Type: text/css');

	$q = isset($_GET['q']) ? $_GET['q'] : null;
	preg_match_all('#[\w\.]+#', $q, $files);
	$content = '';
	
	foreach ($files[0] as $fn){
		$filename = $fn . ".css";		
		$folders = array(
							"../../global/",
							"../../global/css3buttons/",
							"../../global/blackbird/",
							"../../global/tipped/",
							"../../global/fullcalendar/",
							"../../styles/",
							"../../jquery/"
						);
		foreach ( $folders as $folder ){
			if ( file_exists($folder . $filename) ){
				$content .= file_get_contents($folder . $filename);
			}
		}
		$content .= "\n\n";
	}
	
	print $content;
?>