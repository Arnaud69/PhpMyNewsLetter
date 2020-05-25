<?php
session_start();
if(!file_exists("include/config.php")) {
	header("Location:install.php");
	exit;
} else {
	include("_loader.php");
}

$token=(empty($_GET['token'])?"":$_GET['token']);
if(!isset($token) || $token=="")$token=(empty($_GET['token'])?"":$_GET['token']);
if(tok_val($token)){
	if(!checkAdminAccess($row_config_globale['admin_pass'],$form_pass)) {
		if(!empty($_POST['form'])&&$_POST['form'])
			header("Location:login.php?error=1");
		else
			header("Location:login.php");
		exit;
	}
} else {
	header("Location:login.php?error=2");
	exit;
}

$log =(empty($_GET['log'])?"":urldecode($_GET['log']));
if(file_exists($log)){		 
	header("Pragma: public");
	header("Expires: 0"); 
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	header("Cache-Control: private",false);
	header("Content-Type: text/plain\n");
	header("Content-disposition: attachment; filename=".str_replace("logs/","",$log));
	header("Content-Transfer-Encoding: binary"); 
	header("Content-Length: ".filesize($log));
	header("Pragma: no-cache");
	ob_clean(); 
	flush();
	readfile_chunked($log);
}
