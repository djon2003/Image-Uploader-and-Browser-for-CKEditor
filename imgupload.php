<?php

// Copyright (c) 2015, Fujana Solutions - Moritz Maleck. All rights reserved.
// For licensing, see LICENSE.md

// Including the plugin init file, don't delete the following row!
require_once(__DIR__ . '/plugininit.php');


//Ensure user connected, otherwise fallback on login page
if(!isset($_SESSION['username'])){
	require_once(__DIR__ . '/loginindex.php');
	exit;
}

$uploadImagePlugin = isset($_GET["uploadimage"]);

header('content-type: text/html; charset=utf-8');

$info = pathinfo($_FILES["upload"]["name"]);
$ext = $info['extension'];
$ext = strtolower($ext);
$checkExistance = false;
if ($generateFileNameOnUpload || $info["filename"] == "image") {
	//$filename = md5_file($_FILES["upload"]["tmp_name"]) . ".$ext";
	$filename = sha1_file($_FILES["upload"]["tmp_name"]) . ".$ext";
} else {
	$filename = $info["filename"] . ".$ext";
	$checkExistance = !$uploadImagePlugin;
}

$target_file = $useruploadpath . $filename;
$ckfile = $userUploadSiteRoot . '/' . $useruploadfolder . '/' . $filename;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$imageFileType = strtolower($imageFileType);

$errors = [];

/*
// Removed it has a real image didn't go through (like SVG)


// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["upload"]["tmp_name"]);
if($check === false) {
	$errors[] = $uploadimgerrors1;
}
*/


$uploadFile = true;
// Check if file already exists
if (file_exists($target_file)) {
	if ($checkExistance) {
		$errors[] = $uploadimgerrors2;
	} else {
		$uploadFile = false;
		unlink($_FILES["upload"]["tmp_name"]);
	}
}
// Check file size
if ($maxUploadFileSize != 0 && $_FILES["upload"]["size"] > $maxUploadFileSize) {
	$errors[] = $uploadimgerrors3;
}
// Allow certain file formats
if( array_search($imageFileType, $acceptedExtensions) === false ) {
	$errors[] = $uploadimgerrors4;
}



// If no errors, then upload
if (count($errors) == 0 && $uploadFile) {
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
    	chmod($target_file, 0777);
    } else {
		$errors[] = $uploadimgerrors6." ".$target_file." ".$uploadimgerrors7;
    }
}

if (!$uploadImagePlugin) {
	if (isset($_GET['CKEditorFuncNum'])) {
		$CKEditorFuncNum = $_GET['CKEditorFuncNum'];
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$ckfile', '');</script>";
	}
	if (isset($_GET['CKEditorFuncNum']) || count($errors) != 0) {
		echo "<script type='text/javascript'>";
		$alertErrors = $uploadimgerrors5;
		$alertErrors .= "\\n\\n- " . join('\\n -', $errors);
		$alertErrors = str_replace("'", "\\'", $alertErrors);
	
		echo "alert('$alertErrors');";
		
		//echo (!isset($_GET['CKEditorFuncNum']) ? 'history.back();' : '');
		echo "</script>";
	}
	
	header("Location: " . $_SESSION[SESSION_INITIAL_PAGE_URL] . "\n\n");
	
} else {
	$data = [];
	if (count($errors) == 0) {
		$data = [
			"uploaded" => "1",
			"fileName" => $filename,
			"url" => $ckfile
		];
	} else {
		$data = [
			"uploaded" => "0",
			"error" => [
					"message" => join('\\n -', $errors)
			]
		];
	}
	
	echo json_encode($data);
}
