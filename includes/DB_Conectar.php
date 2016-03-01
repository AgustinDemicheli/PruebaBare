<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//error_reporting(0);
ini_set("display_errors", "off");

session_start();

$app_path = $_SERVER["DOCUMENT_ROOT"];

if($_SERVER["SCRIPT_NAME"]<>"/index_proc2.php")
    $esPreview = 1;

if (!isset($db_conectar)) {
switch ($_SERVER["SERVER_NAME"]) {
		
	default:
		$con_dbase         = "guiajudicial";
		$con_userid        = "guiajudicial";
		$app_path 		   = $app_path . "/";
		$con_password      = "mEs4HzbPLfdBCDcB"; 
		$con_server        = "slayer"; 
		$var_path		   = "/";
		$app_IDS		   = $app_path; 
		$var_url		   = "http://guiajudicial.jusbaires.gob.ar";

		$desarrollo=false;
		break;

	}
	

	include_once("DB_MySQL.php");
	include_once("funciones.php");
	require_once ("consejo.php");

	include_once($app_path."includes/imports.php");

	$conn = new TConnection;
	$conn->Connect($con_server, $con_userid, $con_password, $con_dbase);

	$TITULO_SITE = "Consejo de la Magistratura";

}
?>
