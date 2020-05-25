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
$list_id = '' ;
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
	if ( validEmailAddress ( $SenderEmail ) && $list_id == ''  ) {
		$list_id = @current ( $cnx->query("SELECT list_id 
				FROM " . $row_config_globale['table_listsconfig'] . "  
				WHERE from_addr='" . $SenderEmail . "'
				LIMIT 1")->fetch() );
		if ($list_id==0) {
			$code=398;
			$ReturnArray[] = array('code'=>$code);
			echo json_encode($ReturnArray);
			exit;
		}
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
		}
	}
	if ( $code == 0 && validEmailAddress($ContactEmail)) {
		if ( $list_id != '' && is_numeric( $list_id ) ) {
			$x = $cnx->query("SELECT * 
				FROM " . $row_config_globale['table_email'] . " 
					WHERE email='" . $ContactEmail . "'
						AND list_id=" . $list_id )->fetch();
			if ( $x ){
				if ( $cnx->query("DELETE 
					FROM " . $row_config_globale['table_email'] . " 
						WHERE email='" . $ContactEmail . "' 
					AND list_id=" . $x['list_id'] . "
					AND hash='" . $x['hash'] . "'") ) {
					if ( $cnx->query("INSERT INTO " . $row_config_globale['table_email_deleted'] . " (list_id,email,hash,type) 
						 VALUES (" . $x['list_id'] . ",'" . $ContactEmail . "','" . $x['hash'] . "','by_admin')") ) {
						 	$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$x['list_id'],'result'=>200);
					} else {
						$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$x['list_id'],'result'=>249);
					}
				}else {
					$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$x['list_id'],'result'=>239);
				}
			} else {
				$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'result'=>500);
			}
		} elseif ( $list_id == '' ) {
			$x = $cnx->query("SELECT * 
				FROM " . $row_config_globale['table_email'] . " 
					WHERE email='" . $ContactEmail . "'")->fetchAll(PDO::FETCH_ASSOC);
			if ( count ($x) > 0 ) {
				foreach($x as $row){
					if ( $cnx->query("DELETE 
						FROM " . $row_config_globale['table_email'] . " 
							WHERE email='" . $ContactEmail . "' 
						AND list_id=" . $row['list_id'] . "
						AND hash='" . $row['hash'] . "'") ) {
						if ( $cnx->query("INSERT INTO " . $row_config_globale['table_email_deleted'] . " (list_id,email,hash,type) 
							 VALUES (" . $row['list_id'] . ",'" . $ContactEmail . "','" . $row['hash'] . "','by_admin')") ) {
							 	$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$row['list_id'],'result'=>200);
						} else {
							$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$row['list_id'],'result'=>249);
						}
					}else {
						$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$item['list_id'],'result'=>239);
					}
				}
			} else {
				$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>'','result'=>500);
			}
		}
	} else {
		$ReturnArray[] = array('ContactEmail'=>$ContactEmail,'liste'=>$list_id,'result'=>599);
	}
	echo json_encode($ReturnArray);
	exit;
} else {
	$code=198;
	$ReturnArray[] = array('code'=>$code);
	echo json_encode($ReturnArray);
	exit;
}