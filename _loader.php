<?php
session_start();
ob_start("ob_gzhandler");
date_default_timezone_set('Europe/Paris');
include( 'include/config.php' );
if( $type_env=='dev' ) { 
	error_reporting(E_ALL);
	ini_set('display_errors',1);
} else {
	error_reporting(0);
	ini_set("display_errors",0);
}
ini_set('mail.add_x_header','Off');
$_SESSION['timezone'] = $timezone;
$popup = false;
$display_archive = false;
$backup_dir = "../backup_db";
include( 'include/db/db_connector.inc.php' );
include_once( 'include/lib/pmn_fonctions.php' );

if( $type_serveur=='dedicated' ) {
	$cnx->query( "SET sql_mode = '';" );
}
$cnx->query("SET NAMES UTF8");

$row_config_globale = $cnx->query("SELECT * FROM $table_global_config")->fetch();
( count($row_config_globale)> 0) ? $r = 'SUCCESS' : $r = '';
if ($r != 'SUCCESS') {
	include ("include/lang/english.php");
	echo "<div class='error'>" . tr($r) . "<br />";
	echo "</div>";
	exit;
}
if (empty($row_config_globale['language'])) {
	$row_config_globale['language'] = "english";
}
include ("include/lang/" . $row_config_globale['language'] . ".php");

if ($mailq_path = exec('command -v mailq')!='') {
	$is_mq_true = true;
} else {
	$is_mq_true = false;
}
