<?php
include_once("../../includes/DB_Conectar.php");

if(isset($_POST["advID"])  ){//OR isset($_GET["advID"])
	$advID = $_POST["advID"];
	$q = "SELECT advID,advLink FROM  `advf` WHERE  `advID` = ".$advID;
	$rs = $conn->execute($q);

	
	if(!$rs->eof){
		$id = $rs->field("advID");
		$url = $rs->field("advLink");
		
		$eliminar = $app_path . substr($url, 0, strrpos($url, '.')) . "*.*";
		
		//por qué abdf?? no es advf?
		if(!stristr($eliminar, 'abdf/imagenes/')){
			echo false;
		}
		
		//$codigo = substr($url, strrpos($url, '/')+1, strrpos($url, '.'));
		$codigo = substr($url, strrpos($url, '/')+1, strrpos($url, '.')-(strrpos($url, '/')+1));
		if(strlen($codigo)!= 13){
			echo false;
		}
		//$codigo = preg_replace("#([A-Za-z0-9-]+)\/([A-Za-z0-9-]+)\.(.*)#i", "$2", $url);
		
		//$eliminar = preg_replace("/([A-Za-z0-9-]+)\.(.*)/i", "$1*.*", $app_path.$url);
		//echo $eliminar;exit();
		
		
		//$path = substr($url, 0, strrpos($url, '.')) . "*.*";
		
		//$eliminar = preg_replace("/([A-Za-z0-9-]+)\.(.*)/i", "$1*.*", $url);
		
		//$eliminar = $app_path.$eliminar;
		
		foreach (glob($eliminar) as $filename) {
			if(!unlink($filename)){
				$error = true;				
			}
		}
		
								
		
		if(!isset($error)){
			$q = "DELETE FROM advf WHERE advID = ".$advID;
			$r = $conn->execute($q);
			echo true;
		}else{
			echo false;
		}
	}
		
	
}