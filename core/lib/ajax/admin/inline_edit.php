<?php
if (!isset($db)) {
	require_once '../../../lib/bootstrap.php';
}

foreach ($_POST as $k=>$v) {
	if ($k!='id' && $k!='table' && $k!='db') {
		$values[$k] = $db->sqlify($v);
	}
}
if (!empty($_POST['db'])) {
	$db->type = $_POST['db'];
}
$db->update($_POST['table'], array('id'=>$_POST['id']), $values, true);
$db->doCommit();

