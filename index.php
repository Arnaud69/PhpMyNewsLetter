<?php
if (!file_exists("include/config.php")) {
	header("Location:install.php");
	exit;
} else {
	include ("_loader.php");
}
if (isset($_POST['token'])) {
	$token = $_POST['token'];
} elseif (isset($_GET['token'])) {
	$token = $_GET['token'];
} else {
	$token = '';
}
if (!tok_val($token)) {
	quick_Exit();
}
include ("include/php/_vars.php");
include ("include/php/_actions.php");
include ("op.php");
if ( isset($_GET['forceReload']) && $_GET['forceReload']=='true') {
	include ("include/config.php");
}
if (file_exists('include/config_bounce.php')) {
	include ('include/config_bounce.php');
}
$list_name = - 1;
if (isset($_SESSION['dr_liste']) && $_SESSION['dr_liste'] > 0) {
	$list_id = $_SESSION['dr_liste'];
}
if (empty($list_id)) {
	$list_id = get_first_newsletter_id($cnx, $row_config_globale['table_listsconfig']);
}
if (!empty($list_id)) {
	$list_name = get_newsletter_name($cnx, $row_config_globale['table_listsconfig'], $list_id);
	if ($list_name == - 1) unset($list_id);
}
$list = list_newsletter($cnx, $row_config_globale['table_listsconfig']);
if (!$list && $page != "config") {
	$page = "listes";
	$l = 'c';
}
$nbDraft = getMsgDraft($cnx, $list_id, $row_config_globale['table_sauvegarde']);
echo '<!DOCTYPE html>
<html lang="' . tr("LN") . '">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>' . tr("TITLE_ADMIN_PAGE") . '</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="js/wysiwyg/jquery-1.10.2.min.js"></script>
		<script src="js/wysiwyg/jquery-ui.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
		<link href="//code.jquery.com/ui/1.12.0/themes/redmond/jquery-ui.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" crossorigin="anonymous" rel="stylesheet">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
		<!-- (Optional) Latest compiled and minified JavaScript translation files -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/i18n/defaults-' . tr("I18N_LNG") . '.min.js"></script>
		<link href="css/styles.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
			<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script src="js/jquery.growl.js"></script>
		<link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
		<script src="//gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>';
		if ($page == 'globalstats' || $page == 'listes' || $page == 'tracking' && ($_SESSION['dr_stats'] == 'Y' || $_SESSION['dr_is_admin'] == true)) {
			echo '<script src="//www.amcharts.com/lib/3/ammap.js"></script>
			<script src="//www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
			<script src="//www.amcharts.com/lib/3/themes/dark.js"></script>
			<script src="js/Chart.js/Chart.js"></script>';
		}
		if ($page == 'listes' || $page == 'compose') {
			echo '<script src="js/tinymce/tinymce.min.js"></script>';
		}
		echo '<script src="js/jquery.colorbox.js"></script>
		<script src="js/jsclock-0.8.min.js"></script>
		<script src="js/jquery.bootstrap.wizard.js"></script>
		<!-- jQuery DataTable : https://www.datatables.net/ -->
		<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<link rel="icon" type="image/png" href="favicon.png" />
		
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="?page=listes&token=' . $token . '&l=l&list_id=' . @$list_id . '"><i class="glyphicon glyphicon-home"></i>&nbsp;' .tr('HOME') .'</a></li>';
					if($_SESSION['dr_stats']=='Y'||$_SESSION['dr_is_admin']==true) {
						echo '<li><a href="?page=globalstats&token=' . $token . '&l=l&list_id=' . @$list_id . '" data-toggle="tooltip" data-placement="auto" 
							title="'.tr("ALL_STATS").'"><i class="glyphicon glyphicon-stats"></i> ' .tr('GLOBAL_STATISTICS') .'</a></li>';
					}
					if($_SESSION['dr_is_admin']==true) {
						echo '<li class="dropdown" title="'.tr("ADMIN_MANAGEMENTS").'" data-placement="right">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> ' . tr("MANAGEMENTS") . '<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="?page=config&token=' . $token . '&l=l&list_id=' . @$list_id . '" data-toggle="tooltip" data-placement="right"
									title="' . tr("MANAGEMENT_GLOBAL") . '"><i class="glyphicon glyphicon-cog"></i> ' . tr("GCONFIG_TITLE") . '</a></li>
								<li><a href="?page=manage_users&token=' . $token . '&list_id=' . $list_id . '" data-toggle="tooltip" data-placement="right" 
									title="' . tr("MANAGEMENT_USERS") . '"><i class="glyphicon glyphicon-user"></i> ' . tr("USERS_RIGHTS_MANAGEMENT") . '</a></li>
								<li><a href="?page=manage_senders&token=' . $token . '&list_id=' . $list_id . '" data-toggle="tooltip" data-placement="right" 
									title="' . tr("MANAGEMENT_SENDERS") . '"><i class="glyphicon glyphicon-cog"></i> ' . tr("SENDERS_MANAGEMENT") . '</a></li>';
								if($row_config_globale['sending_method']=='lbsmtp'){
									echo '<li><a href="?page=configsmtp&token=' . $token . '&list_id=' . $list_id . '" data-toggle="tooltip" data-placement="right" 
										title="' . tr("MANAGEMENT_SMTP") . '" "Gestion des serveurs SMTP utilisés en load balancing SMTP"><i class="glyphicon glyphicon-cog"></i> ' . tr("GCONFIG_SMTP_LB_TITLE") . '</a></li>';
								}
								echo '<li><a href="?page=backup&token=' . $token . '&list_id=' . $list_id . '" data-toggle="tooltip" data-placement="right"
									title="' . tr("MANAGEMENT_BACKUP") . '"><i class="glyphicon glyphicon-compressed"></i> ' . tr("MENU_BACKUP") . '</a></li>';
								if($_SESSION['dr_envois']=='Y'||$_SESSION['dr_is_admin']==true) {
									echo '<li><a href="?page=manager_global_cron&token=' . $token . '&list_id=' . $list_id . '" data-toggle="tooltip" data-placement="right" 
										title="' . tr("MANAGEMENT_TASKS") . '"><i class="glyphicon glyphicon-calendar"></i> ' . tr("SCHEDULED_TASKS") . '</a></li>';
								}
						echo 	'</ul>
						</li>';
					}
					echo '<li><a href="//www.phpmynewsletter.com/forum/" target="_blank" data-toggle="tooltip" data-placement="auto" 
						title="'.tr("SUPPORT_FORUM").'"><i class="glyphicon glyphicon-plus-sign"></i> ' . tr("SUPPORT") . '</a></li>
					<li><a href="?page=about&token=' . $token . '&list_id=' . @$list_id . '" data-toggle="tooltip" data-placement="auto" 
						title="'.tr("ABOUT_PHPMYNEWSLETTER").'"><i class="glyphicon glyphicon-info-sign"></i> ' . tr("ABOUT") . '</a></li>
					<li><a href="logout.php" data-toggle="tooltip" data-placement="auto" 
						title="'.tr("EXIT").'"><i class="glyphicon glyphicon-log-out"></i> ' . tr("MENU_LOGOUT") . '</a></li>
					<li><a data-toggle="tooltip" data-placement="auto" title="' . tr("CONNECTED_AS") . $_SESSION['user_on_line'] . '"><i>('.$_SESSION['user_on_line'].')</i></a></li>
				</ul>
				<div class="nav navbar-nav navbar-right">';
					$_SESSION['dr_is_admin']==true?checkVersion():'';
					echo '&nbsp;<button class="btn btn-success btn-sm">' .tr("RUNNING_LIST"). ' : <b>' . $list_name . '</b></button>';
					
					if($is_mq_true&&$type_serveur=='dedicated' && $exec_available && ($_SESSION['dr_envois']=='Y'||$_SESSION['dr_is_admin']==true)){
						echo '&nbsp;<span id="mailq"><button type="button" class="btn btn-primary btn-sm"">'.tr("LOOKING_PROGRESS_MAILS").'...</button></span>';
					}
					
					if ($_SESSION['dr_redaction']=='Y'||$_SESSION['dr_is_admin']==true) {
						echo ($nbDraft['NB']==0 ?
							'&nbsp;<button class="btn btn-primary btn-sm">' . tr("NO_CURRENT_DRAFT") . '</button>'
							:
							'&nbsp;<a href="?page=compose&token='.$token.'&list_id='.$list_id.'&op=init"  title="'
							. tr("ACCESS_DRAFT_CONTINUE_WRITING") . '" data-toggle="tooltip" data-placement="auto" class="clearbtn btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i> 1 '
							. tr("CURRENT_DRAFT") . '</a>'
						);
					}
					echo ' <button type="button" class="btn btn-default btn-sm" id="ts" title="' . tr("TIME_SERVER") . '" data-toggle="tooltip" data-placement="auto">--:--:--</button>
				</div>
			</div>
		</nav>
		<div class="page-content container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="content-box-large">';
							include ("include/index_main.php") ;
		echo '					</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="modalPmnl" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">';
					switch ($page) {
						case 'compose':
							include ("include/modals/upload_pj.php");
						break;
						case 'wysiwyg':
							include ("include/modals/help_wysiwyg.php");
						break;
						default:
							echo '<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>';
						break;
					}

		echo '		</div>
			</div>
		</div>
		<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="' 
			. tr('BACK_TOP') . '" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
		<script>
		$("#ts").jsclock("' . date('H:i:s') . '");
		$(".tooltip").tooltip();
		$("ul.nav li.dropdown").tooltip();
		$("ul.nav li.dropdown").hover(function(){$(this).find(".dropdown-menu").stop(true, true).delay(50).fadeIn(50);},function(){$(this).find(".dropdown-menu").stop(true,true).delay(50).fadeOut(50);});
		$("#rootwizard").bootstrapWizard();
		$(document).ready(function(){$(window).scroll(function(){if($(this).scrollTop()>50){$("#back-to-top").fadeIn();}else{$("#back-to-top").fadeOut();}});$("#back-to-top").click(function(){$("#back-to-top").tooltip("hide");$("body,html").animate({scrollTop:0},800);return false;});$("#back-to-top").tooltip("show");});
		</script>
		<script src="js/js.js"></script>
		<style type="text/css">.modal-dialog{width: 80%;}.modal-content{padding:20px;}</style>
		<script>
		function ChangeStatus(action,list_id){
			$.ajax({
				type:"POST",
				url: "include/ajax/send_status.php",
				data: {"action":action,"list_id":list_id,"token":"' . $token . '"},
				cache: false,
				beforeSend:function(){
					return confirm("Are you sure?");
				},
				success: function(data){
					$("#Send_Status").html(data);
					
				}
			});
		}';
		if ($is_mq_true) {
			echo '// mailqueue
			function mq() {
				$.ajax({
					url: "include/ajax/mailqueue.php",
					success: function(data) {
						$(\'#mailq\').html(data);
					}
				});
				setTimeout(mq, 10000);
			}
			mq();';
		}
		echo '</script>
	</body>
</html>';