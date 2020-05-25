<?php
$db_ok = array('mysql','pgsql','mssql','oracle');
if(isset($db_type)&&(in_array($db_type,$db_ok))){ 
	switch($db_type){
		case 'mysql':
			try {
				$cnx = new PDO("mysql:dbname=$database;host=$hostname","$login","$pass");
				$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e) {
				if ( $type_env=='dev' ) {
					die($e->getMessage());//Remove or change message in production code
				}
			}
		break;
		/*
		case 'mssql':
			if(!defined( "_DB_MSSQL_LAYER" )){
				define("_DB_MSSQL_LAYER", 1);
			}
			define("PDO_DSN","mssql:host=$hostname;dbname=$database");
			define("PDO_USERNAME",$login);
			define("PDO_PASSWORD",$pass);
			$cnx = new PDOExtended(PDO_DSN,PDO_USERNAME,PDO_PASSWORD);
		break;
		case 'pgsql':
			if(!defined( "_DB_PGSQL_LAYER" )){
				define("_DB_PGSQL_LAYER", 1);
			}
			define("PDO_DSN","pgsql:host=$hostname;dbname=$database,port=5432");
			define("PDO_USERNAME",$login);
			define("PDO_PASSWORD",$pass);
			$cnx = new PDOExtended(PDO_DSN,PDO_USERNAME,PDO_PASSWORD);
		break;
		case 'oracle':
			if(!defined( "_DB_ORACLE_LAYER" )){
				define("_DB_ORACLE_LAYER", 1);
			}
			define("PDO_DSN","oci://$hostname:1521/$database;charset=UTF-8");
			define("PDO_USERNAME",$login);
			define("PDO_PASSWORD",$pass);
			$cnx = new PDOExtended(PDO_DSN,PDO,USERNAME,PDO_PASSWORD);
		break;
		*/
	}
} else {
	die('une erreur a probablement eu lieu lors de l\'installation...');
}
