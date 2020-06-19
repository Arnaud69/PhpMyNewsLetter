<?php
if(!empty($_GET['m'])&&!empty($_GET['h'])&&!empty($_GET['l'])&&!empty($_GET['r'])){
	include("_loader.php");
	foreach($_GET as $key=>$value){
		$$key = CleanInput($value);
	}
	$r = urldecode($r);
	// on vérifie que les données transmises existent !
	$email_verif = $cnx->query("SELECT email FROM " . $row_config_globale['table_email'] . " 
			WHERE hash  ='" . $h . "'
				AND list_id ='" . $l . "'")->fetchAll();
	$nb_email=count($email_verif);
	if ( $nb_email==1 ) {
		$row_id = $cnx->query("SELECT id FROM " . $row_config_globale['table_track_links'] . " 
				WHERE list_id ='" . $l . "'
					AND msg_id='" . $m . "'
					AND hash  ='" . $h . "'
					AND link  ='" . $r . "'")->fetchAll();
		$nb_result=count($row_id);
		if($nb_result==0){
			$cnx->query("INSERT INTO " . $row_config_globale['table_track_links'] . "
						(list_id,msg_id,link,hash,cpt,dt_track_link) 
					VALUES 
						('" . $l . "','" . $m . "','" . $r . "','" . $h . "','1',now())");
		}elseif($nb_result==1){
			$cnx->query("UPDATE " . $row_config_globale['table_track_links'] . " 
					SET cpt=cpt+1,dt_track_link=now()
				WHERE list_id ='" . $l . "' 
					AND msg_id='" . $m . "' 
					AND hash  ='" . $h . "' 
					AND link  ='" . $r . "'");
		}
		// on vérifie si l'email est inscrit dans la table tracking :
		$row_id = $cnx->query("SELECT id
				FROM " . $row_config_globale['table_tracking'] . "
			WHERE hash='" . $_GET['h'] . "'
				AND subject = (
					SELECT id 
						FROM " . $row_config_globale['table_archives'] . "
					WHERE id='" . $m . "')"->fetchAll();
		$nb_result=count($row_id);
		include('include/lib/class.browser.php');
		require_once 'include/lib/class.mobile.php';
		include("geoloc/geoipcity.inc");
		include("geoloc/geoipregionvars.php");
		$this_browser = new Browser();
		$browser = $this_browser->getBrowser();
		$browser_version = $this_browser->getVersion();
		$browser_platform = $this_browser->getPlatform();
		$browser_user_agent=$this_browser->getUserAgent();
		if ( $browser_platform == 'iPhone' || $browser_platform == 'iPad' ) {
			$browser_platform = 'iOS';
		}
		if ( $browser_platform == 'Apple' ) {
			$browser_platform = 'macOS';
		}
		$detect = new Mobile_Detect;
		$devicetype = ( $detect->isMobile() ? 'mobile' : ( $detect->isTablet() ? 'tablet' : 'computer' ) );
		$gi = geoip_open(realpath("geoloc/GeoLiteCity.dat"),GEOIP_STANDARD);
		$record = geoip_record_by_addr($gi,$ip);
		if( $nb_result==0 ) {
			$cnx->query("INSERT INTO " . $row_config_globale['table_tracking'] . "
					(hash,subject,date,open_count,ip,browser,
					version,platform,useragent,devicetype,
					lat,lng,city,postal_code,region,country) 
				VALUES 
					('" . $h . "','" . $m . "',NOW(),'1','" . $ip . "','" . $browser . "',
					'" . @$browser_version . "','" . @$browser_platform . "','" . @$browser_user_agent . "','" . @$devicetype . "',
					'" . @$record->latitude . "','" . @$record->longitude . "','" . @addslashes(htmlspecialchars($record->city)) . "',
					'" . @$record->postal_code . "','" . @addslashes(htmlspecialchars($GEOIP_REGION_NAME[$record->country_code][$record->region])) . "',
					'" . @addslashes(htmlspecialchars($record->country_name)) . "')");
		} elseif( $nb_result==1 ) {
			$cnx->query("UPDATE " . $row_config_globale['table_tracking'] . " 
					SET date = NOW(),
						open_count = open_count+1,
						ip = '" . $ip . "',
						browser = '" . $browser . "',
						version = '" . $browser_version . "',
						platform = '" . $browser_platform . "',
						useragent = '" . $browser_user_agent . "',
						devicetype = '" . $devicetype . "'
					WHERE hash='" . $h . "' AND subject='" . $m . "'");
		}
	}
}
$redirect = html_entity_decode(urldecode(htmlspecialchars_decode($_GET['r'])), ENT_COMPAT, 'UTF-8');
header("Location:$redirect");
