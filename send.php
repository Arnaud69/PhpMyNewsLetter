<?php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
if (!file_exists("include/config.php")) {
	header("Location:install.php");
	exit;
} else {
	include("_loader.php");
	if (file_exists("include/config_bounce.php")) {
		include("include/config_bounce.php");
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
		die();
	}
}
use PHPMailer;
use Exception;
require 'include/lib/PHPMailer/src/Exception.php';
require 'include/lib/PHPMailer/src/PHPMailer.php';
require 'include/lib/PHPMailer/src/SMTP.php';
if ( $do_encrypt==1 ){
	include('include/lib/class.encrypt.php');
	$en = new Encrypt();
}
$step         = (empty($_GET['step']) ? "" : $_GET['step']);
$subject      = (!empty($_SESSION['subject'])) ? $_SESSION['subject'] : '';
$message      = (!empty($_SESSION['message'])) ? $_SESSION['message'] : '';
$format       = (!empty($_SESSION['format'])) ? $_SESSION['format'] : '';
$draft        = (!empty($_SESSION['draft'])) ? $_SESSION['draft'] : '';
$preheader    = (!empty($_SESSION['preheader'])) ? $_SESSION['preheader'] : '';
$sender_email = (!empty($_SESSION['sender_email'])) ? $_SESSION['sender_email'] : '';
$list_id      = (!empty($_POST['list_id'])) ? (($_POST['list_id']) + 0) : '';
$list_id      = (!empty($_GET['list_id']) && empty($list_id)) ? (($_GET['list_id']) + 0) : (($list_id) + 0);
$begin        = (!empty($_POST['begin'])) ? $_POST['begin'] : '';
$begin        = (!empty($_GET['begin']) && empty($begin)) ? (($_GET['begin']) + 0) : 0;
$msg_id       = (!empty($_GET['msg_id'])) ? (($_GET['msg_id']) + 0) : '';
$sn           = (!empty($_GET['sn'])) ? (($_GET['sn']) + 0) : '';
$error        = (!empty($_GET['error'])) ? $_GET['error'] : '';
$encode       = (!empty($_GET['encode']) && $_GET['encode'] == 'base64') ? 'base64' : 'quoted-printable';
$force        = (!empty($_POST['force'])) ? $_POST['force'] : '';
$force        = (!empty($_GET['force']) && empty($force)) ? $_GET['force'] : '';
$tPath        = ($row_config_globale['path'] == '' ? '/' : '/'.$row_config_globale['path']);
$tPath        = str_replace('//','/',$tPath);
switch ($step) {
	case "send":
		if (isset($force) && $force === true) {
			touch('logs/__SEND_PROCESS__' . $list_id . '.pid');
			$num = get_newsletter_total_subscribers($cnx, $row_config_globale['table_email'], $list_id, $msg_id);
			$cnx->query("INSERT IGNORE into " . $row_config_globale['table_send'] . " (`id_mail`, `id_list`, `cpt`)
					VALUES ('" . $msg_id . "','" . $list_id . "','0')");
			$cnx->query("INSERT IGNORE into " . $row_config_globale['table_send_suivi'] . " (`list_id`, `msg_id`,`nb_send`, `total_to_send`, `tts`)
					VALUES ('" . $list_id . "','" . $msg_id . "',0,'" . $num . "',0)");
		}
		if (!file_exists('logs/__SEND_PROCESS__' . $list_id . '.pid')) {
			if ($_SESSION['dr_log'] == 'Y' && ($begin < $sn)) {
				loggit($_SESSION['dr_id_user'] . '.log', $_SESSION['dr_id_user'] . ' a interrompu un envoi de campagne "' . $subject . '" par "' . $sender_email . '" en ajax');
			}
			$arr = array(
				'TTS' => 'Envoi stoppé'
			);
			echo json_encode($arr);
			die();
		}
		$tts     = 0;
		$start   = microtime(true);
		$dontlog = 0;
		if (!$handler = @fopen('logs/list' . $list_id . '-msg' . $msg_id . '.txt', 'a+')) {
			$dontlog = 1;
		}
		$daylog = @fopen('logs/daylog-' . date("Y-m-d") . '.txt', 'a+');
		$limit  = $row_config_globale['sending_limit'];
		$mail   = new PHPMailer\PHPMailer\PHPMailer;
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		$mail->CharSet		= $row_config_globale['charset'];
		$mail->ContentType	= "text/html";
		$mail->Encoding		= "quoted-printable";
		$mail->PluginDir	= "include/lib/";
		$msg				= get_message($cnx, $row_config_globale['table_archives'], $msg_id);
		$newsletter			= getConfigSender($cnx, $row_config_globale['table_senders'], $msg['sender_email']);
		$sender_email		= @$newsletter['email'];
		$sender_name		= @$newsletter['name_organisation'];
		$reply_email		= @$newsletter['email_reply'];
		if (empty($sender_email)) {
			$emptysender  	= getConfig($cnx, $list_id, $row_config_globale['table_listsconfig']);
			$sender_email 	= $emptysender['from_addr'];
			$sender_name  	= $emptysender['from_name'];
			$reply_email  	= $emptysender['from_addr'];
		}
		
		// recherche du mail de bounce (retour des non distribués), du particulier au général, sinon, par défaut : $bounce_mail
		$tmpBounce = @trim($newsletter['bounce_email']);
		if(empty($tmpBounce)) {
			$tmpBounceBase = trim($bounce_mail);
			if (empty($tmpBounceBase)) { 			// from config_bounce.php : global desc
				$bounce_email = $emptysender['from_addr'];	// from array $emptysender : default desc
			} else {
				$bounce_email = $bounce_mail;
			}
		} else {
			$bounce_email = $newsletter['bounce_email'];
		}
		
		$mail->AddReplyTo($reply_email);
		$mail->SetFrom($sender_email, $sender_name);
		$mail->Sender = $bounce_email;
		$addr = getAddress($cnx, $row_config_globale['table_email'], $list_id, $begin, $limit, $msg_id);
		if ($type_env == 'dev') {
			$daylogmsg = "LIST_ID : $list_id\tBEGIN : $begin\tLIMIT : $limit\tMSG_ID : $msg_id\n";
			fwrite($daylog, $daylogmsg, strlen($daylogmsg));
		}
		$format  = $msg['type'];
		$list_pj = $cnx->query("SELECT *
			FROM " . $row_config_globale['table_upload'] . "
				WHERE list_id=" . $list_id . "
				AND msg_id=" . $msg_id . "
			ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
		if (count($list_pj) > 0) {
			foreach ($list_pj as $item) {
				$mail->AddAttachment('upload/' . $item['name']);
			}
		}
		
		$message       = stripslashes($msg['message']);
		$to_replace    = array("  ","\t","\n","\r","\0","\x0B","\xA0");
		$subject       = stripslashes($msg['subject']);
		$message       = str_replace($to_replace, " ", $message);
		$message       = str_replace('*|MC_PREVIEW_TEXT|*','',$message);
		if (strpos($message, '</style>') === false) {
			$message = '<style type="text/css"></style>' . $message;
		}
		if (strpos($message, '</title>') === false) {
			$message = '<title>[[SUBJECT]]</title>' . $message;
		} elseif (strpos($message, '<title>[[SUBJECT]]</title>') === false && strpos($message, '<title>') !== false) {
			$message = preg_replace("/<title>(.*)<\/title>/","",$message,1);
			$message = '<title>[[SUBJECT]]</title>' . $message;
		}
		$header        = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE]>	
	<html xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<!--[if !IE]>
<!-->
	<html style="margin: 0;padding: 0;" xmlns=3D"http://www.w3.org/1999/xhtml">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<!--[if !mso]><!-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!--<![endif]-->
<meta name="x-apple-disable-message-reformatting" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="description" content="' . $subject . '" />';
		$message       = str_replace('<title>[[SUBJECT]]</title>', $header.'<title>' . $subject . '</title>', $message);
		$preHeaderDesc = stripslashes($msg['preheader']);
		$preHeader     = "<div class='preHeader' align='center' style='display:none !important;visibility:hidden;mso-hide:all;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;'>" . $preHeaderDesc . "</div>";
		$message       = str_replace('</style>', '</style></head><body>'.$preHeader, $message);
		$messageSource = str_replace("  ", " ", $message);
		if ($format == "html") {
			$mail->IsHTML(true);
		}
		$mail->WordWrap = 76;
		if (file_exists("include/DKIM/DKIM_config.php") && ($row_config_globale['sending_method'] == 'smtp' || $row_config_globale['sending_method'] == 'php_mail')) {
			include("include/DKIM/DKIM_config.php");
			$mail->DKIM_domain     = $DKIM_domain;
			$mail->DKIM_private    = $DKIM_private;
			$mail->DKIM_selector   = $DKIM_selector;
			$mail->DKIM_passphrase = $DKIM_passphrase;
			$mail->DKIM_identity   = $DKIM_identity;
		}
		$to_send              = count($addr);
		$view_last_send_mails = "";
		$mail->SMTPKeepAlive  = true;
		for ($i = 0; $i < $to_send; $i++) {
			$last_id_send = $addr[$i]['id'];
			/*$cnx->query("UPDATE " . $row_config_globale['table_send_suivi'] . "
					SET nb_send=nb_send+1,last_id_send=" . $last_id_send . "
						WHERE `msg_id`='" . $msg_id . "' AND `list_id`='" . $list_id . "'");*/
			$time_info     = "";
			$begintimesend = microtime(true);
			$unsubLink     = "";
			$headtrc       = "";
			$body          = "";
			$message       = $messageSource;
			$mail->ClearAllRecipients();
			$mail->ClearCustomHeaders();
			$mail->AddAddress($addr[$i]['email']);
			$view_last_send_mails .= $addr[$i]['email'];
			include("include/lib/switch_smtp.php");
			$mail->XMailer = ' ';
			if ( $do_encrypt==1 ){
				$tracked_mail = $en->encrypt($addr[$i]['email']);
			} else {
				$tracked_mail = $addr[$i]['email'];
			}
			$mail->addCustomHeader("List-Unsubscribe",'<'. $row_config_globale['base_url'] . $tPath . 'subscription.php?i=' . $msg_id . '&list_id='
				. $list_id . '&op=leave&email_addr=' . $tracked_mail . '&h=' . $addr[$i]['hash'] . '>'
				. ( $sender_email != '' ? ', <mailto:' . $sender_email . '?subject=unsubscribe>' : '' )
			);
			if ($row_config_globale['active_tracking'] == '1') {
				$trac = "<img style='border:0' src='" . $row_config_globale['base_url'] . $tPath . "trc.php?i=" . $msg_id . "&h="
				. $addr[$i]['hash'] . "' width='1'  height='1 alt='" . $list_id . "' />";
			} else {
				$trac = "";
			}
			$replacements_adds = array(
				'({user_email})' => $addr[$i]['email'],
				'({newsletter_id})' => $msg_id
				);
			$message = preg_replace( array_keys( $replacements_adds ), array_values( $replacements_adds ), $message );
			if ($format == "html") {
				if ( $row_config_globale['active_tracking'] == '1' ) {
					$new_url = 'href="' . $row_config_globale['base_url'] . $tPath . 'r.php?m=' . $msg_id . '&h=' . $addr[$i]['hash'] . '&l=' . $list_id . '&r=';
					$message   = preg_replace_callback('/href="(http[s]?:\/\/)([^"]+)"/', function($matches) {
						global $new_url;
						return $new_url . (urlencode(@$matches[1] . $matches[2])) . '"';
					}, $message);
				}
				$message = add_alt_tags ($message) ;
				if (strpos($message, '</body>') !== false) {
					$message = str_replace('</body>', '', $message);
					$message = str_replace('</html>', '', $message);
				}
				$headtrc = "<hr noshade='' color='#D4D4D4' width='90%' size='1'>"
							. "<div align='center' style='font-size:12px;font-family:arial,helvetica,sans-serif;padding-bottom:5px;color:#878e83;'>"
							. tr("READ_ON_LINE", "<a href='" . $row_config_globale['base_url'] . $tPath . "online.php?i=$msg_id&list_id=$list_id&email_addr="
							. $tracked_mail . "&h=" . $addr[$i]['hash'] . "'>") . "<br/>"
							. tr("ADD_ADRESS_BOOK", $sender_email) . "<br/>";
				$unsubLink = $headtrc . tr("UNSUBSCRIBE_LINK", "<a href='" . $row_config_globale['base_url'] . $tPath
							. "subscription.php?i=$msg_id&list_id=$list_id&op=leave&email_addr=" . $tracked_mail
							. "&h=" . $addr[$i]['hash'] . "' style='' target='_blank'>")
							. $trac
							. "</div></body></html>";
			} else {
				$body = tr("READ_ON_LINE", "<a href='" . $row_config_globale['base_url'] . $tPath . "online.php?i=$msg_id&list_id=$list_id&email_addr=" . $tracked_mail . "&h=" . $addr[$i]['hash'] . "'>") . "<br/>";
				$body .= tr("ADD_ADRESS_BOOK", $sender_email) . "<br/>";
				$unsubLink = $row_config_globale['base_url'] . $tPath . "subscription.php?i=" . $msg_id . "&list_id=$list_id&op=leave&email_addr=" . urlencode($tracked_mail) . "&h=" . $addr[$i]['hash'];
			}
			$subject       = (strtoupper($row_config_globale['charset']) == "UTF-8" ? $subject : iconv("UTF-8", $row_config_globale['charset'], $subject));
			$body          = $message . $unsubLink ;
			$mail->Subject = $subject;
			$mail->msgHTML($body);
			// https://github.com/PHPMailer/PHPMailer/issues/892
			// dkim=fail (body hash did not verify)
			$htmlMsg = "";
			$lines = explode("\n", $body);
			foreach ($lines as $line) $htmlMsg .= trim($line)."\n";
			$mail->msgHTML($htmlMsg);
			@set_time_limit(300);
			$ms_err_info = '';
			if (!$mail->Send()) {
				$view_last_send_mails .= ' <b>' . tr("MAIL_IN_ERROR") . '</b>';
				$cnx->query("UPDATE " . $row_config_globale['table_send'] . "
								SET error=error+1
							WHERE `id_mail`='" . $msg_id . "'
								AND `id_list`='" . $list_id . "'");
				$ms_err_info = $mail->ErrorInfo;
				$cnx->query("INSERT INTO " . $row_config_globale['table_email_deleted'] . "
					(id,email,list_id,hash,error,status,type,categorie,short_desc,long_desc,campaign_id)
					SELECT id,email,list_id,hash,'Y',NULL,'',NULL,'','" . CleanInput($ms_err_info) . "','" . $msg_id . "'
						FROM " . $row_config_globale['table_email'] . "
							WHERE email='" . $addr[$i]['email'] . "'
								AND list_id='" . $list_id . "'");
				$cnx->query("DELETE FROM " . $row_config_globale['table_email'] . "
							WHERE email='" . $addr[$i]['email'] . "'
								AND list_id='" . $list_id . "'");
				$daylogmsg = date("Y-m-d H:i:s") . " : envoi à " . $addr[$i]['email'] . " en erreur $ms_err_info\n";
				fwrite($daylog, $daylogmsg, strlen($daylogmsg));
			} else {
				$view_last_send_mails .= ' <b>OK</b>';
				$cnx->query("UPDATE " . $row_config_globale['table_email'] . "
							SET campaign_id='" . $msg_id . "'
						WHERE email='" . $addr[$i]['email'] . "'
							AND list_id='" . $list_id . "'");
				$cnx->query("UPDATE " . $row_config_globale['table_send'] . "
							SET cpt=cpt+1
						WHERE `id_mail`='" . $msg_id . "'
							AND `id_list`='" . $list_id . "'");
				$ms_err_info = 'OK';
				$daylogmsg   = date("Y-m-d H:i:s") . " : envoi à " . $addr[$i]['email'] . " OK\n";
				fwrite($daylog, $daylogmsg, strlen($daylogmsg));
			}
			$view_last_send_mails .= '<br/>';
			$cnx->query("UPDATE " . $row_config_globale['table_send_suivi'] . "
						SET nb_send=nb_send+1,last_id_send=" . $addr[$i]['id'] . "
					WHERE `msg_id`='" . $msg_id . "' AND `list_id`='" . $list_id . "'");
			$endtimesend = microtime(true);
			$time_info   = substr(($endtimesend - $begintimesend), 0, 5);
			$errstr      = date("Y-m-d H:i:s") . "\tID: " . $addr[$i]['id'] . "\t" . $time_info . "\t\t " . $ms_err_info . " \t" . $addr[$i]['email'] . "\r\n";
			if (!$dontlog) {
				fwrite($handler, $errstr, strlen($errstr));
			}
			$daylogmsg = date("Y-m-d H:i:s") . " : envoi à " . $addr[$i]['email'] . " OK\n";
			fwrite($daylog, $errstr, strlen($errstr));
		}
		$end   = microtime(true);
		$tts   = substr(($end - $start), 0, 5);
		$begin = $begin + $limit;
		if ($begin < $sn) {
			$arr = array(
				'step' => 'send',
				'error' => $error,
				'begin' => $begin,
				'list_id' => $list_id,
				'msg_id' => $msg_id,
				'sn' => $sn,
				'token' => $token,
				'pct' => number_format((($begin / $sn) * 100), 2),
				'TTS' => $tts,
				'force' => false,
				'view_last_send_mails' => $view_last_send_mails
			);
			echo json_encode($arr);
			$cnx->query("UPDATE " . $row_config_globale['table_send_suivi'] . "
					SET tts=tts+'" . $tts . "',last_id_send='" . $last_id_send . "'
				WHERE list_id='" . $list_id . "' 
					AND msg_id='" . $msg_id . "'");
		} else {
			clearstatcache();
			if (file_exists('logs/__SEND_PROCESS__' . $list_id . '.pid')) {
				if ( unlink('logs/__SEND_PROCESS__' . $list_id . '.pid')===TRUE ) {
					$result_tts_for_sending = $cnx->query('SELECT tts FROM ' . $row_config_globale['table_send_suivi'] . ' 
							WHERE msg_id=' . $msg_id . '
								AND list_id=' . $list_id )->fetch(PDO::FETCH_ASSOC);
					$result_count_for_sending = $cnx->query('SELECT cpt,error FROM ' . $row_config_globale['table_send'] . ' 
							WHERE id_mail=' . $msg_id . '
								AND id_list=' . $list_id )->fetch(PDO::FETCH_ASSOC);
					$finalmsg = "------------------------------------------------------------\r\n";
					$finalmsg .= "Finished at " . date("Y-m-d H:i:s") . "\r\n";
					$finalmsg .= "============================================================\r\n";
					$finalmsg .= "Temps utilisateur (en secondes)     : " . $result_tts_for_sending['tts'] . "\n";
					$finalmsg .= "Mails envoyés                       : " . $result_count_for_sending['cpt'] . "\n";
					$finalmsg .= "Mails en erreur                     : " . $result_count_for_sending['error'] . "\n";
					if (!$dontlog) {
						fwrite($handler, $finalmsg, strlen($finalmsg));
						fclose($handler);
						$finalmsg = '' ;
					}
				}
			}
			if ($_SESSION['dr_log'] == 'Y') {
				loggit($_SESSION['dr_id_user'] . '.log', $_SESSION['dr_id_user'] . ' a fini un envoi de campagne "' . $subject . '" par "' . $sender_email . '" en ajax');
			}
			$daylogmsg = date("Y-m-d H:i:s") . " : fin globale de l envoi du message $msg_id, sujet \"" . $subject . "\", sur liste " . $list_id . "\n";
			fwrite($daylog, $daylogmsg, strlen($daylogmsg));
			$arr = array(
				'step' => 'send',
				'error' => $error,
				'begin' => $sn,
				'list_id' => $list_id,
				'msg_id' => $msg_id,
				'encode' => $encode,
				'sn' => $sn,
				'token' => $token,
				'pct' => 100,
				'TTS' => $tts,
				'view_last_send_mails' => $view_last_send_mails,
				'force' => false
			);
			echo json_encode($arr);
		}
		fclose($daylog);
		break;
	default:
		$message	= $_SESSION['message'];
		$subject	= $_SESSION['subject'];
		$format		= $_SESSION['format'];
		$sender_email	= $_SESSION['sender_email'];
		$draft		= $_SESSION['draft'];
		$preheader	= $_SESSION['preheader'];
		if ($_SESSION['dr_log'] == 'Y') {
			loggit($_SESSION['dr_id_user'] . '.log', $_SESSION['dr_id_user'] . ' a commencé un envoi de campagne "' . $subject . '" par "' . $sender_email . '" en ajax');
		}
		$date    = date("Y-m-d H:i:s");
		$msg_id  = save_message($cnx, $row_config_globale['table_archives'], $subject, $format, $message, $date, $list_id, $sender_email, $draft, $preheader);
		$dontlog = 0;
		if (!$handler = @fopen('logs/list' . $list_id . '-msg' . $msg_id . '.txt', 'a+')) {
			$dontlog = 1;
		}
		$daylog    = @fopen('logs/daylog-' . date("Y-m-d") . '.txt', 'a+');
		$daylogmsg = $date . " : message sauvegardé sous Numéro d'envoi : $msg_id\n";
		fwrite($daylog, $daylogmsg, strlen($daylogmsg));
		$cnx->query("UPDATE " . $row_config_globale['table_upload'] . "
				SET msg_id=" . $msg_id . "
				WHERE list_id=" . $list_id . "
				AND msg_id=0");
		$daylogmsg = "\r\n**********************************************************\r\n" . $date . " : initialisation envoi message $msg_id liste $list_id\n";
		fwrite($daylog, $daylogmsg, strlen($daylogmsg));
		touch('logs/__SEND_PROCESS__' . $list_id . '.pid');
		$num = get_newsletter_total_subscribers($cnx, $row_config_globale['table_email'], $list_id, $msg_id);
		$cnx->query("INSERT into " . $row_config_globale['table_send'] . " (`id_mail`, `id_list`, `cpt`)
				VALUES ('" . $msg_id . "','" . $list_id . "','0')");
		$cnx->query("INSERT into " . $row_config_globale['table_send_suivi'] . " (`list_id`, `msg_id`,`nb_send`, `total_to_send`, `tts`)
				VALUES ('" . $list_id . "','" . $msg_id . "',0,'" . $num . "',0)");
		$errstr = "=GLOBAL=ENVIRONNEMENT=======================================\r\n";
		if (version_compare(PHP_VERSION, '5.3.0', '>')) {
			$errstr .= "PHP : " . phpversion() . " " . tr("OK_BTN") . "\r\n";
		} else {
			$errstr .= "PHP : " . phpversion() . " " . tr("INSTALL_OBSOLETE") . "<\r\n";
		}
		if (extension_loaded('imap')) {
			$errstr .= "imap " . tr("OK_BTN") . "\r\n";
		} else {
			$errstr .= "imap " . tr("NOT_FOUND") . "\r\n";
		}
		if (extension_loaded('curl')) {
			$errstr .= "curl " . tr("OK_BTN") . "\r\n";
		} else {
			$errstr .= "curl " . tr("NOT_FOUND") . "\r\n";
		}
		if (is_exec_available()) {
			$errstr .= "exec " . tr("OK_BTN") . "\r\n";
		} else {
			$errstr .= "exec " . tr("NOT_FOUND") . "\r\n";
		}
		$errstr .= "============================================================\r\n";
		$errstr .= date("d M Y") . "\r\n";
		$errstr .= "Started at " . date("Y-m-d H:i:s") . "\r\n";
		$errstr .= "Date \t\t\t\t ID \t\t  Time \t\t Status \t\t Recipient  \r\n";
		$errstr .= "------------------------------------------------------------\r\n";
		if (!$dontlog) {
			fwrite($handler, $errstr, strlen($errstr));
			fclose($handler);
		}
		fwrite($daylog, $errstr, strlen($errstr));
		fclose($daylog);
		DelMsgTemp($cnx, $list_id, $row_config_globale['table_sauvegarde']);
		echo json_encode(array(
			'step' => 'send',
			'error' => 0,
			'begin' => 0,
			'list_id' => (($list_id) + 0),
			'msg_id' => (($msg_id) + 0),
			'encode' => $encode,
			'sn' => (($num) + 0),
			'token' => $token,
			'pct' => 0,
			'force' => 'continue'
		));
		break;
}
