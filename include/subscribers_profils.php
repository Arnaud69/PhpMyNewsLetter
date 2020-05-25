<?php
if(is_int($ta)&&$ta!='all'){
	$p_where =	" WHERE e.list_id=".$list_id." AND (SELECT count(distinct(subject)) FROM ". $row_config_globale['table_tracking'] ." dt WHERE dt.hash = e.hash)>0" ;
	$totalsubscribers = getSubscribersNumbers($cnx , $row_config_globale['table_email'] , $list_id);
	$headerProfil = '<header><h4>' . tr("SUBSCRIBER_PROFILS") . ', liste ' . $list_id . ' : ' . $list_name . ' (' . $totalsubscribers . ' ' . tr("MENU_SUBSCRIBERS") . ')</h4></header>';
}else{
	$p_where = " WHERE (SELECT count(distinct(subject)) FROM ". $row_config_globale['table_tracking'] ." dt WHERE dt.hash = e.hash)>0";
	$totalsubscribers = getSubscribersTotal($cnx , $row_config_globale['table_email']);
	$headerProfil = '<header><h4>' . tr("SUBSCRIBER_PROFILS") .', '. tr("ALL_LISTS") .' (' . $totalsubscribers . ' ' . tr("MENU_SUBSCRIBERS") . ')</h4></header>';
}
if(($_SESSION['dr_abonnes']=='Y'&&$_SESSION['dr_liste']==0)||$_SESSION['dr_is_admin']==true) {
echo strtolower('<div align="center">' . tr("DISPLAY") . ' : ' . tr("LIST") . ' : 
		 <a href="?page=profils&token='.$token.'&list_id='.$list_id.'&st=1&ta=list&itemp=30">'. tr("DEFAULT") .'</a>, 
		 <a href="?page=profils&token='.$token.'&list_id='.$list_id.'&st=1&ta=all&itemp='.$itemp.'">'. tr("ALL_LISTS") .'</a></div>');
}
echo $headerProfil;
$p_select = " SELECT e.id,e.email,e.hash,e.campaign_id,a.subject,
		   (SELECT SUM(l.cpt)FROM ". $row_config_globale['table_track_links'] ." l WHERE l.hash = e.hash GROUP BY l.hash) AS SUMCPT,
		   (SELECT count(distinct(subject)) FROM ". $row_config_globale['table_tracking'] ." dt WHERE dt.hash = e.hash) AS CPTCP,
		   (SELECT min(subject) FROM ". $row_config_globale['table_tracking'] ." mis WHERE mis.hash = e.hash) AS MINCP,
		   (SELECT max(subject) FROM ". $row_config_globale['table_tracking'] ." mas WHERE mas.hash = e.hash) AS MAXCP " ;
$p_from =	" FROM " . $row_config_globale['table_email'] . " e
				  LEFT JOIN ". $row_config_globale['table_archives'] ." a ON a.id=e.campaign_id " ;
$p_group =	" GROUP BY e.hash ";
$p_order =	" ORDER BY email ASC ";
$query = $p_select.$p_from.$p_where.$p_group.$p_order.@$p_limit; 
$tab_users=$cnx->query($query)->fetchAll(PDO::FETCH_ASSOC);
echo '<table cellpadding="0" cellspacing="0" border="0" class="display table" id="datatable">
<thead> 
	<tr> 
		<th class="text-left">'  .tr("EMAIL")				   .'</th>
		<th class="text-center">'.tr("LAST_CAMPAIGN_SEND")   .'</th>
		<th class="text-center">'.tr("FIRST_CAMPAIGN_SEND")  .'</th>
		<th class="text-center">'.tr("LAST_CAMPAIGN_OPEN")   .'</th>
		<th class="text-center">'.tr("COUNT_CAMPAIGNS_TOTAL").'</th>
		<th class="text-center">'.tr("COUNT_CAMPAIGNS_OPEN") .'</th>
		<th class="text-right">' .tr("RATIO_READER")		   .'</th>
		<th class="text-center">'.tr("PROFIL")			   .'</th>
		<th class="text-center">'.tr("COUNT_CLICK")		   .'</th>
		<th class="text-right">' .tr("RATIO_CLICKER")		   .'</th>
	</tr> 
</thead>
<tfoot> 
	<tr> 
		<th class="text-left">'  .tr("EMAIL")				   .'</th>
		<th class="text-center">'.tr("LAST_CAMPAIGN_SEND")   .'</th>
		<th class="text-center">'.tr("FIRST_CAMPAIGN_SEND")  .'</th>
		<th class="text-center">'.tr("LAST_CAMPAIGN_OPEN")   .'</th>
		<th class="text-center">'.tr("COUNT_CAMPAIGNS_TOTAL").'</th>
		<th class="text-center">'.tr("COUNT_CAMPAIGNS_OPEN") .'</th>
		<th class="text-right">' .tr("RATIO_READER")		   .'</th>
		<th class="text-center">'.tr("PROFIL")			   .'</th>
		<th class="text-center">'.tr("COUNT_CLICK")		   .'</th>
		<th class="text-right">' .tr("RATIO_CLICKER")		   .'</th>
	</tr> 
</tfoot>
<tbody>';
foreach	 ($tab_users as $item){
	$nbcmpsend=0;
	$profil = '<span style="padding:3px;font: 12px arial,sans-serif;">';
	$bgcolor = $rgbcolor ='';
	$statclk = $statread = '==';
	if((int)$item['MINCP']>0&&(int)$item['campaign_id']>0) {
		$nbcmpsend_where = " list_id=".$list_id." AND ";
		$nbcmpsend=@current($cnx->query("
				 SELECT COUNT(id) 
					 FROM ". $row_config_globale['table_archives'] ." 
				 WHERE $nbcmpsend_where 
					   id>=".$item['MINCP']." AND id<=".$item['campaign_id'].";")->fetch());
	}
	if($nbcmpsend<5){
		$profil .= tr("WAITING_FOR_MORE_STATS");
		$bgcolor = '#d1cdc6';
		$rgbcolor= 'rgb(0,0,0)';
		$statread = '==';
	}elseif($nbcmpsend>4){
		if(($item['SUMCPT']/$nbcmpsend*100)>50) {
			$profil .= tr("OPENER_CLICKER");
			$bgcolor = '#0cb21f';
			$rgbcolor= 'rgb(255,255,255)';
		} elseif(($item['CPTCP']/$nbcmpsend*100)>50) {
			$profil .= tr("OPENER_READER");
			$bgcolor = '#0cb21f';
			$rgbcolor= 'rgb(255,255,255)';
		} elseif(($item['SUMCPT']/$nbcmpsend*100)>25) {
			$profil .= tr("MIDDLE_CLICKER");
			$bgcolor = '#1b7be2';
			$rgbcolor= 'rgb(255,255,255)';
		} elseif(($item['CPTCP']/$nbcmpsend*100)>25) {
			$profil .= tr("MIDDLE_READER");
			$bgcolor = '#1b7be2';
			$rgbcolor= 'rgb(255,255,255)';
		} elseif(($item['SUMCPT']/$nbcmpsend*100)>10) {
			$profil .= tr("SOME_CLICKS");
			$bgcolor = '#ffe102';
			$rgbcolor= 'rgb(0,0,0)';
		} elseif(($item['CPTCP']/$nbcmpsend*100)>10) {
			$profil .= tr("SOME_READ");
			$bgcolor = '#ffe102';
			$rgbcolor= 'rgb(0,0,0)';
		} elseif(($item['SUMCPT']/$nbcmpsend*100)>0) {
			$profil .= tr("SMALL_CLICKS");
			$bgcolor = '#ff7700';
			$rgbcolor= 'rgb(0,0,0)';
		} elseif(($item['CPTCP']/$nbcmpsend*100)>0) {
			$profil .= tr("SMALL_READ");
			$bgcolor = '#ff7700';
			$rgbcolor= 'rgb(0,0,0)';
		} elseif(($item['CPTCP']/$nbcmpsend*100)==0) {
			$profil .= tr("NO_OPEN");
			$bgcolor = '#e80707';
			$rgbcolor= 'rgb(255,255,255)';
		}
		$statclk = sprintf("%01.2f",($item['SUMCPT']/$nbcmpsend*100)) . '% ';
		$statread= sprintf("%01.2f",($item['CPTCP']/$nbcmpsend*100)) . '% ';
	}
	$profil .= '</span>';
	echo '<tr>
		<td class="text-left"><a data-toggle="modal" data-target="#modalPmnl" data-tooltip="tooltip" href="include/subscriber_detail.php?hash='
			. $item['hash'] . '&list_id=' . $list_id . '&token=' . $token . '" title="Détail activité">' . $item['email'] .'</td>
		<td class="text-center">'. ($item['campaign_id']!=0?$item['campaign_id']:'==') /* dernière campagne envoyée */ .'</td>
		<td class="text-center">'. ($item['MINCP']!=''?$item['MINCP']:'==') /* 1ère campagne envoyée */ .'</td>
		<td class="text-center">'. ($item['MAXCP']!=''?$item['MAXCP']:'==') /* dernière campagne ouverte */ .'</td>
		<td class="text-center">'. $nbcmpsend /* campagnes totales */ .'</td>
		<td class="text-center">'. $item['CPTCP'] /* campagnes lues */ .'</td> 
		<td class="text-right">' . $statread /* ratio lecture */ . '</td>
		<td class="text-center" style="background-color:'.$bgcolor.';color:'.$rgbcolor.';">'. $profil /* profil */ .'</td>
		<td class="text-center">'. ($item['SUMCPT']!=''?$item['SUMCPT']:0) /* nombre de clics */ .'</td>
		<td class="text-right">' . $statclk /* ratio cliqueur */. '</td>
	</tr>';
}
echo '<tbody>
</table>
<script>
$(\'.modal-body\').on(\'loaded.bs.modal\', function (e) {
	$(\'.modal-body\').removeData();
});
$(document).ready(function(){
	$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () {
		$(this).removeData(\'bs.modal\');
	});
});
$(document).ready(function(){
	$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () {
		$(this).removeData(\'bs.modal\');
		$("#" + $(this).attr("id") + " .modal-body").empty();
		$("#" + $(this).attr("id") + " .modal-body").append("Loading...");
	});
});
</script>';
