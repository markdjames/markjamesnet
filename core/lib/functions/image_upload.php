<?php
/*********************************
* Process image upload and move to correct place
* 
* @file = binary data - $_FILE data uploaded from form
* @path:String - sub-folder to place image
* @id:Int - relevent id of record
*********************************/
function processImageUpload($file, $path, $id=NULL, $dir_override=NULL) {

	$id = ($id==NULL) ? "" : "/".$id;
	
	// DENOTES THE DIRECTORY THE IMAGE WILL BE STORED IN
	if ($dir_override==NULL) {
		$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/assets/images/".$path.$id;
		@mkdir($dir, 0777);
	} else {
		$dir = $_SERVER['DOCUMENT_ROOT'].BASE.$dir_override;
	}

	// GET DETAILS FROM THE IMAGE INTO VARIABLES
	$fileName = $file['name'];
	$tmpName  = $file['tmp_name'];
	$fileSize = $file['size'];
	$fileType = $file['type'];
	
	if ($fileSize < 5242880) {
	
		$fileNameNoExt 	= explode(".", $fileName);
		$ext      		= strtolower(substr(strrchr($fileName, "."), 1)); 
		//$randName 		= urlify($fileNameNoExt[0]).substr((md5(rand() * time())), 0, 5);
		$randName 		= urlify($fileNameNoExt[0]);
		$filePath 		= $dir."/".$randName.".".$ext;
		if ($dir_override==NULL) {
			$fileRef = "/assets/images/".$path.$id."/".$randName.".".$ext;
		} else {
			$fileRef = $dir_override."/".$randName.".".$ext;
		}
	
		if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
			if ($ext == 'jpg' || $ext == 'jpeg') {
			  $imgmain = imagecreatefromjpeg($tmpName);
			} elseif ($ext == 'gif') {
			  $imgmain = imagecreatefromgif($tmpName);
			} if ($ext == 'png') {
			  $imgmain = imagecreatefrompng($tmpName);
			} 
			$widthmain = imagesx($imgmain);
			$heightmain = imagesy($imgmain);
		
			$max_widthmain =  1176;
			$max_heightmain = 800;
			$ratiomain = $max_widthmain/$widthmain;
			  
			if($widthmain > $max_widthmain) { 
				$new_widthmain = $widthmain * $ratiomain; 
				$new_heightmain = $heightmain * $ratiomain; 
			} else {
				$new_widthmain = $widthmain; 
				$new_heightmain = $heightmain;
			} 
			// create a new temporary image
			$tmp_imgmain = imagecreatetruecolor( $new_widthmain, $new_heightmain );
		
			// copy and resize old image into new image 
			imagecopyresampled( $tmp_imgmain, $imgmain, 0, 0, 0, 0, $new_widthmain, $new_heightmain, $widthmain, $heightmain );

			  // save to a file
			if ($ext == 'jpg') {
				imagejpeg($tmp_imgmain, "$filePath" );
			} elseif ($ext == 'gif') {
				imagegif( $tmp_imgmain, "$filePath" );
			} else {		
				imagepng( $tmp_imgmain, "$filePath" );
			}

			return $fileRef;
		} else {
			$_SESSION['error'] = "Warning: the image you choose is not a valid image file. Please select a GIF, JPG or PNG file.";
		}
	} else {
		$_SESSION['error'] = "Warning: the image you selected is too big - please choose an image with a smaller file size";
	}	
	echo $_SESSION['error'];
}