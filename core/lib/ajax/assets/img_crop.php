<?php
if (isset($_POST['file']) && !empty($_POST['dir'])) {
	require_once '../../../lib/bootstrap.php';
	
	$values['crop'] = $db->sqlify(json_encode($_POST['coords']));
	$values['path'] = $db->sqlify($_POST['path']);
	
	/**************************
	* Check if record exists for this image on this page
	**************************/
	$check = $image->getImageSettings(str_replace(" ", "%20", $_POST['dir']."/".$_POST['file']), $_POST['path']);
	if ($check) {
		$db->update('images', array('id'=>$check['id']), $values);
	} else {
		$values['dir'] = $db->sqlify(str_replace(" ", "%20", $_POST['dir']."/".$_POST['file']));
		$db->insert('images', $values);
	}
	$db->doCommit();

	/**************************
	* Check if record exists for this image in general
	**************************/
	unset($values['path']);
	$check = $image->getImageSettings(str_replace(" ", "%20", $_POST['dir']."/".$_POST['file']));
	if ($check) {
		$db->update('images', array('id'=>$check['id']), $values);
	} else {
		$values['dir'] = $db->sqlify(str_replace(" ", "%20", $_POST['dir']."/".$_POST['file']));
		$db->insert('images', $values);
	}
	
	$db->doCommit();
}
