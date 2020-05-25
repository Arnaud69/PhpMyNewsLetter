<?php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: text/plain');
if(!file_exists("../config.php")) {
	header("Location:../../install.php");
	exit;
} else {
	include("../../_loader.php");
	$token=(empty($_POST['token'])?"":$_POST['token']);
	if(!isset($token) || $token=="")$token=(empty($_GET['token']) ? "" : $_GET['token']);
	if(!tok_val($token)){
		header("Location:../../login.php?error=2");
	}

	$list_id  = $_POST['list_id'];
	$nl = getConfig($cnx, $list_id, $row_config_globale['table_sauvegarde']);
	$textarea = $nl['textarea'];
	$pattern = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
	$txError = 0;
	$txSucces = 0;
	$error = '';
	$redir = '';
	if($num_found = preg_match_all($pattern, $textarea, $out)){
		foreach ($out[0] as $url) {
			$curl = curl_init();
			if (preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $url)!='www.w3.org') {
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,2);
				curl_setopt($curl, CURLOPT_TIMEOUT, 3);
				$result = curl_exec($curl);
				$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				if ( $httpCode == 404 ) {
					$error .= $url. ($httpCode>399?" : <span style='color:red;font-weight:bold'>" . $httpCode ."</span>":"") 
						. ($result === false?" : <span style='color:red;font-weight:bold'>site KO</span>":"") . "<br>";
					$txError++;
				} else {
					$newUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
					if ($newUrl !== $url) {
						curl_setopt($curl, CURLOPT_URL, $url);
						curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,2);
						curl_setopt($curl, CURLOPT_TIMEOUT, 3);
						$result = curl_exec($curl);
						if ( $result === false || $httpCode == 404 ) {
							$error .= $url. ($httpCode>399?" : <span style='color:red;font-weight:bold'>" . $httpCode ."</span>":"") 
								. ($result === false?" : <span style='color:red;font-weight:bold'>site KO</span>":"") . "<br>";
							$txError++;
						} else {
							$txSucces++;
						}
					} else {
						$txSucces++;
					}
				}
			} else {
				$txSucces++;
			}
		}
		curl_close($curl);
		$missing_alts = list_missing_tags($textarea);
		echo "<br><div class='alert alert-info col-lg-12'><h5>" . $num_found . "  liens trouvé(s), " . ($txSucces+$txError) . " testé(s) :<br>";
		if($txSucces>0) {
			echo "<span style='color:green;font-weight:bold'>" . $txSucces . " Liens OK.</span><br>";
		}
		if($txError>1) {
			echo "<span style='color:red;font-weight:bold'>$txError erreurs :</span><br>" . $error ;
		} elseif($txError==1) {
			echo "<span style='color:red;font-weight:bold'>1 erreur :</span><br>" . $error ;
		}
		
		if ( $redir!='' ) {
			echo "<span style='color:red;font-weight:bold'>URL(s) redirigée(s) :</span> " . $redir;
		}
		if ($missing_alts!=''){
			echo "<br><span style='color:red;font-weight:bold'>Balise(s) alt manquante(s) :</span><br>
				<span style='color:green;'>(Ces balises seront ajoutées automatiquement à l'envoi du mail si vous ne les corrigez pas. Score spam = -0,5)</span>" 
				. $missing_alts ;
		}
		echo "</h5></div>";
	}    
}
