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
	if ( empty($_GET['list_id'] ) ) {
		if ( empty($_POST['list_id']) ) {
			$list_id = '';
		} else {
			$list_id = $_POST['list_id'];
		}
	}  else {
		$list_id = $_GET['list_id'];
	}
	if ( empty($_GET['SenderEmail'] ) ) {
		if ( empty($_POST['SenderEmail']) ) {
			$SenderEmail = '';
		} else {
			$SenderEmail=$_POST['SenderEmail'];
		}
	} else {
		$SenderEmail=$_GET['SenderEmail'];
	}
	if ( validEmailAddress ( $SenderEmail ) && $list_id != ''  ) {
		$list_id = @current ( $cnx->query("SELECT list_id 
				FROM " . $row_config_globale['table_listsconfig'] . "  
				WHERE from_addr='" . $SenderEmail . "'
					AND list_id=" . $list_id . "
				LIMIT 1")->fetch() );
		if ($list_id==0) {
			$code=398;
			$ReturnArray[] = array('code'=>$code);
			echo json_encode($ReturnArray);
			exit;
		} else {
			$SQL = "SELECT * FROM " . $row_config_globale['table_crontab'] . " WHERE etat='running' AND list_id='" . $list_id . "'" ;
		}
	} elseif ( validEmailAddress ( $SenderEmail ) && $list_id == ''  ) {
		$list_id = @current ( $cnx->query("SELECT list_id 
				FROM " . $row_config_globale['table_listsconfig'] . "  
				WHERE from_addr='" . $SenderEmail . "'
				LIMIT 1")->fetch() );
		if ($list_id==0) {
			$code=298;
			$ReturnArray[] = array('code'=>$code);
			echo json_encode($ReturnArray);
			exit;
		} else {
			$SQL = "SELECT * FROM " . $row_config_globale['table_crontab'] . " WHERE etat='running' AND list_id='" . $list_id . "'" ;
		}
	} elseif ( $SenderEmail == '' && $list_id != '' && is_numeric( $list_id ) ) {
		$list_id = @current ( $cnx->query("SELECT list_id 
				FROM " . $row_config_globale['table_listsconfig'] . "  
				WHERE list_id='" . $list_id . "'
				LIMIT 1")->fetch() );
		if ($list_id==0) {
			$code=298;
			$ReturnArray[] = array('code'=>$code);
			echo json_encode($ReturnArray);
			exit;
		} else {
			$SQL = "SELECT * FROM " . $row_config_globale['table_crontab'] . " WHERE etat='running' AND list_id='" . $list_id . "'" ;
		}
	} elseif ( $SenderEmail == '' && $list_id == '' ) {
		$SQL = "SELECT * FROM " . $row_config_globale['table_crontab'] . " WHERE etat='running'" ;
	}
	if ( $code == 0 ) {
		$x = $cnx->query($SQL)->fetchAll(PDO::FETCH_ASSOC);
		if ( count ($x) > 0 ) {
			foreach($x as $row){
				// on va chercher l'état de l'envoi :
				$state_sending = ( $cnx->query("SELECT * 
					FROM " . $row_config_globale['table_send'] . "  
					WHERE id_list='" . $row['list_id'] . "' AND id_mail='" . $row['msg_id'] . "' 
					LIMIT 1")->fetch() );
				$ReturnArray[] = array('list_id'=>intval($row['list_id']),'msg_id'=>intval($row['msg_id']),'job_id'=>$row['job_id'],
					'subject'=>$row['mail_subject'],'schedule_date'=>$row['date'],'send'=>intval($state_sending['cpt']),
					'error'=>intval($state_sending['error']),'leave'=>intval($state_sending['leave']),'result'=>200);
			}
		} else {
			$ReturnArray[] = array('result'=>500);
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