<?php
echo "<header><h4>".tr("USERS_RIGHTS_MANAGEMENT")."</h4></header>";
switch($viewms){
	case 'manage':
		if(empty($account)) {
			echo '<div class="row"><div class="col-md-12">
				<div class="alert alert-danger">' . tr("USERS_CHOICE_ERROR") . '.</div>
				</div></div>';
		} else {
			if(!$row=getOneUserFull($cnx,$row_config_globale['table_users'],$account)) {
				echo '<div class="row"><div class="col-md-12">
					<div class="alert alert-danger">' . tr("USERS_CHOICE_ERROR_FINDING") . ' : <b>'.$account.'</b></div>
					</div></div>';
			} else {
				echo '<div class="row" style="margin-bottom:9px;"><div class="col-md-12">
					<div class="subnav_menu">
					<a href="?page=manage_users&token=' . $token . '&list_id=' . $list_id 
						. '&viewms=list" data-toggle="tooltip" title="' . tr("USERS_CHOICE_LIST_USERS") 
						. '" class="clearbtn btn btn-primary btn-sm"><i class="glyphicon glyphicon-list"></i> ' 
						. tr("USERS_LIST") . '</a>
					&nbsp;<a href="?page=manage_users&token=' . $token . '&list_id=' . $list_id 
						. '&viewms=add" data-toggle="tooltip" title="' . tr("USERS_ADD_USER") 
						. '" class="clearbtn btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i> ' 
						. tr("USERS_ADD_A_USER"). '</a>
					</div>
					</div></div>
					<div class="row">
					<div class="col-md-12">
					<h3>' . tr("USERS_MODIFY_ACCOUNT") . '</h3>
					</div>
					<form method="post" name="global_users" action="" enctype="multipart/form-data">
					<div class="col-md-12">
					<div class="module_content">
					<h4>Paramètres et droits du compte '.$row[0]['id_user'].' </h4>
					</div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Nom du compte</label>
					<input type="text" name="usname" class="form-control" value="'.$row[0]['id_user'].'" readonly></div>
					</div>
					<div class="col-md-6"><div class="form-group"><label>Adresse email</label>
					<input type="text" name="usmail" class="form-control" value="'.$row[0]['email'].'"></div>
					</div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Mot de passe</label> (saisir un nouveau mot de passe ou laisser à blanc pour ne pas le modifier)
					<input type="text" name="uspass" class="form-control" value=""></div>
					</div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les listes</label>
					<div>Ce droit permet de gérer les listes des abonnés : créer une liste, fusionner des listes, supprimer une liste, ainsi que paramètrer la liste<br>
					<b>Vous devez sélectionner une liste au minimum pour si vous laissez ce droit à \'Off\' !</b></div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<div class="checkbox"><label><input type="checkbox" '
					.($row[0]['listes']=='Y'?'checked':'').' data-toggle="toggle" data-size="small" name="listes"></label></div></div></div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les abonnés</label>
					<div>Ce droit permet de gérer les listes des abonnés : ajouter un abonné, supprimer un abonné, importer une liste, corriger les abonnés en erreur</div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<div class="checkbox"><label><input type="checkbox" '
					.($row[0]['abonnes']=='Y'?'checked':'').' data-toggle="toggle" data-size="small" name="abonnes"></label></div></div></div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit de rédaction</label>
					<div>Ce droit permet de rédiger un nouveau message, créer des templates, accéder aux archives des messages et d\'envoyer des mails de prévisualisation</div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<div class="checkbox"><label><input type="checkbox" '
					.($row[0]['redaction']=='Y'?'checked':'').' data-toggle="toggle" data-size="small" name="redaction"></label></div></div></div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les envois</label>
					<div>Ce droit permet d\'envoyer les messages de previsualisation, d\'envoyer une campagne en direct, de planifier une campagne.<br>
					<b>Ce droit nécessite le droit de rédaction pour pouvoir être utilisé !</b></div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<div class="checkbox"><label><input type="checkbox" '
					.($row[0]['envois']=='Y'?'checked':'').' data-toggle="toggle" data-size="small" name="envois"></label></div></div></div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les retours</label>
					<div>Ce droit permet d\'accéder aux traitements des mails en retour après envoi des campagnes</div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<div class="checkbox"><label><input type="checkbox" '
					.($row[0]['bounce']=='Y'?'checked':'').' data-toggle="toggle" data-size="small" name="bounce"></label></div></div></div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit d\'accès aux statistiques</label>
					<div>Ce droit permet de visualiser les statistiques globales ainsi que les statistiques des listes</div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<div class="checkbox"><label><input type="checkbox" '
					.($row[0]['stats']=='Y'?'checked':'').' data-toggle="toggle" data-size="small" name="stats"></label></div></div></div></div>	
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Enregsitrer les actions</label>
					<div>Les actions de cet utilisateur peuvent être enregistrées pour être visualisées ultérieurement</div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<div class="checkbox"><label><input type="checkbox" '
					.($row[0]['log']=='Y'?'checked':'').' data-toggle="toggle" data-size="small" name="log"></label></div></div></div></div>
					<div class="row"><div class="col-md-6"><div class="form-group"><label>Choix de la liste ou des listes pour action :</label>
					<div>Les actions définies de cet utilisateur peuvent porter sur toutes les listes ou sur une liste particulière à sélectionner</div>
					</div></div>
					<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
					<label>
					<select name="liste" class="selectpicker" data-width="auto">
					<option value="0">Toutes les listes</option>';
				$list=list_newsletter($cnx,$row_config_globale['table_listsconfig']);
				foreach ($list as $item) {
						echo '<option value="'.$item['list_id'].'" '
							. ($item['list_id']==$row[0]['liste']?' selected ':'').'>'.$item['newsletter_name'].'</option>';
				}
				echo '</select>
					</label></div></div></div>		
					<div class="row"><div class="col-md-4"></div>
					<div class="col-md-4"><input type="submit" value="Modifier ce compte" class="form-control btn btn-success" /></div>
					<input type="hidden" name="page" value="manage_users">
					<input type="hidden" name="viewms" value="list">
					<input type="hidden" name="op" value="modifyUser">
					<input type="hidden" name="list_id" value="'.$list_id.'">
					<input type="hidden" name="token" value="'.$token.'">
					<div class="col-md-4"></div></div>
					</form>';
			}
		}
		break;
	case 'add':
		echo '<div class="row" style="margin-bottom:9px;"><div class="col-md-12">
			<div class="subnav_menu">
			<a href="?page=manage_users&token=' . $token . '&list_id=' . $list_id 
			.'&viewms=list" data-toggle="tooltip" title="Afficher la liste des utilisateurs et leurs droits" class="clearbtn btn btn-primary btn-sm"><i class="glyphicon glyphicon-list"></i> Liste des utilisateurs</a>
			</div>
			</div></div>
			<div class="row">
			<div class="col-md-12">
			<h3>Ajout et paramètrage d\'un compte utilisateur</h3>
			</div>
			<form method="post" name="global_users" action="" enctype="multipart/form-data">
			<div class="col-md-12">
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Nom du compte</label>
			<input type="text" name="usname" class="form-control" required autofocus></div>
			</div>
			<div class="col-md-6"><div class="form-group"><label>Adresse email</label>
			<input type="text" name="usmail" class="form-control" autocomplete="nope" required></div>
			</div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Mot de passe</label>
			<input type="text" name="uspass" class="form-control" value="" autocomplete="nope" required></div>
			</div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les listes</label>
			<div>Ce droit permet de gérer les listes des abonnés : créer une liste, fusionner des listes, supprimer une liste, ainsi que paramètrer la liste<br>
			<b>Vous devez sélectionner une liste au minimum pour si vous laissez ce droit à \'Off\' !</b></div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<div class="checkbox"><label><input type="checkbox" data-toggle="toggle" data-size="small" name="listes"></label></div></div></div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les abonnés</label>
			<div>Ce droit permet de gérer les abonnés : ajouter un abonné, supprimer un abonné, importer une liste, corriger les abonnés en erreur</div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<div class="checkbox"><label><input type="checkbox" data-toggle="toggle" data-size="small" name="abonnes"></label></div></div></div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit de rédaction</label>
			<div>Ce droit permet de rédiger un nouveau message, créer des templates, accéder aux archives des messages et d\'envoyer des mails de prévisualisation</div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<div class="checkbox"><label><input type="checkbox" data-toggle="toggle" data-size="small" name="redaction"></label></div></div></div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les envois</label>
			<div>Ce droit permet d\'envoyer les messages de previsualisation, d\'envoyer une campagne en direct, de planifier une campagne<br>
			<b>Ce droit nécessite le droit de rédaction pour pouvoir être utilisé !</b></div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<div class="checkbox"><label><input type="checkbox" data-toggle="toggle" data-size="small" name="envois"></label></div></div></div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit sur les retours</label>
			<div>Ce droit permet d\'accéder aux traitements des mails en retour après envoi des campagnes</div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<div class="checkbox"><label><input type="checkbox" data-toggle="toggle" data-size="small" name="bounce"></label></div></div></div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Droit d\'accès aux statistiques</label>
			<div>Ce droit permet de visualiser les statistiques globales ainsi que les statistiques des listes</div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<div class="checkbox"><label><input type="checkbox" data-toggle="toggle" data-size="small" name="stats"></label></div></div></div></div>	
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Enregistrer les actions</label>
			<div>Les actions de cet utilisateur peuvent être enregistrées pour être visualisées ultérieurement</div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<div class="checkbox"><label><input type="checkbox" data-toggle="toggle" data-size="small" name="log"></label></div></div></div></div>
			<div class="row"><div class="col-md-6"><div class="form-group"><label>Choix de la liste ou des listes pour action :</label>
			<div>Les actions définies de cet utilisateur peuvent porter sur toutes les listes ou sur une liste particulière à sélectionner</div>
			</div></div>
			<div class="col-md-6"><div class="form-group"><div style="padding:5px"></div>
			<label>
			<select name="liste" class="selectpicker" data-width="auto">
			<option value="0">Toutes les listes</option>';
		$list=list_newsletter($cnx,$row_config_globale['table_listsconfig']);
		foreach ($list as $item) {
			echo '<option value="'.$item['list_id'].'">'.$item['newsletter_name'].'</option>';
		}
		echo '</select>
			</label></div></div></div>
			<div class="row"><div class="col-md-4"></div>
			<div class="col-md-4"><input type="submit" value="Ajouter ce compte" class="form-control btn btn-success" /></div>
			<input type="hidden" name="page" value="manage_users">
			<input type="hidden" name="viewms" value="list">
			<input type="hidden" name="op" value="addUser">
			<input type="hidden" name="list_id" value="'.$list_id.'">
			<input type="hidden" name="token" value="'.$token.'">
			<div class="col-md-4"></div></div>
			</form>';
		break;
	default:
	case 'list':
		if($op=='modifyUser'){
			if(isset($_POST['listes'])&&($_POST['listes']=='on')) { $listes='Y';  } else { $listes='N';	 }
			if(isset($_POST['abonnes'])&&($_POST['abonnes']=='on')) { $abonnes='Y';	 } else { $abonnes='N';	 }
			if(isset($_POST['redaction'])&&($_POST['redaction']=='on')) { $redaction='Y';  } else { $redaction='N';	 }
			if(isset($_POST['envois'])&&($_POST['envois']=='on')) { $envois='Y';  } else { $envois='N';	 }
			if(isset($_POST['bounce'])&&($_POST['bounce']=='on')) { $bounce='Y';  } else { $bounce='N';	 }
			if(isset($_POST['stats'])&&($_POST['stats']=='on')) { $stats='Y';  } else { $stats='N';	 }
			if(isset($_POST['log'])&&($_POST['log']=='on')) { $log='Y';	 } else { $log='N';	 }
			if(isset($_POST['liste'])&&($_POST['liste']!='')) { $liste=$_POST['liste']; } else { $liste=''; }
			if(isset($_POST['uspass'])&&(trim($_POST['uspass'])!='')){ 
				$password = 'password = '.escape_string( md5(CleanInput($_POST['uspass']))).', ';
			} else { 
				$password = '';
			}
			$sqlmodifyUser = 'UPDATE '.$row_config_globale['table_users'].' SET
				email = '.escape_string(CleanInput($_POST['usmail'])).',
				'.$password.'
				listes = '.escape_string(CleanInput($listes)).',
				abonnes = '.escape_string(CleanInput($abonnes)).',
				redaction = '.escape_string(CleanInput($redaction)).',
				envois = '.escape_string(CleanInput($envois)).',
				bounce = '.escape_string(CleanInput($bounce)).',
				stats = '.escape_string(CleanInput($stats)).',
				liste = '.escape_string(CleanInput($liste)).',
				log = '.escape_string(CleanInput($log)).'
			WHERE id_user = '.escape_string(CleanInput($_POST['usname']));
			if (!$cnx->query($sqlmodifyUser)) {
				echo '<div class="row"><div class="col-md-12">
					<div class="alert alert-danger">Erreur sur modification du compte : <b>'.CleanInput($_POST['usname']).'</b>.</div>
					</div></div>';
			} else {
				echo '<div class="row"><div class="col-md-12">
					<div class="alert alert-success">Compte <b>'.CleanInput($_POST['usname']).'</b> mis à jour correctement.</b></div>
					</div></div>';
			}		
		}
		if($op=='addUser'){
			if(isset($_POST['usname'])&&($_POST['usname']!='')) { $id_user=$_POST['usname']; }
			if(isset($_POST['usmail'])&&($_POST['usmail']!='')) { $email=$_POST['usmail']; }
			if(isset($_POST['listes'])&&($_POST['listes']=='on')) { $listes='Y'; } else { $listes='N'; }
			if(isset($_POST['abonnes'])&&($_POST['abonnes']=='on')) { $abonnes='Y'; } else { $abonnes='N'; }
			if(isset($_POST['redaction'])&&($_POST['redaction']=='on')) { $redaction='Y'; } else { $redaction=''; }
			if(isset($_POST['envois'])&&($_POST['envois']=='on')) { $envois='Y'; } else { $envois='N'; }
			if(isset($_POST['bounce'])&&($_POST['bounce']=='on')) { $bounce='Y'; } else { $bounce='N'; }
			if(isset($_POST['stats'])&&($_POST['stats']=='on')) { $stats='Y'; } else { $stats='N'; }
			if(isset($_POST['log'])&&($_POST['log']=='on')) { $log='Y'; } else { $log='N'; }
			if(isset($_POST['liste'])&&($_POST['liste']!='')) { $liste=$_POST['liste']; } else { $liste=''; }
			if(isset($_POST['uspass'])&&(trim($_POST['uspass'])!='')){ $password=$_POST['uspass']; } 
			$sqladdUser = 'INSERT INTO '.$row_config_globale['table_users'].' 
				(id_user, email, password, listes, abonnes, redaction, envois, bounce, 
				stats, liste ,log)
				VALUES ('.escape_string(CleanInput($id_user)).',
					'.escape_string(CleanInput($email)).',
					'.escape_string(md5(CleanInput($_POST['uspass']))).',
					'.escape_string(CleanInput($listes)).',
					'.escape_string(CleanInput($abonnes)).',
					'.escape_string(CleanInput($redaction)).',
					'.escape_string(CleanInput($envois)).',
					'.escape_string(CleanInput($bounce)).',
					'.escape_string(CleanInput($stats)).',
					'.escape_string(CleanInput($liste)).',
					'.escape_string(CleanInput($log)).'
				)';
			if (!$cnx->query($sqladdUser)) {
				echo '<div class="row"><div class="col-md-12">
					<div class="alert alert-danger">Erreur sur création du compte : <b>' . CleanInput($id_user).'</b>.</div>
					</div></div>';
			} else {
				echo '<div class="row"><div class="col-md-12">
					<div class="alert alert-success">Création du compte <b>' . CleanInput($id_user).'</b> correcte.</b></div>
					</div></div>';
			}		
		}
		if($op=='delUser'){
			$sqldelUser = 'DELETE FROM '.$row_config_globale['table_users'].'
					WHERE id_user = '.escape_string(CleanInput($account));
			if (!$cnx->query($sqldelUser)) {
				echo '<div class="row"><div class="col-md-12">
				<div class="alert alert-danger">Erreur sur suppression du compte : <b>' . CleanInput($account).'</b>.</div>
				</div></div>';
			} else {
				echo '<div class="row"><div class="col-md-12">
				<div class="alert alert-success">Compte <b>' . CleanInput($account).'</b> supprimé correctement</b></div>
				</div></div>';
			}		
		}
		echo '<div class="row" style="margin-bottom:9px;">
			<div class="col-md-12">
				<div class="subnav_menu">
					<a href="?page=manage_users&token=' . $token . '&list_id=' . $list_id 
					.'&viewms=add" data-toggle="tooltip" title="Ajouter un utilisateur et paramétrer ses droits" class="clearbtn btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i> Ajouter un utilisateur</a>
				</div>
			</div>
		</div>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="datatable">
		<thead> 
			<tr> 
				<th style="text-align:left">Compte</th>
				<th style="text-align:center">Droits : </th>
				<th style="text-align:center">Listes</th>
				<th style="text-align:center">Abonnés</th>
				<th style="text-align:center">Rédaction</th>
				<th style="text-align:center">Envois</th>
				<th style="text-align:center">Stats</th>
				<th style="text-align:center">Bounce</th>
				<th style="text-align:center">Sur liste :</th>
				<th></th>
				<th style="text-align:center">Gérer</th>
			</tr> 
		</thead>
		<tfoot> 
			<tr> 
				<th style="text-align:left">Compte</th>
				<th style="text-align:center">Droits : </th>
				<th style="text-align:center">Listes</th>
				<th style="text-align:center">Abonnés</th>
				<th style="text-align:center">Rédaction</th>
				<th style="text-align:center">Envois</th>
				<th style="text-align:center">Stats</th>
				<th style="text-align:center">Bounce</th>
				<th style="text-align:center">Sur liste :</th>
				<th></th>
				<th style="text-align:center">Gérer</th>
			</tr> 
		</tfoot>
		<tbody>';
		$row=getUsersFull($cnx,$row_config_globale['table_users'],$row_config_globale['table_listsconfig']);
		if ( $row!=false ) {
			foreach	 ($row as $item){
				echo '<tr> 
					<td style="text-align:left">'.$item['id_user'].'</td>
					<td></td>
					<td style="text-align:center">'.($item['listes']=='Y'?'<i class="glyphicon glyphicon-ok" style="color:green" />':'<i class="glyphicon glyphicon-remove" style="color:red"/>').'</td>
					<td style="text-align:center">'.($item['abonnes']=='Y'?'<i class="glyphicon glyphicon-ok" style="color:green" />':'<i class="glyphicon glyphicon-remove" style="color:red"/>').'</td>
					<td style="text-align:center">'.($item['redaction']=='Y'?'<i class="glyphicon glyphicon-ok" style="color:green" />':'<i class="glyphicon glyphicon-remove" style="color:red"/>').'</td>
					<td style="text-align:center">'.($item['envois']=='Y'?'<i class="glyphicon glyphicon-ok" style="color:green" />':'<i class="glyphicon glyphicon-remove" style="color:red"/>').'</td>
					<td style="text-align:center">'.($item['stats']=='Y'?'<i class="glyphicon glyphicon-ok" style="color:green" />':'<i class="glyphicon glyphicon-remove" style="color:red"/>').'</td>
					<td style="text-align:center">'.($item['bounce']=='Y'?'<i class="glyphicon glyphicon-ok" style="color:green" />':'<i class="glyphicon glyphicon-remove" style="color:red"/>').'</td>
					<td style="text-align:center">'.($item['liste']>0?'('.$item['liste'].') '.$item['newsletter_name']:'Toutes listes').'</td>';
				if(is_file("logs/".$item['id_user'].".log")){
					echo '<td style="text-align:center"><a data-toggle="modal" data-target="#modalPmnl" data-tooltip="tooltip" href="include/view_log.php?token='
						. $token . '&u=' . $item['id_user'] . '&t=u&" title="Voir le log des action du compte ' . $item['id_user'] . '"><i class="glyphicon glyphicon-search"></i></a></td>';
				} else {
					echo '<td style="text-align:center">Pas de log</td>';
				}
				echo '<td style="text-align:center">
					<a href="?page=manage_users&token=' . $token 
					. '&list_id=' . $list_id . '&account='.$item['email'].'&viewms=manage" 
					data-toggle="tooltip" title="Modifier cet utilisateur">
					<button type="button" class="btn btn-default btn-sm">
					<span class="glyphicon glyphicon-pencil"></span></button></a>
					<a href="?page=manage_users&token=' . $token 
					. '&list_id=' . $list_id . '&account=' . $item['id_user'] . '&viewms=list&op=delUser" 
					data-toggle="tooltip" title="Supprimer cet utilisateur ?" onclick="return confirm(\'Supprimer le compte utilisateur '.str_replace("'",' ',$item['id_user']).' ?\')">
					<button type="button" class="btn btn-default btn-sm">
					<span class="glyphicon glyphicon-remove"></span></button></a>
				</td></tr>';
			}
		}
		echo '</tbody></table>';
	break;
}