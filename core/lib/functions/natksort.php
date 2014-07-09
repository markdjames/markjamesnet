<?php
/**************************
* Natural sort array by its keys
**************************/
function natksort($aToBeSorted) {
	$aResult = array();
	$aKeys = array_keys($aToBeSorted);
	natcasesort($aKeys);
	foreach ($aKeys as $sKey) {
		$aResult[$sKey] = $aToBeSorted[$sKey];
	}
	return $aResult;
}