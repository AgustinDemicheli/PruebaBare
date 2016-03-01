<?php  
    
	
	    /*
	    Copyright 2008, 2009, 2010, 2011 Patrik Hultgren
	    
	    YOUR PROJECT MUST ALSO BE OPEN SOURCE IN ORDER TO USE THIS VERSION OF PHP IMAGE EDITOR.
	    BUT YOU CAN USE PHP IMAGE EDITOR JOOMLA PRO IF YOUR CODE NOT IS OPEN SOURCE.
	    
	    This file is part of PHP Image Editor Normal.
	
	    PHP Image Editor Normal is free software: you can redistribute it and/or modify
	    it under the terms of the GNU General Public License as published by
	    the Free Software Foundation, either version 3 of the License, or
	    (at your option) any later version.
	
	    PHP Image Editor Normal is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.
	
	    You should have received a copy of the GNU General Public License
	    along with PHP Image Editor Normal. If not, see <http://www.gnu.org/licenses/>.
	    */
	
	include_once("../../includes/DB_Conectar.php");
	
	$idFoto = $_GET["idFoto"];
	
	$datosF = $conn->execute("SELECT * FROM advf WHERE advID = '".$idFoto."'");
	
	$rutaFoto = $app_path.$datosF->field("advLink");
	$descFoto = $datosF->field("advTitulo");
	$catFoto = $datosF->field("catID");
	DEFINE("LAUNCHER",$_GET["launcher"]);
	
	DEFINE("RUTA_FOTO", $rutaFoto);
	DEFINE("CAT_FOTO", $catFoto);

	$_SESSION["RUTA_FOTO"] = RUTA_FOTO;

    include 'lite/shared/index.php';
?>