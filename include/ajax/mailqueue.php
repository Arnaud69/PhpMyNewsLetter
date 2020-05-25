<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: text/plain'); 
if(!file_exists("../config.php")) {
	header("Location:../../install.php");
	exit;
} else {
	include("../../_loader.php");
	$token=(empty($_SESSION['_token'])?"":$_SESSION['_token']);
	if(!isset($token) || $token=="")$token=(empty($_GET['token'])?"":$_GET['token']);
	if(!tok_val($token)){
		header("Location:../../login.php?error=2");
		exit;
	}
}

if($exec_available){
	$results = array();
	$current_object = null;
	$old_locale = getlocale(LC_ALL);
	setlocale(LC_ALL, 'C');
	$current_object = array();
	$pipe = popen($mailq_path, 'r');
	while($pipe) {
		$line = fgets($pipe);
		if(trim($line)=='Mail queue is empty'){
			echo '<button type="button" class="btn btn-primary btn-sm">'.tr("NO_MAIL_IN_PROCESS").'</button>';
			pclose($pipe);
			setlocale(LC_ALL, $old_locale);
			exit(1);
		} else {
			if ($line === false)break;
			if (strncmp($line, '-', 1) === 0)continue;
			$line = trim($line);
			$res = preg_match('/(\w+)\*{0,1}\s+(\d+)\s+(\w+\s+\w+\s+\d+\s+\d+:\d+:\d+)\s+([^ ]+)/', $line, $matmq);
			$email_en_mq = @$matmq[4];
			$emailmq = trim($row_config_globale['admin_email']);
			$exp = "/^(.*)@(.*)$/";
			preg_match($exp, $emailmq, $mailmq);
			preg_match($exp, $email_en_mq , $mailFoundmq);
			if ($res && $mailFoundmq[2]==$mailmq[2]) {
				$current_object[] = array(
					'id' => $matmq[1],
					'size' => intval($matmq[2]),
					'date' => strftime($matmq[3]),
					'sender' => $matmq[4],
					'failed' => false,
					'recipients' => ''
				);
			}
		}
	}
	pclose($pipe);
	setlocale(LC_ALL, $old_locale);
	$mails_en_cours = count($current_object);
	if($mails_en_cours>0){
		echo '<a href="?page=manager_mailq&token='.$token.'" title="'.tr("PENDING_MAILS_MANAGEMENT").'" class="clearbtn btn btn-warning btn-sm">'.$mails_en_cours.' '.tr("PENDING_MAILS").'</a>';
	} else {
		echo '<button type="button" class="btn btn-primary btn-sm">'.tr("NO_MAIL_IN_PROCESS").'</button>';
	}
}


