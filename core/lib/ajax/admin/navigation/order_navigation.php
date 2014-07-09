<?php
require_once '../../../../lib/bootstrap.php';

if (!empty($_POST['order'])) {
	parse_str($_POST['order'], $order);

	$i=0;
	foreach ($order['nav'] as $p) {
		$split = explode("type", $p);
		$db->type='site';
		if ($split[1]=='page') {
			$db->update('pages', array('id'=>$split[0]), array('order'=>$i));
		} else {
			$db->update('modules', array('id'=>$split[0]), array('order'=>$i));
		}
		$i++;
	}
	$db->doCommit();
}