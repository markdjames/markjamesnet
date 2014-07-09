<?php
header("Content-type: text/xml; charset=utf-8"); 
require 'core/lib/bootstrap.php';

$pages = $m->getModules();

// get all pages from the database and loop through, adding each to the array of pages
$pages_db = $db->select("SELECT * FROM pages WHERE archived=0");
foreach ($pages_db as $page) {
	$tmp_pages = buildArrayFromPath(explode("/", $page['path']));
	$pages = array_merge_recursive($pages, $tmp_pages);
}
$pages = $site->siteMap($pages, BASE, 'array');

echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">';

foreach ($pages as $page) {
	echo "<url>";
	echo "<loc>".URL.DIR.$page."</loc>";
	echo "</url>";
}
echo "</urlset>";
