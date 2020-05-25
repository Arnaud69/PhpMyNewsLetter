<?php
session_start();
if ( !file_exists( "config.php" ) ) {
	header( "Location:../install.php" );
	exit;
} else {
	include( "../_loader.php" );
	$token = ( empty( $_POST[ 'token' ] ) ? "" : $_POST[ 'token' ] );
	if ( !isset( $token ) || $token == "" )
		$token = ( empty( $_GET[ 'token' ] ) ? "" : $_GET[ 'token' ] );
	if ( !tok_val( $token ) ) {
		header( "Location:../login.php?error=2" );
		exit;
	}
}
$actions_possibles = array(
	'update',
	'delete',
	'new',
	'manage' 
);
if ( isset( $_POST[ 'action' ] ) && in_array( $_POST[ 'action' ], $actions_possibles ) ) {
	$action = $_POST[ 'action' ];
} else {
	header( "Location:../login.php?error=2" );
	exit;
}
$list_id  = ( empty( $_POST[ 'list_id' ] ) ? ( empty( $_GET[ 'list_id' ] ) ? die( 'Demande de transaction impossible' ) : $_GET[ 'list_id' ] ) : $_POST[ 'list_id' ] );
$continue = true;
if ( $continue ) {
	$cronID = cronID();
	$cnx->query( "SET NAMES UTF8" );
	exec( "crontab -l > " . __DIR__ . "/backup_crontab/$cronID" );
	switch ( $action ) {
		case 'new':
			$pmin	  = intval( $_POST[ 'min' ] );
			$phour	  = intval( $_POST[ 'hour' ] );
			$pday	  = intval( $_POST[ 'day' ] );
			$pmonths  = intval( $_POST[ 'months' ] );
			$pyear	  = intval( $_POST[ 'year' ] );
			$min	  = ( is_numeric( $pmin ) && $pmin < 60 && $pmin >= 0 ? $pmin : die( 'min vide :' . $pmin ) );
			$hour	  = ( is_numeric( $phour ) && $phour < 24 && $phour >= 0 ? $phour : die( 'hour vide :' . $phour ) );
			$day	  = ( is_numeric( $pday ) && $pday < 32 && $pday > 0 ? $pday : die( 'day vide :' . $pday ) );
			$month	  = ( is_numeric( $pmonths ) && $pmonths < 13 && $pmonths > 0 ? $pmonths : die( 'months vide : ' . $pmonths ) );
			$year	  = ( is_numeric( $pyear ) && ( $pyear == date('Y') || $pyear == (date('Y')+1) ) ? $pyear : die( 'year vide : ' . $pyear ) );
			$DupTask  = ( $_POST[ 'DupTask' ] == 'yes' ? 'yes' : '' );
			if ( $DupTask == 'yes' ) {
				// on a une tâche dupliquée :
				// variable $liste originelle :
				$list_origine = intval( $_POST[ 'list_origine' ] );
				// variable message origine dupliqué :
				$msg_origine = intval( $_POST[ 'msg_origine' ] );
				
				// on crée une nouvelle archive :
				$id	  = $cnx->query( 'SELECT id FROM ' . $row_config_globale[ 'table_archives' ] . ' ORDER BY id DESC' )->fetch( PDO::FETCH_ASSOC );
				$msg_id	  = $id[ 'id' ] + 1;
				
				// on crée une nouvelle tâche :
				$new_task = "$min $hour $day $month * " . exec( "command -v php" ) . " " . __DIR__ 
					. "/task.php $cronID >/dev/null # JOB : $cronID list_id : $list_id msg_id : $msg_id Creation : " 
					. date( "Y-m-d H:i:s" ) . " Envoi : $day/$month/$year $hour:$min:00 ###";
				
				append_cronjob( $new_task /*. PHP_EOL*/ );
				
				// on duplique les infos de la table archive
				$cnx->query( 'INSERT INTO ' . $row_config_globale['table_archives'] . ' (id , date , type , subject , message , list_id, draft, preheader)
					SELECT ' . $msg_id . ' , "' . date( "Y-m-d H:i:s" ) . '" , type , subject , message , ' . $list_id . ' , draft, preheader
						FROM ' . $row_config_globale['table_archives'] . '
					WHERE id = ' . $msg_origine );
									
				$cnx->query( 'UPDATE ' . $row_config_globale['table_archives'] . ' 
						SET sender_email = 
							(SELECT sender_email 
								FROM ' . $row_config_globale[ 'table_listsconfig' ] . ' 
							WHERE list_id = ' . $list_id . ') 
					WHERE id = ' . $msg_id );
							
				// on duplique les infos de la table upload
				$cnx->query( 'INSERT INTO ' . $row_config_globale[ 'table_upload' ] . ' (list_id , msg_id , name)
					SELECT ' . $list_origine . ' , ' . $msg_id . ' , name 
						FROM ' . $row_config_globale[ 'table_upload' ] . '
					WHERE  msg_id = ' . $msg_origine );
				
				// on crée une nouvelle entrée dans la table crontab :
				$cnx->query( 'INSERT INTO ' . $row_config_globale[ 'table_crontab' ] . ' VALUES
					("","' . $cronID . '","' . $list_id . '","' . $msg_id . '","' . $min . '","' . $hour . '",
					 "' . $day . '","' . $month . '","scheduled","' . addslashes( $new_task ) . '",
					 (SELECT message FROM ' . $row_config_globale[ 'table_archives' ] . ' WHERE id = ' . $msg_origine . '),
					 (SELECT subject FROM ' . $row_config_globale[ 'table_archives' ] . ' WHERE id = ' . $msg_origine . '),
					 "html","' . $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':00")' );
				
			} elseif ( $DupTask == '' || empty($DupTask) ) {
				// on crée une tâche :
				$id	  = $cnx->query( 'SELECT id FROM ' . $row_config_globale[ 'table_archives' ] . ' ORDER BY id DESC' )->fetch( PDO::FETCH_ASSOC );
				$msg_id	  = $id[ 'id' ] + 1;
				
				$new_task = "$min $hour $day $month * " . exec( "command -v php" ) . " " . __DIR__ 
					. "/task.php $cronID >/dev/null # JOB : $cronID list_id : $list_id msg_id : $msg_id Creation : " 
					. date( "Y-m-d H:i:s" ) . " Envoi : $day/$month/$year $hour:$min:00 ###";

				append_cronjob( $new_task /*. PHP_EOL*/ );
				
				$cnx->query( 'INSERT INTO ' . $row_config_globale[ 'table_crontab' ] . ' VALUES
					("","' . $cronID . '","' . $list_id . '","' . $msg_id . '","' . $min . '","' . $hour . '",
					 "' . $day . '","' . $month . '","scheduled","' . addslashes( $new_task ) . '",
					 (SELECT textarea FROM ' . $row_config_globale[ 'table_sauvegarde' ] . ' WHERE list_id = "' . $list_id . '"),
					 (SELECT subject FROM ' . $row_config_globale[ 'table_sauvegarde' ] . ' WHERE list_id = "' . $list_id . '"),
					 "html","' . $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':00")' );
								 
				$cnx->query( 'UPDATE ' . $row_config_globale[ 'table_upload' ] . ' 
						SET msg_id=' . $msg_id . ' 
					WHERE list_id=' . $list_id . ' AND msg_id=0' );
				
				$cnx->query( 'INSERT INTO ' . $row_config_globale['table_archives'] . ' (id , date , type , subject , message , list_id)
					SELECT ' . $msg_id . ' , "' . date( "Y-m-d H:i:s" ) . '" , type , mail_subject , mail_body , list_id 
						FROM ' . $row_config_globale['table_crontab'] . '
					WHERE list_id = ' . $list_id . ' 
						AND job_id = "' . $cronID . '"');
									
				$cnx->query( 'UPDATE ' . $row_config_globale['table_archives'] . ' SET
						 sender_email = (SELECT sender_email FROM ' . $row_config_globale[ 'table_sauvegarde' ] . ' WHERE list_id = ' . $list_id . '),
						 draft = (SELECT draft FROM ' . $row_config_globale[ 'table_sauvegarde' ] . ' WHERE list_id = "' . $list_id . '"),
						 preheader = (SELECT preheader FROM ' . $row_config_globale[ 'table_sauvegarde' ] . ' WHERE list_id = ' . $list_id . ' )
					 WHERE id = ' . $msg_id  );
				
				$cnx->query( 'DELETE FROM ' . $row_config_globale[ 'table_sauvegarde' ] . ' 
					WHERE list_id = ' . $list_id );
				
				echo '<script>$(function(){$("input[name=MsgID]").val("' . $msg_id . '");});</script>';				
			}
			$continue_transaction = true;
			break;
			
		case 'update':
			$continue_transaction = false; // on fera un header sur le manage cron
			// on va chercher l'ancienne tâche cron :
			$pmin	  = intval( $_POST[ 'min' ] );
			$phour	  = intval( $_POST[ 'hour' ] );
			$pday	  = intval( $_POST[ 'day' ] );
			$pmonths  = intval( $_POST[ 'month' ] );
			$pyear	  = intval( $_POST[ 'year' ] );
			$cronID   = $_POST[ 'deltask' ];
			$min	  = ( is_numeric( $pmin ) && $pmin < 60 && $pmin >= 0 ? $pmin : die( 'min vide :' . $pmin ) );
			$hour	  = ( is_numeric( $phour ) && $phour < 24 && $phour >= 0 ? $phour : die( 'hour vide :' . $phour ) );
			$day	  = ( is_numeric( $pday ) && $pday < 32 && $pday > 0 ? $pday : die( 'day vide :' . $pday ) );
			$month	  = ( is_numeric( $pmonths ) && $pmonths < 13 && $pmonths > 0 ? $pmonths : die( 'months vide : ' . $pmonths ) );
			$year	  = ( is_numeric( $pyear ) && ( $pyear == date('Y') || $pyear == (date('Y')+1) ) ? $pyear : die( 'year vide : ' . $pyear ) );
			$detail_crontab = $cnx->query( 'SELECT job_id,list_id,msg_id,mail_subject,min,hour,day,month,etat,command
								FROM ' . $row_config_globale[ 'table_crontab' ] . ' 
							WHERE list_id=' . $list_id . '
								AND job_id="' . $cronID . '"' )->fetchAll( PDO::FETCH_ASSOC );
			if ( count( $detail_crontab ) == 1 && $detail_crontab[ 0 ][ 'etat' ] == 'done' ) {
				return false; // Cette tâche a déjà été exécutée !!!
			} else {
				// on crée la nouvelle tâche :
				$msg_id = $detail_crontab[ 0 ][ 'msg_id' ] ;
				$new_task = "$min $hour $day $month * " . exec( "command -v php" ) . " " . __DIR__ 
					. "/task.php $cronID >/dev/null # JOB : $cronID list_id : $list_id msg_id : $msg_id Creation : " 
					. date( "Y-m-d H:i:s" ) . " Envoi : $day/$month/$year $hour:$min:00 ###";
				$output = shell_exec( 'crontab -l' );
				if ( stristr( $output, $detail_crontab[ 0 ][ 'command' ] ) ) {
					$newcron = str_replace( $detail_crontab[ 0 ][ 'command' ], $new_task , $output );
					file_put_contents( 'backup_crontab/' . $detail_crontab[ 0 ][ 'job_id' ] . '_delete', $newcron );
					exec( 'crontab ' . __DIR__ . '/backup_crontab/' . $detail_crontab[ 0 ][ 'job_id' ] . '_delete' );
					// on a bien fait la mise à jour, on met à jour la table :
					$cnx->query( 'UPDATE ' . $row_config_globale[ 'table_crontab' ] . '
						SET min=' . $min . ', hour=' . $hour . ', day=' . $day . ', month=' . $month . ', 
							date="' . $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':00",
							command="' . $new_task . '"
						WHERE list_id=' . $list_id . '
							AND job_id="' . $cronID . '"' );
				}
			}
			break;
			
		case 'delete':
			$min	  = ( isset( $_POST[ 'deltask' ] ) && $_POST[ 'deltask' ] != '' ? $_POST[ 'deltask' ] : die( ) );
			$detail_crontab = $cnx->query( 'SELECT job_id,list_id,msg_id,mail_subject,min,hour,day,month,etat,command
								FROM ' . $row_config_globale[ 'table_crontab' ] . ' 
							WHERE list_id=' . $list_id . '
								AND job_id="' . $_POST[ 'deltask' ] . '"' )->fetchAll( PDO::FETCH_ASSOC );
			if ( count( $detail_crontab ) == 1 && $detail_crontab[ 0 ][ 'etat' ] == 'done' ) {
				$cnx->query( 'DELETE FROM ' . $row_config_globale[ 'table_crontab' ] . '
							WHERE list_id=' . $list_id . '
								AND job_id="' . $_POST[ 'deltask' ] . '"' );
				return true;
			} elseif ( count( $detail_crontab ) == 1 && $detail_crontab[ 0 ][ 'etat' ] != 'done' ) {
				$output = shell_exec( 'crontab -l' );
				if ( stristr( $output, $detail_crontab[ 0 ][ 'command' ] ) ) {
					$newcron = str_replace( $detail_crontab[ 0 ][ 'command' ], '', $output );
					file_put_contents( 'backup_crontab/' . $detail_crontab[ 0 ][ 'job_id' ] . '_delete', $newcron . PHP_EOL );
					exec( 'crontab ' . __DIR__ . '/backup_crontab/' . $detail_crontab[ 0 ][ 'job_id' ] . '_delete' );
				} else {
					// echo tr("SCHEDULE_TASK_NOT_FOUND");
				}
				$cnx->query( 'DELETE FROM ' . $row_config_globale[ 'table_crontab' ] . '
						WHERE list_id=' . $list_id . '
							AND job_id="' . $_POST[ 'deltask' ] . '"' );
				
				return true;
				exit( 0 );
			} elseif ( count( $detail_crontab ) != 1 ) {
				return false;
				exit( );
			}
			$continue_transaction = false;
			break;
	}
	
	if ( $continue_transaction ) {
		$list_crontab = $cnx->query( 'SELECT job_id,list_id,msg_id,mail_subject,min,hour,day,month,etat
						FROM ' . $row_config_globale[ 'table_crontab' ] . ' 
							WHERE list_id=' . $list_id . ' 
						ORDER BY date DESC' )->fetchAll( PDO::FETCH_ASSOC );
		echo '<header><h4>' . tr( "SCHEDULE_SEND_SCHEDULED" ) . ' : </h4></header>
			<table class="tablesorter table table-striped" cellspacing="0">  
			<thead> 
				<tr> 
					' . tr( "SCHEDULE_REPORT_HEAD" ) . '
				</tr> 
			</thead>
			<tfoot> 
				<tr> 
					' . tr( "SCHEDULE_REPORT_HEAD" ) . '
				</tr> 
			</tfoot> 
			<tbody>';
		$month_tab = tr( "MONTH_TAB" );
		$step_tab  = tr( "SCHEDULE_STATE" );
		if ( count( $list_crontab ) > 0 ) {
			foreach ( $list_crontab as $x ) {
				echo '<tr';
				if ( $x[ 'job_id' ] == $cronID ) {
					echo ' style="background:#B5E5EF"';
				}
				echo ' class="'.$x['job_id'].'">
					<td style="padding-top:14px;">' . $x[ 'job_id' ] . '</td>
					<td style="padding-top:14px;">' . $x[ 'list_id' ] . '</td>
					<td style="padding-top:14px;">' . stripslashes( $x[ 'mail_subject' ] ) . '</td>
					<td style="padding-top:14px;">' . sprintf( "%02d", $x[ 'day' ] ) . ' ' . $month_tab[ $x[ 'month' ] ] . ' à ' . sprintf( "%02d", $x[ 'hour' ] ) . 'h' . sprintf( "%02d", $x[ 'min' ] ) . '</td>
					<td style="padding-top:14px;">' . $x[ 'etat' ] . '</td>';
				if(is_file("../logs/list".$x['list_id']."-msg".$x['msg_id'].".txt")){
					echo '<td><a data-toggle="modal" data-target="#modalPmnl" data-tooltip="tooltip" href="include/view_log.php?list_id='
						.$x['list_id'].'&id_mail='.$x['msg_id'].'&t=l&token='
						.$token.'" title="'. tr( "TRACKING_VIEW_LOG_SEND" ) .'">
						<button type="button" class="deltask btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></button></a></td>';
				} else {
					echo '<td style="padding-top:14px;">'.tr("SCHEDULE_NO_LOG").'.</td>';	 
				}
				echo '<td><form id="'.$x['job_id'].'" method="post">';
				if($x['etat']=='scheduled'){
					echo '<a title="'.tr("SCHEDULE_DELETE_TASK").'" data-toggle="tooltip">
						<button type="button" class="deltask btn btn-default btn-sm"><i class="glyphicon glyphicon-trash"></i></button></a>
						<input type="hidden" value="'.$x['job_id'].'" id="deltask">
						<input type="hidden" value="'.$token.'" id="token">
						<input type="hidden" value="'.$list_id.'" name="list_id">';
				}
				echo '</form></td>
				</tr>';
			}
			echo '</table>
			<script>
				$(".deltask").click(function() {
					var task=$(this).closest("form").attr("id");
					var tlistid=$(this).closest("form").find("input[name=list_id]").val();
					var dt="."+task;
					var ds="deltask="+task+"&token=' . $token . '&list_id="+tlistid+"&action=delete";
					$.ajax({type:"POST",
						url:"include/manager_cron.php",
						data:ds,
						success: function(){
							$(dt).hide("slow");
						}
					});
				});
			</script>';
		} else {
			echo '	<tr>
					<td colspan="5" align="center">' . tr( "SCHEDULE_NO_SEND_SCHEDULED" ) . '</td>
				</tr>
			</table>';
		}
	} else {
		header('Location: ../index.php?page=manager_global_cron&token=' . $token );
	}
}





