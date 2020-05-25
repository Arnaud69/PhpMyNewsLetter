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
	if ( empty($_GET['list_id'] ) ) {
		if ( empty($_POST['list_id']) ) {
			if ( empty($_GET['SenderEmail'] ) ) {
				if ( empty($_POST['SenderEmail']) ) {
					$code = 399;
					$ReturnArray[] = array('code'=>$code);
					echo json_encode($ReturnArray);
					exit;
				} else {
					$SenderEmail = $_POST['SenderEmail'];
				}
			} else {
				$SenderEmail = $_GET['SenderEmail'];
			}
		} else {
			$list_id = $_POST['list_id'];
		}
	} else {
		$list_id = $_GET['list_id'];
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
		if ( trim ( $SenderEmail ) != '' && validEmailAddress ( $SenderEmail ) ) {
			$list_id = @current ( $cnx->query("SELECT list_id 
					FROM " . $row_config_globale['table_listsconfig'] . "  
					WHERE from_addr='" . $SenderEmail . "'
					LIMIT 1")->fetch() );
			if ( $list_id == '' ) {
				$code=300;
				$ReturnArray[] = array('code'=>$code);
				echo json_encode($ReturnArray);
				exit;
			}
		} else {
			$list_id = '' ;
		}
		if ( $list_id != '' && validEmailAddress ( $ContactEmail ) ) {
			if ( addSubscriberDirect($cnx, $row_config_globale['table_email'] , $list_id, $ContactEmail)!=false ) {
				$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$list_id,'result'=>200);
			} else {
				$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$list_id,'result'=>500);
			}
		} else {
			$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'result'=>599);
		}
		echo json_encode($ReturnArray);
		exit;
	}
	$ReturnArray[] = array('code'=>$code);
	echo json_encode($ReturnArray);
	exit;
} else {
	$code=198;
	$ReturnArray[] = array('code'=>$code);
	echo json_encode($ReturnArray);
	exit;
}
