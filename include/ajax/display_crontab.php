<?php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: text/html'); 

if(!file_exists("../config.php")) {
	header("Location:../../install.php");
	exit;
} else {
	include("../../_loader.php");
	$token=(empty($_POST['token'])?"":$_POST['token']);
	if(!isset($token) || $token=="")$token=(empty($_GET['token'])?"":$_GET['token']);
	if(!tok_val($token)){
		header("Location:../../login.php?error=2");
		exit;
	} else {
		$crontab = shell_exec('crontab -l');
		if ( trim($crontab) == '' ) {
			echo tr( "SCHEDULE_NO_SEND_SCHEDULED" );
		} else {
			echo '<pre style="background-color:black;background-image:radial-gradient(rgba(0,150,0,0.75),black 120%);margin:0;overflow:hidden;padding: 2rem;color:white;font:1.0rem Inconsolata,monospace;text-shadow:0 0 5px #C8C8C8;">' . trim($crontab) . '</pre>';
		}
	}
}