<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/uploads'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	$filepath= rtrim($targetFolder,'/') . '/' . $_FILES['Filedata']['name'];
	//echo $_FILES['Filedata']['name'];die();
	// Validate the file type
	$fileTypes = array('wav','mp3','flv','mod','ra','cd','md','asf','jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	var_dump($fileParts);
	echo $_FILES['Filedata']['name'].'123';
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo  $filepath;
	} else {
		echo '上传失败';
	}
}
?>