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
	if ( empty($_GET['msg_id'] ) ) {
		if ( empty($_POST['msg_id']) ) {
			$code = 499;
			$ReturnArray[] = array('code'=>$code);
			echo json_encode($ReturnArray);
			exit;
		} else {
			$msg_id = $_POST['msg_id'];
		}
	} else {
		$msg_id = $_GET['msg_id'];
	}
	if ( $code == 0 && is_numeric($msg_id) && $msg_id>0) {
		$x = $cnx->query("SELECT id,subject 
				FROM " . $row_config_globale['table_archives'] . " 
					WHERE id=" . $msg_id )->fetchAll(PDO::FETCH_ASSOC);
			if ( count ($x) > 0 ) {
				foreach($x as $row){
					$ReturnArray[] = array('msg_id'=>$row['id'] , 'Subject'=>$row['subject']);
				}
			} else {
				$ReturnArray[] = array('msg_id'=>$msg_id , 'result'=>500);
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