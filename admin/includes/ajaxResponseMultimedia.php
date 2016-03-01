<?php
require_once 'funciones_multimedia.php';
if($_POST["ajax"]==1){
	//busca categorias hijas
	if($_POST["BuscarCategoria"]==1){
		getCategoriasMultimedia(intval($_POST["parent"]), $_POST["selected"]);
		die();
	}
}