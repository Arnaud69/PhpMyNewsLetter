<?php
if(!file_exists("config.php")) {
	header("Location:../install.php");
	exit;
} else {
	include("../_loader.php");
	if(isset($_POST['token'])){$token=$_POST['token'];}elseif(isset($_GET['token'])){$token=$_GET['token'];}else{$token='';}
	if(!tok_val($token)){
		header("Location:login.php?error=2");
		exit;
	}
}

$id_mail = (!empty($_GET['id_mail'])) ? intval($_GET['id_mail']) : '';
$list_id = (!empty($_GET['list_id'])) ? intval($_GET['list_id']) : '';
if(empty($id_mail)&&empty($list_id)){
	header("Location:../login.php?error=2");
	exit;
}
include 'lib/constantes_stats.php';
$modal_title = $cnx->query("SELECT subject 
			FROM ".$row_config_globale['table_archives']." 
		WHERE id=".$id_mail." AND list_id=".$list_id)->fetch();
$title_modal = $modal_title['subject'];
echo '
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Liste <b>' . $list_id . '</b>, campagne "<i>' . $title_modal . '</i>"</h4>
</div>
<div class="modal-body">
<script type="text/javascript">$(document).ready(function() { $(".tablesorter").tablesorter(); } );</script>
<script type="text/javascript" src="js/amcharts/pie.js"></script>
<script type="text/javascript" src="js/amcharts/themes/light.js"></script>
<script type="text/javascript" src="js/amcharts/themes/none.js"></script>
<header>
	<h4>' . tr("CLICKED_LINK_REPORT") . '</h4>
</header>';

$count_clicked_links = $cnx->query("SELECT SUM(cpt) AS CPT 
					FROM ".$row_config_globale['table_track_links']." 
				WHERE list_id=" . $list_id . "
					AND msg_id=" . $id_mail . " 
				ORDER BY CPT DESC")->fetch();
if($count_clicked_links['CPT']>0){
	echo '<table class="tablesorter table table-striped" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="text-align:left">' . tr("CLICKED_LINK") . '</th>
				<th style="text-align:right">' . tr("CLICKED_COUNT") . '</th>
				<th style="text-align:right">%</th> 
			</tr> 
		</thead> 
		<tbody>';
	$links = $cnx->query("SELECT link,sum(cpt) AS CPT_PER_LINK
				FROM " . $row_config_globale['table_track_links'] . " 
			WHERE list_id=" . $list_id . "
				AND msg_id=" . $id_mail . "
			GROUP BY substr(link,1,25)
			ORDER BY cpt DESC")->fetchAll(PDO::FETCH_ASSOC);
	$chart_data='';
	$datalinks = '';
	@(int)$cptlinks='';
	@(int)$totalAffiche = 0;
	foreach($links as $row){
		$percent = number_format(($row['CPT_PER_LINK']/$count_clicked_links['CPT']*100), 2, ',', '');
		$percentcss = number_format(($row['CPT_PER_LINK']/$count_clicked_links['CPT']*100),0, ',', '');
		(intval(strlen($row['link']))>30)?$clicked_link=substr($row['link'], 0, 30).'[...]':$clicked_link=$row['link'];
		echo '<tr>
			<td style="text-align:left">' . $row['link'] . '</td>
			<td style="text-align:right">' . $row['CPT_PER_LINK'] . '</td>
			<td style="text-align:right">' . $percent . '%</td>
		</tr>';
		$cptlinks .= $row['CPT_PER_LINK'].',' ;
		$datalinks .= '"' . $clicked_link . '",';
	}
	echo '</table>
	<header>
		<h4>' . tr("CLICKED_LINK_REPORT_GRAPHIC") . '</h4>
	</header>
	<div class="row">
		<div class="col-md-4">
			<div class="text-center">
				<canvas id="DchartLinks" />
			</div>
		</div>
	</div>';
} else {
	echo '<h4 class="alert alert-warning">'.tr("CLICKED_LINK_NO_LINK").'</h4>';
}
$count_open = $cnx->query("SELECT SUM(open_count) AS total 
				FROM ".$row_config_globale['table_tracking']." 
			WHERE subject=".$id_mail)->fetch();
$total = $count_open['total'];
$results_stat_browser = $cnx->query('SELECT DISTINCT(CONCAT(browser,\' \',SUBSTRING_INDEX(version,\'.\',1))) AS browser, COALESCE(SUM(open_count),0) AS data
					FROM ' . $row_config_globale['table_tracking'] . ' 
				WHERE subject='.$id_mail.' 
					AND browser!=\'\'
					AND version!=\'unknown\'
					AND browser NOT IN (\'iPhone\',\'iPad\',\'Android\')
					AND CONCAT(browser,\' \',SUBSTRING_INDEX(version,\'.\',1)) NOT IN (\'Mozilla 4.0\',\'Mozilla 5.0\')
				GROUP BY CONCAT(browser,\' \',SUBSTRING_INDEX(version,\'.\',1))
				HAVING COUNT(*)>'.($total/100).'
				ORDER BY data DESC;');
if ( $results_stat_browser->rowCount() > 0 ) {
	$databrowser = '';
	@(int)$cptbrowser = '';
	@(int)$totalAffiche = '';
	foreach ($results_stat_browser as $tab) {
		$cptbrowser .= $tab['data'] .',' ;
		$databrowser .= '"' . $tab['browser'] . ' ('.round(((int)$tab['data']/$total*100),2).'%) ",';
		@$totalAffiche = $totalAffiche+(int)$tab['data'];
	}
	
	if ( $total-$totalAffiche > 0 ) {
		$cptbrowser .= $total-$totalAffiche ;
		$databrowser .= '"Autres ('.round((( $total-$totalAffiche )/$total*100),2).'%) "';
	}
}
$results_stat_platform = $cnx->query('SELECT DISTINCT(platform) AS platform, COALESCE(SUM(open_count),0) AS data
						FROM ' . $row_config_globale['table_tracking'] . ' 
					WHERE subject='.$id_mail.' 
						AND platform!=\'\' 
						AND platform!=\'unknown\'
					GROUP BY platform
					HAVING COUNT(*)>'.($total/100).'
					ORDER BY data DESC;');

if ( $results_stat_platform->rowCount() > 0 ) {
	$dataplatform = '';
	@(int)$cptplatform = '';
	@(int)$totalAffiche = '';
	foreach ($results_stat_platform as $tab) {
		$cptplatform .=	 $tab['data'] . ',';
		$dataplatform .= '"' . $tab['platform'] . ' ('.round(((int)$tab['data']/$total*100),2).'%) ",';
		@$totalAffiche = $totalAffiche+(int)$tab['data'];
	}
	if ( $total-$totalAffiche > 0 ) {
		$cptplatform .= $total-$totalAffiche ;
		$dataplatform .= '"Autres ('.round((($total-$totalAffiche )/$total*100),2).'%) "';
	}
}
$results_stat_devicetype= $cnx->query('SELECT DISTINCT(devicetype) AS devicetype, COALESCE(SUM(open_count),0) AS data
						FROM ' . $row_config_globale['table_tracking'] . ' 
					WHERE subject='.$id_mail.' 
						AND devicetype!=\'\'
					GROUP BY devicetype
					HAVING COUNT(*)>'.($total/100).'
					ORDER BY data DESC;'
					);

if ( $results_stat_devicetype->rowCount() > 0 ) {
	$datadevicetype = '';
	@(int)$cptdevicetype='';
	@(int)$totalAffiche ='';
	foreach ($results_stat_devicetype as $tab) {
		$cptdevicetype .= $tab['data'] . ',';
		$datadevicetype .= '"' . $tab['devicetype'] . ' (' . round(((int)$tab['data']/$total*100),2) . '%) ",';
		@$totalAffiche = $totalAffiche+(int)$tab['data'];
	}
	if ( $total-$totalAffiche > 0 ) {
		$cptdevicetype .= $total-$totalAffiche ;
		$datadevicetype .= '"Autres (' . round((($total-$totalAffiche )/$total*100),2) . '%) "';
	}
}
$TOTALUSERAGENT = $cnx->query('SELECT SUM(open_count) AS total 
				FROM ' . $row_config_globale['table_tracking'] . ' 
			WHERE subject=' . $id_mail)->fetch();
$totalua = $TOTALUSERAGENT['total'];
$totalAffiche = 0;
$results_stat_ua= $cnx->query('SELECT DISTINCT(useragent) AS useragent,
				COALESCE(SUM(open_count),0) AS data
			FROM ' . $row_config_globale['table_tracking'] . ' 
				WHERE subject=' . $id_mail . ' 
				   AND (useragent like "%outlook%"
				   OR useragent like "%Thunderbird%"
				   OR useragent like "%Icedove%"
				   OR useragent like "%Shredder%"
				   OR useragent like "%Airmail%"
				   OR useragent like "%Lotus-Notes%"
				   OR useragent like "%Barca%"
				   OR useragent like "%Postbox%"
				   OR useragent like "%MailBar%"
				   OR useragent like "%The Bat!%"
				   OR useragent like "%GoogleImageProxy%")
				GROUP BY useragent
				ORDER BY data DESC;');

if ( $results_stat_ua->rowCount() > 0 ) {
	$tmpDataUa=array();
	foreach ($results_stat_ua as $tab) {
		$str = $tab['useragent'];
		$mua=array();
		if(preg_match('/Thunderbird(?:\/(\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Thunderbird']=@$tmpDataUa['Thunderbird']+$tab['data'];
		}elseif(preg_match('/Shredder(?:\/(\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Shredder']=@$tmpDataUa['Shredder']+$tab['data'];
		}elseif(preg_match('/Icedove(?:\/(\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Icedove']=@$tmpDataUa['Icedove']+$tab['data'];
		}elseif(preg_match('/Outlook-Express(?:\/(\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Outlook-Express']=@$tmpDataUa['Outlook-Express']+$tab['data'];
		/*
		}elseif(preg_match('/Microsoft Outlook(?: Mail)?(?:[\/ ](\d+[\.\d]+))?/iD', $str, $matches)) {
			$tmpDataUa['Microsoft Outlook']=$tmpDataUa['Microsoft Outlook']+$tab['data'];
		*/
		}elseif(preg_match('/Microsoft Office Outlook 12\.\d+\.\d+|MSOffice 12/iD', $str)) {
			$tmpDataUa['Outlook 2007']=@$tmpDataUa['Outlook 2007']+$tab['data'];
		}elseif(preg_match('/Microsoft Outlook 14\.\d+\.\d+|MSOffice 14/iD', $str)) {
			$tmpDataUa['Outlook 2010']=@$tmpDataUa['Outlook 2010']+$tab['data'];
		}elseif(preg_match('/Microsoft Outlook 15\.\d+\.\d+/iD', $str)) {
			$tmpDataUa['Outlook 2013']=@$tmpDataUa['Outlook 2013']+$tab['data'];
		}elseif(preg_match('/Microsoft Outlook (?:Mail )?16\.\d+\.\d+/iD', $str)) {
			$tmpDataUa['Outlook 2016']=@$tmpDataUa['Outlook 2016']+$tab['data'];
		/*
		fin spec MSoffice
		*/
		}elseif(preg_match('/Lotus-notes(?:\/(\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Lotus-notes']=@$tmpDataUa['Lotus-notes']+$tab['data'];
		}elseif(preg_match('/Postbox(?:[\/ ](\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Postbox']=@$tmpDataUa['Postbox']+$tab['data'];
		}elseif(preg_match('/MailBar(?:[\/ ](\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['MailBar']=@$tmpDataUa['MailBar']+$tab['data'];
		}elseif(preg_match('/The Bat!(?: Voyager)?(?:[\/ ](\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['The Bat!']=@$tmpDataUa['The Bat!']+$tab['data'];
		}elseif(preg_match('/Barca(?:Pro)?(?:[\/ ](\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Barca']=@$tmpDataUa['Barca']+$tab['data'];
		}elseif(preg_match('/Airmail(?: (\d+[\.\d]+))?/iD', $str)) {
			$tmpDataUa['Airmail']=@$tmpDataUa['Airmail']+$tab['data'];
		}elseif(preg_match('/GoogleImageProxy?/iD', $str)) {
			@$tmpDataUa['Gmail']=@$tmpDataUa['Gmail']+$tab['data'];
		}
	}
	$cptua = '';
	$dataua ='';
	arsort($tmpDataUa);
	foreach ($tmpDataUa as $uaName => $value) {
		$cptua .= $value . ',';
		$dataua .= '"' . $uaName . ' ('.round(((int)$value/$total*100),1).'%) ",';
		@$totalAfficheUa = $totalAfficheUa+(int)$value;
	}
	if ( $totalua-$totalAfficheUa ) {
		$cptua .= $totalua-$totalAfficheUa;
		$dataua .= '"Autres ('.round((($totalua-$totalAfficheUa)/$total*100),2).'%) "';
	}
}
$sql = 'SELECT DATE_FORMAT(CONCAT(DATE(`date`)," ",HOUR(`date`),":00"),"%d/%m/%Y %H:%i") AS DTHR, COUNT( * ) AS CPTDTHR
		FROM ' . $row_config_globale['table_tracking'] . ' 
	WHERE subject =' . $id_mail . ' 
	GROUP BY DTHR
	ORDER BY STR_TO_DATE(DTHR,"%d/%m/%Y %H:%i")';
	//ORDER BY DATE(`date`) ASC';
$results_dthr = $cnx->query($sql);

if ( $results_dthr->rowCount() > 0 ) {
	$labelsdthr='';
	@(int)$datadthr;
	foreach ($results_dthr as $tab) {
		$labelsdthr .= "'" . $tab['DTHR'] . "',";
		@$datadthr  .= (int)$tab['CPTDTHR'] . ',';
	}
}

echo '<header>
	<h4>' . tr("OPEN_DETAILLED_CAMPAIN") . '</h4>
</header>
<div class="row">
	<div class="col-md-3">
		<h4>' . tr("CLICKED_LINK_REPORT_ENVIRONMENT") . '</h4>
		<div class=" text-center">
			<canvas id="DPmnlStatsBrowser" />
		</div>
		<div id="DPmnlStatsBrowser-legend" class="chart-legend"></div>
	</div>
	<div class="col-md-3">
		<h4>' . tr("MAIL_CLIENT") . '</h4>
		<div class=" text-center">
			<canvas id="DPmnlPim" />
		</div>
		<div id="DPmnlPim-legend" class="chart-legend"></div>
	</div>
	<div class="col-md-3">
		<h4>' . tr("CLICKED_LINK_REPORT_OS") . '</h4>
		<div class=" text-center">
			<canvas id="DPmnlStatsPlatform" />
		</div>
		<div id="DPmnlStatsPlatform-legend" class="chart-legend"></div>
	</div>
	<div class="col-md-3">
		<h4>' . tr("SUPPORT") . '</h4>
		<div class=" text-center">
			<canvas id="DPmnlStatsDevicetype" />
		</div>
		<div id="DPmnlStatsDevicetype-legend" class="chart-legend"></div>
	</div>
	
</div>
<div class="text-center">
	<h4>' . tr('OPEN_BY_HOURS') . '</h4>
	<canvas id="ClicByHoursDetail"></canvas>
</div>
<script>
	Chart.defaults.global.legend.display = false;';
	if($count_clicked_links['CPT']>0){
		echo 'var DchartLinks = $("#DchartLinks");
		var DmLinks = new Chart(DchartLinks,{ type: "pie", data:{ labels:[' . $datalinks . '], datasets: [{ data: [' . $cptlinks . '],backgroundColor:[' . $BACKGROUND_STATS_COLORS . ']}]}});';
	}
	echo 'var DPmnlChartBrowser = $("#DPmnlStatsBrowser");
	var DmCbrowser = new Chart(DPmnlChartBrowser, { type: "pie",data:{ labels:[' . $databrowser . '],datasets: [{ data: [' . $cptbrowser . '],backgroundColor:[' . $BACKGROUND_STATS_COLORS . ']}]}});
	document.getElementById("DPmnlStatsBrowser-legend").innerHTML = DmCbrowser.generateLegend();
	var DPmnlChartPim = $("#DPmnlPim");
	var DmPim = new Chart(DPmnlPim, { type: "pie",data:{ labels:[' . $dataua . '],datasets: [{ data: [' . $cptua . '],backgroundColor:[' . $BACKGROUND_STATS_COLORS . ']}]}});
	document.getElementById("DPmnlPim-legend").innerHTML = DmPim.generateLegend();
	var DPmnlChartPlatform = document.getElementById("DPmnlStatsPlatform");
	var DmCplatform = new Chart(DPmnlChartPlatform, { type: "pie",data:{ labels:[' . $dataplatform . '],datasets: [{ data: [' . $cptplatform . '],backgroundColor:[' . $BACKGROUND_STATS_COLORS . ']}]}});
	document.getElementById("DPmnlStatsPlatform-legend").innerHTML = DmCplatform.generateLegend();
	var DPmnlChartDevicetype = $("#DPmnlStatsDevicetype");
	var DmCdevicetype = new Chart(DPmnlChartDevicetype, { type: "pie",data:{ labels:[' . $datadevicetype . '],datasets: [{ data: [' . $cptdevicetype . '],backgroundColor:[' . $BACKGROUND_STATS_COLORS . ']}]}});
	document.getElementById("DPmnlStatsDevicetype-legend").innerHTML = DmCdevicetype.generateLegend();
	var PmnlDthr = $("#ClicByHoursDetail");
	var barData = {
		labels: [' . $labelsdthr . '],
		datasets: [
			{
				label: "' . tr('OPEN_BY_HOURS') . ' ",
				backgroundColor:"rgba(54, 162, 235, 0.2)",
				borderColor:"rgba(54, 162, 235, 1)",
				borderWidth: 1,
				data: [' . $datadthr . ']
			},
		],
		options: {
			responsive: false, maintainAspectRatio: false
		}
	};
	var mCCntClkHour = new Chart(PmnlDthr,{type:"line",data:barData });
</script>
</div>
<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';





				
