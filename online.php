<?php
$i = (!empty($_GET['i'])) ? intval($_GET['i']) : false;
$l = (!empty($_GET['list_id']) ? intval($_GET['list_id']) : false);
$e = (!empty($_GET['email_addr']) ? $_GET['email_addr'] : false);
$h = (!empty($_GET['h'])) ? $_GET['h'] : false;
if(!$i && !$l && !$e && !$h) {
	header("Location:/");
} else {
	include("_loader.php");
	$tPath = ($row_config_globale['path'] == '' ? '/' : '/'.$row_config_globale['path']);
	$tPath = str_replace('//','/',$tPath);
	if ($h=='fake_hash') {
		$msg = get_message_preview($cnx, $row_config_globale['table_sauvegarde'], $l);
		$messageTemp = stripslashes($msg['textarea']);
		$subject = $msg['subject'];
		$trac  = "";
	} else {
		$msg = get_message($cnx, $row_config_globale['table_archives'], $i);
		$messageTemp = stripslashes($msg['message']);
		$trac  = '<img src="' . $row_config_globale['base_url'] . $tPath . 'trc.php?i= ' . $i . '&h=' . $h . ' width="1" />';
		$subject = $msg['subject'];
	}
	$newsletter = getConfig($cnx, $l, $row_config_globale['table_listsconfig']);
	/*$body = '<html><head><meta charset="utf-8"/></head><body>
		<div align="center" style="font-size:10pt;font-family:arial,helvetica,sans-serif;padding-bottom:5px;color:#878e83;">'
		. tr('READ_ON_LINE', '<a href="' . $row_config_globale['base_url'] . $tPath . 'online.php?i=' . $i . '&list_id=' . $l . '&email_addr=' . $e . '&h=' . $h . '">') . '<br/>'
		. tr("ADD_ADRESS_BOOK", $newsletter['from_addr'] ) 
		. '<br/><hr noshade="" color="#D4D4D4" width="90%" size="1"></div>';*/
	$messageTemp       = str_replace('*|MC_PREVIEW_TEXT|*','',$messageTemp);
	if (strpos($messageTemp, '</style>') === false) {
		$messageTemp = '<style type="text/css"></style>' . $messageTemp;
	}
	if (strpos($messageTemp, '</title>') === false) {
		$messageTemp = '<title>[[SUBJECT]]</title>' . $messageTemp;
	} elseif (strpos($messageTemp, '<title>[[SUBJECT]]</title>') === false && strpos($messageTemp, '<title>') !== false) {
		$messageTemp = preg_replace("/<title>(.*)<\/title>/","",$messageTemp,1);
		$messageTemp = '<title>[[SUBJECT]]</title>' . $messageTemp;
	}
	
	$header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	$messageTemp       = str_replace('<title>[[SUBJECT]]</title>', $header.'<title>' . $subject . '</title></head><body>', $messageTemp);
	$new_url = 'href="' . $row_config_globale['base_url'] . $tPath . 'r.php?m=' . $i .'&h=' . $h . '&l=' . $l . '&r=';
	$message   = preg_replace_callback('/href="(http[s]?:\/\/)([^"]+)"/', function($matches) {
		global $new_url;
		return $new_url . (urlencode(@$matches[1] . $matches[2])) . '"';
	}, $messageTemp);
	
	$unsubLink = "<br/><div align='center' style='padding-top:10px;font-size:10pt;font-family:arial,helvetica,sans-serif;padding-bottom:10px;color:#878e83;'><hr noshade='' color='#D4D4D4' width='90%' size='1'>"
		.tr("UNSUBSCRIBE_LINK","<a href='" . $row_config_globale['base_url'] . $tPath . "subscription.php?i=$i&list_id=$l&op=leave&email_addr=$e&h=$h' style='' target='_blank'>")
		."</div></body></html>";
	echo $message . $unsubLink;
}
