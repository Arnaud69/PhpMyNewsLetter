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
	if ( $code == 0 && validEmailAddress($ContactEmail)) {
		$x = $cnx->query("SELECT list_id 
				FROM " . $row_config_globale['table_email'] . " 
					WHERE email='" . $ContactEmail . "'")->fetchAll(PDO::FETCH_ASSOC);
			if ( count ($x) > 0 ) {
				foreach($x as $row){
					$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$row['list_id']);
				}
			} else {
				$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'result'=>500);
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