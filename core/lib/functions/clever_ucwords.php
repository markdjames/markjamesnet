<?php
function clever_ucwords($string) {
	$small_words = array('and', 'or', 'by', 'for', 'the', 'de', 'in', 'arr', 'orch', 'of');
	$abbrv = array('arr'=>'arr.', 'No 1'=>'No. 1', 'No 2'=>'No. 2', 'No 3'=>'No. 3', 'No 4'=>'No. 4', 'No 5'=>'No. 5', 'No 6'=>'No. 6', 'No 7'=>'No. 7', 'No 8'=>'No. 8', 'No 9'=>'No. 9', 'No 10'=>'No. 10', 'Iii'=>'III', 'Ii'=>'II', 'Iv'=>'IV', 'Vi'=>'VI', 'Iiii'=>'IIII', 'orch '=>'orch.');
	$uppercase = array('re-rite'=>'RE-RITE','Esa-pekka'=>'Esa-Pekka');
	
	if (strpos($string, '--')!==false) {
		$string = substr($string, 0, strpos($string,"--")).
					"<em> ".
					substr($string, strpos($string,"--")+2, strrpos($string,"--")-(strpos($string,"--")+2)).
					"</em>".
					substr($string, strrpos($string,"--")+2);
	}
	$string = preg_replace('#(?<!^)\b('.implode('|', $small_words).')\b#', '@@DO_NOT_CAPITALIZE@@$1', $string); 
	$string = str_ireplace("O'", "O' %% ", $string);
	$string = ucwords(str_replace("-", "- ", $string));
	$string = str_replace("- ", "-", $string);
	$string = str_ireplace("O' %% ", "O'", $string);
	$string = str_replace('@@DO_NOT_CAPITALIZE@@', '', $string);
	foreach ($abbrv as $key=>$val) {
		$string = preg_replace("/\b".$key."\b/i", $val, $string);
	}
	foreach ($uppercase as $key=>$val) {
		$string = str_ireplace($key, $val, $string);
	}
	
	return $string;
}