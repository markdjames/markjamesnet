<?php
require_once '../../core/lib/bootstrap.php';

function is_ani($filename) {
    if(!($fh = @fopen($filename, 'rb')))
        return false;
    $count = 0;
    //an animated gif contains multiple "frames", with each frame having a
    //header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)
   
    // We read through the file til we reach the end of the file, or we've found
    // at least 2 frame headers
    while(!feof($fh) && $count < 2) {
        $chunk = fread($fh, 1024 * 100); //read 100kb at a time
        $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
   }
   
    fclose($fh);
    return $count > 1;
}

chdir($_SERVER['DOCUMENT_ROOT']);
$doc_root = rtrim($_SERVER['DOCUMENT_ROOT']."/");

$_GET['img'] = "/".ltrim($_GET['img'], '/');

if (strpos($_GET['img'], "http")===false) {
	$base_dir = BASE;
	if (!empty($base_dir) && strpos($_GET['img'], BASE."")!==0) {
		$_GET['img'] = BASE."/".$_GET['img'];
	}

	if (is_file($doc_root.str_replace("__", ".", urldecode($_GET['img'])))) {
		$image = $doc_root.str_replace("__", ".", urldecode($_GET['img']));
		
	} elseif (is_file($doc_root.BASE.'/images/tmp/'.basename($_GET['img']))) {
		$image = $doc_root.BASE.'/images/tmp/'.basename($_GET['img']);
	} else {
		exit();
	}
} else {
	if (is_file($doc_root.BASE.'/images/tmp/'.basename($_GET['img']))) {
		$image = $doc_root.BASE.'/images/tmp/'.basename($_GET['img']);
	
	} else {
		$tmp_image = $curl->save_image(str_replace("__", ".", urldecode($_GET['img'])), NULL, '/images/tmp');
		$image = $doc_root.BASE."/".ltrim($tmp_image, '/');
	}
}

$imagename = basename($image);
$ext = strtolower(substr(strrchr($imagename, "."), 1)); 

if ($ext=="jpg") {
	$simg = imagecreatefromjpeg($image);
} elseif ($ext=="gif") {
	if ($_GET['ani']=='true' && is_ani($image)) {
		header('Content-Type: image/gif');
		echo file_get_contents($image); 
		exit();
	} else {
		$simg = imagecreatefromgif($image);
	}
} elseif ($ext=="png" || $ext=="jpeg") {
	$simg = imagecreatefrompng($image);
} else {
	exit();
}

$size = getimagesize($image);
$w = $size[0];
$h = $size[1];


if (!isset($_GET['height']) && isset($_GET['width'])) {
	if ($w > $h) {
		$r = $w/$h;
		$_GET['height'] = $_GET['width']/$r;
	} else {
		$r = $h/$w;
		$_GET['height'] = $_GET['width'];
		$_GET['width'] = $_GET['height']/$r;
	}
}

function upFactor($val, $orig, $scale=300) {
	$scale_1 = $scale / 100;
	$percentage = round($val) / $scale_1;
	return ($orig / 100) * $percentage;
}

if (isset($_GET['x1']) && isset($_GET['y1'])) {
	$img_r = $simg;
	
	// up the coords based on scale of 300px
	$x1 = upFactor($_GET['x1'], $w);
	$y1 = upFactor($_GET['y1'], $w);
	$crop_w = upFactor($_GET['w'], $w);
	$crop_h = upFactor($_GET['h'], $w);

	$simg = imagecreatetruecolor($crop_w,$crop_h);
	imagecopyresampled($simg,$img_r,0,0,$x1,$y1,$crop_w,$crop_h,$crop_w,$crop_h);
	
	$w = $crop_w;
	$h = $crop_h;
}

if (isset($_GET['crop']) && $_GET['crop']=='no') {
	$nw = (isset($_GET['width']))?$_GET['width']:$w;
	$nh = (isset($_GET['height']))?$_GET['height']:$h;

	$dimg = imagecreatetruecolor($_GET['width'], $_GET['height']);
	$white = imagecolorallocate($dimg, 255, 255, 255);
	imagefilledrectangle($dimg, 0, 0, $_GET['width'], $_GET['height'], $white);
	
	if ($nw>$nh) {
		$nw = $w / $h * $nh;
		imagecopyresampled($dimg, $simg, (($_GET['width']-$nw)/2), 0, 0, 0, $nw, $_GET['height'], $w, $h );
	} else {
		$nh = $h / $w * $nw;
		imagecopyresampled($dimg, $simg, 0, (($_GET['height']-$nh)/2), 0, 0, $_GET['width'], $nh, $w, $h );
	}
	
} else {
	
	
	// create a new temporary image
	if (strpos($_GET['img'], "youtube.com")!==false) {
		$ytimg = imagecreatetruecolor($w, $h-90);
		imagecopyresampled($ytimg, $simg, 0, -45, 0, 0, $w, $h, $w, $h);
		$simg = $ytimg;
		$h = $h-90;
	}
	if (strpos($_GET['img'], "vimeocdn.com")!==false) {
		$ytimg = imagecreatetruecolor($w, $h-34);
		imagecopyresampled($ytimg, $simg, 0, -17, 0, 0, $w, $h, $w, $h);
		$simg = $ytimg;
		$h = $h-34;
	}
	/***********************
	* Special hack for CD images so they don't crop when in widescreen ratio
	***********************/
	if (strpos($_GET['img'], "/products/")!==false && $_GET['width']>$_GET['height']+10) {
		$ytimg = imagecreatetruecolor($w, $h);
		$white = imagecolorallocate($ytimg, 255, 255, 255);
		imagefilledrectangle($ytimg, 0, 0, $w, $h, $white);
		imagecopyresampled($ytimg, $simg, 100, (($w-($h-200))/2), 0, 0, $h-200, $h-200, $w, $h);
		$simg = $ytimg;
	}
	
	$nw = (isset($_GET['width']))?$_GET['width']:$w;
	$nh = (isset($_GET['height']))?$_GET['height']:$h;
	
	$dimg = imagecreatetruecolor($nw, $nh);
	
	$white = imagecolorallocate($dimg, 255, 255, 255);
	if ($ext=='png') {
		$black = imagecolorallocate($dimg, 0, 0, 0);
		imagecolortransparent($dimg, $black);
		imagealphablending($dimg, false);
		imagesavealpha($dimg, true);
	} else {
		imagefilledrectangle($dimg, 0, 0, $nw, $nh, $white);
	}
	
	$wm = $w/$nw;
	$hm = $h/$nh;
	
	$h_height = $nh/2;
	$w_height = $nw/2;
	
	$final_height = ($nw * ($h/$w));
	$height_diff = ($final_height - $nh)/2;
	$final_width = ($nh * ($w/$h));
	$width_diff = ($final_width - $nw)/2;
	
	if (is_resource($simg)) {
		if($final_width < $nw) {
			imagecopyresampled($dimg, $simg, 0, -$height_diff, 0, 0, $nw, $final_height, $w, $h );
		
		} elseif($final_width > $nw)  {
			imagecopyresampled($dimg, $simg, -$width_diff, 0, 0, 0, $final_width, $nh, $w, $h );
			
		} else {
			imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
		}
	} else {
		exit();
	}
}

if (!empty($_GET['credit'])) {
	$white = imagecolorallocatealpha($dimg, 255, 255, 255, 50);
	$black = imagecolorallocate($dimg, 0, 0, 0);

	$bounds = imagettfbbox (9, 0, $_SERVER['DOCUMENT_ROOT'].'/core/images/font.ttf', "© ".$_GET['credit']);

	imagefilledrectangle($dimg, 0, ($nh-$bounds[2])-13, -$bounds[5]+8, $nh, $white);
	imagettftext($dimg, 9, 90, 15, $nh-5, $black, $_SERVER['DOCUMENT_ROOT'].'/core/images/font.ttf', "© ".$_GET['credit']);
}


// Output the image

if ($ext=="jpg" || $ext=="gif" || $ext=="jpeg") {
	header('Content-Type: image/jpeg');
	header("Content-Disposition: inline; filename=".basename($image, $ext).".jpg");
	imageinterlace($dimg, true);
	if ($_SESSION['mobile']) {
		imagejpeg($dimg, NULL, 30);
	} else {
		if ($_GET['quality']='high') {
			imagejpeg($dimg, NULL, 100);
		} elseif($_GET['quality']='low') {
			imagejpeg($dimg, NULL, 50);
		} else {
			imagejpeg($dimg, NULL, 80);
		}
	}
} elseif ($ext=="png") {
	header('Content-Type: image/png');
	header("Content-Disposition: inline; filename=".basename($image, $ext).".png");
	if ($_GET['quality']='high') {
		imagepng($dimg, NULL, 0);
	} elseif($_GET['quality']='low') {
		imagepng($dimg, NULL, 6);
	} else {
		imagepng($dimg, NULL, 3);
	}
} else {
	exit();
}

// Free up memory
imagedestroy($dimg);

?>