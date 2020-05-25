<?php
if(!file_exists("config.php")) {
	header("Location:/install.php");
	exit;
} else {
	include("../_loader.php");
	if(isset($_POST['token'])){$token=$_POST['token'];}elseif(isset($_GET['token'])){$token=$_GET['token'];}else{$token='';}
	if(!tok_val($token)){
		header("Location:/login.php?error=2");
		exit;
	}
}

$hash = (!empty($_GET['hash'])) ? $_GET['hash'] : '';
$list_id = (!empty($_GET['list_id'])) ? intval($_GET['list_id']) : '';

if(empty($hash)&&empty($list_id)){
	header("Location:/login.php?error=2");
	exit;
}
echo '<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">
<header>
	<h4>Détail de l\'activité d\'un abonné</h4>
</header>';
$dtls = $cnx->query('SELECT a.subject, a.id, a.list_id, a.date AS date_envoi, t.hash, t.date AS date_lecture, t.city, t.country
	FROM ' . $row_config_globale['table_archives'] . ' a 
		LEFT JOIN ' . $row_config_globale['table_tracking'] . ' t
			ON t.subject=a.id
			AND t.hash="' . CleanInput($hash) . '"
			AND a.list_id= "' . CleanInput($list_id) . '"
		WHERE a.id>=(
			SELECT MIN(trs.subject) 
				FROM ' . $row_config_globale['table_tracking'] . ' trs
				RIGHT JOIN ' . $row_config_globale['table_archives'] . ' ars ON trs.subject=ars.id
					WHERE hash="' . CleanInput($hash) . '" 
			)
			AND a.list_id="' . CleanInput($list_id) . '"
		GROUP BY a.id, a.subject
		ORDER BY a.id DESC')->fetchAll(PDO::FETCH_ASSOC);

if(count($dtls)>0){
	echo '<table class="tablesorter table table-striped" cellspacing="0"> 
		<thead> 
			<tr>
				<th>Campagne</th>
				<th>Date envoi</th>
				<th>Date lecture</th>
			</tr> 
		</thead> 
		<tbody>';
	foreach($dtls as $row){

			echo '<tr>
				<td>(' . $row['id'] . ') ' . $row['subject'] . '</td>
				<td>' . $row['date_envoi'] . '</td>
				<td>' . ($row['date_lecture']==''?'<b>Non lue ou non ouverte</b>':$row['date_lecture']) . '</td>
			</tr>';

	}
	echo '</tbody>
	</table>';

}
echo '<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>';




				
