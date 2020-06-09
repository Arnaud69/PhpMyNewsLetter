<?php
if ($op == "saveGlobalconfig") {
	if ($configSaved) {
		echo "<h4 class='alert alert-success'>" . tr("GCONFIG_SUCCESSFULLY_SAVED") . ".</h4>";
		if ($_POST['file'] == 1 && !$configFile){
			echo "<h4 class='alert alert-danger'>" . tr("UNABLE_WRITE_CONFIG") .".</h4>";
		}
	} else {
		if ($configFile == -1){
			echo "<h4 class='alert alert-danger'>" . tr("UNABLE_WRITE_CONFIG") .".</h4>";
		} else if ($file == 1){
			echo "<h4 class='alert alert-danger'>" . tr("ERROR_WHILE_SAVING_CONFIGURATION") . "</h4>";
		}
	}
}
include 'include/lib/constantes_conf.php';
if ( isset($_GET['forceReload']) && $_GET['forceReload']=='true') {
	include ("include/config.php");
}
echo "<form method='post' name='global_config' enctype='multipart/form-data'>
<header><h4 class='tabs_involved'>" . tr('GCONFIG_TITLE') . "</h4></header>
<div class='row'>
	<div class='col-md-10'>
		<div id='rootwizard'>
			<div class='navbar'>
				<div class='navbar-inner'>
					<div class='container'>
						<ul>
							<li><a href='#tab1' data-toggle='tab'>" . tr('INSTALL_DB_TITLE') . "</a></li>
							<li><a href='#tab2' data-toggle='tab'>" . tr('INSTALL_ENVIRONMENT') . "</a></li>
							<li><a href='#tab3' data-toggle='tab'>" . tr('INSTALL_MESSAGE_SENDING_TITLE') . "</a></li>
							<li><a href='#tab4' data-toggle='tab'>" . tr('BOUNCE') . "</a></li>
							<li><a href='#tab5' data-toggle='tab'>" . tr('GCONFIG_SUBSCRIPTION_TITLE') . "</a></li>
							<li><a href='#tab7' data-toggle='tab'>" . tr('GCONFIG_DKIM_SPF_DMARC') . "</a></li>
							<li><a href='#tab6' data-toggle='tab'>" . tr('GCONFIG_MISC_TITLE') . "</a></li>
							<li><a href='#tab8' data-toggle='tab'>APIS</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class='tab-content'>
				<div id='tab1' class='tab-pane'>
					<div class='module_content'>
						<h4>" . tr('GCONFIG_DB_TITLE'). "</h4>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_HOST") . "</label>
								<input type='hidden' name='file' value='1'><input class='form-control' type='text' name='db_host' value='" . htmlspecialchars($hostname) . "' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_DBNAME") . "</label>
								<input class='form-control' type='text' name='db_name' value='" . htmlspecialchars($database) . "' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("INSTALL_DB_TYPE") . "</label><br>
									<select name='db_type' class='selectpicker' data-width='auto'>
									<option value='mysql' selected>MySQL</option>
									</select>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_LOGIN") . "</label>
									<input autocomplete='off' class='form-control' type='text' name='db_login' value='" . htmlspecialchars($login) . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_PASSWD") . "</label>
									<input autocomplete='off' class='form-control' type='password' name='db_pass' value='" . htmlspecialchars($pass) . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_CONFIG_TABLE") . "</label>
									<input class='form-control' type='text' name='table_config' value='" . htmlspecialchars($table_global_config) . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_MAIL") . "</label>
									<input class='form-control' type='text' name='table_email' value='" . htmlspecialchars($row_config_globale['table_email']) . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_TEMPORARY") . "</label>
									<input class='form-control' type='text' name='table_temp' value='" . htmlspecialchars($row_config_globale['table_temp']) . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_NEWSCONFIG") . "</label>
									<input class='form-control' type='text' name='table_listsconfig' value='" . htmlspecialchars($row_config_globale['table_listsconfig']) . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_ARCHIVES") . "</label>
									<input class='form-control' type='text' name='table_archives' value='" . htmlspecialchars($row_config_globale['table_archives']) . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_SUBMOD") . "</label>
									<input class='form-control' type='text' name='table_sub' value='" . htmlspecialchars($row_config_globale['mod_sub_table']) . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_TRACK") . "</label>
									<input class='form-control' type='text' name='table_track' value='" . htmlspecialchars($row_config_globale['table_tracking']) . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_SEND") . "</label>
									 <input class='form-control' type='text' name='table_send' value='" . htmlspecialchars($row_config_globale['table_send']) . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_SV") . "</label>
									<input class='form-control' type='text' name='table_sauvegarde' value='" . htmlspecialchars($row_config_globale['table_sauvegarde']) . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_UPLOAD") . "</label>
									<input class='form-control' type='text' name='table_upload' value='" . htmlspecialchars($row_config_globale['table_upload']) . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_MAIL_DELETED") . "</label>
									<input class='form-control' type='text' name='table_email_deleted' value='" . htmlspecialchars($row_config_globale['table_email_deleted']) . "' />
								</div>		
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_DB_TABLE_SENDERS") . "</label>
									<input class='form-control' type='text' name='table_senders' value='" . htmlspecialchars($row_config_globale['table_senders']) . "' />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id='tab2' class='tab-pane'>
					<div class='module_content'>
						<h4>" . tr("GCONFIG_MANAGE_ENVIRONMENT" ) .  "</h4>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("INSTALL_SERVER_TYPE") . "</label><br>
									<select name='type_serveur' class='selectpicker' data-width='auto'>
									<option value='shared' " . ( $type_serveur=='shared'?'selected="selected"':'') . ">" . tr("SHARED_SERVER" ) .  "</option>
									<option value='dedicated' " . ( $type_serveur=='dedicated'?'selected="selected"':'') . ">" . tr("DEDICATED_SERVER") . "</option>
									</select>
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'>
									<label>" . tr("INSTALL_ENVIRONMENT") . "</label><br>
									<select name='type_env' class='selectpicker' data-width='auto'>
									<option value='dev' " . ($type_env=='dev' ?'selected':'') . ">" . tr("INSTALL_DEVELOPMENT") . "</option>
									<option value='prod' " . ( $type_env=='prod'?'selected':'') . ">" . tr("INSTALL_PRODUCTION") ."</option>
									</select>
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("LOCAL_TIME_ZONE") . " : </label><br>
									<select name='timezone' class='selectpicker' data-width='auto'>
									" . $PAYS_WITH_OPTION ."
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id='tab3' class='tab-pane'>
					<div class='module_content'>
						<h4>" . tr("GCONFIG_MESSAGE_HANDLING_TITLE") . "</h4>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_ADMIN_NAME") . "</label>
									<input class='form-control' type='text' name='admin_name' size='30' value='" . htmlspecialchars($row_config_globale['admin_name']) . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_ADMIN_MAIL") . "</label>
									<input class='form-control' type='text' name='admin_email' size='30' value='" . htmlspecialchars($row_config_globale['admin_email']) . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>Code 'mail-tester', exemple : test-abcd1234a@srv1.mail-tester.com</label>
									<input class='form-control' type='text' name='code_mailtester' size='50' value='" . ($code_mailtester!='' ? $code_mailtester : '') . "' />
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>Encrypter les mails dans les liens de tracking ?</label><br>";
									if($do_encrypt=='0'||$do_encrypt==''){
										echo "<input type='radio' name='do_encrypt' value='0' checked='checked'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='do_encrypt' value='1'>&nbsp;" . tr("YES") . "";
									}elseif($do_encrypt=='1'){
										echo "<input type='radio' name='do_encrypt' value='0'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='do_encrypt' value='1' checked='checked'>&nbsp;" . tr("YES") . "";
									}
						echo "		</div>
							</div>
						</div>
						<h4>" . tr("GCONFIG_TIMER_CROM_TIMER_AJAX") . "</h4>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_TIMER_AJAX") . ", ". tr("GCONFIG_SECONDES") ." (" . tr("GCONFIG_TIME_FOR_EACH_LOOP") . ")</label>
									<input class='form-control' type='text' name='timer_ajax' size='30' value='" . ($timer_ajax!='' ? $timer_ajax : '10') . "' /></div>
								</div>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_NUM_LOOP") . "</label>
									<input class='form-control' type='text' name='sending_limit' size='3' value='".$row_config_globale['sending_limit']."' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>" . tr("GCONFIG_TIMER_CRON") . ", ". tr("GCONFIG_SECONDES") ." (" . tr("GCONFIG_TIME_FOR_EACH_SEND") . ")</label>
									<input class='form-control' type='text' name='timer_cron' size='30' value='" . ($timer_cron!='' ? $timer_cron : '3') . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-3'>
								<div class='form-group'><label>". tr("GCONFIG_ALERT_END_SCHEDUL_TASK") . " ?</label><br>";
								if($end_task=='0'||$end_task==''){
									echo "<input type='radio' name='end_task' value='0' checked='checked'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='end_task' value='1'>&nbsp;" . tr("YES") . "";
								}elseif($end_task=='1'){
									echo "<input type='radio' name='end_task' value='0'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='end_task' value='1' checked='checked'>&nbsp;" . tr("YES") . "";
								}
						echo "		</div>
							</div>";
						if(@$free_id!=''&&$free_pass!=''){
							echo "<div class='col-md-4'>
								<div class='form-group'><label>Recevoir un FREE sms de fin de tâche planifiée ?</label><br>";
								if($end_task_sms=='0'){
									echo "<input type='radio' name='end_task_sms' value='0' checked='checked'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='end_task_sms' value='1'>&nbsp;" . tr("YES") . "";
								}elseif($end_task_sms=='1'){
									echo "<input type='radio' name='end_task_sms' value='0'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='end_task_sms' value='1' checked='checked'>&nbsp;" . tr("YES") . "";
								}
								echo "</div>
							</div>";
						}
						echo "	<div class='col-md-3'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_CHARSET") . "</label><br>
									<select name='charset' class='selectpicker' data-width='auto'>";
									sort($locals);
									foreach ($locals as $local) {
										echo "<option value='$local'" . ($row_config_globale['charset'] == $local ? ' selected' : '') . ">$local</option>";
									}
						echo "			</select>
								</div>
							</div>
							<div class='col-md-2'>
								<div class='form-group'><label>Tracking ?</label><br>";
								if($row_config_globale['active_tracking']=='0'){
									echo "<input type='radio' name='active_tracking' value='0' checked='checked'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='active_tracking' value='1'>&nbsp;" . tr("YES") . "";
								}elseif($row_config_globale['active_tracking']=='1'){
									echo "<input type='radio' name='active_tracking' value='0'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='active_tracking' value='1' checked='checked'>&nbsp;" . tr("YES") . "";
								}
						echo "		</div>
							</div>
						</div>
						<h4>" .tr("GCONFIG_SMTP_CONFIGURATION"). "</h4>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_SEND_METHOD") . "</label><br>
									<select name='sending_method' onChange='checkSMTP()' class='selectpicker' data-width='auto'>
										<option value='smtp' ";
										if ($row_config_globale['sending_method'] == "smtp") 
											echo "selected='selected'" ;
										echo ">SMTP</option>
										<option value='smtp_over_tls' ";
										if ($row_config_globale['sending_method'] == "smtp_over_tls") 
											echo "selected='selected'" ;
										echo ">SMTP TLS (port 587)</option>
										<option value='smtp_over_ssl' ";
										if ($row_config_globale['sending_method'] == "smtp_over_ssl") 
											echo "selected='selected'" ;
										echo ">SMTP SSL (port 465)</option>
										<option value='lbsmtp' ";
										if ($row_config_globale['sending_method'] == "lbsmtp") 
											echo "selected='selected' " ;
										echo ">Load Balancing SMTP</option>
										<option value='smtp_gmail_tls' ";
										if ($row_config_globale['sending_method'] == "smtp_gmail_tls")
											echo "selected='selected'" ;
										echo ">SMTP GMAIL TLS (port 587)</option>
										<option value='smtp_gmail_ssl' ";
										if ($row_config_globale['sending_method'] == "smtp_gmail_ssl") 
											echo "selected='selected'" ;
										echo ">SMTP GMAIL SSL (port 465)</option>
										<option value='php_mail' ";
										if ($row_config_globale['sending_method'] == "php_mail") 
											echo "selected='selected'" ;
										echo ">" . tr("GCONFIG_MESSAGE_SEND_METHOD_FUNCTION") . "</option>
										<option value='smtp_mutu_ovh' ";
										if ($row_config_globale['sending_method'] == "smtp_mutu_ovh") 
											echo "selected='selected'" ;
										echo ">SMTP " . tr("INSTALL_SHARED") . " OVH</option>
										<option value='smtp_mutu_1and1' ";
										if ($row_config_globale['sending_method'] == "smtp_mutu_1and1") 
											echo "selected='selected'" ;
										echo ">SMTP " . tr("INSTALL_SHARED") . " 1AND1</option>
										<option value='smtp_mutu_gandi' ";
										if ($row_config_globale['sending_method'] == "smtp_mutu_gandi") 
											echo "selected='selected'" ;
										echo ">SMTP " . tr("INSTALL_SHARED") . " GANDI</option>
										<option value='smtp_mutu_online' ";
										if ($row_config_globale['sending_method'] == "smtp_mutu_online") 
											echo "selected='selected'" ;
										echo ">SMTP " . tr("INSTALL_SHARED") . " ONLINE</option>
										<option value='smtp_mutu_infomaniak' ";
										if ($row_config_globale['sending_method'] == "smtp_mutu_infomaniak") 
											echo "selected='selected'" ;
										echo ">SMTP " . tr("INSTALL_SHARED") . " INFOMANIAK</option>
										<option value='smtp_one_com' ";
										if ($row_config_globale['sending_method'] == "smtp_one_com") 
											echo "selected='selected'" ;
										echo ">SMTP ONE.COM</option>
										<option value='smtp_one_com_ssl' ";
										if ($row_config_globale['sending_method'] == "smtp_one_com_ssl") 
											echo "selected='selected'" ;
										echo ">SMTP ONE.COM SSL</option>
									</select>
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_SMTP_HOST") . "</label>
									<input class='form-control' type='text' name='smtp_host' value='".$row_config_globale['smtp_host']."' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_SMTP_PORT") . "</label>
									<input class='form-control' type='text' name='smtp_port' value='".$row_config_globale['smtp_port']."' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_SMTP_AUTH") . "</label><br>";
									if($row_config_globale['smtp_auth']=="0"){
										echo "<input type='radio' name='smtp_auth' value='0' checked='checked'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='smtp_auth' value='1'>&nbsp;" . tr("YES") . "";
									}elseif($row_config_globale['smtp_auth']=="1"){
										echo "<input type='radio' name='smtp_auth' value='0'>&nbsp;" . tr("NO") . "&nbsp;<input type='radio' name='smtp_auth' value='1' checked='checked'>&nbsp;" . tr("YES") . "";
									}
							echo "	</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_SMTP_LOGIN") . "</label>
									<input class='form-control' type='text' name='smtp_login' value='" . ( $row_config_globale['smtp_login']!=''?$row_config_globale['smtp_login']:'') . "' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_MESSAGE_SMTP_PASSWORD") . "</label>
									<input class='form-control' type='password' name='smtp_pass' value='" . ( $row_config_globale['smtp_pass']!=''?$row_config_globale['smtp_pass']:'') . "' />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id='tab4' class='tab-pane'>
					<div class='module_content'>
						<h4>" . tr("GCONFIG_MANAGE_BOUNCE") . "</h4>
						" . tr("BOUNCE_WARNING") . "
						<div class='alert alert-danger'>" . tr("ALERT_MAIL_BOUNCE") . "</div>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("MAIL_FOR_BOUNCE") . "</label>
								<input class='form-control' type='text' name='bounce_mail' id='bounce_mail' value='" . (!empty($bounce_mail) ? $bounce_mail: '') . "' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_HOST_MAIL") . "</label>
									<input class='form-control' type='text' name='bounce_host' id='bounce_host' value='" . (!empty($bounce_host) ? $bounce_host : 'localhost') . "' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("INSTALL_DB_LOGIN") . "</label>
									<input class='form-control' type='text' name='bounce_user' id='bounce_user' value='" . (!empty($bounce_user) ? $bounce_user : '') . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("INSTALL_DB_PASS") . "</label>
									<input class='form-control' type='password' name='bounce_pass' id='bounce_pass' value='" . (!empty($bounce_pass) ? $bounce_pass : '') . "' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_PORT") . "</label>
									<input class='form-control' type='text' name='bounce_port' id='bounce_port' value='" . (!empty($bounce_port) ? $bounce_port : '110') . "' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_SERVICE") . "</label>
									<input class='form-control' type='text' name='bounce_service' id='bounce_service' value='" . (!empty($bounce_service) ? $bounce_service : 'pop3') . "' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_SERVICE_OPTION") . "</label>
									<input class='form-control' type='text' name='bounce_option' id='bounce_option' value='" . (!empty($bounce_option) ? $bounce_option : 'notls') . "'>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-3'>
								<input class='btn btn-success' type='button' name='action' id='TestBounce' value='" . tr("GCONFIG_TEST_BOUNCE") . "' />
								<input type='hidden' name='bounce_token' id='bounce_token' value='$token'>
							</div>
							<div class='col-md-9'>
								<span id='RsBounce' align='center'>&nbsp;</span>
							</div>
						</div>
						<script>
						$('#TestBounce').click(function(){
							$('#RsBounce').html('" . tr("GCONFIG_TRY_CONNECT") . "...');
							$.ajax({
								type:'POST',
								url: 'include/ajax/test_imap.php',
								data: {'bounce_host':$('#bounce_host').val(),'bounce_user':$('#bounce_user').val(),'bounce_pass':$('#bounce_pass').val(),'bounce_port':$('#bounce_port').val(),'bounce_service':$('#bounce_service').val(),'bounce_option':$('#bounce_option').val(),'token':$('#bounce_token').val()},
								cache: false,
								success: function(data){
									$('#RsBounce').html(data);
								}
							});
						});
						</script>";
					echo '	
					</div>
				</div>';
				echo "
				<div id='tab5' class='tab-pane'>
					<div class='module_content'>
						<h4>" . tr("GCONFIG_SUBSCRIPTION_TITLE") . "</h4>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_SUBSCRIPTION_CONFIRM_SUB") . "</label><br>
									<input type='radio' name='sub_validation'	 value='0' ";
									if (!$row_config_globale['sub_validation']) 
										echo "checked='checked'";
									echo "> " . tr("NO") . "
									&nbsp;<input type='radio' name='sub_validation' value='1' ";
									if ($row_config_globale['sub_validation']) 
										echo "checked='checked'";
									echo "> " . tr("YES") . "
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>". tr("GCONFIF_SUBSCRIPTION_VALIDATION_TIMEOUT") ."</label>
									<input class='form-control' type='text' name='validation_period' value='".$row_config_globale['validation_period']."' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_ALERT_SUB") . "</label><br>
									<input type='radio' name='alert_sub' value='0' ";
									if (!$row_config_globale['alert_sub']) 
										echo "checked='checked'";
									echo "> " . tr("NO") . "
									&nbsp;<input type='radio' name='alert_sub' value='1' ";
									if ( $row_config_globale['alert_sub'] || !isset($row_config_globale['alert_sub']) || $row_config_globale['alert_sub']=='' ) 
										echo "checked='checked'";
									echo "> " . tr("YES") ."
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_SUBSCRIPTION_CONFIRM_UNSUB") . "</label><br>
									<input type='radio' name='unsub_validation' value='0' ";
									if (!$row_config_globale['unsub_validation']) 
										echo "checked='checked'";
									echo "> " . tr("NO") . "
									&nbsp;<input type='radio' name='unsub_validation' value='1' ";
									if ($row_config_globale['unsub_validation']) 
										echo "checked='checked'";
									echo "> " . tr("YES") ."
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("ALERT_UNSUB") . "</label><br>
									<input type='radio' name='alert_unsub' value='0' ";
									if ( $alert_unsub=='0' ) 
										echo "checked='checked'";
									echo " > " . tr("NO") . "
									&nbsp;<input type='radio' name='alert_unsub' value='1' ";
									if ( $alert_unsub=='1' ) 
										echo "checked='checked'";
									echo " > " . tr("YES") ."</div>
								</div>
							</div>
						</div>";
						if( @$free_id!='' && $free_pass!='' ){
							echo "
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>Etre averti des nouvelles inscriptions par FREE sms ?</label><br>
									<input type='radio' name='sub_validation_sms' value='0' ";
									if ( @$sub_validation_sms==0 ) 
										echo "checked='checked'";
									echo " > " . tr("NO") . "
									&nbsp;<input type='radio' name='sub_validation_sms' value='1' ";
									if ( @$sub_validation_sms==1 || !isset($sub_validation_sms) || $sub_validation_sms=='' ) 
										echo "checked='checked'";
									echo " > " . tr("YES") . "
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>Etre averti d'une désinscription par FREE sms ?</label><br>
									<input type='radio' name='unsub_validation_sms' value='0' ";
									if ( @$unsub_validation_sms==0 ) 
										echo "checked='checked'";
									echo " > " . tr("NO") . "
									&nbsp;<input type='radio' name='unsub_validation_sms' value='1' ";
									if ( @$unsub_validation_sms==1 || !isset($unsub_validation_sms) || $unsub_validation_sms=='' ) 
										echo "checked='checked'";
									echo " > " . tr("YES") . "
								</div>
							</div>
						</div>";
						}
						echo "
					
				</div>";
				echo "
				<div id='tab6' class='tab-pane'>
					<div class='module_content'>
						<h4>" . tr("GCONFIG_MISC_TITLE") . "</h4>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>". tr("GCONFIG_MISC_ADMIN_PASSW") . " " . tr("GCONFIG_MISC_ADMIN_PASSW2") ."</label>
									<input class='form-control' type='password' name='admin_pass' value='' autocomplete='off' />
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>". tr("GCONFIG_MISC_BASE_URL") . " (Sans le / de fin !)</label>
									<input class='form-control' type='text' name='base_url' value='".$row_config_globale['base_url']."' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>". tr("GCONFIG_MISC_BASE_PATH") . "</label>
									<input class='form-control' type='text' name='path' value='".$row_config_globale['path']."' />
								</div>
							</div>
							<div class='col-md-4'>
								<div class='form-group'><label>". tr("GCONFIG_MISC_LANGUAGE") . "</label>
									<br><select name='language' class='selectpicker' data-width='auto'>".getLanguageList($row_config_globale['language']) . "</select>
								</div>
							</div>
						</div>
						
						<h4>". tr("GCONFIG_DATABASE_BACKUPS") . " :</h4>
						<div class='row'>
							<div class='col-md-6'>
								<div class='form-group'><label>". tr("GCONFIG_NUMBER_BACKUPS") . " :</label>
									<input class='form-control' type='text' name='nb_backup' value='".@$nb_backup."' autocomplete='off' />
								</div>
							</div>
						</div>";
						if ( $row_config_globale['language'] == "francais" ) {
							echo "<h4>Paramètres SMS API (pour les titulaires de ligne FREE Mobile)</h4>
								<div class='row'>
									<div class='col-md-4'>
										<div class='form-group'><label>Identifiant FREE</label>
											<input class='form-control' type='text' name='free_id' value='".@$free_id."' />
										</div>
									</div>
									<div class='col-md-4'>
										<div class='form-group'><label>Clé d'identification au service :</label>
											<input class='form-control' type='text' name='free_pass' value='".@$free_pass."' />
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										Ce service gratuit proposé par free est intégré dans PhpMyNewsLetter pour vous informer :<br>
										- des fins d'envois des messages planifiés (si option cochée et identifiants FREE renseignés)<br>
										- des nouvelles inscriptions (si option cochée et identifiants FREE renseignés)<br>
										- des désinscriptions (si option cochée et identifiants FREE renseignés)<br>
										Pour activer ce service, il faut que vous soyiez titulaire d'une ligne mobile FREE et que vous activiez le service dans votre espace personnel :<br>
										> Connexion sur <a href='https://mobile.free.fr/moncompte/' target='_blank'>FREE</a> > Gérer mon compte > Mes options > Notifications par SMS<br>
										<div align='center'><img src='css/NotifSMS-f9edd.png' /><br>&copy; <a href='https://www.freenews.fr/freenews-edition-nationale-299/free-mobile-170/nouvelle-option-notifications-par-sms-chez-free-mobile-14817'>Freenews</a></div>
										Vous renseignerez ici vos identifiants FREE (l'identifiant de connexion à votre compte) et la clé d'identification au service.<br>
										Ce n'est que lorsque ces identifiants auront été renseignés ET enregistrés que les options de notifications seront disponibles.<br>
										Les notifications seront adressées au seul numéro de mobile lié à ce compte
									</div>
								</div>";
						}
				
				$DomainToTest = parseUrl($row_config_globale['base_url']);
				if (file_exists("include/DKIM/DKIM_config.php") && ($row_config_globale['sending_method'] == 'smtp' || $row_config_globale['sending_method'] == 'php_mail')) {
					$test_dkim_apis=0;
				} else {
					$test_dkim_apis=1;
				}
				echo "	</div>
				</div>
				<div id='tab7' class='tab-pane'>
					<div class='module_content'>
						<h4>" . tr("GCONFIG_DKIM_TITLE") . "</h4>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>URL à tester</label><br>
									<input class='form-control' type='text' name='domaine' id='domaine' value='" . $DomainToTest[6] . "' autocomplete='off' />
								</div>
							</div>
							<div class='col-md-4'>
								<br>Ajuster le smtp si besoin (ex, domaine : example.com, smtp : smtp.exple.com)
							</div>
						</div>
						<div class='row'>
							<div class='col-md-4'>
								<div class='form-group'><label>" . tr("GCONFIG_DKIM_SELECTOR")  . "</label><br>
									<input class='form-control' type='text' name='key_dkim' id='key_dkim' value='" . $key_dkim . "' autocomplete='off' />
								</div>
							</div>
							<div class='col-md-4'>
								<br>" . tr("GCONFIG_DKIM_EXPLAIN") . "
							</div>
						</div>
						<div class='row'>
							<div class='col-md-2'>
								<input class='btn btn-success' type='button' name='action' id='TestKeys' value='" . tr("GCONFIG_DKIM_TEST") . "' />
								<input type='hidden' name='key_token' id='key_token' value='" . $token . "'>
							</div>
							<div class='col-md-1'>
							</div>
							<div class='col-md-9'>
								<span id='RsTestKeys'>&nbsp;</span>
							</div>
						</div>
					</div>
				</div>
				<script>
				$('#TestKeys').click(function(){
					$('#RsTestKeys').html('" . tr("RUNNING_TEST") . "');
					$.ajax({
						type:'POST',
						cache: false,
						url: 'https://www.phpmynewsletter.com/apis/dns.php', 
						data: {'key_dkim':$('#key_dkim').val(),'domaine':$('#domaine').val()".($test_dkim_apis==1?", 'test_dkim':1":"").",'type_return':'html'},
						success: function(response){
							$('#RsTestKeys').html(response);
						}
					});
				});
				</script>
				<div id='tab8' class='tab-pane'>
					<div class='module_content'>
						<h4>APIS</h4>
						<div class='row'>
							<div class='col-md-2'>
								<div class='form-group'><label>APIS actifs ?</label><br>
									<input type='radio' name='apis_available' value='0' ";
									if ( @$apis_available==0 ) 
										echo "checked='checked'";
									echo " > " . tr("NO") . "
									&nbsp;<input type='radio' name='apis_available' value='1' ";
									if ( @$apis_available==1 || !isset($apis_available) || $apis_available=='' ) 
										echo "checked='checked'";
									echo " > " . tr("YES") . "
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'><label>Clé API</label><br>
									<div class='alert alert-danger'><b>Chaîne de caractère de 32 caractère [a-zA-Z0-9]</b>
										<input type='text' id='api_key' name='api_key' value='" . @$api_key . "' class='form-control'>
									</div>
								</div>
							</div>
							<div class='col-md-4'>
								<div class='alert alert-warning'>
									<label>Attention :</label><br>
									En générant une nouvelle clé, celle ci ne sera active et valable qu'après avoir sauvegardé 
									l'ensemble des paramètres de la configuration.
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-8 text-center'>
								<input class='btn btn-success' type='button' name='action' id='GenApiKey' value='Générer une nouvelle clé' />
								<input type='hidden' name='key_token' id='api_token' value='" . $token . "'>
							</div>
							<div class='col-md-4'>
							<script>
							$('#GenApiKey').click(function(){
								$.ajax({
									type:'POST',
									url: 'include/ajax/gen_key.php',
									data: {'token':$('#api_token').val()},
									cache: false,
									success: function(data){
										$('#api_key').val(data);
										$('#api_key').trigger( 'change' );
									}
								});
							});
							</script>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='col-md-2'>
		<div class='content-box fixedBox'>
			<h4>" . tr( "ACTIONS") . " :</h4>
			<input type='hidden' name='op' value='saveGlobalconfig'>
			<input type='hidden' name='mod_sub' value='0'>
			<input type='hidden' name='token' value='" . $token . "' />
			<input type='submit' value='" . tr("GCONFIG_SAVE_BTN") . "' class='btn btn-success'>
		</div>
	</div>
</div>
</form>";


