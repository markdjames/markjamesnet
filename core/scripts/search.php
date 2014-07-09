<?php
#!/usr/local/bin/php -q
chdir(dirname(__FILE__));

require '../../lib/config.php';

function getFiles($dir) {
	if (is_dir($dir)) {
		if($dh = opendir($dir)) {
	
			$files = Array();
			$inner_files = Array();
	
			while($file = readdir($dh)) {
				if($file != "." && $file != ".." && $file != "_notes" && $file[0] != '.') {
					if(is_dir($dir . "/" . $file)) {
						$inner_files = getFiles($dir . "/" . $file);
						if(is_array($inner_files)) $files = array_merge($files, $inner_files); 
					} else {
						array_push($files, $dir . "/" . $file);
					}
				}
			}
	
			closedir($dh);
			return $files;
		}
	}
}

$dir = "../lib/functions";
foreach (getFiles($dir) as $key=>$file){
	require_once $file;
}
$dir = "../classes";
foreach (getFiles($dir) as $key=>$file){
	require_once $file;
}

// wipe current search table
$db->custom("DELETE FROM search");
$db->custom("ALTER TABLE search AUTO_INCREMENT=0");

// do all custom pages
$db->type = 'site';
$records = $db->select("SELECT * FROM pages WHERE archived=0");
$modules = $db->select("SELECT * FROM modules");
$records = (count($records)) ? array_merge($records, $modules) : $modules;

foreach ($records as $record) {
	unset($v);
	$content = (strlen($record['content'])>250) ? rtrim(substr($record['content'], 0, 250))."..." : $record['content'] ;
	$id = (isset($record['pid'])) ? $record['pid'] : $record['id'] ;
	
	$v['path'] 			= ltrim($record['path'],"/");
	$v['title'] 		= $record['title'];
	$v['description'] 	= trim(strip_tags($content));
	$v['data'] 			= replace_latin(strip_tags($record['title']." / ".$record['content']." / ".$record['description']." / ".$record['keywords']));
	$v['type'] 			= 'pages';
	$v['rid'] 			= $id;
	
	$db->insert('search', $v);
}
