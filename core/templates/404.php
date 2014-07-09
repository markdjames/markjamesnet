<?php
if (!isset($db)) {

	require_once '../../lib/config.php';
	
	$_SESSION['postdata'] = $_POST;
	
	// checks if content is avaliable in site core and redirects as nessecary
	if (is_file($_SERVER['DOCUMENT_ROOT'].str_replace(BASE, BASE.'/core', $_SERVER['REQUEST_URI']))) {
		header('Location: '.str_replace(BASE, BASE.'/core', $_SERVER['REQUEST_URI']));
	} else {
		?>
        <h1>404!</h1>
        <?php
	}
} else {
	?>
	<h1>404!</h1>
    <?php
}