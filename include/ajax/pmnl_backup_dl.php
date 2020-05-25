<?php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json'); 
if(!file_exists("../config.php")) {
	header("Location:../../install.php");
	exit;
} else {
	include("../../_loader.php");
	if(isset($_GET['token'])){$token=$_GET['token'];}else{$token='';}
	if(!tok_val($token)){
		header("Location:../../login.php?error=2");
		die();
	}
}

if (strpos($_GET['t'], "\0") !== false) {
	die('');
} else {
	$backup = basename($_GET['t']);
}
define('ALLOWED_REFERRER', $row_config_globale['base_url']);
if (ALLOWED_REFERRER !== '' && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']), strtoupper(ALLOWED_REFERRER)) === false)) {
	die("Internal server error. Please contact system administrator.");
}
$PmnlBackUpToDownload = $backup_dir.'/'. $backup ;
$fsize = filesize($PmnlBackUpToDownload);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$backup\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);
$file = @fopen($PmnlBackUpToDownload, "rb");
if ($file) {
	while (!feof($file)) {
		print(fread($file, 1024*8));
		flush();
		if (connection_status()!=0) {
			@fclose($file);
			die();
		}
	}
	@fclose($file);
}
