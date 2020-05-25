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
	if(isset($_POST['token'])){$token=$_POST['token'];}else{$token='';}
	if(!tok_val($token)){
		header("Location:../../login.php?error=2");
		die();
	}
}

if(!is_dir($backup_dir)){
	if(mkdir("$backup_dir",0755)){
		// continue
	} else {
		$arr=array(
			'status'=>'error',
			'successmsg'=>'erreur de création du répertoire de sauvegarde include/backup_db.<br>'. tr("CHECK_PERMISSIONS_OR_CREATE") . ' "include/backup_db" ' . tr("MANUALLY")
		);
		echo json_encode($arr, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		die();
	}
}
require('../lib/class.bkpmnl.php');
new BackupMySQL(array(
	'host'		=> $hostname,
	'username'	=> $login,
	'passwd'	=> $pass,
	'dbname'	=> $database,
	'dossier'	=> $backup_dir.'/',
	'prefixe'	=> $prefix,
	'token'		=> $token,
	'racine'	=> '',
	'nbr_fichiers'	=> $nb_backup
));