<?php
if(!file_exists("config.php")) {
	header("Location:../install.php");
	exit;
} else {
	include("../_loader.php");
}
$token=(empty($_GET['token'])?"":$_GET['token']);
if(!isset($token) || $token=="")$token=(empty($_GET['token'])?"":$_GET['token']);
if (isset($_POST['token'])) {
	$token = $_POST['token'];
} elseif (isset($_GET['token'])) {
	$token = $_GET['token'];
} else {
	$token = '';
}
if (!tok_val($token)) {
	quick_Exit();
	die();
}
extract($_GET,EXTR_OVERWRITE);
$row = $cnx->query("SELECT date, type, subject, message, list_id, preheader 
		FROM " . $row_config_globale['table_archives'] . " 
	WHERE id=" . $id_mail . "
		AND list_id=" . $list_id
		)->fetch(PDO::FETCH_ASSOC);
if ( count($row) > 1 ) {
	echo $row['message'];
} else {
	die();
}
