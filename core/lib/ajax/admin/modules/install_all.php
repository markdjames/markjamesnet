<?php
if (!$db) {
	require_once '../../../../lib/bootstrap.php';
}
$modules = $m->getModules();
$modules_array = $site->siteMap($modules, BASE, 'array');	

foreach ($modules_array as $path) {
	$module = $m->getModuleByPath($path);
	if (!$module) {
		$mod = explode("/", $path);
		$module = end($mod);
		
		$values['path'] = $db->sqlify($path);
		$values['title'] = $db->sqlify(ucwords(str_replace("_", " ", $module)));
		$db->type='site';
		$db->insert("modules", $values);		
	}
}
$db->doCommit();

