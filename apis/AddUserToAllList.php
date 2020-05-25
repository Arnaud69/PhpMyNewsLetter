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
	if ( empty($_GET['ContactEmail'] ) ) {
		if ( empty($_POST['ContactEmail']) ) {
			$code = 499;
			$ReturnArray[] = array('code'=>$code);
			echo json_encode($ReturnArray);
			exit;
		} else {
			$ContactEmail = $_POST['ContactEmail'];
		}
	} else {
		$ContactEmail = $_GET['ContactEmail'];
	}
	if ( $code == 0 ) {
		$list = list_newsletter($cnx, $row_config_globale['table_listsconfig']);
		// puis on ajoute : function addSubscriberDirect($cnx, $table_email, $list_id, $addr)
		if ( validEmailAddress ( $ContactEmail ) ) {
			foreach($list as $item) {
				if ( addSubscriberDirect($cnx, $row_config_globale['table_email'] , $item['list_id'] , $ContactEmail)!=false ) {
					$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$item['list_id'],'result'=>200);
				} else {
					$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$item['list_id'],'result'=>500);
				}
			}
		} else {
			$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'result'=>599);
		}
	}
	echo json_encode($ReturnArray);
	exit;
} else {
	$code=198;
	$ReturnArray[] = array('code'=>$code);
	echo json_encode($ReturnArray);
	exit;
}
