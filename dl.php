<?php
if(!file_exists("include/config.php")) {
	header("Location:install.php");
	exit;
} else {
	include("_loader.php");
	$token=(empty($_GET['token'])?"":$_GET['token']);
	if(!tok_val($token)){
		header("Location:login.php?error=2");
		exit;
	}
}
ob_start();
$log =(empty($_GET['log'])?"":urldecode($_GET['log']));
if(file_exists(__DIR__ . '/' .$log)){		 
	header("Pragma: public");
	header("Expires: 0"); 
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	header("Cache-Control: private",false);
	header("Content-Type: text/plain\n");
	header("Content-disposition: attachment; filename=" . str_replace("logs/","",$log));
	header("Content-Transfer-Encoding: binary"); 
	header("Content-Length: " . filesize(__DIR__ . '/' .$log));
	header("Pragma: no-cache");
	ob_clean(); 
	ob_end_flush();
	readfile_chunked(__DIR__ . '/' . $log);
}
