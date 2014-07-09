<?
if (!empty($_POST['func'])) {
	require_once '../bootstrap.php';
	
	switch($_POST['func']) {
		case 'urlify':
			// pass string as arg
			echo urlify($_POST['args'], false);
			break;
		default:
			break;
	}
}