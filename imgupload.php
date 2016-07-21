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

header('content-type: text/html; charset=utf-8');

$info = pathinfo($_FILES["upload"]["name"]);
$ext = $info['extension'];
$ext = strtolower($ext);
if ($generateFileNameOnUpload) {
	$randomLetters = $rand = substr(md5(microtime()),rand(0,26),6);
	$imgnumber = count(scandir($useruploadpath));
	$filename = "$imgnumber$randomLetters.$ext";
} else {
	$filename = $info["filename"] . ".$ext";
}

$target_file = $useruploadpath . $filename;
$ckfile = $userUploadSiteRoot . $useruploadpath . $filename;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$imageFileType = strtolower($imageFileType);

$errors = [];

/*
// Removed it has a real image didn't go through

*/

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["upload"]["tmp_name"]);
if($check === false) {
	$errors[] = $uploadimgerrors1;
}


// Check if file already exists
if (file_exists($target_file)) {
	$errors[] = $uploadimgerrors2;
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
if (count($errors) == 0) {
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
    	chmod($target_file, "0777");
    	
        if(isset($_GET['CKEditorFuncNum'])){
            $CKEditorFuncNum = $_GET['CKEditorFuncNum'];
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$ckfile', '');</script>";
        }
    } else {
		$errors[] = $uploadimgerrors6." ".$target_file." ".$uploadimgerrors7;
    }
}

echo "<script>";
if (count($errors) != 0) {
	$alertErrors = $uploadimgerrors5;
	$alertErrors .= "\\n\\n- " . join('\\n -', $errors);
	$alertErrors = str_replace("'", "\\'", $alertErrors);
		
	echo "alert('$alertErrors');";
}

echo (!isset($_GET['CKEditorFuncNum']) ? 'history.back();' : '');
echo "</script>";
