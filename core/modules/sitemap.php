<?php
$pages = $m->getModules();

// get all pages from the database and loop through, adding each to the array of pages
$pages_db = $db->select("SELECT * FROM pages WHERE archived=0");
if (count($pages_db)) {
	foreach ($pages_db as $page) {
		$tmp_pages = buildArrayFromPath(explode("/", $page['path']));
		$pages = array_merge_recursive($pages, $tmp_pages);
	}
}

ksort($pages);
$page['title'] = "Site Map";
$page['content'] = "<p>A complete list of content on this site.</p><br />".$site->siteMap($pages, BASE);
