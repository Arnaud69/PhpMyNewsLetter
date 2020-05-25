<?php
session_start();
ob_start();
date_default_timezone_set('Europe/Berlin');
if(!file_exists("include/config.php")){
	header("Location:install.php");
	exit;
} else{
	include("_loader.php");
}

use PHPMailer;
use Exception;
require 'include/lib/PHPMailer/src/Exception.php';
require 'include/lib/PHPMailer/src/PHPMailer.php';
require 'include/lib/PHPMailer/src/SMTP.php';

$tPath        = ($row_config_globale['path'] == '' ? '/' : '/'.$row_config_globale['path']);
$tPath        = str_replace('//','/',$tPath);
if(isset($_GET['x'])) {
	$is_admin=current($cnx->query("SELECT count(*) AS is_admin
		FROM $table_global_config 
			WHERE lost_pass=" . escape_string(CleanInput($_GET['x'])) . " 
				AND admin_email=" . escape_string(CleanInput($_GET['m'])) . ";")->fetch());
	if($is_admin) {
		$new_pass=randomPassword(12,1,"lower_case,upper_case,numbers,special_symbols");
		$cnx->query("UPDATE " . $table_global_config. " 
			SET admin_pass='" . md5($new_pass[0]). "' 
				WHERE admin_email=" . escape_string(CleanInput($_GET['m'])). "
					AND lost_pass=" . escape_string(CleanInput($_GET['x'])));
		$subj = 'Nouveau mot de passe / reset password !';
		$lost_msg = '<br /><br /><br /><br /><br />
			<table style="height: 217px; margin-left: auto; margin-right: auto;" width="660">
			<tbody>
			<tr><td style="text-align: center;"><span style="color: #2446a2;font-size: 14pt;">
			<img src="https://www.phpmynewsletter.com/css/images/phpmynewsletter_v2.png" alt="" width="123" height="72" /><br />Nouveau mot de passe / reset password !</td></tr>
			<tr><td><span style="color: #2446a2;">Voici votre nouveau mot de passe, comme demandé / This is your new password, as asked.</span></td></tr>
			<tr><td align="center"><span style="color: #000000;"><h2>' . $new_pass[0] . '</h2></td></tr>
			<tr><td><span style="color: #2446a2;">Vous pouvez vous connecter / You can connect :</td></tr>
			<tr><td><span style="color: #2446a2;"><a href="' . $row_config_globale['base_url'] . $tPath . '">' . $row_config_globale['base_url'] . $tPath . '</a></td></tr>
			</tbody>
			</table>';
		sendEmail($row_config_globale['sending_method'],$row_config_globale['admin_email'], $row_config_globale['admin_email'], 
			$row_config_globale['admin_name'], $subj, $lost_msg, $row_config_globale['smtp_auth'], $row_config_globale['smtp_host'], 
			$row_config_globale['smtp_login'], $row_config_globale['smtp_pass'], $row_config_globale['charset']);
		header("Location: login.php?pass");
		die();
	} else {
		header("Location: login.php?pass");
	}	
}
$is_admin=current($cnx->query("SELECT count(*) AS is_admin
		FROM " . $table_global_config . "
			WHERE admin_email=" . escape_string(CleanInput($_POST['form_mail_admin'])) . ";")->fetch());
if($is_admin) {
	function random_str($length){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	$chaine_pass = random_str(32);
	$link_reset = $row_config_globale['base_url'] . $tPath . "reset.php?x=" . $chaine_pass . "&m=" . $_POST['form_mail_admin'];
	$cnx->query("UPDATE " . $table_global_config. " 
			SET lost_pass=" . escape_string($chaine_pass). " 
				WHERE admin_email='" . CleanInput($_POST['form_mail_admin']) . "'");
	$subj = 'Mot de passe perdu / lost password !';
	$lost_msg = '<br /><br /><br /><br /><br />
		<table style="height: 217px; margin-left: auto; margin-right: auto;" width="660">
		<tbody>
		<tr><td style="text-align: center;"><span style="color: #2446a2;font-size: 14pt;">
		<img src="https://www.phpmynewsletter.com/css/images/phpmynewsletter_v2.png" alt="" width="123" height="72" /><br />Mot de passe perdu / reset password !</td></tr>
		<tr><td><span style="color: #2446a2;">Quelqu\'un a demandé la réinitialisation de votre mot de passe.</span></td></tr>
		<tr><td><span style="color: #2446a2;"><i>Somebody asked to recover password.</i></span></td></tr>
		<tr><td><span style="color: #2446a2;">Si c\'est bien le cas, cliquez sur le lien ci-dessous.<br>
		Sinon, connectez-vous et changez rapidement votre mot de passe.</span></td></tr>
		<tr><td><span style="color: #2446a2;"><i>If you dit it, please click on link below.<br>
		Else, connect and change password.</i></span></td></tr>
		<tr><td><span style="color: #2446a2;">Cliquez ici ou copier/coller dans votre navigateur :</span></td></tr>
		<tr><td><span style="color: #2446a2;"><i>Click here or copy and paste in your browser :</i></span></td></tr>
		<tr><td><span style="color: #2446a2;"><a href="'.$link_reset.'">'.$link_reset.'</a></span></td></tr>
		</tbody>
		</table>';
	sendEmail($row_config_globale['sending_method'],$row_config_globale['admin_email'], $row_config_globale['admin_email'], 
		$row_config_globale['admin_name'], $subj, $lost_msg, $row_config_globale['smtp_auth'], $row_config_globale['smtp_host'], 
		$row_config_globale['smtp_login'], $row_config_globale['smtp_pass'], $row_config_globale['charset']);
}
header("Location:login.php");
