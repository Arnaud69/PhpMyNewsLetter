<?php
	 
$list_crontab = $cnx->query( 'SELECT c.*, l.newsletter_name
	FROM ' . $row_config_globale[ 'table_crontab' ] . ' c
		LEFT JOIN ' . $row_config_globale[ 'table_listsconfig' ] . ' l 
		ON c.list_id=l.list_id
	ORDER BY c.date DESC' )->fetchAll( PDO::FETCH_ASSOC );
echo '
<header><h4>' . tr( "SCHEDULE_SEND_SCHEDULED" ) . ' : </h4></header>
<div id="dc">(Afficher la crontab)</div><div id="cr_show"></div>
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
if ( isset ( $action ) && $action == 'MT' ) {
	$action == 'MT' ;
} else {
	$action == '';
}
if ( count( $list_crontab ) > 0 ) {
	foreach ( $list_crontab as $x ) {
		$DatePlanif = strtotime ( $x[ 'date' ] );
		if ( $action == 'MT' && $job_id == $x[ 'job_id' ] ) {
			$days_value = '';
			for($days=1;$days<32;$days++){
				$days_value .= '<option value=' . $days . '>' . $days . '</option>';
			}
			$hours_value = '';
			for($hours=0;$hours<24;$hours++){
				$hours_value .= '<option value=' . $hours . '>' . $hours . '</option>';
			}
			$mins_value = '';
			for($min=0;$min<60;$min++){
				$mins_value .= '<option value=' . $min . '>' . $min . '</option>';
			}
			$years_value = '<option value=' . date('Y') . '>' . date('Y') . '</option><option value=' . (date('Y')+1) . '>' . (date('Y')+1) . '</option>';
			echo '<tr' . ( $x[ 'job_id' ] == @$cronID ? ' style="background:#B5E5EF"':'' ) . ' class="'.$x['job_id'].'">
				<td style="padding-top:14px;">' . $x[ 'job_id' ] . '</td>
				<td style="padding-top:14px;">' . $x[ 'list_id' ] . '</td>
				<td style="padding-top:14px;">' . stripslashes( $x[ 'mail_subject' ] ) . '</td>
				<td colspan="4" style="padding-top:14px;">Ancienne date :' 
					. sprintf( "%02d", $x[ 'day' ] ) 
					. ' ' . ucfirst ( $month_tab[ $x[ 'month' ] ] ) . ' ' 
					. date( "Y" , $DatePlanif  ) 
					. ' à ' . sprintf( "%02d", $x[ 'hour' ] ) . 'h' . sprintf( "%02d", $x[ 'min' ] ) . '</td>';
			echo '</tr>
			<tr>
				<td colspan="7" class="text-center">
					<form method="post" action="include/manager_cron.php">
						Nouvelle date pour ' . $x[ 'job_id' ] . ' :
						Le <select name="day" id="days" class="selectpicker" data-width="auto">' . $days_value . '</select>
						<select name="month" class="selectpicker" data-width="auto">' . tr("SCHEDULE_MONTHS_OPTION") . '</select>
						<select name="year" class="selectpicker" data-width="auto">' . $years_value . '</select>
						à <select name="hour" class="selectpicker" data-width="auto">' . $hours_value . '</select> H : 
						<select name="min" class="selectpicker" data-width="auto">' . $mins_value . '</select> Min
						<input type="submit" class="btn btn-primary btn-sm" value="OK">
						<input type="hidden" value="' . $x['job_id'] . '" name="deltask">
						<input type="hidden" value="' . $token . '" name="token">
						<input type="hidden" value="' . $x['list_id'] . '" name="list_id">
						<input type="hidden" value="' . $x['msg_id'] . '" name="msg_id">
						<input type="hidden" value="update" name="action">
					</form>
				</td>
			</tr>';
			
		} else {
			// si une tâche est à running et que l'on a pas de fichier pid posé, alors on passe l'état à done, et on pousse cette
			// valeur dans $x[ 'etat' ] pour afficher correctement !
			if ( $x['etat']=='running' && (!is_file("logs/__SEND_PROCESS__" . $x['list_id'] . ".pid")) ){
				// on a bien un process zombie !
				$cnx->query('UPDATE ' . $row_config_globale['table_crontab'] . '
						SET etat="done"
					WHERE list_id=' . $x[ 'list_id' ] . '
						AND msg_id=' . $x['msg_id'] . '
						AND job_id="' . $x[ 'job_id' ] . '"');
				$x['etat']='done';
			}
			echo '<tr' . ( $x[ 'job_id' ] == @$cronID ? ' style="background:#B5E5EF"':'' ) . ' class="'.$x['job_id'].'">
				<td style="padding-top:14px;">' . $x[ 'job_id' ] . '</td>
				<td style="padding-top:14px;">' . $x[ 'list_id' ] . '</td>
				<td style="padding-top:14px;">' . stripslashes( $x[ 'mail_subject' ] ) . '</td>
				<td style="padding-top:14px;">' 
					. sprintf( "%02d", $x[ 'day' ] ) 
					. ' ' . ucfirst ( $month_tab[ $x[ 'month' ] ] ) . ' ' 
					. date( "Y" , $DatePlanif  ) 
					. ' à ' . sprintf( "%02d", $x[ 'hour' ] ) . 'h' . sprintf( "%02d", $x[ 'min' ] ) . '</td>
				<td style="padding-top:14px;">' . $x[ 'etat' ] . '</td>';
			if(is_file("logs/list".$x['list_id']."-msg".$x['msg_id'].".txt")){
				echo '<td><a data-toggle="modal" data-target="#modalPmnl" data-tooltip="tooltip" href="include/view_log.php?list_id='
					.$x['list_id'].'&id_mail='.$x['msg_id'].'&t=l&token='
					.$token.'" title="'. tr( "TRACKING_VIEW_LOG_SEND" ) .'">
					<button type="button" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-search"></i></button></a>
					<a data-toggle="tooltip" href="dl.php?log=logs/list' . $x['list_id'] . '-msg' . $x['msg_id'] . '.txt&token='
					. $token . '" title="Telecharger le fichier log de l\'envoi">
					<button type="button" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-download-alt"></i></button></a></td>';
			} else {
				echo '<td style="padding-top:14px;">'.tr("SCHEDULE_NO_LOG").'.</td>';	 
			}
			echo '<td>';
			if($x['etat']=='scheduled' && $action != 'MT' ){
				echo '<form id="'.$x['job_id'].'" method="post">
					<a href="' . $_SERVER['PHP_SELF'] . '?page=manager_global_cron&token=' 
					. $token . '&action=MT&job_id=' . $x['job_id'] . '" title="'.tr("MODIFY_DATE_TASK").'" data-toggle="tooltip">
					<button type="button" class="btn btn-primary btn-sm">
					<i class="glyphicon glyphicon-pencil"></i></button></a>				
					<a title="'.tr("SCHEDULE_DELETE_TASK").'" data-toggle="tooltip">
					<button type="button" class="deltask btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></button></a>
					<input type="hidden" value="'.$x['job_id'].'" id="deltask">
					<input type="hidden" value="'.$token.'" id="token">
					<input type="hidden" value="'.$x['list_id'].'" name="list_id">
					</form>';
			}
			echo '
				</td>
			</tr>';
		}
	}
	echo '</table>
	<script>
	$(".deltask").click(function() {
		var task=$(this).closest("form").attr("id");
		var tlistid=$(this).closest("form").find("input[name=list_id]").val();
		var dt="."+task;
		var ds="deltask="+task+"&token=' . $token . '&list_id="+tlistid+"&action=delete";
		var myImg = "//www.phpmynewsletter.com/wp-content/uploads/2017/04/cropped-phpmynewsletter_v2.png";
		$.ajax({type:"POST",
			url:"include/manager_cron.php",
			data:ds,
			success: function(){
				$(dt).hide("slow");
				var options = {
					title: "",
					options: {
						body: "Tâche planifiée correctement supprimée",
						icon: myImg,
						lang: "fr-FR"
					}
				};
				$("#easyNotify").easyNotify(options);
			},
			error: function(){
				var options = {
					title: "",
					options: {
						body: "Suppression de tâche en erreur !",
						icon: myImg,
						lang: "fr-FR"
					}
				};
				$("#easyNotify").easyNotify(options);
			}
		});
	});
	$("#dc").click(function(){
		$("#dc").html("' . tr("LOADING") . '");
		$.ajax({
			type:"POST",
			cache: false,
			url: "include/ajax/display_crontab.php", 
			data: {"token":"' . $token .'"},
			success: function(response){
				$("#dc").hide("slow");
				$("#cr_show").html(response);
			},
			error: function(response){
				$("#dc").html("<span class=\'alert alert-danger\'>Erreur</span>");
			},
		});
	});
	</script>';
} else {
	echo '<tr>
	<td colspan="5" align="center">' . tr( "SCHEDULE_NO_SEND_SCHEDULED" ) . '</td>
	</tr>
	</table>';
}
