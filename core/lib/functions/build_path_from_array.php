<?php
/***************************
* function to turn path array in to multi-dimension array with keys matching the path
*
* @param $path:Array = path as single level array
* returns array()
***************************/
function buildArrayFromPath($path) {
	$out = array();
	while ($pop = array_pop($path)) {
		$out = (count($out)) ? array($pop => $out) : array($pop=>$pop);
	}
	return $out;
}