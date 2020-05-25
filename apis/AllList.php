<?php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8'); 
if(!file_exists("../include/config.php")) {
	header("Location:../install.php");
	exit;
} else {
	include("../_loader.php");
}
$code=0;
$ReturnArray = array();
$ReturnArray = array();
if ( $apis_available == 1 ) {
	if ( empty($_GET['key'] ) ) {
		if ( empty($_POST['key']) ) {
			$code = 199;
		} else {
			if ( $_POST['key']==$api_key ) {
				// continue
			} else {
				$code = 199;
				$ReturnArray[] = array('code'=>$code);
				echo json_encode($ReturnArray);
				exit;
			}
		}
	} else {
		if ( $_GET['key']==$api_key ) {
			// continue
		} else {
			$code = 199;
			$ReturnArray[] = array('code'=>$code);
			echo json_encode($ReturnArray);
			exit;
		}
	}
	if ( $code == 0 ) {
		$list = list_newsletter($cnx, $row_config_globale['table_listsconfig']);
		foreach($list as $item) {
			$lnl = list_newsletter_last_id_send($cnx, $row_config_globale['table_send'], $item['list_id'], $row_config_globale['table_archives']);
			$TrueSub = getSubscribersNumbers($cnx, $row_config_globale['table_email'], $item['list_id']);
			$ReturnArray[] = array('liste'=>$item['list_id'],'name'=>$item['newsletter_name'],
				'subscribers'=>$TrueSub,'last_campaign_id'=>$lnl[0]['id_mail'],'last_campaign_title'=>$lnl[0]['subject'] );
		}
		echo json_encode($ReturnArray);
		exit;
	}
} else {
	$code=198;
	$ReturnArray[] = array('code'=>$code);
	echo json_encode($ReturnArray);
	exit;
}