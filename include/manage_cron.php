<?php
if(isset($_POST['NEWTASK'])&&$_POST['NEWTASK']=='SCHEDULE_NEW_TASK'&&$list_id==$_POST['list_id']){
	$msg		= getConfig($cnx,$list_id,$row_config_globale['table_sauvegarde']);
	$subject	= stripslashes($msg['subject']);
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
	$lists_value = '<option></option>';
	foreach($list as $item) {
		$lists_value .= '<option value=' . $item['list_id'] . '>' . htmlentities($item['newsletter_name'],ENT_QUOTES) . '</option>';
	}
	echo '
	<div id="planifjob">
		<header>
			<h4>' . tr("SCHEDULE_A_SEND") . ' : ' . $list_name . '</h4>
		</header>
		' . tr("SCHEDULE_EXPLAIN", $subject) . '
		<div class="alert alert-info text-center" id="infoOtherTask"><h5>Vous pourrez planifier l\'envoi de cette campagne pour d\'autres listes une fois la planification de cet envoi effectuée.</h5></div>
		<form class="cf" id="cf">
		<table class="tablesorter table table-striped" cellspacing="0">
			<tbody>
				<tr> 
					' . tr("SCHEDULE_DATE_HEAD") . '
				</tr>
				<tr>
					<td><select name="days" id="days" class="selectpicker" data-width="auto">' . $days_value . '</select></td>
					<td><select name="months" id="months" class="selectpicker" data-width="auto">' . tr("SCHEDULE_MONTHS_OPTION") . '</select></td>
					<td><select name="years" id="years" class="selectpicker" data-width="auto">' . $years_value . '</select></td>
					<td><select name="hours" id="hours" class="selectpicker" data-width="auto">' . $hours_value . '</select></td>
					<td><select name="mins" id="mins" class="selectpicker" data-width="auto">' . $mins_value . '</select></td>
					<td>' . $list_name . '</td>
					<td>' . tr("SCHEDULE_RESULT", $subject) . '</td>
					<td style="text-align:center;">
						<div id="formSubCronJob">
							<select name="list_id" style="display:none" ><option value=' . $list_id .'></option></select>
							<input type="hidden" name="DupTask" value="">
							<input type="button" name="subcronjob" value="' . tr("SUBMIT") . '" id="subcronjob" class="btn btn-primary">
						</div>
						<div id="RRTask"></div>
					</td>
				</tr>
			</tbody>
		</table>
		</form>
		<input type="hidden" name="MsgID" id="MsgID" value="">
		<div id="form_data"></div>
		<table class="tablesorter table table-striped" cellspacing="0">
			<tbody>		
				<tr>
					<td class="text-center"><input type="button" id="add_item" value="Planifier un autre envoi" class="btn btn-primary" style="display:none"></td>
				</tr>
			</tbody>
		</table>
		<script>
			var months=["",' . tr("SCHEDULE_JS_LIST_MONTH") . '],month,hour,minute,day;
			function n(n){return n>9?""+n:"0"+n;}
			function uuidv4(){return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c => (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16))}
			$(document).on("change","#mins",function(){$(this).closest("form").find("#dmi").html(n($(this).val()))});
			$(document).on("change","#hours",function(){$(this).closest("form").find("#dh").html(n($(this).val()))});
			$(document).on("change","#days",function(){$(this).closest("form").find("#dd").html(n($(this).val()))});
			$(document).on("change","#months",function(){$(this).closest("form").find("#dmo").html(n($(this).val()))});
			$(document).on("change","#years",function(){$(this).closest("form").find("#dy").html(n($(this).val()))});
			$(document).on("change","#list_id",function(){$(this).closest("form").find("#dli").html($(this).val())});
			$(document).on("click", "#subcronjob",function(){
				var tdays=$(this).closest("form").find("select[name=days]").val();
				var tmonths=$(this).closest("form").find("select[name=months]").val();
				var tyears=$(this).closest("form").find("select[name=years]").val();
				var thours=$(this).closest("form").find("select[name=hours]").val();
				var tmins=$(this).closest("form").find("select[name=mins]").val();
				var tlistid=$(this).closest("form").find("select[name=list_id]").val();
				if(!tlistid.length){alert("Erreur sur choix de la liste");return false;}
				var DupTask=$(this).closest("form").find("input[name=DupTask]").val();
				var ds="min="+tmins+"&hour="+thours+"&day="+tdays+"&months="+tmonths+"&year="+tyears+"&token=' . $token . '&list_id="+tlistid+"&action=new";
				if(DupTask.length){var MsgOri=$("#MsgID").val();var dsDupTask="&DupTask=yes&list_origine=' . $list_id .'&msg_origine="+MsgOri;ds=ds+dsDupTask;}else{ds=ds+"&DupTask=NoThisTime";}
				var self = this;
				$.ajax({
					type:"POST",
					url:"include/manager_cron.php",
					data:ds,
					cache:false,
					success:function(data) {
						$(self).closest("form").find("#formSubCronJob").css({"display":"none"});
						$(self).closest("form").find("#RRTask").html("<div class=\'alert alert-success\'>Envoi planifié</div>");
						/*$("#planifjob").hide("slow");*/
						$("#add_item").css({"display":""});
						$("#jobcronlist").html(data);
					},
					error : function(response){
						$(self).closest("form").find("#formSubCronJob").css({"display":"none"});
						$(self).closest("form").find("#RRTask").html("<div class=\'alert alert-danger\'><b>Planification en erreur !<br>Code : "+response.status+"</b></div>");
					}
				});
			});
			$(document).ready(function(){
				$("#add_item").click(function(){
					var varGenFormTask=uuidv4();
					var myFormHtmlTask=\'<form class="cf" id="\'+varGenFormTask+\'"><table class="tablesorter table table-striped" cellspacing="0"><tbody><tr>' 
					. tr("SCHEDULE_DATE_HEAD") . '</tr><tr><td><select name="days" id="days" class="selectpicker" data-width="auto">' . $days_value . '</select></td>'
					. '<td><select name="months" id="months" class="selectpicker" data-width="auto">' . tr("SCHEDULE_MONTHS_OPTION") . '</select></td>'
					. '<td><select name="years" id="years" class="selectpicker" data-width="auto">' . $years_value . '</select></td>'
					. '<td><select name="hours" id="hours" class="selectpicker" data-width="auto">' . $hours_value . '</select></td>'
					. '<td><select name="mins" id="mins" class="selectpicker" data-width="auto">' . $mins_value . '</select></td>'
					. '<td><select name="list_id" id="list_id" class="selectpicker" data-width="auto" required>' . $lists_value . '</select></td>'
					. '<td>' . str_replace("'"," ",tr("SCHEDULE_RESULT", $subject)) . '<br>Liste : <span id="dli">--</span></td>'
					. '<td style="text-align:center;"><div id="formSubCronJob"><input type="hidden" name="DupTask" value="yes">'
					. '<input type="button" value="' . tr("SUBMIT") . '" id="subcronjob" class="btn btn-primary"></div>'
					. '<div id="RRTask"></div></td></tr></table></form>\';
					$("#form_data").append(myFormHtmlTask);
					$(".selectpicker").selectpicker("refresh");
				});
			})
		</script>
	</div>
	<hr>';
}
echo '<div id="jobcronlist">';
$list_crontab = $cnx->query('SELECT job_id,list_id,msg_id,mail_subject,min,hour,day,month,etat,`date`
				FROM ' . $row_config_globale['table_crontab'] . ' 
					WHERE list_id=' . $list_id . ' 
				ORDER BY date DESC')->fetchAll(PDO::FETCH_ASSOC);
echo '<header><h4>' . tr("SCHEDULE_SEND_SCHEDULED") . ' : ' . $list_name . '</h4></header>
<table class="tablesorter table table-striped" cellspacing="0">  
	<thead> 
		<tr> 
			' . tr("SCHEDULE_REPORT_HEAD") . '
		</tr> 
	</thead>
	<tfoot> 
		<tr> 
			' . tr("SCHEDULE_REPORT_HEAD") . '
		</tr> 
	</tfoot> 
	<tbody>';
$month_tab=tr("MONTH_TAB");
$step_tab=tr("SCHEDULE_STATE");
if(count($list_crontab)>0){
	foreach($list_crontab as $x){
		echo '<tr class="' . $x['job_id'] . '">
			<td style="padding-top:14px;">' . $x['job_id'] . '</td>
			<td style="padding-top:14px;">' . $x['list_id'] . '</td>
			<td style="padding-top:14px;">' . stripslashes($x['mail_subject']) . '</td>
			<td style="padding-top:14px;">' . sprintf("%02d",$x['day']) . ' ' . $month_tab[$x['month']]
				 . ' à ' . sprintf("%02d",$x['hour']) . 'h' . sprintf("%02d",$x['min']) . '</td>
			<td style="padding-top:14px;">' . $step_tab[$x['etat']] . '</td>';
			
		if(is_file("logs/list".$x['list_id']."-msg".$x['msg_id'].".txt")){
			echo '<td><a data-toggle="modal" data-target="#modalPmnl" data-tooltip="tooltip" href="include/view_log.php?list_id='
				.$x['list_id'] . '&id_mail=' . $x['msg_id'] . '&t=l&token='
				 .$token . '" title="' .  tr( "TRACKING_VIEW_LOG_SEND" ) . '">
				 <button type="button" class="deltask btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></button></a></td>';
		} else {
			echo '<td style="padding-top:14px;">' . tr("SCHEDULE_NO_LOG") . ' . </td>';	 
		}
		
		echo '<td><form id="' . $x['job_id'] . '" method="post">';
		if($x['etat']=='scheduled'){
			echo '<a title="' . tr("SCHEDULE_DELETE_TASK") . '" data-toggle="tooltip">
			<button type="button" class="deltask btn btn-default btn-sm"><i class="glyphicon glyphicon-trash"></i></button></a>
				<input type="hidden" value="' . $x['job_id'] . '" id="deltask">
				<input type="hidden" value="' . $token . '" id="token">
				<input type="hidden" value="' . $list_id . '" name="list_id">';
		}
		echo '</form></td>
		</tr>';
	}
	echo '</table>
	<script>
		$(".deltask").click(function() {
			var task=$(this).closest("form").attr("id");
			var dt="."+task;
			var ds="deltask="+task+"&token=' . $token . '&list_id=' . $list_id . '&action=delete";
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
	echo '
		<tr>
			<td colspan="5" align="center">' . tr("SCHEDULE_NO_SEND_SCHEDULED") . '</td>
		</tr>
	</table>';
}
echo '</div>';
