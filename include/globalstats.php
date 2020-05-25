<?php

$alert_error_date = '' ;

if ( isset ( $_POST['begin_stats'] ) && ( DateTime::createFromFormat('Y-m-d', $_POST['begin_stats'])->format('Y-m-d') === $_POST['begin_stats'] ) ) {
	$date_min = $_POST['begin_stats'];
} elseif ( isset ( $_GET['begin_stats'] ) && ( DateTime::createFromFormat('Y-m-d', $_GET['begin_stats'])->format('Y-m-d') === $_GET['begin_stats'] ) ) {
	$date_min = $_GET['begin_stats'];
} else {
	$date_min = '';
}
if ( isset ( $_POST['end_stats'] ) && ( DateTime::createFromFormat('Y-m-d', $_POST['end_stats'])->format('Y-m-d') === $_POST['end_stats'] ) ) {
	$date_max = $_POST['end_stats'];
	$date1=date_create($date_min);
	$date2=date_create($date_max );
	$diff=date_diff($date1,$date2);
	if ( ((int)$diff->format("%R%a"))<0 ){
		$alert_error_date='<div class="alert alert-danger">Dates choisies en erreur</div>';
		$date_min='';$date_max='';
	} else {
		$alert_error_date='';
	}
} elseif ( isset ( $_GET['end_stats'] ) && ( DateTime::createFromFormat('Y-m-d', $_GET['end_stats'])->format('Y-m-d') === $_GET['end_stats'] ) ) {
	$date_max = $_GET['end_stats'];
	$date1=date_create($date_min);
	$date2=date_create($date_max );
	$diff=date_diff($date1,$date2);
	if ( ((int)$diff->format("%R%a"))<0 ){
		$alert_error_date='<div class="alert alert-danger">Dates choisies en erreur</div>';
		$date_min='';$date_max='';
	} else {
		$alert_error_date='';
	}
} else {
	$date_max = '';
}

$array_sub_allowed = array('env','map');
if ( isset ( $_GET['view'] ) && in_array($_GET['view'], $array_sub_allowed) ) {
	$view = $_GET['view'];
} elseif ( isset ( $_POST['view'] ) && in_array($_POST['view'], $array_sub_allowed) ) {
	$view = $_POST['view'];
} else {
	$view = 'env';
}

$row = get_stats_send_global($cnx,$row_config_globale,$date_min,$date_max);
$array_date_stats = get_stats_date($cnx,$row_config_globale);

echo '<script type="text/javascript" src="js/amcharts/amcharts.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/ammap.js"></script>
<script src="//www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
<script src="//www.amcharts.com/lib/3/themes/dark.js"></script>
<script type="text/javascript" src="js/amcharts/serial.js"></script>
<script type="text/javascript" src="js/amcharts/lang/fr.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link type="text/css" href="js/amcharts/plugins/export/export.css" rel="stylesheet">
<header><h4>' . tr("KEY_NUMBERS_ALL_LISTS") . '</h4></header>
<div class="row">
	<form method="post" action="' . $_SERVER['PHP_SELF'] . '">
		<div class="col-md-6">
			<ul class="nav nav-pills">
				<li' . ($view=='env'?' class="active"':'') . '><a href="' . $_SERVER['PHP_SELF'] . '?page=globalstats&token=' . $token . '&list_id=' . $list_id .'&view=env&begin_stats=' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . '&end_stats=' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . '">' . tr("ENVIRONMENT_ALL_CAMPAIGNS") . '</a></li>
				<li class="dropdown' . ($view=='map'?' active':'') . '">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">' . tr('GEOLOCALISATION') . '<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="' . $_SERVER['PHP_SELF'] . '?page=globalstats&token=' . $token . '&list_id=' . $list_id .'&view=map&begin_stats=' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . '&end_stats=' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . '&tm=twn">' . tr('BY_TOWN') . '</a></li>
						<li><a href="' . $_SERVER['PHP_SELF'] . '?page=globalstats&token=' . $token . '&list_id=' . $list_id .'&view=map&begin_stats=' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . '&end_stats=' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . '&tm=ctry">' . tr('BY_COUNTRY') . '</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="col-md-2">
			<div class="input-group">
	  			<span class="input-group-addon">Du : </span>
				<input class="form-control" type="date" name="begin_stats" value="' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . '" min="' . $array_date_stats[0]['DT_MIN'] . '" max="' . $array_date_stats[0]['DT_MAX'] . '">
			</div>
		</div>
		<div class="col-md-2">
			<div class="input-group">
	  			<span class="input-group-addon">Au: </span>
	  			<input class="form-control" type="date" name="end_stats" value="' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . '" min="' . $array_date_stats[0]['DT_MIN'] . '" max="' . $array_date_stats[0]['DT_MAX'] . '">
			</div>
		</div>
		<div class="col-md-1 text-right">
			<input type="submit" value="OK" class="btn btn-primary btn-sm">
		</div>
		<input type="hidden" value="globalstats" name="page">
		<input type="hidden" value="' . $token . '" name="token">
		<input type="hidden" value="' . $view . '" name="view">
		<input type="hidden" value="' . @$tm . '" name="tm">
	</form>
	<form method="post" action=""' . $_SERVER['PHP_SELF'] . '">
		<div class="col-md-1">
			<input type="hidden" name="begin_stats" value="' . $array_date_stats[0]['DT_MIN'] . '">
  			<input type="hidden" name="end_stats" value="' . $array_date_stats[0]['DT_MAX']. '">
			<input type="submit" value="RAZ" class="btn btn-primary btn-sm">
			<input type="hidden" value="globalstats" name="page">
			<input type="hidden" value="' . $list_id . '" name="list_id">
			<input type="hidden" value="' . $token . '" name="token">
			<input type="hidden" value="' . $view . '" name="view">
			<input type="hidden" value="' . @$tm . '" name="tm">
		</div>
	</form>
</div>';
if(count($row)>0){
	if($page != "config"){
		$OPENRATE = @round(($row[0]['TID']/($row[0]['TMAILS']-$row[0]['TERROR'])*100),1);//OPEN RATE
		$CTR = @round(($row[0]['CPT_CLICKED']/$row[0]['TMAILS']*100),1);//CTR
		$ACTR = @round(($row[0]['CPT_CLICKED']/$row[0]['TOPEN']*100),1);//ACTR
		echo '<table class="tablesorter table table-striped"> 
		<thead> 
			<tr>
				<th style="text-align:center">' . tr("CAMPAIGNS") . '</th>
				<th style="text-align:center">' . tr("SCHEDULE_CAMPAIGN_SENDED") . '</th>
				<th style="text-align:center">' . tr("TRACKING_READ") . '</th>
				<th style="text-align:center">' . tr("TRACKING_OPENED")	. '</th>
				<th style="text-align:center">' . tr("CLICKS") . '</th>
				<th style="text-align:center">' . tr("OPEN_RATE") . '</th>
				<th style="text-align:center">' . tr("CTR") . '</th>
				<th style="text-align:center">' . tr("ACTR")  . '</th>
				<th style="text-align:center">' . tr("TRACKING_ERROR") . '</th>
				<th style="text-align:center">' . tr("TRACKING_UNSUB") . '</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td style="text-align:center"><h2>'. $row[0]['TSEND'] .	 '</h2></td>
				<td style="text-align:center"><h2>'. $row[0]['TMAILS'] . '</h2></td>
				<td style="text-align:center"><h2>'. ($row[0]['TOPEN']!=''?$row[0]['TOPEN']:0) . '</h2></td>
				<td style="text-align:center"><h2>'. $row[0]['TID'] . '</h2></td>
				<td style="text-align:center"><h2>'. $row[0]['CPT_CLICKED'] . '</h2></td>
				<td style="text-align:center"><h2><a data-toggle="tooltip" title="'. tr( "TRACKING_BULLE_OPEN_RATE" ) .'">'.($OPENRATE>0?'<b>'.$OPENRATE.'</b>':0).'%</a></h2></td>
				<td style="text-align:center"><h2><a data-toggle="tooltip" title="'. tr( "TRACKING_BULLE_ACTR" ) .'">'.($ACTR>0?'<b>'.$ACTR.'</b>':0).'%</a></h2></td>
				<td style="text-align:center"><h2>'. $row[0]['TERROR']. '</h2></td>
				<td style="text-align:center"><h2>'. $row[0]['TLEAVE']. '</h2></td>
			</tr>
		</table>';
	} elseif($list_name == -1) {
		$error_list = true;
	} elseif(empty($list) && $page != "newsletterconf" && $page != "config") {
		echo "<div align='center' class='tooltip critical'>".tr("ERROR_NO_NEWSLETTER_CREATE_ONE")."</div>";
		$error_list = true;
		exit();
	} else {
		// dummy !
	}
	switch ( $view ) {
		default:
		case 'env' :
			$TOTALBROWSER = $cnx->query('SELECT COUNT(*) AS total 
				FROM ' . $row_config_globale['table_tracking'] . '
					WHERE browser!=\'\'
					   AND version!=\'unknown\'
					   AND browser NOT IN (\'iPhone\',\'iPad\',\'Android\')
					   AND CONCAT(browser,\' \',SUBSTRING_INDEX(version,\'.\',1)) NOT IN (\'Mozilla 4.0\',\'Mozilla 5.0\')
					   AND subject IN
						(SELECT id FROM ' . $row_config_globale['table_archives'] . '
						WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
						AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")'
			)->fetch();
			$total = $TOTALBROWSER['total'];
			$results_stat_browser = $cnx->query('SELECT CONCAT(browser,\' \',SUBSTRING_INDEX(version,\'.\',1)) AS browser,	COALESCE(COUNT(*),0) AS data
				FROM ' . $row_config_globale['table_tracking'] . ' 
					WHERE browser!=\'\'
					   AND version!=\'unknown\'
					   AND browser NOT IN (\'iPhone\',\'iPad\',\'Android\')
					   AND version!=\'unknown\'
					   AND CONCAT(browser,\' \',SUBSTRING_INDEX(version,\'.\',1)) NOT IN (\'Mozilla 4.0\',\'Mozilla 5.0\')
					   AND subject IN
						(SELECT id FROM ' . $row_config_globale['table_archives'] . '
						WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
						AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")
				GROUP BY CONCAT(browser,\' \',SUBSTRING_INDEX(version,\'.\',1))
				HAVING COUNT(*)>'.($total/100).'
					ORDER BY data DESC;'
			)->fetchAll();
			if (count($results_stat_browser)>0&&$total>0) {
				$databrowser = '';
				@(int)$cptbrowser;
				@(int)$totalAffiche;
				foreach ($results_stat_browser as $tab) {
					@$cptbrowser .= (int)$tab['data'] .',' ;
					$databrowser .= '"' . $tab['browser'] . ' ('.round(((int)$tab['data']/$total*100),2).'%) ",';
					@$totalAffiche = $totalAffiche+(int)$tab['data'];
				}
				$cptbrowser .= $total-$totalAffiche ;
				$databrowser .= '"Others <1% ('.round((($total-$totalAffiche )/$total*100),2).'%) ",';
			}
			$TOTALBROWSER = $cnx->query('SELECT COUNT(*) AS total 
				FROM ' . $row_config_globale['table_tracking'] . '
					WHERE platform!="" 
						AND platform!="unknown"
						AND version!=\'unknown\'
					   	AND subject IN
							(SELECT id FROM ' . $row_config_globale['table_archives'] . '
							WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
							AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")'
			)->fetch();
			$total = $TOTALBROWSER['total'];
			$results_stat_platform = $cnx->query('SELECT DISTINCT(platform) AS platform,
					COALESCE(COUNT(*),0) AS data
				FROM ' . $row_config_globale['table_tracking'] . ' 
					WHERE platform!=\'\' 
						AND platform!=\'unknown\'
						AND subject IN
							(SELECT id FROM ' . $row_config_globale['table_archives'] . '
							WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
							AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")
				GROUP BY platform
				ORDER BY data DESC;'
			)->fetchAll();
			if (count($results_stat_platform)>0&&$total>0) {
				$dataplatform = '';
				@(int)$cptplatform;
				foreach ($results_stat_platform as $tab) {
					@$cptplatform .=  $tab['data'] . ',';
					$dataplatform .= '"' . $tab['platform'] . ' ('.round(((int)$tab['data']/$total*100),2).'%) ",';
				}
			}
			$TOTALDEVICE = $cnx->query('SELECT COUNT(*) AS total 
				FROM ' . $row_config_globale['table_tracking'] . '
					WHERE devicetype!=""
						AND subject IN
							(SELECT id FROM ' . $row_config_globale['table_archives'] . '
							WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
							AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")'
			)->fetch();
			$total = $TOTALDEVICE['total'];
			$results_stat_devicetype= $cnx->query('SELECT DISTINCT(devicetype) AS devicetype,
					COALESCE(COUNT(*),0) AS data
				FROM ' . $row_config_globale['table_tracking'] . ' 
					WHERE devicetype!=""
						AND subject IN
							(SELECT id FROM ' . $row_config_globale['table_archives'] . '
							WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
							AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")
				GROUP BY devicetype
				ORDER BY data DESC;'
			)->fetchAll();
			if (count($results_stat_devicetype)>0&&$total>0) {
				$datadevicetype = '';
				(int)$cptdevicetype = '';
				foreach ($results_stat_devicetype as $tab) {
					$cptdevicetype .= $tab['data'] . ',';
					$datadevicetype .= '"' . $tab['devicetype'] . ' ('.round(((int)$tab['data']/$total*100),2).'%) ",';
				}
			}
			$TOTALUSERAGENT = $cnx->query('SELECT COUNT(*) AS total 
				FROM ' . $row_config_globale['table_tracking'] . ' 
					WHERE (
						useragent REGEXP "MSOffice [[:digit:]]"
						OR useragent LIKE "%Thunderbird%"
						OR useragent LIKE "%Icedove%"
						OR useragent LIKE "%Shredder%"
						OR useragent LIKE "%Airmail%"
						OR useragent LIKE "%Lotus-Notes%"
						OR useragent LIKE "%Barca%"
						OR useragent LIKE "%Postbox%"
						OR useragent LIKE "%MailBar%"
						OR useragent LIKE "%The Bat!%"
						OR useragent LIKE "%GoogleImageProxy%")
						AND subject IN
							(SELECT id FROM ' . $row_config_globale['table_archives'] . '
							WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
							AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")'
			)->fetch();
			$totalua = $TOTALUSERAGENT['total'];
			$results_stat_ua= $cnx->query('SELECT useragent,
						COALESCE(COUNT(*),0) AS data
					FROM ' . $row_config_globale['table_tracking'] . ' 
						WHERE (
							useragent REGEXP "MSOffice [[:digit:]]"
							OR useragent LIKE "%Thunderbird%"
							OR useragent LIKE "%Icedove%"
							OR useragent LIKE "%Shredder%"
							OR useragent LIKE "%Airmail%"
							OR useragent LIKE "%Lotus-Notes%"
							OR useragent LIKE "%Barca%"
							OR useragent LIKE "%Postbox%"
							OR useragent LIKE "%MailBar%"
							OR useragent LIKE "%The Bat!%"
							OR useragent LIKE "%GoogleImageProxy%")
							AND subject IN
								(SELECT id FROM ' . $row_config_globale['table_archives'] . '
								WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
								AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")
						GROUP BY useragent
						ORDER BY data DESC;'
			)->fetchAll();
			if (count($results_stat_ua)>0) {
				$tmpDataUa=array(
				);
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
						$tmpDataUa['Gmail']=@$tmpDataUa['Gmail']+$tab['data'];
					}
				}
				(int)$cptua = '';
				$dataua = '';
				arsort($tmpDataUa);
				foreach ($tmpDataUa as $uaName => $value) {
					$cptua .= $value . ',';
					$dataua .= '"' . $uaName . ' ('.round(((int)$value/$totalua*100),1).'%) ",';
				}
			}
			$TOTALDOMAINES = $cnx->query('SELECT COUNT(*) AS total 
				FROM ' . $row_config_globale['table_email']
			)->fetch();
			$total = $TOTALDOMAINES['total'];
			$results_stat_domaines= $cnx->query('SELECT DISTINCT(LOWER(SUBSTRING_INDEX(email,\'@\',-1))) AS DOMAINES, COUNT(*) AS DATA 
				FROM ' . $row_config_globale['table_email'] . ' 
				GROUP BY DOMAINES
				HAVING COUNT(*)>'.($total/100).'
					ORDER BY DATA DESC;'
			)->fetchAll();
			if (count($results_stat_domaines)>0&&$total>0) {
				$datadomaines = '';
				(int)$cptdomaines = '';
				(int)$totalAffiche = 0;
				foreach ($results_stat_domaines as $tab) {
					$cptdomaines .= $tab['DATA'] . ',';
					$datadomaines .= '"' . $tab['DOMAINES'] . ' ('.round(((int)$tab['DATA']/$total*100),2).'%) ",';
					$totalAffiche = $totalAffiche+(int)$tab['DATA'];
				}
				$cptdomaines .= $total-$totalAffiche ;
				$datadomaines .= '"Others <1% ('.round((($total-$totalAffiche )/$total*100),2).'%) ",';
			}
			$TOTALDOMAINESCLK = $cnx->query('SELECT COUNT(*) AS total 
				FROM ' . $row_config_globale['table_email'] . ' E
					RIGHT JOIN ' . $row_config_globale['table_tracking'] . ' T ON E.hash=T.hash
				WHERE E.campaign_id>0
					AND subject IN
						(SELECT id FROM ' . $row_config_globale['table_archives'] . '
						WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
						AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")'
			)->fetch();
			$total = $TOTALDOMAINESCLK['total'];
			$results_stat_domaines_clk= $cnx->query('SELECT LOWER(SUBSTRING_INDEX(E.email,\'@\',-1)) AS DOMAINES, COUNT(T.id) AS DATA
				FROM ' . $row_config_globale['table_email'] . ' E
					LEFT JOIN ' . $row_config_globale['table_tracking'] . ' T ON E.hash=T.hash
				WHERE T.subject IN
					(SELECT id FROM ' . $row_config_globale['table_archives'] . '
					WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
					AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")
				GROUP BY DOMAINES
				HAVING COUNT(T.id)>'.($total/100).'
					ORDER BY DATA DESC;'
			)->fetchAll();
			if (count($results_stat_domaines_clk)>0&&$total>0) {
				$datadomainesclk = '';
				(int)$cptdomainesclk = '';
				(int)$totalAfficheclk = 0;
				foreach ($results_stat_domaines_clk as $tab) {
					$cptdomainesclk .= (int)$tab['DATA'] . ',';
					$datadomainesclk .= '"' . $tab['DOMAINES'] . ' ('.round(((int)$tab['DATA']/$total*100),2).'%) ",';
					$totalAfficheclk= $totalAfficheclk+(int)$tab['DATA'];
				}
				if(($total-$totalAfficheclk)>0){
					$cptdomainesclk .= $total-$totalAfficheclk;
					$datadomainesclk .= '"Others <1% ('.round((($total-$totalAfficheclk)/$total*100),2).'%) ",';
				}
			}
			$results_dthr = $cnx->query('SELECT HOUR(`date`) AS DTHR, COUNT( * ) AS CPTDTHR
				FROM ' . $row_config_globale['table_tracking'] . ' 
				WHERE subject IN
					(SELECT id FROM ' . $row_config_globale['table_archives'] . '
					WHERE date>"' . ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
					AND date<"' . ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59")
				GROUP BY DTHR
				ORDER BY DTHR;')->fetchAll();
			$labelsdthr='';
			(int)$datadthr = '';
			if (count($results_dthr) >0) {
				foreach ($results_dthr as $tab) {
					$labelsdthr.="'".sprintf("%02d",$tab['DTHR'])."H00',";
					$datadthr.= (int)$tab['CPTDTHR'].',';
				}
			}
			echo '<header><h4>' . tr("ENVIRONMENT_ALL_LISTS") . '</h4></header>
			<table class="table table-striped">
				<tr>
					<td width="25%"><div align="center"><h4>' . tr("CLICKED_LINK_REPORT_ENVIRONMENT") . '</h4></div><canvas id="PmnlStatsBrowser" /></td>
					<td width="25%"><div align="center"><h4>' . tr("MAIL_CLIENT") . '</h4></div><canvas id="PmnlPim" /></td>
					<td width="25%"><div align="center"><h4>' . tr("CLICKED_LINK_REPORT_OS") . '</h4></div><canvas id="PmnlStatsPlatform" /></td>
					<td width="25%"><div align="center"><h4>' . tr("SUPPORT") . '</h4></div><canvas id="PmnlStatsDevicetype" /></td>
				</tr>
				<tr>
					<td><div id="PmnlStatsBrowser-legend" class="chart-legend"></div></td>
					<td><div id="PmnlPim-legend" class="chart-legend"></div></td>
					<td><div id="PmnlStatsPlatform-legend" class="chart-legend"></div></td>
					<td><div id="PmnlStatsDevicetype-legend" class="chart-legend"></div></td>
				</tr>
				<tr>
					<td width="25%"><div align="center"><h4>' . tr("CLICKED_DISTINCT_DOMAINS") . '</h4></div><canvas id="PmnlDistctDomain" /></td>
					<td width="25%"><div align="center"><h4>' . tr("CLICKED_BY_DOMAINS") . '</h4></div><canvas id="PmnlCntClkDomain" /></td>
					<td width="50%" colspan="2" rowspan="2" align="center"><h4>' . tr('CLICK_BY_HOURS') . '</h4><canvas id="ClicByHours" style="width:70%;height:300px;"></canvas></td>
				</tr>
				<tr>
					<td><div id="PmnlDistctDomain-legend" class="chart-legend"></div></td>
					<td><div id="PmnlCntClkDomain-legend" class="chart-legend"></div></td>
					<td></td>
				</tr>
			</table>
			<script>
			Chart.defaults.global.legend.display = false;
			var PmnlChartBrowser = $("#PmnlStatsBrowser");
			var mCbrowser = new Chart(PmnlChartBrowser, { type: \'pie\',data:{ labels:[' . $databrowser . '],datasets: [{ data: [' . $cptbrowser . '],backgroundColor:[\'#ff0000\',\'#ff4000\',\'#ff8000\',\'#ffbf00\',\'#ffff00\',\'#bfff00\',\'#80ff00\',\'#40ff00\',\'#00ff00\',\'#00ff40\',\'#00ff80\',\'#00ffbf\',\'#00ffff\',\'#00bfff\',\'#0080ff\',\'#0040ff\',\'#0000ff\',\'#4000ff\',\'#8000ff\',\'#bf00ff\',\'#ff00ff\',\'#ff00bf\',\'#ff0080\',\'#ff0040\',\'#ff0000\',\'#946d70\',\'#563957\',\'#5e6370\',\'#78bac2\',\'#376182\',\'#3a000f\',\'#85888c\',\'#cd7320\',\'#7f9c95\',\'#b4eeb4\',\'#794044\',\'#205c2e\',\'#1c6d26\',\'#ff0f3b\',\'#4a4146\',\'#a4a0a2\',\'#0011a8\',\'#000532\',\'#d3f660\',\'#546226\',\'#ff4265\',\'#292929\',\'#8e561a\',\'#ffe4e1\',\'#ffc0cb\',\'#000000\',\'#ff0000\',\'#1075bc\',\'#07adeb\',\'#acdfe8\',\'#f5f5f5\',\'#277ead\',\'#eff3f9\',\'#eff3f9\',\'#511323\',\'#ffe4e1\',\'#141414\',\'#ff4265\',\'#54ff9f\',\'#cbf3ad\',\'#543544\',\'#15315c\'],}]},});
			document.getElementById(\'PmnlStatsBrowser-legend\').innerHTML = mCbrowser.generateLegend();
			var PmnlChartPim = $("#PmnlPim");
			var mPim = new Chart(PmnlPim, { type: \'pie\',data:{ labels:[' . $dataua . '],datasets: [{ data: [' . $cptua . '],backgroundColor:[\'#ff0000\',\'#ff4000\',\'#ff8000\',\'#ffbf00\',\'#ffff00\',\'#bfff00\',\'#80ff00\',\'#40ff00\',\'#00ff00\',\'#00ff40\',\'#00ff80\',\'#00ffbf\',\'#00ffff\',\'#00bfff\',\'#0080ff\',\'#0040ff\',\'#0000ff\',\'#4000ff\',\'#8000ff\',\'#bf00ff\',\'#ff00ff\',\'#ff00bf\',\'#ff0080\',\'#ff0040\',\'#ff0000\',\'#946d70\',\'#563957\',\'#5e6370\',\'#78bac2\',\'#376182\',\'#3a000f\',\'#85888c\',\'#cd7320\',\'#7f9c95\',\'#b4eeb4\',\'#794044\',\'#205c2e\',\'#1c6d26\',\'#ff0f3b\',\'#4a4146\',\'#a4a0a2\',\'#0011a8\',\'#000532\',\'#d3f660\',\'#546226\',\'#ff4265\',\'#292929\',\'#8e561a\',\'#ffe4e1\',\'#ffc0cb\',\'#000000\',\'#ff0000\',\'#1075bc\',\'#07adeb\',\'#acdfe8\',\'#f5f5f5\',\'#277ead\',\'#eff3f9\',\'#eff3f9\',\'#511323\',\'#ffe4e1\',\'#141414\',\'#ff4265\',\'#54ff9f\',\'#cbf3ad\',\'#543544\',\'#15315c\'],}]},});
			document.getElementById(\'PmnlPim-legend\').innerHTML = mPim.generateLegend();
			var PmnlChartPlatform = document.getElementById("PmnlStatsPlatform");
			var mCplatform = new Chart(PmnlChartPlatform, { type: \'pie\',data:{ labels:[' . $dataplatform . '],datasets: [{ data: [' . $cptplatform . '],backgroundColor:[\'#ff0000\',\'#ff4000\',\'#ff8000\',\'#ffbf00\',\'#ffff00\',\'#bfff00\',\'#80ff00\',\'#40ff00\',\'#00ff00\',\'#00ff40\',\'#00ff80\',\'#00ffbf\',\'#00ffff\',\'#00bfff\',\'#0080ff\',\'#0040ff\',\'#0000ff\',\'#4000ff\',\'#8000ff\',\'#bf00ff\',\'#ff00ff\',\'#ff00bf\',\'#ff0080\',\'#ff0040\',\'#ff0000\',\'#946d70\',\'#563957\',\'#5e6370\',\'#78bac2\',\'#376182\',\'#3a000f\',\'#85888c\',\'#cd7320\',\'#7f9c95\',\'#b4eeb4\',\'#794044\',\'#205c2e\',\'#1c6d26\',\'#ff0f3b\',\'#4a4146\',\'#a4a0a2\',\'#0011a8\',\'#000532\',\'#d3f660\',\'#546226\',\'#ff4265\',\'#292929\',\'#8e561a\',\'#ffe4e1\',\'#ffc0cb\',\'#000000\',\'#ff0000\',\'#1075bc\',\'#07adeb\',\'#acdfe8\',\'#f5f5f5\',\'#277ead\',\'#eff3f9\',\'#eff3f9\',\'#511323\',\'#ffe4e1\',\'#141414\',\'#ff4265\',\'#54ff9f\',\'#cbf3ad\',\'#543544\',\'#15315c\'],}]},});
			document.getElementById(\'PmnlStatsPlatform-legend\').innerHTML = mCplatform.generateLegend();
			var PmnlChartDevicetype = $("#PmnlStatsDevicetype");
			var mCdevicetype = new Chart(PmnlChartDevicetype, { type: \'pie\',data:{ labels:[' . $datadevicetype . '],datasets: [{ data: [' . $cptdevicetype . '],backgroundColor:[\'#ff0000\',\'#ff4000\',\'#ff8000\',\'#ffbf00\',\'#ffff00\',\'#bfff00\',\'#80ff00\',\'#40ff00\',\'#00ff00\',\'#00ff40\',\'#00ff80\',\'#00ffbf\',\'#00ffff\',\'#00bfff\',\'#0080ff\',\'#0040ff\',\'#0000ff\',\'#4000ff\',\'#8000ff\',\'#bf00ff\',\'#ff00ff\',\'#ff00bf\',\'#ff0080\',\'#ff0040\',\'#ff0000\',\'#946d70\',\'#563957\',\'#5e6370\',\'#78bac2\',\'#376182\',\'#3a000f\',\'#85888c\',\'#cd7320\',\'#7f9c95\',\'#b4eeb4\',\'#794044\',\'#205c2e\',\'#1c6d26\',\'#ff0f3b\',\'#4a4146\',\'#a4a0a2\',\'#0011a8\',\'#000532\',\'#d3f660\',\'#546226\',\'#ff4265\',\'#292929\',\'#8e561a\',\'#ffe4e1\',\'#ffc0cb\',\'#000000\',\'#ff0000\',\'#1075bc\',\'#07adeb\',\'#acdfe8\',\'#f5f5f5\',\'#277ead\',\'#eff3f9\',\'#eff3f9\',\'#511323\',\'#ffe4e1\',\'#141414\',\'#ff4265\',\'#54ff9f\',\'#cbf3ad\',\'#543544\',\'#15315c\'],}]},});
			document.getElementById(\'PmnlStatsDevicetype-legend\').innerHTML = mCdevicetype.generateLegend();
			var PmnlDistctDomain = $("#PmnlDistctDomain");
			var mCDistctDomain = new Chart(PmnlDistctDomain, { type: \'pie\',data:{ labels:[' . $datadomaines . '],datasets: [{ data: [' . $cptdomaines . '],backgroundColor:[\'#ff0000\',\'#ff4000\',\'#ff8000\',\'#ffbf00\',\'#ffff00\',\'#bfff00\',\'#80ff00\',\'#40ff00\',\'#00ff00\',\'#00ff40\',\'#00ff80\',\'#00ffbf\',\'#00ffff\',\'#00bfff\',\'#0080ff\',\'#0040ff\',\'#0000ff\',\'#4000ff\',\'#8000ff\',\'#bf00ff\',\'#ff00ff\',\'#ff00bf\',\'#ff0080\',\'#ff0040\',\'#ff0000\',\'#946d70\',\'#563957\',\'#5e6370\',\'#78bac2\',\'#376182\',\'#3a000f\',\'#85888c\',\'#cd7320\',\'#7f9c95\',\'#b4eeb4\',\'#794044\',\'#205c2e\',\'#1c6d26\',\'#ff0f3b\',\'#4a4146\',\'#a4a0a2\',\'#0011a8\',\'#000532\',\'#d3f660\',\'#546226\',\'#ff4265\',\'#292929\',\'#8e561a\',\'#ffe4e1\',\'#ffc0cb\',\'#000000\',\'#ff0000\',\'#1075bc\',\'#07adeb\',\'#acdfe8\',\'#f5f5f5\',\'#277ead\',\'#eff3f9\',\'#eff3f9\',\'#511323\',\'#ffe4e1\',\'#141414\',\'#ff4265\',\'#54ff9f\',\'#cbf3ad\',\'#543544\',\'#15315c\'],}]},});
			document.getElementById(\'PmnlDistctDomain-legend\').innerHTML = mCDistctDomain.generateLegend();
			var PmnlCntClkDomain = $("#PmnlCntClkDomain");
			var mCCntClkDomain = new Chart(PmnlCntClkDomain, { type: \'pie\',data:{ labels:[' . $datadomainesclk . '],datasets: [{ data: [' . $cptdomainesclk . '],backgroundColor:[\'#ff0000\',\'#ff4000\',\'#ff8000\',\'#ffbf00\',\'#ffff00\',\'#bfff00\',\'#80ff00\',\'#40ff00\',\'#00ff00\',\'#00ff40\',\'#00ff80\',\'#00ffbf\',\'#00ffff\',\'#00bfff\',\'#0080ff\',\'#0040ff\',\'#0000ff\',\'#4000ff\',\'#8000ff\',\'#bf00ff\',\'#ff00ff\',\'#ff00bf\',\'#ff0080\',\'#ff0040\',\'#ff0000\',\'#946d70\',\'#563957\',\'#5e6370\',\'#78bac2\',\'#376182\',\'#3a000f\',\'#85888c\',\'#cd7320\',\'#7f9c95\',\'#b4eeb4\',\'#794044\',\'#205c2e\',\'#1c6d26\',\'#ff0f3b\',\'#4a4146\',\'#a4a0a2\',\'#0011a8\',\'#000532\',\'#d3f660\',\'#546226\',\'#ff4265\',\'#292929\',\'#8e561a\',\'#ffe4e1\',\'#ffc0cb\',\'#000000\',\'#ff0000\',\'#1075bc\',\'#07adeb\',\'#acdfe8\',\'#f5f5f5\',\'#277ead\',\'#eff3f9\',\'#eff3f9\',\'#511323\',\'#ffe4e1\',\'#141414\',\'#ff4265\',\'#54ff9f\',\'#cbf3ad\',\'#543544\',\'#15315c\'],}]},});
			document.getElementById(\'PmnlCntClkDomain-legend\').innerHTML = mCCntClkDomain.generateLegend();
			var PmnlDthr = $("#ClicByHours");
			var barData = {
				labels: [' . $labelsdthr . '],
				datasets: [
					{
						label: \'' .tr('CLICK_BY_HOURS') . '\',
						backgroundColor:\'rgba(54, 162, 235, 0.2)\',
						borderColor:\'rgba(54, 162, 235, 1)\',
						borderWidth: 1,
						data: [' . $datadthr . ']
					},
				],
				options: {
					responsive: true, maintainAspectRatio: false
				}
			};
			var mCCntClkHour = new Chart(PmnlDthr, { type: \'line\',data:barData});
			</script>';
			break;
		case 'map' :
			echo '<a name="map" id="map"></a>
			<div id="chartdivmap"></div>';
			switch($tm){
				case 'ctry' :
					$sql = 'SELECT DISTINCT(t.country),
							COALESCE(COUNT(*),0) AS data, c.code, c.lat, c.long, c.color
						FROM ' . $row_config_globale['table_tracking'] . ' t
							LEFT JOIN ' . $row_config_globale['table_codes'] . ' c ON t.country=c.country
						WHERE t.city!="" 
							AND useragent NOT LIKE \'%ggpht.com%GoogleImageProxy%\'
							AND subject IN (
								SELECT id_mail 
									FROM ' . $row_config_globale['table_send'] . '  s
									LEFT JOIN '.$row_config_globale['table_archives'].' a 
										ON a.id = s.id_mail
									WHERE a.date>"'. ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
										AND a.date<"'. ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59"
							)
							AND c.lat IS NOT NULL
						GROUP BY c.country
						ORDER BY data DESC;';
					$results_stat_latlong = $cnx->query($sql)->fetchAll();;
					if (count($results_stat_latlong) >0) {
						$latlong='';
						$mapData='';
						foreach ($results_stat_latlong as $tab) {
							if (trim($tab['code'])!='') {
								$latlong.='latlong["'.$tab['code'].'"] = {"latitude":'.$tab['lat'].', "longitude":'.$tab['long'].'};';
								$mapData.='{"code":"'.$tab['code'].'" , "name":"'.$tab['country'].'", "value":'.$tab['data'].', "color":"'.$tab['color'].'"},';
							}
						}
					}
					echo '<script>
					var latlong = {};
					' . $latlong . '
					var mapData = [' . $mapData . '];
					var map;
					var minBulletSize = 10;
					var maxBulletSize = 40;
					var min = Infinity;
					var max = -Infinity;
					for (var i = 0; i < mapData.length; i++) {
						var value = mapData[i].value;
						if (value < min) { min = value; }
						if (value > max) { max = value; }
					}
					AmCharts.ready(function() {
						AmCharts.theme = AmCharts.themes.dark;
						map = new AmCharts.AmMap();
						map.addTitle("' . tr("OPEN_ALL_LIST") .'", 14, "rgba(54, 162, 235, 1)");
						map.areasSettings = {
							unlistedAreasColor: "#000000",
							unlistedAreasAlpha: 0.2,
							autoZoom: true,
							selectedColor: "#CC0000"
						};
						map.imagesSettings.balloonText = "<span style=\'font-size:12px;\'><b>[[title]]</b>: [[value]]</span>";
						var dataProvider = {
							mapVar: AmCharts.maps.worldLow,
							getAreasFromMap: true,
							images: []
						}
						map.export = {
							"enabled": true
						}
						map.smallMap = {}
						var maxSquare = maxBulletSize * maxBulletSize * 2 * Math.PI;
						var minSquare = minBulletSize * minBulletSize * 2 * Math.PI;
						for (var i = 0; i < mapData.length; i++) {
							var dataItem = mapData[i];
							var value = dataItem.value;
							var square = (value - min) / (max - min) * (maxSquare - minSquare) + minSquare;
							if (square < minSquare) {
								square = minSquare;
							}
							var size = Math.sqrt(square / (Math.PI * 2));
							var id = dataItem.code;
							dataProvider.images.push({
								type: "circle",
								width: size,
								height: size,
								color: dataItem.color,
								longitude: latlong[id].longitude,
								latitude: latlong[id].latitude,
								title: dataItem.name,
								value: value
							});
						}
						map.dataProvider = dataProvider;
						map.write("chartdivmap");
					});
					</script>';
					break;
				default :
					$sql = 'SELECT DISTINCT(CONCAT(city,\',\',postal_code)) AS latlong,
								COALESCE(COUNT(*),0) AS data, t.lat, t.lng, t.city, t.country, c.color
						FROM ' . $row_config_globale['table_tracking'] . ' t
							LEFT JOIN ' . $row_config_globale['table_codes'] . ' c ON t.country=c.country
						WHERE t.city!="" 
							AND useragent NOT LIKE \'%ggpht.com%GoogleImageProxy%\' /* Exclude Google / gmail */
							AND subject IN (
								SELECT id_mail 
									FROM ' . $row_config_globale['table_send'] . '  s
									LEFT JOIN '.$row_config_globale['table_archives'].' a 
										ON a.id = s.id_mail
									WHERE a.date>"'. ($date_min!=''?$date_min:$array_date_stats[0]['DT_MIN']) . ' 00:00:00" 
										AND a.date<"'. ($date_max!=''?$date_max:$array_date_stats[0]['DT_MAX']) . ' 23:59:59"
							)
							AND c.lat IS NOT NULL
						GROUP BY city
						HAVING COUNT(*)>0
						ORDER BY data DESC;';
					$results_stat_latlong = $cnx->query($sql)->fetchAll();
					if (count($results_stat_latlong) >0) {
						$latlong='';
						$mapData='';
						foreach ($results_stat_latlong as $tab) {
							$latlong.='latlong["'.$tab['latlong'].'"] = {"latitude":'.$tab['lat'].', "longitude":'.$tab['lng'].'};';
							$name='';
							$name = ($tab['city']!="undefined"?$tab['city']:$tab['country'].($tab['postal_code']!=''?' ('.$tab['postal_code'].')':'(Géolocalisation approximative)'));
							$mapData.='{"code":"'.$tab['latlong'].'" , "name":"'.$name.'", "value":'.$tab['data'].', "color":"'.$tab['color'].'"},';
						}
					}
					echo '<script>
					var latlong = {};
					' . $latlong . '
					var mapData = [' . $mapData . '];
					var map;
					var minBulletSize = 5;
					var maxBulletSize = 15;
					var min = Infinity;
					var max = -Infinity;
					for (var i = 0; i < mapData.length; i++) {
						var value = mapData[i].value;
						if (value < min) { min = value; }
						if (value > max) { max = value; }
					}
					AmCharts.ready(function() {
						AmCharts.theme = AmCharts.themes.dark;
						map = new AmCharts.AmMap();
						map.addTitle("' . tr("OPEN_ALL_LIST") . '", 14, "rgba(54, 162, 235, 1)");
						map.areasSettings = {
							unlistedAreasColor: "#000000",
							unlistedAreasAlpha: 0.2,
							autoZoom: true,
							selectedColor: "#CC0000"
						};
						map.imagesSettings.balloonText = "<span style=\'font-size:12px;\'><b>[[title]]</b>: [[value]]</span>";
						var dataProvider = {
							mapVar: AmCharts.maps.worldLow,
							getAreasFromMap: true,
							images: []
						}
						map.export = {
							"enabled": true
						}
						map.smallMap = {}
						var maxSquare = maxBulletSize * maxBulletSize * 2 * Math.PI;
						var minSquare = minBulletSize * minBulletSize * 2 * Math.PI;
						for (var i = 0; i < mapData.length; i++) {
							var dataItem = mapData[i];
							var value = dataItem.value;
							var square = (value - min) / (max - min) * (maxSquare - minSquare) + minSquare;
							if (square < minSquare) {
								square = minSquare;
							}
							var size = Math.sqrt(square / (Math.PI * 2));
							var id = dataItem.code;
							dataProvider.images.push({
								type: "circle",
								width: size,
								height: size,
								color: dataItem.color,
								longitude: latlong[id].longitude,
								latitude: latlong[id].latitude,
								title: dataItem.name,
								value: value
							});
						}
						map.dataProvider = dataProvider;
						map.write("chartdivmap");
					});
					</script>';
					break;
			break;
		}
	}
} else {
	echo tr("TRACKING_NO_DATA_AVAILABLE").'<h4 class="alert alert-info">...</h4>';
}


