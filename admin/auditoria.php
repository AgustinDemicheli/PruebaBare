<?
	include_once("../includes/DB_Conectar.php");
	include_once("../includes/lib/auth.php");

	
	/***OJO esta funcion en produccion no funciona
	$f=cal_days_in_month(CAL_GREGORIAN, $temp_month, $temp_year);
	**/
	
	
	// Obtengo los colores propios de cada Row
	$rs_color	= $conn->execute("select admin_auditoria_config.crear, admin_auditoria_config.modificar, admin_auditoria_config.eliminar, admin_auditoria_config.activar, admin_auditoria_config.aprobar from admin_auditoria_config limit 1");

	// Obtener la string y el color adecuado para la accion tomada sobre el registro.
	function getAction($action, $type) {
		global $rs_color;

		switch($type) {
			case 'string':
				switch($action) {
					case 2:
						return "Creacion";
					break;

					case 4:
						return "Modificacion";
					break;

					case 8:
						return "Eliminacion";
					break;

					case 16:
						return "Activacion";
					break;

					case 32:
						return "Aprobacion";
					break;
				}
			break;

			case 'color':
				switch($action) {
					case 2:
						return $rs_color->field('crear');
					break;

					case 4:
						return $rs_color->field('modificar');
					break;

					case 8:
						return $rs_color->field('eliminar');
					break;

					case 16:
						return $rs_color->field('activar');
					break;

					case 32:
						return $rs_color->field('aprobar');
					break;
				}
			break;
		}
	}

	// Paginado y recopilacion de registros
	if(isset($_GET['pagina'])) { $pagina = $_GET['pagina']; $limit = -15 + $_GET['pagina'] * 15; } else { $pagina = "1"; $limit = "0"; }

	if(isset($_GET['order'])) {
		foreach($_GET['order'] as $key => $value) {
			foreach($value as $subkey => $subvalue) {
				if(!isset($order_by)) {
					$order_by	= "admin_auditoria." . $subkey . " " . $subvalue;
				}else{
					$order_by	.= ", admin_auditoria." . $subkey . " " . $subvalue;
				}
			}
		}
	}else{
		$order_by	= "admin_auditoria.id desc";
	}

	if(isset($_GET['search'])) {
		###### Busqueda Avanzada ######
		$advsearch_user		= $_GET['advuser'];
		$advsearch_sector	= $_GET['advsector'];
		$advsearch_IP		= $_GET['advIP'];
		$advsearch_from		= $_GET['advfrom'];
		$advsearch_to		= $_GET['advto'];
		$advsearch_action	= $_GET['advaction'];
		#### Fin Busqueda Avanzada ####

		##### Defino condiciones #####
		if(count($advsearch_user) > 0) {
			for($index = 0; $index < count($advsearch_user); $index++) { 
				if($index == 0) { $search_user = " and (admin_auditoria.usuario_id = '" . $advsearch_user[$index] . "'"; }
				if($index != 0) { $search_user .= " or admin_auditoria.usuario_id = '" . $advsearch_user[$index] . "'"; }
				if($index == (count($advsearch_user) - 1)) { $search_user .= ")"; }
			}
		}else{
			$search_user	= "";
		}
		if($advsearch_sector != '0') { $search_sector = " and admin_auditoria.menu_id = '" . $advsearch_sector . "'"; }else{ $advsearch_sector = ""; }
		if($advsearch_IP != '') { $search_IP = " and admin_auditoria.IP = '" . $advsearc_IP . "'"; }else{ $advsearch_IP = ""; }
		if($advsearch_action != '0') { $search_action = " and admin_auditoria.accion = '" . $advsearch_action . "'"; }else{ $advsearch_action = ""; }
		### Fin Defino condiciones ###

		###### Paginado ######
		$recordset		= $conn->execute("select count(*) as total from admin_auditoria inner join admin_menu on(admin_auditoria.menu_id = admin_menu.id) inner join admin_usuarios on(admin_auditoria.usuario_id = admin_usuarios.id) where admin_auditoria.fecha >= '" . $advsearch_from . "' and admin_auditoria.fecha <= '" . $advsearch_to . "' " . $search_user . $search_sector . $search_IP . $search_action);
		$auditoria_total_pagina	= $recordset->field('total');
	
		if($auditoria_total_pagina > 0) { if(!is_int($auditoria_total_pagina / 15)) { $auditoria_total_pagina = intval($auditoria_total_pagina / 15 + 1); }else{ $auditoria_total_pagina = intval($auditoria_total_pagina / 15); } }else{ $auditoria_total_pagina = 1; }
		#### Fin Paginado ####

		###### Auditoria ######
		$recordset	= $conn->execute("select admin_menu.tabla_asociada as Seccion, admin_usuarios.usuario as Usuario, admin_auditoria.contenido_id as Contenido, admin_auditoria.ip as IP, admin_auditoria.fecha as Fecha, admin_auditoria.accion as Accion from admin_auditoria inner join admin_menu on(admin_auditoria.menu_id = admin_menu.id) inner join admin_usuarios on(admin_auditoria.usuario_id = admin_usuarios.id) where admin_auditoria.fecha >= '" . $advsearch_from . "' and admin_auditoria.fecha <= '" . $advsearch_to . "' " . $search_user . $search_sector . $search_IP . $search_action . " order by " . $order_by . " limit " . $limit . ", 15");
		#### Fin Auditoria ####
	}else{
		###### Paginado ######
		$recordset		= $conn->execute("select count(*) as total from admin_auditoria inner join admin_menu on(admin_auditoria.menu_id = admin_menu.id) inner join admin_usuarios on(admin_auditoria.usuario_id = admin_usuarios.id)");
		$auditoria_total_pagina	= $recordset->field('total');
	
		if($auditoria_total_pagina > 0) { if(!is_int($auditoria_total_pagina / 15)) { $auditoria_total_pagina = intval($auditoria_total_pagina / 15 + 1); }else{ $auditoria_total_pagina = intval($auditoria_total_pagina / 15); } }else{ $auditoria_total_pagina = 1; }
		#### Fin Paginado ####

		###### Auditoria ######
		$recordset	= $conn->execute("select admin_menu.tabla_asociada as Seccion, admin_usuarios.usuario as Usuario, admin_auditoria.contenido_id as Contenido, admin_auditoria.ip as IP, admin_auditoria.fecha as Fecha, admin_auditoria.accion as Accion from admin_auditoria inner join admin_menu on(admin_auditoria.menu_id = admin_menu.id) inner join admin_usuarios on(admin_auditoria.usuario_id = admin_usuarios.id) order by " .  $order_by . " limit " . $limit . ", 15");
		#### Fin Auditoria ####
	}
?>
<html>
	<head>
		<title>Auditoria de Registros</title>
		<link rel="stylesheet" href="css/stylo.css" type="text/css">
	</head>
	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body">
<?
    include_once("_barra.php");
?>	
	
	<div class="why" id="outerDiv">
	<br>
	<script language="javascript">
	function selectDate(type) {
		totaldays	= new Array('', '31', '29', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');

		datefrom	= document.getElementById('advfrom');
		dateto		= document.getElementById('advto');

		yearfrom	= document.getElementById('advyearfrom');
		yearto		= document.getElementById('advyearto');

		monthfrom	= document.getElementById('advmonthfrom');
		dayfrom		= document.getElementById('advdayfrom');

		monthto		= document.getElementById('advmonthto');
		dayto		= document.getElementById('advdayto');

		switch(type) {
			case 'yearfrom':
				datefrom.value	= yearfrom.value + '/' + monthfrom.value + '/' + dayfrom.value;
				dateto.value	= yearto.value + '/' + monthto.value + '/' + dayto.value;
			break;

			case 'yearto':
				datefrom.value	= yearfrom.value + '/' + monthfrom.value + '/' + dayfrom.value;
				dateto.value	= yearto.value + '/' + monthto.value + '/' + dayto.value;
			break;

			case 'monthfrom':
				dayfrom.length	= 0;

				for(index = 1; index <= totaldays[monthfrom.value]; index++) { dayfrom.options[index-1] = new Option(index, index); }

				datefrom.value	= yearfrom.value + '/' + monthfrom.value + '/' + dayfrom.value;
				dateto.value	= yearto.value + '/' + monthto.value + '/' + dayto.value;
			break;

			case 'monthto':
				dayto.length	= 0;

				for(index = 1; index <= totaldays[monthto.value]; index++) { dayto.options[index-1] = new Option(index, index); }

				datefrom.value	= yearfrom.value + '/' + monthfrom.value + '/' + dayfrom.value;
				dateto.value	= yearto.value + '/' + monthto.value + '/' + dayto.value;
			break;

			case 'dayfrom':
				datefrom.value	= yearfrom.value + '/' + monthfrom.value + '/' + dayfrom.value;
				dateto.value	= yearto.value + '/' + monthto.value + '/' + dayto.value;
			break;

			case 'dayto':
				datefrom.value	= yearfrom.value + '/' + monthfrom.value + '/' + dayfrom.value;
				dateto.value	= yearto.value + '/' + monthto.value + '/' + dayto.value;
			break;
		}
	}
	</script>
	<form id="searchform" name="searchform" action="auditoria.php" method="get">
		<input name="search" type="hidden" value="search">
		<table width="730" align="center" cellpadding="0" cellspacing="0" border="1" class="tablaGrande">
			<tr class="Title">
				<td align="center">Busqueda de registros</td>
			</tr>
			<tr>
				<td>
					<table width="100%" cellpadding="0" border="1">
						<tr>
							<td width="33%" height="118" align="center" class="tituloOferta">Usuarios<br>
								<select id="advuser" name="advuser[]" class="comun" style="width: 150px; height: 75px;" multiple>
<?
								$rs_usuario = $conn->execute("select admin_usuarios.id, admin_usuarios.usuario from admin_usuarios order by admin_usuarios.usuario");

								while(!$rs_usuario->eof) {
									$usuario_id	= $rs_usuario->field('id');
									$usuario_nombre	= $rs_usuario->field('usuario');

									if(isset($advsearch_user) and array_keys($advsearch_user, $usuario_id)) {
										print("<option value=\"" . $usuario_id . "\" selected>" . $usuario_nombre . "</option>");
									}else{
										print("<option value=\"" . $usuario_id . "\">" . $usuario_nombre . "</option>");
									}

									$rs_usuario->movenext();
								}
?>
								</select>
							</td>
							<td width="33%" align="center" class="tituloOferta">Fecha desde<br>
						    		<select class="comun" id="advdayfrom" onChange="selectDate('dayfrom');">
<?
									if(isset($advsearch_from)) {
										$temp_date	= split('/', $advsearch_from);

										$temp_day	= $temp_date[2];
										$temp_month	= $temp_date[1];
										$temp_year	= $temp_date[0];

										$temp_totaldays	= cal_days_in_month(CAL_GREGORIAN, $temp_month, $temp_year);
									}else{
										$temp_day	= date('j', (mktime() - 604800));
										$temp_month	= date('n', (mktime() - 604800));
										$temp_year	= date('Y', (mktime() - 604800));

										$temp_totaldays	= cal_days_in_month(CAL_GREGORIAN, $temp_month, $temp_year);
									}

									for($index = 1; $index <= $temp_totaldays; $index++) {
										if($index == $temp_day) {
											print("<option value=\"" . $index . "\" selected>" . $index . "</option>");
										}else{
											print("<option value=\"" . $index . "\">" . $index . "</option>");
										}
									}
?>
								</select>
								<select class="comun" id="advmonthfrom" onChange="selectDate('monthfrom');">
									<option value="1" <?=($temp_month == 1)? 'selected':'';?>>Enero</option>
									<option value="2" <?=($temp_month == 2)? 'selected':'';?>>Febrero</option>
									<option value="3" <?=($temp_month == 3)? 'selected':'';?>>Marzo</option>
									<option value="4" <?=($temp_month == 4)? 'selected':'';?>>Abril</option>
									<option value="5" <?=($temp_month == 5)? 'selected':'';?>>Mayo</option>
									<option value="6" <?=($temp_month == 6)? 'selected':'';?>>Junio</option>
									<option value="7" <?=($temp_month == 7)? 'selected':'';?>>Julio</option>
									<option value="8" <?=($temp_month == 8)? 'selected':'';?>>Agosto</option>
									<option value="9" <?=($temp_month == 9)? 'selected':'';?>>Septiembre</option>
									<option value="10" <?=($temp_month == 10)? 'selected':'';?>>Octubre</option>
									<option value="11" <?=($temp_month == 11)? 'selected':'';?>>Noviembre</option>
									<option value="12" <?=($temp_month == 12)? 'selected':'';?>>Diciembre</option>
								</select>
								<select class="comun" id="advyearfrom" onChange="selectDate('yearfrom');">
<?
									$rs_year	= $conn->execute("SELECT SUBSTRING_INDEX(min(admin_auditoria.fecha), '-', 1) as minyear, SUBSTRING_INDEX(max(admin_auditoria.fecha), '-', 1) as maxyear from admin_auditoria");

									$min_year	= $rs_year->field('minyear');
									$max_year	= $rs_year->field('maxyear');

									for($year = $max_year; $year >= $min_year; $year--) {
										if($year == $temp_year) {
											print("<option value=\"" . $year . "\" selected>" . $year. "</option>");
										}else{
											print("<option value=\"" . $year . "\">" . $year . "</option>");
										}
									}

?>
								</select>
							        <input type="hidden" id="advfrom" name="advfrom" value="<?=($advsearch_from)? $advsearch_from : date('Y\/n\/j', (mktime() - 604800));?>">
								<br><br>Fecha hasta<br> 
								<select class="comun" id="advdayto" onChange="selectDate('dayto');">
<?
									if(isset($advsearch_to)) {
										$temp_date	= split('/', $advsearch_to);

										$temp_day	= $temp_date[2];
										$temp_month	= $temp_date[1];
										$temp_year	= $temp_date[0];

										$temp_totaldays	= cal_days_in_month(CAL_GREGORIAN, $temp_month, $temp_year);
									}else{
										$temp_day	= date('j', mktime());
										$temp_month	= date('n', mktime());
										$temp_year	= date('Y', mktime());

										$temp_totaldays	= cal_days_in_month(CAL_GREGORIAN, $temp_month, $temp_year);
									}

									for($index = 1; $index <= $temp_totaldays; $index++) {
										if($index == $temp_day) {
											print("<option value=\"" . $index . "\" selected>" . $index . "</option>");
										}else{
											print("<option value=\"" . $index . "\">" . $index . "</option>");
										}
									}
?>
								</select>
								<select class="comun" id="advmonthto" onChange="selectDate('monthto');">
									<option value="1" <?=($temp_month == 1)? 'selected':'';?>>Enero</option>
									<option value="2" <?=($temp_month == 2)? 'selected':'';?>>Febrero</option>
									<option value="3" <?=($temp_month == 3)? 'selected':'';?>>Marzo</option>
									<option value="4" <?=($temp_month == 4)? 'selected':'';?>>Abril</option>
									<option value="5" <?=($temp_month == 5)? 'selected':'';?>>Mayo</option>
									<option value="6" <?=($temp_month == 6)? 'selected':'';?>>Junio</option>
									<option value="7" <?=($temp_month == 7)? 'selected':'';?>>Julio</option>
									<option value="8" <?=($temp_month == 8)? 'selected':'';?>>Agosto</option>
									<option value="9" <?=($temp_month == 9)? 'selected':'';?>>Septiembre</option>
									<option value="10" <?=($temp_month == 10)? 'selected':'';?>>Octubre</option>
									<option value="11" <?=($temp_month == 11)? 'selected':'';?>>Noviembre</option>
									<option value="12" <?=($temp_month == 12)? 'selected':'';?>>Diciembre</option>
								</select>
								<select class="comun" id="advyearto" onChange="selectDate('yearto');">
<?
									$rs_year	= $conn->execute("SELECT SUBSTRING_INDEX(min(admin_auditoria.fecha), '-', 1) as minyear, SUBSTRING_INDEX(max(admin_auditoria.fecha), '-', 1) as maxyear from admin_auditoria");

									$min_year	= $rs_year->field('minyear');
									$max_year	= $rs_year->field('maxyear');

									for($year = $max_year; $year >= $min_year; $year--) {
										if($year == $temp_year) {
											print("<option value=\"" . $year . "\" selected>" . $year. "</option>");
										}else{
											print("<option value=\"" . $year . "\">" . $year . "</option>");
										}
									}

?>
								</select>
								<input type="hidden" id="advto" name="advto" value="<?=($advsearch_to)? $advsearch_to : date('Y\/n\/j');?>">
							</td>
							<td width="33%" align="center" class="tituloOferta">Seccion<br>
						  		<select id="advsector" name="advsector" class="comun">
				 					<option value="0">Todas</option>
<?
									$rs_sector	= $conn->execute("select distinct admin_auditoria.menu_id as id, admin_menu.tabla_asociada as seccion from admin_auditoria inner join admin_menu on(admin_auditoria.menu_id = admin_menu.id)");

									while(!$rs_sector->eof) {
										$seccion_id	= $rs_sector->field('id');
										$seccion_nombre	= $rs_sector->field('seccion');

										if(isset($advsearch_sector) && ($advsearch_sector == $seccion_id)) {
											print("<option value=\"" . $seccion_id . "\" selected>" . $seccion_nombre . "</option>");
										}else{
											print("<option value=\"" . $seccion_id . "\">" . $seccion_nombre . "</option>");
										}

										$rs_sector->movenext();
									}
?>
								</select>
								<br><br>
							    Accion<br>
								<select id="advaction" name="advaction" class="comun">
									<option value="0" <?=(isset($advsearch_action) && ($advsearch_action == '0'))? 'selected':'';?>>Todas</option>
									<option value="2" <?=(isset($advsearch_action) && ($advsearch_action == '2'))? 'selected':'';?>>Creacion</option>
									<option value="4" <?=(isset($advsearch_action) && ($advsearch_action == '4'))? 'selected':'';?>>Modificacion</option>
									<option value="8" <?=(isset($advsearch_action) && ($advsearch_action == '8'))? 'selected':'';?>>Eliminacion</option>
									<option value="16" <?=(isset($advsearch_action) && ($advsearch_action == '16'))? 'selected':'';?>>Activacion</option>
									<option value="32" <?=(isset($advsearch_action) && ($advsearch_action == '32'))? 'selected':'';?>>Aprobacion</option>
								</select>
							</td>
						</tr>
  						<tr>
							<td height="48" colspan="3" align="center" class="tituloOferta"><input type="submit" class="boton" value="Buscar" /></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
	<br>
	<table width="730" align="center" cellpadding="0" cellspacing="0" border="1" class="tablaGrande">
		<tr class="Title">
<?
			// Ordenamiento por Usuario
			if(isset($_GET['order'][0])) {
				$order_usuario		= eregi_replace('(&){0,1}(order\[0\]\[usuario_id\]=)(asc|desc){1}', '', $_SERVER['QUERY_STRING']);

				if($_GET['order'][0][usuario_id] == 'asc') {
					$order_usuario		.= "&order[0][usuario_id]=desc";
					$order_usuario_img	= "&nbsp;<img src=\"images/flecha_up.gif\">";
				}
				if($_GET['order'][0][usuario_id] == 'desc') {
					$order_usuario		.= "";
					$order_usuario_img	= "&nbsp;<img src=\"images/flecha_down.gif\">";
				}

				
			}else{
				$order_usuario		= eregi_replace('(&){0,1}(order\[0\]\[usuario_id\]=)(desc|asc){1}', '', $_SERVER['QUERY_STRING']) . "&order[0][usuario_id]=asc";
				$order_usuario_img	= "";
			}

			// Ordenamiento por Seccion
			if(isset($_GET['order'][1])) {
				$order_seccion	= eregi_replace('(&){0,1}(order\[1\]\[menu_id\]=)(asc|desc){1}', '', $_SERVER['QUERY_STRING']);

				if($_GET['order'][1][menu_id] == 'asc') {
					$order_seccion		.= "&order[1][menu_id]=desc";
					$order_seccion_img	= "&nbsp;<img src=\"images/flecha_up.gif\">";
				}
				if($_GET['order'][1][menu_id] == 'desc') {
					$order_seccion		.= "";
					$order_seccion_img	= "&nbsp;<img src=\"images/flecha_down.gif\">";
				}
			}else{
				$order_seccion		= eregi_replace('(&){0,1}(order\[1\]\[menu_id\]=)(desc|asc){1}', '', $_SERVER['QUERY_STRING']) . "&order[1][menu_id]=asc";
				$order_seccion_img	= "";
			}

			// Ordenamiento por Contenido
			if(isset($_GET['order'][2])) {
				$order_contenido = eregi_replace('(&){0,1}(order\[2\]\[contenido_id\]=)(asc|desc){1}', '', $_SERVER['QUERY_STRING']);

				if($_GET['order'][2][contenido_id] == 'asc') {
					$order_contenido	.= "&order[2][contenido_id]=desc";
					$order_contenido_img	= "&nbsp;<img src=\"images/flecha_up.gif\">";
				}
				if($_GET['order'][2][contenido_id] == 'desc') {
					$order_contenido	.= "";
					$order_contenido_img	= "&nbsp;<img src=\"images/flecha_down.gif\">";
				}
			}else{
				$order_contenido	= eregi_replace('(&){0,1}(order\[2\]\[contenido_id\]=)(desc|asc){1}', '', $_SERVER['QUERY_STRING']) . "&order[2][contenido_id]=asc";
				$order_contenido_img	= "";
			}

			// Ordenamiento por IP
			if(isset($_GET['order'][3])) {
				$order_ip = eregi_replace('(&){0,1}(order\[3\]\[ip\]=)(asc|desc){1}', '', $_SERVER['QUERY_STRING']);

				if($_GET['order'][3][ip] == 'asc') {
					$order_ip	.= "&order[3][ip]=desc";
					$order_ip_img	= "&nbsp;<img src=\"images/flecha_up.gif\">";
				}
				if($_GET['order'][3][ip] == 'desc') {
					$order_ip	.= "";
					$order_ip_img	= "&nbsp;<img src=\"images/flecha_down.gif\">";
				}
			}else{
				$order_ip	= eregi_replace('(&){0,1}(order\[3\]\[ip\]=)(desc|asc){1}', '', $_SERVER['QUERY_STRING']) . "&order[3][ip]=asc";
				$order_ip_img	= "";
			}

			// Ordenamiento por Fecha
			if(isset($_GET['order'][4])) {
				$order_fecha = eregi_replace('(&){0,1}(order\[4\]\[fecha\]=)(asc|desc){1}', '', $_SERVER['QUERY_STRING']);

				if($_GET['order'][4][fecha] == 'asc') {
					$order_fecha		.= "&order[4][fecha]=desc";
					$order_fecha_img	= "&nbsp;<img src=\"images/flecha_up.gif\">";
				}
				if($_GET['order'][4][fecha] == 'desc') {
					$order_fecha		.= "";
					$order_fecha_img	= "&nbsp;<img src=\"images/flecha_down.gif\">";
				}
			}else{
				$order_fecha		= eregi_replace('(&){0,1}(order\[4\]\[fecha\]=)(desc|asc){1}', '', $_SERVER['QUERY_STRING']) . "&order[4][fecha]=asc";
				$order_fecha_img	= "";
			}

			// Ordenamiento por Accion
			if(isset($_GET['order'][5])) {
				$order_accion = eregi_replace('(&){0,1}(order\[5\]\[accion\]=)(asc|desc){1}', '', $_SERVER['QUERY_STRING']);

				if($_GET['order'][5][accion] == 'asc') {
					$order_accion		.= "&order[5][accion]=desc";
					$order_accion_img	= "&nbsp;<img src=\"images/flecha_up.gif\">";
				}
				if($_GET['order'][5][accion] == 'desc') {
					$order_accion		.= "";
					$order_accion_img	= "&nbsp;<img src=\"images/flecha_down.gif\">";
				}
			}else{
				$order_accion		= eregi_replace('(&){0,1}(order\[5\]\[accion\]=)(desc|asc){1}', '', $_SERVER['QUERY_STRING']) . "&order[5][accion]=asc";
				$order_accion_img	= "";
			}
?>
			<td width="110" align="center"><a href="auditoria.php?<?=$order_usuario;?>" style="color: white;"><strong>Usuario</strong></a><?=$order_usuario_img;?></td>
			<td width="110" align="center"><a href="auditoria.php?<?=$order_seccion;?>" style="color: white;"><strong>Seccion</strong></a><?=$order_seccion_img;?></td>
			<td width="100" align="center"><a href="auditoria.php?<?=$order_contenido;?>" style="color: white;"><strong>Contenido</strong></a><?=$order_contenido_img;?></td>
			<td width="105" align="center"><a href="auditoria.php?<?=$order_ip;?>" style="color: white;"><strong>IP</strong></a><?=$order_ip_img;?></td>
			<td width="200" align="center"><a href="auditoria.php?<?=$order_fecha;?>" style="color: white;"><strong>Fecha</strong></a><?=$order_fecha_img;?></td>
			<td width="105" align="center"><a href="auditoria.php?<?=$order_accion;?>" style="color: white;"><strong>Accion</strong></a><?=$order_accion_img;?></td>
		</tr>
<?
		if($recordset->eof) {
?>
		<tr>
			<td align="center" colspan="6"><b>No se han encontrado resultados.</b></td>
		</tr>
<?
		}

		while(!$recordset->eof) {
			$auditoria_seccion	= $recordset->field('Seccion');
			$auditoria_usuario	= $recordset->field('Usuario');
			$auditoria_contenido	= $recordset->field('Contenido');
			$auditoria_ip		= $recordset->field('IP');
			$auditoria_fecha	= $recordset->field('Fecha');
			$auditoria_accion	= $recordset->field('Accion');
?>
		<tr bgcolor="<?=getAction($auditoria_accion, 'color');?>">
			<td align="center"><?=$auditoria_usuario;?></td>
			<td align="center"><?=$auditoria_seccion;?></td>
			<td align="center"><?=$auditoria_contenido;?></td>
			<td align="center"><?=$auditoria_ip;?></td>
			<td align="center"><?=$auditoria_fecha;?></td>
			<td align="center"><?=getAction($auditoria_accion, 'string');?></td>
		</tr>
<?
		$recordset->movenext();
	}
?>
	</table>
	<table width="730" align="center" cellpadding="3" cellspacing="0">
		<tr>
			<td align="center">
<?
			// Definimos pagina anterior
			if($pagina > 1) {
				if(isset($_SERVER['QUERY_STRING'])) {
					$paginado .= "<a href=\"auditoria.php?pagina=" . ($pagina - 1) . "&" . eregi_replace('(pagina=' . $pagina . ')(&){0,1}', '', $_SERVER['QUERY_STRING']) . "\" style=\"text-decoration:none;\">«</a>";
				}else{
					$paginado .= "<a href=\"auditoria.php?pagina=" . ($pagina - 1) . "\" style=\"text-decoration:none;\">«</a>";
				}
			}else{
				$paginado .= "«";
			}

			// Definimos paginas
			for($index = ($pagina - 5); ($index <= ($pagina - 1)); $index++) {
				if($index > 0) {
					$paginado .= "<font size=\"2\" face=\"arial\">&nbsp;<a href=\"auditoria.php?pagina=" . $index . "&" . eregi_replace('(pagina=' . $pagina . ')(&){0,1}', '', $_SERVER['QUERY_STRING']) . "\" style=\"text-decoration:none;\">" . $index . "</a>&nbsp;</font>";

					if(($index >= ($pagina - 5)) && ($index >= 1)) { $paginado .= "•"; }
				}
			}

			$paginado	.= "<font size=\"2\" face=\"arial\">&nbsp;<b>" . $pagina . "</b>&nbsp;</font>";

			for($index = ($pagina + 1); (($index <= ($pagina + 5)) && ($index <= $auditoria_total_pagina)); $index++) {
				if($index <= ($pagina + 5) && ($index <= $auditoria_total_pagina)) { $paginado .= "•"; }

				$paginado .= "<font size=\"2\" face=\"arial\">&nbsp;<a href=\"auditoria.php?pagina=" . $index . "&" . eregi_replace('(pagina=' . $pagina . ')(&){0,1}', '', $_SERVER['QUERY_STRING']) . "\" style=\"text-decoration:none;\">" . $index . "</a>&nbsp;</font>";
			}

			// Definimos pagina posterior
			if($pagina < $auditoria_total_pagina) {
				if(isset($_SERVER['QUERY_STRING'])) {
					$paginado .= "<a href=\"auditoria.php?pagina=" . ($pagina + 1) . "&" . eregi_replace('(pagina=' . $pagina . ')(&){0,1}', '', $_SERVER['QUERY_STRING']) . "\" style=\"text-decoration:none;\">»</a>";
				}else{
					$paginado .= "<a href=\"auditoria.php?pagina=" . ($pagina + 1) . "\" style=\"text-decoration:none;\">«</a>";
				}
			}else{
				$paginado .= "»";
			}

			print($paginado);
?>
				<script language="javascript">
				function checkPage(object) {
					regExp	= /^[0-9]+$/;

					if(!regExp.test(object.value) || (object.value == 0) || (object.value > <?=$auditoria_total_pagina;?>)) {
						alert("Debe ingresar un numero de pagina valido");
						object.value	= '1';

						return false;
					}else{
						document.location = "auditoria.php?pagina=" + object.value + "&<?=eregi_replace('(pagina=' . $pagina . ')(&){0,1}', '', $_SERVER['QUERY_STRING']);?>";
					}
				}
				</script>
				<br>
				<input id="page" type="text" class="comun" style="width: 30px; text-align: center;" value="1" maxlength="3">
				<input type="button" onclick="return checkPage(document.getElementById('page'));" value="Ir" class="boton">
				<br>
				<font size="2" face "arial"><b>(Total paginas : <?=$auditoria_total_pagina;?>)</b></font>
			</td>
		</tr>
	</table>
	</div>
</html>