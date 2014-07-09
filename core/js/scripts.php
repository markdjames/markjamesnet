<?php 
require_once '../../core/lib/bootstrap.php'; 
header ("content-type: text/javascript"); 
?>
var URL = '<?=URL?>';
var BASE = '<?=BASE?>';
var DIR = '<?=DIR?>';
var SUBDIR = '<?=(!empty($_SESSION['SUBDIR']))?$_SESSION['SUBDIR']:"";?>';
<?php /*var USERID = '<?=$_SESSION['userid']?>'; */ ?>
var MOBILE = <?=(isset($_SESSION['mobile']) && $_SESSION['mobile'])?'true':'false'?>;
var TABLET = <?=(isset($_SESSION['tablet']) && $_SESSION['tablet'])?'true':'false'?>;
var THEME = '<?=$db->checkSettings('theme')?>';

<?php 
if (isset($_SESSION['is_admin'])) {
	?>
    var is_admin = 'true';
    <?php
}

$dir = "../js";


$extensions = array('js');

$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/core/js";
$scripts = scan_dir($dir);
if (count($scripts)) {
	foreach ($scripts as $key=>$file){
		$ext = substr($file, strrpos($file, ".") + 1); 
		if (strpos($file, 'jquery')===false && strpos($file, 'modernizr')===false && strpos($file, 'mootools')===false && in_array($ext, $extensions)) {
			echo "//".$file."\n";
			require_once $file;
			echo "\n\n";
		}
	} 
}

if (!defined('SUBDIR')) {
	$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/js";
	$scripts = scan_dir($dir);
	if (count($scripts)) {
		foreach ($scripts as $key=>$file){
			$ext = substr($file, strrpos($file, ".") + 1); 
			if (strpos($file, 'jquery')===false && strpos($file, 'modernizr')===false && strpos($file, 'mootools')===false && in_array($ext, $extensions)) {
				echo "//".$file."\n";
				require_once $file;
				echo "\n\n";
			}
		} 
	}
}

$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/core/blocks";
$scripts = scan_dir($dir);
if (count($scripts)) {
	foreach ($scripts as $key=>$file){
		$ext = substr($file, strrpos($file, ".") + 1); 
		if ($ext=='js') {
			echo "//".$file."\n";
			require_once $file;
			echo "\n\n";
		}
	} 
}

if (empty($_SESSION['SUBDIR'])) {
	$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/blocks";
	$scripts = scan_dir($dir);
	if (count($scripts)) {
		foreach ($scripts as $key=>$file){
			$ext = substr($file, strrpos($file, ".") + 1); 
			if ($ext=='js') {
				echo "//".$file."\n";
				require_once $file;
				echo "\n\n";
			}
		} 
	}
} else {
	
	$dir = $_SERVER['DOCUMENT_ROOT'].$_SESSION['SUBDIR']."/js";
	$scripts = scan_dir($dir);

	if (count($scripts)) {
		foreach ($scripts as $key=>$file){
			$ext = substr($file, strrpos($file, ".") + 1); 
			if (strpos($file, 'jquery')===false && strpos($file, 'modernizr')===false && strpos($file, 'mootools')===false && in_array($ext, $extensions)) {
				echo "//".$file."\n";
				require_once $file;
				echo "\n\n";
			}
		} 
	}
}
?>