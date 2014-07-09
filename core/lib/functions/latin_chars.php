<?php
define("LATIN1_UC_CHARS", 	"À Á Â Ã Ä Å Æ Ç È É Ê Ë Ì Í Î Ï Ð Ñ Ò Ó Ô Õ Ö Ø Ù Ú Û Ü Ý Ł");
define("LATIN1_LC_CHARS", 	"à á â ã ä å æ ç è é ê ë ì í î ï ð ñ ò ó ô õ ö ø ù ú û ü ý ł");
define("UC_CHARS", 			"A A A A A A A C E E E E I I I I D N O O O O O O U U U U Y L");
define("LC_CHARS", 			"a a a a a a a c e e e e i i i i d n o o o o o o u u u u y l");

function uc_latin1 ($str) {
    $str = strtoupper(strtr($str, LATIN1_LC_CHARS, LATIN1_UC_CHARS));
	$str = strtoupper(strtr($str, LATIN1_LC_CHARS, LATIN1_UC_CHARS));
    return strtr($str, array("ß" => "SS"));
}

function replace_latin ($str) {

	
	$normalizeChars = array(
		'Á'=>'A', 'À'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Å'=>'A', 'Ä'=>'A', 'Æ'=>'AE', 'Ç'=>'C',
		'É'=>'E', 'È'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Í'=>'I', 'Ì'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ð'=>'Eth',
		'Ñ'=>'N', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O',
		'Ú'=>'U', 'Ù'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',

		'á'=>'a', 'à'=>'a', 'â'=>'a', 'ã'=>'a', 'å'=>'a', 'ä'=>'a', 'æ'=>'ae', 'ç'=>'c', 'č'=>'c',
		'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e', 'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i', 'ł'=>'l',
		'ð'=>'eth','ñ'=>'n', 'ó'=>'o', 'ò'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ř'=>'r', 'š'=>'s', 
		'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u', 'ů'=>'u', 'ý'=>'y',
	   
		'ß'=>'sz', 'þ'=>'thorn', 'ÿ'=>'y'
	);
	$str = strtr($str, $normalizeChars);

	return $str;
}