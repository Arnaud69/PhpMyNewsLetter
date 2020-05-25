<?php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: text/plain');
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

echo unique_id();