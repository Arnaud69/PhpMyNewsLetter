<?php
if(!file_exists("config.php")) {
	header("Location:../install.php");
	exit;
} else {
	include("../_loader.php");
}
$token=(empty($_GET['token'])?"":$_GET['token']);
if(!isset($token) || $token=="")$token=(empty($_GET['token'])?"":$_GET['token']);
if (isset($_POST['token'])) {
	$token = $_POST['token'];
} elseif (isset($_GET['token'])) {
	$token = $_GET['token'];
} else {
	$token = '';
}
if (!tok_val($token)) {
	quick_Exit();
	die();
}
extract($_GET,EXTR_OVERWRITE);
echo '<style type="text/css">body,td,th{font-size:12px;font-family:Arial,Helvetica,sans-serif;}</style>
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div><div class="modal-body">
<code>';
$temp_color='#FFFFFF';
switch($t){
	case 'smtp':
		$log="../logs/smtp_debug_".$list_id.".log";
			if(is_file($log)){
				$fp=fopen($log,'r');
				while(!feof($fp)){
					$ligne=fgets($fp,4096); // 4096 à modifier après confirmation MAX RECSIZE
					echo '<div id="item_lu" style="background-color:'.$temp_color.';color:#000;">'
						. str_replace(' ','&nbsp;',trim($ligne)).'<br>';
					if($temp_color=='#ECE9D8'){$temp_color='#FFFFFF';}else{$temp_color='#ECE9D8';}
					echo '</div>'."\n";
				}
			}	
	break;
	case 'd':
		$log="../logs/daylog-$day.txt";
		if(is_file($log)){
			$fp=fopen($log,'r');
			while(!feof($fp)){
				$ligne=fgets($fp,4096); // 4096 à modifier après confirmation MAX RECSIZE
				echo '<div id="item_lu" style="background-color:'.$temp_color.';color:#000;">'
					. str_replace(' ','&nbsp;',trim($ligne)).'<br>';
				if($temp_color=='#ECE9D8'){$temp_color='#FFFFFF';}else{$temp_color='#ECE9D8';}
				echo '</div>'."\n";
			}
		}
	break;
	case 'l':
		$log="../logs/list$list_id-msg$id_mail.txt";
		if(is_file($log)){
			$fp=fopen($log,'r');
			while(!feof($fp)){
				$ligne=fgets($fp,4096); // 4096 à modifier après confirmation MAX RECSIZE
				echo '<div id="item_lu" style="background-color:'.$temp_color.';color:#000;">'
					. str_replace(' ','&nbsp;',trim($ligne)).'<br>';
				if($temp_color=='#ECE9D8'){$temp_color='#FFFFFF';}else{$temp_color='#ECE9D8';}
				echo '</div>'."\n";
			}
		}
	break;
	case 'u':
		$log="../logs/$u.log";
		if(is_file($log)){
			$fp=fopen($log,'r');
			while(!feof($fp)){
				$ligne=fgets($fp,4096); // 4096 à modifier après confirmation MAX RECSIZE
				echo '<div id="item_lu" style="background-color:'.$temp_color.';color:#000;">'
					. str_replace(' ','&nbsp;',trim($ligne)).'<br>';
				if($temp_color=='#ECE9D8'){$temp_color='#FFFFFF';}else{$temp_color='#ECE9D8';}
				echo '</div>'."\n";
			}
		}
	break;
	default:
		echo 'Oups ! This is a mistake...';
	break;
}
echo '</code>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';






















