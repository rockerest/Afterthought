<?php
	$paths = array(
		'.', 'backbone', 'components', 'content', 'model', 'render', 'scripts', 'styles', 'images'
	);

	$includePath = implode( PATH_SEPARATOR, $paths );
	set_include_path( get_include_path() . PATH_SEPARATOR . $includePath );

	require_once('Page.php');
	$tmpl = new Template();

	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : 200;

	$page = new Page("HTTP Status " . $tmpl->code . " Page", 'errors');

	$page->run();

	switch( $tmpl->code ){
		case 404:
				// Page or File Not Found
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "It appears that URL is broken.<br />Or the file got moved<br />Or something.<br />It appears the only course of action is to give up completely.";
				break;
		case 500:
				//Server Config or Script Crashed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Oh jeez.  I'm so sorry.  I don't even know what happened.<br />I swear this doesn't usually happen.<br />...<br />Um, the server is broken.  Come back later.";
				break;
		case 200:
		default:
				//Server Config or Script Crashed
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "Oh, you think you're SO sneaky.  Move along, nothing to see here.";
				break;
	}

	$html = $tmpl->build('error.html');
	$css = $tmpl->build('error.css');
	$js = $tmpl->build('error.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'error'
											),
						'js' => array(	'code' => $js,
										'link' => 'error'
										)
						);

	print $page->build($appContent);

?>
