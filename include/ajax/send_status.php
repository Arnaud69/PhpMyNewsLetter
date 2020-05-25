<?php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: text/plain'); 

if(!file_exists("../config.php")) {
	header("Location:../../install.php");
	exit;
} else {
	include("../../_loader.php");
	$token=(empty($_POST['token'])?"":$_POST['token']);
	if(!isset($token) || $token=="")$token=(empty($_GET['token'])?"":$_GET['token']);
	if(!tok_val($token)){
		header("Location:../../login.php?error=2");
		exit;
	}
}
!empty($_POST['action']) ? $action=$_POST['action'] : die('UNKNOWN_STATUS');
!empty($_POST['list_id']) ? $list_id=$_POST['list_id'] : die('UNKNOWN_STATUS');
switch ($action){
	case 'stop':
		if (file_exists("../../logs/__SEND_PROCESS__" . $list_id . ".pid" )){
			if (unlink("../../logs/__SEND_PROCESS__" . $list_id . ".pid" )) {	  
				echo '<div class="alert alert-success">' . tr("SEND_CORRECTLY_STOPPED") . '</div>';
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a stoppé l\'envoi planifié de la liste ' . $list_id );
				}
			} else {
				echo '<div class="alert alert-error">' . tr("SEND_STOP_ERROR") . '</div>';
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a rencontré une erreur lors de l\'arrêt de l\'envoi de la liste ' . $list_id );
				}	
			}	
		} elseif (file_exists("../../logs/__SEND_PROCESS__" . $list_id . ".paused" )){
			if (unlink("../../logs/__SEND_PROCESS__" . $list_id . ".paused" )) {	  
				echo '<div class="alert alert-success">' . tr("SEND_CORRECTLY_STOPPED") . '</div>';
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a stoppé l\'envoi planifié de la liste ' . $list_id );
				}
			} else {
				echo '<div class="alert alert-error">' . tr("SEND_STOP_ERROR") . '</div>';
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a rencontré une erreur lors de l\'arrêt de l\'envoi de la liste ' . $list_id );
				}	
			}	
		}else {
			echo '<div class="alert alert-error">' . tr("UNKNOW_SEND") . '</div>';
		}
	break;
	case 'pause':
		if (file_exists("../../logs/__SEND_PROCESS__" . $list_id . ".pid" )){
			if (rename("../../logs/__SEND_PROCESS__" . $list_id . ".pid","../../logs/__SEND_PROCESS__" . $list_id . ".paused"  )) {	  
				echo '<a class="btn btn-danger btn-sm" onclick="ChangeStatus(\'stop\',' . $list_id . ')" data-toggle="tooltip" title="'
				. tr("CLICK_STOP_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_STOP_SEND") . ' ?\')">
				<span class="glyphicon glyphicon-remove-sign"></span></a>
				<a class="btn btn-success btn-sm" onclick="ChangeStatus(\'restart\',' . $list_id . ')" data-toggle="tooltip" title="'
				. tr("CLICK_RESTART_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_RESTART_SEND") . ' ?\')">
				<span class="glyphicon glyphicon-play"></span></a>
				<a class="btn btn-warning btn-sm" onclick="ChangeStatus(\'status\',' . $list_id . ')" data-toggle="tooltip" title="'
				. tr("SEND_STATUS") . ' ?" ><span class="glyphicon glyphicon-question-sign"></span></a>';
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a mis en pause l\'envoi planifié de la liste ' . $list_id );
				}
			} else {
				echo '<div class="alert alert-error">' . tr("SEND_PAUSED_ERROR") . '</div>';	
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a rencontré une erreur lors de la mise en pause de l\'envoi de la liste ' . $list_id );
				}
			}	
		} else {
			echo '<div class="alert alert-error">' . tr("UNKNOW_SEND") . '</div>';
		}
	break;
	case 'restart':
		if (file_exists("../../logs/__SEND_PROCESS__" . $list_id . ".paused" )){
			if (rename("../../logs/__SEND_PROCESS__" . $list_id . ".paused","../../logs/__SEND_PROCESS__" . $list_id . ".pid"  )) {	  
				echo '<a class="btn btn-danger btn-sm" onclick="ChangeStatus(\'stop\',' . $list_id . ')" data-toggle="tooltip" title="'
				. tr("CLICK_STOP_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_STOP_SEND") . ' ?\')">
				<span class="glyphicon glyphicon-remove-sign"></span></a>
				<a class="btn btn-primary btn-sm" onclick="ChangeStatus(\'pause\',' . $list_id . ')" data-toggle="tooltip" title="'
				. tr("CLICK_PAUSE_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_PAUSE_SEND") . ' ?\')">
				<span class="glyphicon glyphicon-pause"></span></a>
				<a class="btn btn-warning btn-sm" onclick="ChangeStatus(\'status\',' . $list_id . ')" data-toggle="tooltip" title="'
				. tr("SEND_STATUS") . ' ?" ><span class="glyphicon glyphicon-question-sign"></span></a>';
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a réactivé l\'envoi planifié de la liste ' . $list_id );
				}
			} else {
				echo '<div class="alert alert-error">' . tr("SEND_PAUSED_ERROR") . '</div>';
				if($_SESSION['dr_log']=='Y') {
					loggit($_SESSION['dr_id_user'].'.log', date("H:i:s") . ' : ' . $_SESSION['dr_id_user'] . ' a rencontré une erreur lors de la relance de l\'envoi de la liste ' . $list_id );
				}	
			}	
		} else {
			echo '<div class="alert alert-error">' . tr("UNKNOW_SEND") . '</div>';
		}
	break;
	case 'status':
		if (file_exists("../../logs/__SEND_PROCESS__" . $list_id . ".paused" )){
			echo '<script>alert("SEND is waiting to restart");</script>
			<a class="btn btn-danger btn-sm" onclick="ChangeStatus(\'stop\',' . $list_id . ')" data-toggle="tooltip" title="'
			. tr("CLICK_STOP_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_STOP_SEND") . ' ?\')">
			<span class="glyphicon glyphicon-remove-sign"></span></a>
			<a class="btn btn-success btn-sm" onclick="ChangeStatus(\'restart\',' . $list_id . ')" data-toggle="tooltip" title="'
			. tr("CLICK_RESTART_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_RESTART_SEND") . ' ?\')">
			<span class="glyphicon glyphicon-play"></span></a>
			<a class="btn btn-warning btn-sm" onclick="ChangeStatus(\'status\',' . $list_id . ')" data-toggle="tooltip" title="'
			. tr("SEND_STATUS") . ' ?" ><span class="glyphicon glyphicon-question-sign"></span></a>';
		} elseif (file_exists("../../logs/__SEND_PROCESS__" . $list_id . ".pid" )){
			echo '<script>alert("SEND is running");</script>
			<a class="btn btn-danger btn-sm" onclick="ChangeStatus(\'stop\',' . $list_id . ')" data-toggle="tooltip" title="'
			. tr("CLICK_STOP_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_STOP_SEND") . ' ?\')">
			<span class="glyphicon glyphicon-remove-sign"></span></a>
			<a class="btn btn-primary btn-sm" onclick="ChangeStatus(\'pause\',' . $list_id . ')" data-toggle="tooltip" title="'
			. tr("CLICK_PAUSE_SEND") . ' ?" onclick="return confirm(\'' . tr("WARNING_PAUSE_SEND") . ' ?\')">
			<span class="glyphicon glyphicon-pause"></span></a>
			<a class="btn btn-warning btn-sm" onclick="ChangeStatus(\'status\',' . $list_id . ')" data-toggle="tooltip" title="'
			. tr("SEND_STATUS") . ' ?" ><span class="glyphicon glyphicon-question-sign"></span></a>';
		} else {
			echo '<div class="alert alert-error">' . tr("UNKNOW_SEND") . '</div>';
		}
	break;
}
			

