<?php
if(!file_exists("include/config.php")) {
	header("Location:install.php");
	exit;
} else {
	include("_loader.php");
}
if (isset($_POST['token'])) {
	$token = $_POST['token'];
} elseif (isset($_GET['token'])) {
	$token = $_GET['token'];
} else {
	$token = '';
}
if (!tok_val($token)) {
	quick_Exit();
}
$list_id = $_POST['list_id'];
$list_total_subscribers=get_newsletter_total_subscribers($cnx,$row_config_globale['table_email'],$list_id,-1);
if($list_total_subscribers>1000000){
	ini_set('memory_limit', '2G');
}

export_subscribers($cnx, $row_config_globale['table_email'], $list_id);


