<?php
date_default_timezone_set('Europe/Berlin');
if(!file_exists("include/config.php")){
	header("Location:install.php");
	exit;
} else{
	include("_loader.php");
}

if(isset($_POST['token'])){$token=$_POST['token'];}elseif(isset($_GET['token'])){$token=$_GET['token'];}else{$token='';}
if(!tok_val($token)){
	quick_Exit();
}
if(empty($row_config_globale['language']))$row_config_globale['language']="english";
include("include/lang/".$row_config_globale['language'].".php");
$list_id = (!empty($_GET['list_id']) && empty($list_id)) ? (int)$_GET['list_id'] : (int)$list_id;
echo '<!DOCTYPE html>
<html lang="' . tr("LN") . '"">
<head>
	<meta charset="utf-8" />
		<title>' . tr("UPLOAD_ADD") . '</title>
		<script src="js/dropzone.min.js"></script>
		<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="css/dropzone.min.css" />
		<!--[if lte IE 8]>
		<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
		<script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/scripts.js"></script>
	</head>
<body>
	<div id="main" class="column">
		<article class="module width_full">
			<header><h3>' . tr("UPLOAD_ADD") . '</h3></header>
			<div class="module_content">
			' . tr("UPLOAD_EXPLAIN") . '
				<div id="dropzone">
					<form action="include/upload_files.php" class="dropzone dz-clickable" id="pj-upload">
						<div class="dz-default dz-message">
							<span>' . tr("UPLOAD_DROP_FILES") . '</span>
						</div>
						<input type="hidden" name="list_id" value="' . $list_id . '" />
						<input type="hidden" name="token" value="' . $token . '" />
					</form>
			</div>
		</article>
	</div>
	<script>Dropzone.options.dropzone={acceptedFiles:".*"};</script>
</body>
</html>';



















