<?php

function getSeccion($id_seccion)
{
	global $conn;

	$seccion = $conn->getRecordset("
	SELECT S.*, SP.titulo_menu , S2.nombre_seccion_menu AS padre
	FROM secciones S 
	INNER JOIN secciones_padre SP ON S.id_seccion_principal = SP.id 
	LEFT JOIN secciones S2 ON S.id_seccion_padre = S2.id 
	WHERE S.id = '".$id_seccion."' AND S.activo = 'S'");

	$seccion[0]["VIDEOS_CONTENIDO"] = array();
	$seccion[0]["FOTOS_CONTENIDO"] = array();
	$seccion[0]["ARCHIVOS"] = array();
	$seccion[0]["ENLACES_RELACIONADOS"] = array();
	$seccion[0]["CONTENIDO_RELACIONADO"] = array();

	//Fotos contenidos
	$rsFotosContenido = $conn->execute("SELECT cf.id_foto, cf.epigrafe, cf.orden, advf.advLink
										FROM secciones_fotos AS cf
										INNER JOIN advf ON cf.id_foto = advf.advID
										WHERE cf.id_seccion=" . $id_seccion);
	while (!$rsFotosContenido->eof) {
		$vFotoContenido = array("ID_FOTO" => $rsFotosContenido->field("id_foto"), "EPIGRAFE" => $rsFotosContenido->field("epigrafe"), "LINK" => $rsFotosContenido->field("advLink"));
		array_push($seccion[0]["FOTOS_CONTENIDO"], $vFotoContenido);
		$rsFotosContenido->movenext();
	}

	//Videos
	$rsVideos = $conn->execute("SELECT id, video_medio, video_codigo, video_descripcion, epigrafe FROM secciones_videos WHERE id_seccion = '" . $id_seccion . "' ORDER BY orden");
	while (!$rsVideos->eof) {
		$vVideo = array("ID" => $rsVideos->field("id"), "MEDIO" => $rsVideos->field("video_medio"), "CODIGO" => $rsVideos->field("video_codigo"), "DESCRIPCION" => $rsVideos->field("video_descripcion"));
		array_push($seccion[0]["VIDEOS_CONTENIDO"], $vVideo);
		$rsVideos->movenext();
	}

	//Archivos relacionados
	$rsArchivos = $conn->execute("SELECT ca.id_archivo, advf.advTitulo, advf.advLink
								  FROM secciones_archivos AS ca
								  INNER JOIN advf ON ca.id_archivo = advf.advID
								  WHERE ca.id_seccion = " . $id_seccion);
	while (!$rsArchivos->eof) {
		$vArchivo = array("ID" => $rsArchivos->field("id_archivo"), "TITULO" => $rsArchivos->field("advTitulo"), "LINK_ABSOLUTE" => $var_url . "/" . $rsArchivos->field("advLink"));

		array_push($seccion[0]["ARCHIVOS"], $vArchivo);

		$rsArchivos->movenext();
	}

	//Enlaces relacionados
	$rsEnlaces = $conn->execute("SELECT id, enlace_titulo, enlace, enlace_target
								 FROM secciones_enlaces
								 WHERE id_seccion='" . $id_seccion . "'
								 ORDER BY orden");
	while (!$rsEnlaces->eof) {
		$vEnlace = array("ID" => $rsEnlaces->field("id"), "TITULO" => $rsEnlaces->field("enlace_titulo"), "ENLACE" => $rsEnlaces->field("enlace"), "TARGET" => $rsEnlaces->field("enlace_target"));
		array_push($seccion[0]["ENLACES_RELACIONADOS"], $vEnlace);
		$rsEnlaces->movenext();
	}

	//Secciones relacionados.
	$rsContenido = $conn->execute("SELECT CC.id_seccion_rel, C.nombre_seccion
	   FROM secciones_secciones AS CC
	   INNER JOIN secciones AS C ON CC.id_seccion_rel = C.id
	   WHERE CC.id_seccion = '" . $id_seccion . "' AND C.activo='S'");

	while (!$rsContenido->eof) {
		$linkANotaRel = "/s" . $rsContenido->field("id_seccion_rel") . "/" . htmlentities_dir($rsContenido->field("nombre_seccion"));
		$vCont = array( "ID" => $rsContenido->field("id_seccion_rel"), "TITULO" => $rsContenido->field("nombre_seccion"), "LINK" => $linkANotaRel );
		array_push($seccion[0]["CONTENIDO_RELACIONADO"], $vCont);
		$rsContenido->movenext();
	}

	$seccion[0]["cuerpo"] = SetTagsFoto($seccion[0]['FOTOS_CONTENIDO'],$seccion[0]["cuerpo"]);
	$seccion[0]["cuerpo"] = SetTagsVideo($seccion[0]['VIDEOS_CONTENIDO'],$seccion[0]["cuerpo"]);
	$seccion[0]["cuerpo"] = reemplazarSubtitulos($seccion[0]["cuerpo"]);
	$seccion[0]["cuerpo"] = reemplazarFrasesWide($seccion[0]["cuerpo"]);
	$seccion[0]["cuerpo"] = reemplazarModulo($seccion[0]["cuerpo"],$seccion[0]["modulo_fondo"],$seccion[0]["modulo_icono"],$seccion[0]["modulo_titulo"],$seccion[0]["modulo_cuerpo"]);
	$seccion[0]["cuerpo"] = SetTagCodigoJS($seccion[0]["codigo_js"],$seccion[0]["cuerpo"]);
	$seccion[0]["cuerpo"] = SetTagCodigoJS2($seccion[0]["codigo_js2"],$seccion[0]["cuerpo"]);
	
	return $seccion;

}


function urlAmigable($string = '') {
    return preg_replace('/[^-a-z0-9]+/i', '-', strtolower(trim($string)));
}


function getMenu()
{
	global $conn;
	$menu = array();
	$submenu = array();

	$m = $conn->getRecordset("SELECT * FROM secciones_padre WHERE activo = 'S' ORDER BY menu_orden");
	
	for($i=0;$i<count($m);$i++)
	{

		$aux = $conn->getRecordset("SELECT id,nombre_seccion_menu,orden_seccion,link_menu FROM secciones WHERE activo = 'S' AND id_seccion_principal = '".$m[$i]["id"]."' AND id_seccion_padre = 0 ORDER BY orden_seccion");

		for($j=0;$j<count($aux);$j++)
		{
			$aux2 = $conn->getRecordset("SELECT id,nombre_seccion_menu,orden_seccion,link_menu FROM secciones WHERE activo = 'S' AND id_seccion_principal = '".$m[$i]["id"]."' AND id_seccion_padre = '".$aux[$j]["id"]."' ORDER BY orden_seccion ");

			array_push($submenu, array( "ID" => $aux[$j]["id"] , "TITULO" => $aux[$j]["nombre_seccion_menu"] , "LINK_MENU" => $aux[$j]["link_menu"] , "NIETOS" => $aux2 ) );
			$aux2 = array();
		}

		$aux = array();

		array_push($menu, array( "ID" => $m[$i]["id"] , "TITULO" => $m[$i]["titulo_menu"], "LINK_MENU" => $m[$i]["link_menu"] , "HIJOS" => $submenu ) );

		$submenu = array();
	
	}

	return $menu;
}

function getAnterior($orden)
{
	global $conn;

	$sql = "SELECT id, nombre_seccion_menu FROM secciones WHERE orden_seccion < ".$orden."  AND (link_menu = '' OR ISNULL(link_menu) ) ORDER BY orden_seccion DESC LIMIT 1";
	$anterior = $conn->getRecordset($sql);

	return $anterior;
}

function getSiguiente($orden)
{
	global $conn;

	$sql = "SELECT id, nombre_seccion_menu FROM secciones WHERE orden_seccion > ".$orden." AND (link_menu = '' OR ISNULL(link_menu) ) ORDER BY orden_seccion LIMIT 1";
	$siguiente = $conn->getRecordset($sql);

	return $siguiente;
}

function getSlider()
{
	global $conn;
	$rs = $conn->getRecordset("SELECT * FROM home WHERE tipo_modulo = 'especialesPortada' AND valor2_modulo <> '' ORDER BY orden");

	return $rs;
}

function getSextuple()
{
	global $conn;
	$rs = $conn->getRecordset("SELECT * FROM home WHERE tipo_modulo = 'especialesModulo'  ORDER BY orden");

	return $rs;
}

function getAccesos()
{
	global $conn;
	$rs = $conn->getRecordset("SELECT * FROM home WHERE tipo_modulo = 'especialesAcceso'  ORDER BY orden");

	return $rs;
}

function getOrganismos($current,$cant,$params)
{
	global $conn;

	if($params["tipo"] > 0)
		$where .= " AND T.id = '".$params["tipo"]."'";

	if($params["comuna"] > 0)
		$where .= " AND C.id = '".$params["comuna"]."'";

	if($params["orgpadre"] > 0)
		$where .= " AND O.id_padre = '".$params["orgpadre"]."'";

	$rs = $conn->getRecordset("
	SELECT O.* , T.tipo AS tipo_organismo, C.numero AS comuna_numero, C.descripcion AS comuna_nombre
	FROM organismos O 
	LEFT JOIN organismos_tipo T ON O.id_tipo_organismo = T.id
	LEFT JOIN comunas C ON O.id_comuna = C.id
	WHERE O.activo = 'S' ".$where."
	LIMIT " . $current . ",".$cant);

	return $rs;
}

function cantOrganismos($params)
{
	global $conn;

	if($params["tipo"] > 0)
		$where .= " AND T.id = '".$params["tipo"]."'";

	if($params["comuna"] > 0)
		$where .= " AND C.id = '".$params["comuna"]."'";

	if($params["orgpadre"] > 0)
		$where .= " AND O.id_padre = '".$params["orgpadre"]."'";

	$rs = $conn->getRecordset("
	SELECT COUNT(*) AS cant
	FROM organismos O 
	LEFT JOIN organismos_tipo T ON O.id_tipo_organismo = T.id
	LEFT JOIN comunas C ON O.id_comuna = C.id
	WHERE O.activo = 'S' ".$where."
	");

	return intval($rs[0]["cant"]);
}

function getOrganismoById($id)
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT O.* , T.tipo AS tipo_organismo, C.numero AS comuna_numero, C.descripcion AS comuna_nombre
	FROM organismos O 
	LEFT JOIN organismos_tipo T ON O.id_tipo_organismo = T.id
	LEFT JOIN comunas C ON O.id_comuna = C.id
	WHERE O.activo = 'S' AND O.id = '".$id."'");

	return $rs;
}


function getJuzgados($current,$cant,$params)
{
	global $conn;

	if($params["tipoj"] > 0)
		$where .= " AND T.id = '".$params["tipoj"]."'";

	if($params["fuero"] > 0)
		$where .= " AND F.id = '".$params["fuero"]."'";


	$rs = $conn->getRecordset("
	SELECT J.* , T.tipo AS tipo_juzgado, C.numero AS comuna_numero, C.descripcion AS comuna_nombre, F.fuero
	FROM juzgados J 
	LEFT JOIN juzgados_tipo T ON J.id_tipo_juzgado = T.id
	LEFT JOIN fueros F ON J.id_fuero = F.id
	LEFT JOIN comunas C ON J.id_comuna = C.id
	WHERE J.activo = 'S' ".$where."
	LIMIT " . $current . ",".$cant);

	return $rs;
}

function cantJuzgados($params)
{
	global $conn;

	if($params["tipoj"] > 0)
		$where .= " AND T.id = '".$params["tipoj"]."'";

	if($params["fuero"] > 0)
		$where .= " AND F.id = '".$params["fuero"]."'";

	$rs = $conn->getRecordset("
	SELECT COUNT(*) AS cant
	FROM juzgados J 
	LEFT JOIN juzgados_tipo T ON J.id_tipo_juzgado = T.id
	LEFT JOIN fueros F ON J.id_fuero = F.id
	LEFT JOIN comunas C ON J.id_comuna = C.id
	WHERE J.activo = 'S' ".$where."
	");

	return intval($rs[0]["cant"]);
}

function getJuzgadoById($id)
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT J.* , T.tipo AS tipo_juzgado, C.numero AS comuna_numero, C.descripcion AS comuna_nombre, F.fuero
	FROM juzgados J 
	LEFT JOIN juzgados_tipo T ON J.id_tipo_juzgado = T.id
	LEFT JOIN fueros F ON J.id_fuero = F.id
	LEFT JOIN comunas C ON J.id_comuna = C.id
	WHERE J.activo = 'S'  AND J.id = '".$id."'");

	return $rs;
}


function getOrganismoPadre($id)
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT O.*
	FROM organismos O 
	WHERE O.activo = 'S' AND O.id = '".$id."'");

	return $rs;
}

function getMarkers($tipo)
{
	global $conn;

	switch($tipo)
	{
		case "juzgados";
			$rs = $conn->getRecordset("
			SELECT J.id AS id, numero_juzgado AS nombre, tipo, latitud, longitud, mapa_zoom , '/juzgado/' AS link FROM juzgados J LEFT JOIN juzgados_tipo T ON J.id_tipo_juzgado = T.id WHERE J.activo = 'S' AND latitud <> '' ORDER BY latitud
			");
			break;
		case "poder":
			$rs = $conn->getRecordset("
			SELECT O.id AS id, nombre AS nombre, tipo, latitud, longitud, mapa_zoom, '/organismo/' AS link FROM organismos O LEFT JOIN organismos_tipo T ON O.id_tipo_organismo = T.id WHERE O.activo = 'S' AND latitud <> '' AND T.id = 1 	
			");
			break;
		case "gratuitos":
			$rs = $conn->getRecordset("
			SELECT O.id AS id, nombre AS nombre, tipo, latitud, longitud, mapa_zoom, '/organismo/' AS link FROM organismos O LEFT JOIN organismos_tipo T ON O.id_tipo_organismo = T.id WHERE O.activo = 'S' AND latitud <> '' AND T.id = 2	
			");
			break;
		default:
			$rs = $conn->getRecordset("
			SELECT O.id AS id, nombre AS nombre, tipo, latitud, longitud, mapa_zoom, '/organismo/' AS link FROM organismos O LEFT JOIN organismos_tipo T ON O.id_tipo_organismo = T.id WHERE O.activo = 'S' AND latitud <> '' 
			UNION
			SELECT J.id AS id, numero_juzgado AS nombre, tipo, latitud, longitud, mapa_zoom , '/juzgado/' AS link FROM juzgados J LEFT JOIN juzgados_tipo T ON J.id_tipo_juzgado = T.id WHERE J.activo = 'S' AND latitud <> '' 	
			");
			break;
	}

	return $rs;
}

function getFueros()
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT * FROM fueros WHERE activo = 'S' 	
	");

	return $rs;
}

function getTiposJuzgados()
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT * FROM juzgados_tipo WHERE activo = 'S' 	
	");

	return $rs;
}

function getTiposOrganismos()
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT * FROM organismos_tipo WHERE activo = 'S' 	
	");

	return $rs;
}

function getComunas()
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT * FROM comunas WHERE activo = 'S' 	
	");

	return $rs;
}

function getOrgPadres()
{
	global $conn;

	$rs = $conn->getRecordset("
	SELECT O.* FROM organismos O WHERE O.activo = 'S' AND O.id IN (SELECT DISTINCT id_padre FROM organismos O2 WHERE O2.activo = 'S') 
	");

	return $rs;
}

function buscar($str)
{
	global $conn;

	$campos = array("busqueda");
	$buf = explode(" ",$str);

	$len = 3;
	foreach($campos as $campo)
	{
		$cond .= " ( ";
		foreach($buf as $palabra)
		{
			$cond .= " ( $campo like '%$palabra%' ) and";
		}
		$cond = substr($cond,0,strlen($cond)-$len);
		$cond .=" ) or ";
	}
	$cond = substr($cond,0,strlen($cond)-$len);

	$aux = $conn->getRecordset("SELECT * FROM busqueda WHERE 1=1 AND ".$cond." ORDER BY orden");

	return $aux;
}

?>