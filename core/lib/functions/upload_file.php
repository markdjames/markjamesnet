<?php
if (!empty($_FILES) && $_POST['function']=='upload_file') { 
	require_once '../bootstrap.php';
	
	/*********************************
	* Process file upload and move to correct place
	*********************************/
	// DENOTES THE DIRECTORY THE IMAGE WILL BE STORED IN
	$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/".$_POST['target'];
	@mkdir($dir, 0777);

	// GET DETAILS FROM THE IMAGE INTO VARIABLES
	$fileName = $_FILES['file']['name'];
	$tmpName  = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];
	$fileType = $_FILES['file']['type'];
		
	$fileNameNoExt 	= explode(".", $fileName);
	$ext      		= strtolower(substr(strrchr($fileName, "."), 1)); 
	
	if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
		$fileRef = processImageUpload($_FILES['file'], $_POST['target'], NULL, "/".trim($_POST['target'], "/"));
	} else {
		$randName 		= urlify($fileNameNoExt[0]);
		$filePath 		= $dir."/".$randName.".".$ext;
		$fileRef 		= $_POST['target']."/".$randName.".".$ext;

		move_uploaded_file($_FILES["file"]["tmp_name"], $filePath);
	}
	echo $fileRef;
}