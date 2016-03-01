<? 
			include_once("../includes/DB_Conectar.php"); 
			include_once("../includes/lib/auth.php");
			
			if(is_array($_POST)&&count($_POST)>1&&isset($_POST["save_type"])) 
			{ 

				if(isset($_POST["id"])&&is_numeric($_POST["id"])) 
				{
					// Checkeo si hubo un cambio de Activo o Estado. ( Si el usuario no puede cambiar el Activo o Estado este se vuelve 
					if($usr->_menuRights != '-1' and isset($_POST["columnRights"])) {
						foreach($_POST["columnRights"] as $key => $value) {
							switch($key) {
								case 16:
									if($usr->_menuRights[4] == 1) {
									}else{
										$_POST[$value]	= 'N';
									}
								break;

								case 32:
									if($usr->_menuRights[5] == 1) {
									}else{
										$_POST[$value]	= 'P';
									}
								break;
							}
						}
					}

					$sql = "update secciones set orden_seccion = '".$_POST['orden_seccion']."',id_seccion_principal = '".$_POST['id_seccion_principal']."',id_seccion_padre = '".$_POST['id_seccion_padre']."',nombre_seccion = '".$_POST['nombre_seccion']."',nombre_seccion_menu = '".$_POST['nombre_seccion_menu']."',volanta = '".$_POST['volanta']."',subtitulo = '".$_POST['subtitulo']."',copete = '".$_POST['copete']."',cuerpo = '".$_POST['cuerpo']."',modulo_fondo = '".$_POST['modulo_fondo']."',modulo_icono = '".$_POST['modulo_icono']."',modulo_titulo = '".$_POST['modulo_titulo']."',modulo_cuerpo = '".$_POST['modulo_cuerpo']."',codigo_js = '".$_POST['codigo_js']."',codigo_js2 = '".$_POST['codigo_js2']."',activo = '".$_POST['activo']."',link_menu = '".$_POST['link_menu']."' where id = ".$_POST["id"];
					$conn->execute($sql); 
					$id = $_POST["id"];

					// Insert en tabla de auditoria
					$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '4')");
				} 
				else 
				{ 
					$sql = "insert into secciones (orden_seccion,id_seccion_principal,id_seccion_padre,nombre_seccion,nombre_seccion_menu,volanta,subtitulo,copete,cuerpo,modulo_fondo,modulo_icono,modulo_titulo,modulo_cuerpo,codigo_js,codigo_js2,activo,link_menu) values ('".$_POST['orden_seccion']."','".$_POST['id_seccion_principal']."','".$_POST['id_seccion_padre']."','".$_POST['nombre_seccion']."','".$_POST['nombre_seccion_menu']."','".$_POST['volanta']."','".$_POST['subtitulo']."','".$_POST['copete']."','".$_POST['cuerpo']."','".$_POST['modulo_fondo']."','".$_POST['modulo_icono']."','".$_POST['modulo_titulo']."','".$_POST['modulo_cuerpo']."','".$_POST['codigo_js']."','".$_POST['codigo_js2']."','".$_POST['activo']."','".$_POST['link_menu']."')"; 
					$conn->execute($sql); 
					$rs = $conn->execute("select last_insert_id()"); 
					$id = $rs->field(0);

					// Insert en tabla de auditoria
					$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '2')");
				}
				
// Comienzo de query por elemento de relacion
if(is_array($_POST) && count($_POST) > 1 &&isset($_POST["save_type"]) ) {
	 $conn->execute( "delete from secciones_secciones  where id_seccion = '" . $id . "'  and tipo = ''"); 
	
	if(count($_POST['contenidosrelacionados_dst_select']) > 0) { 
		for($index = 0; $index < count($_POST['contenidosrelacionados_dst_select']); $index++) 
		{ 
			$conn->execute("insert into secciones_secciones (id_seccion, id_seccion_rel, orden,tipo) values('" . $id . "', '" . $_POST['contenidosrelacionados_dst_select'][$index] . "','".$index."','')");
		} 
	} 
}
// Fin de query por elemento de relacion

// Comienzo de query por elemento de relacion
if(is_array($_POST) && count($_POST) > 1 && isset($_POST["save_type"]) )
{
	$conn->execute("delete from secciones_enlaces where id_seccion = '" . $id . "'"); 

	if(count($_POST['enlaces_dst_select']) > 0) {
		for($index = 0; $index < count($_POST['enlaces_dst_select']); $index++)
		{ 
			list($enlace_titulo , $enlace , $enlace_target) = explode('||' , $_POST['enlaces_dst_select'][$index]);
			
			$conn->execute("insert into secciones_enlaces (id_seccion, enlace ,  enlace_titulo,  enlace_target, orden) values('" . $id . "', '" . $enlace . "', '" . $enlace_titulo . "', '" . $enlace_target . "',".$index.")"); 
		} 
	} 
}
// Fin de query por elemento de relacion

// Comienzo de query por elemento de relacion
if(is_array($_POST) && count($_POST) > 1 &&isset($_POST["save_type"]) )
{ 
	$conn->execute("delete from secciones_fotos where id_seccion = '" . $id . "'");
								
	if(count($_POST['fotos_dst_select']) > 0) { 
		for($index = 0; $index < count($_POST['fotos_dst_select']); $index++) 
		{ 
			$conn->execute("insert into secciones_fotos (id_seccion, id_foto, orden, epigrafe) values('" . $id . "', '" .$_POST['fotos_dst_select'][$index] . "', '".$index."','".$_POST['ep_fotos_dst_select_'.$_POST['fotos_dst_select'][$index]]."')");
		} 
	} 
}
// Fin de query por elemento de relacion

    // VIDEOS

    if (is_array($_POST) && count($_POST) > 1 && isset($_POST["save_type"])) {
        $conn->execute("delete from secciones_videos where id_seccion = '" . $id . "'");

        if (count($_POST['videos_dst_select']) > 0) {
            for ($index = 0; $index < count($_POST['videos_dst_select']); $index++) {
                $datos_video = explode('||', $_POST['videos_dst_select'][$index]);
                if (count($datos_video) > 1 || $datos_video[2] == "PROPIO") {
                    list($name_video, $url_video, $medio_video) = $datos_video;
                } else {
                    $sql = "SELECT advTitulo from advf where advID = " . intval($_POST['videos_dst_select'][$index]);
                    $rds = $conn->getRecordset($sql);
                    $name_video = $rds[0]["advTitulo"];
                    $url_video = '';
                    $id_video = intval($_POST['videos_dst_select'][$index]);
                    $medio_video = "PROPIO";
                }
                $insert_sql = "insert into secciones_videos 
					SET id_seccion = '" . $id . "',
						id_video = '" . $id_video . "',
						video_codigo = '" . $url_video . "',  
						video_descripcion = '" . $name_video . "', 
						video_medio = '" . $medio_video . "', 
						orden = " . $index;

                $conn->execute($insert_sql);
            }
        }
    }

// Comienzo de query por elemento de relacion
if(is_array($_POST) && count($_POST) > 1 &&isset($_POST["save_type"])) { 
                                    $conn->execute("delete from secciones_archivos where id_seccion = '" . $id . "'"); 
                                    if(count($_POST['archivos_dst_select']) > 0) { 
                                        for($index = 0; $index < count($_POST['archivos_dst_select']); 
                                            $index++) { 
                                                $conn->execute("insert into secciones_archivos 
                                                    (id_seccion, 
                                                    id_archivo, 
                                                        orden,
                                                        epigrafe) values(
                                                        '" . $id . "', 
                                                        '" . $_POST['archivos_dst_select'][$index] . "',
                                                        '".$index."',
                                                        '".$_POST['ep_archivos_dst_select_'.$_POST['archivos_dst_select'][$index]]."')"); } } }
// Fin de query por elemento de relacion

				if($_POST["save_type"]=="1")
				{
					header("Location: secciones.php?p=".$_REQUEST['p']."&orden=".$_REQUEST['orden']."&filtros=".$_REQUEST['filtros']."&filtros_p=".$_REQUEST['filtros_p']);
					die();
				}
				else
				{
					header("Location: ".$_SERVER["PHP_SELF"]."?id=".$id."&p=".$_REQUEST['p']."&orden=".$_REQUEST['orden']."&filtros=".$_REQUEST['filtros']."&filtros_p=".$_REQUEST['filtros_p']); 
					die(); 
				}
			} 


			
			if(isset($_GET["id"])) 
			{ 
				$sql = "select * from secciones where id = ".$_GET["id"]; 
				$rs = $conn->execute($sql); 
				foreach($rs->recordset() as $key => $value) 
				{ 
					$$key = $value; 
				} 
			} 


			?><html>
    <head>
        <title><?=$TITULO_SITE?> - Secciones</title>
        <link rel="stylesheet" href="css/stylo.css" type="text/css">
        <script type='text/javascript'>
            function Chk() {
                
                return true;
            }
            function set_type(valor) {
                document.getElementById("save_type").value = valor;
            }
        </script>
        <script language='Javascript' src='../includes/validar_datos.js'></script>
		<script language='Javascript' src='../includes/lib/jQuery/jquery.js'></script>
		<script language='Javascript' src='contenidos_edit.js'></script>
		<script type='text/javascript' src='../includes/lib/DOMWindow/jquery.DOMWindow.js'></script>
		<?include_once("ckeditor/ckeditor.php");?>
				
    </head>
    <body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll='no'>
        <?include_once("_barra.php");?>
        <div class="why" id="outerDiv">  <br>
            <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Secciones</td>
                </tr>
            </table>
            <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
                <!--
                <tr valign="top">
                    <td height="15" width="610" align="center" class="arial11" valign="middle">  </td>
                </tr>
                -->
            </table>
            <form method="post" name="formedit" action='<?=$_SERVER["PHP_SELF"];?>?menu=<?=$usr->_menu;?>' onsubmit='return Chk();'>
                <input type="hidden" name="save_type" id="save_type" value="0">
                <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
                    <tr>
                        <td width="5" align="left" valign="top"><img src="images/corner_si2.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td width="5" align="right" valign="top"><img src="images/corner_sd2.gif" width="5" height="5"></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                        <td align="center" bgcolor="#e5e5e5"><table width='50%' border="0" cellpadding="0" cellspacing="5">
                                <tr>
                                    <td align="left"><?=($usr->checkUserRights(2) != 'disabled')? '<img onclick=\'javascript:document.location.href="' . $_SERVER["PHP_SELF"] . '";\' src="images/btn_nuevo.gif" style="cursor:pointer;" border="0">':'';?>
                                    </td>
                                    <td align='center' style="padding-right:15px;"><img onclick='document.location = "secciones.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">
                                    </td>
                                    <td align='center' style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" onclick="javascript:set_type(0);" src="images/btn_guardar.gif">'); ?></td>
                                    <td align='center'><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" src="images/btn_guardaryvolver.gif" onclick="javascript:set_type(1);" value=\'Guardar y volver al listado\'>'); ?></td>
                                </tr>
                            </table></td>
                        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
                    </tr>
                </table>
                <br>
                <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
                    <tr>
                        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#ffffff"></td>
                        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#ffffff">&nbsp;</td>
                        <td align="center" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse;">
                                
                                <input id='id' name='id' type='hidden' value='<?=(isset($id)?$id:"")?>'>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Seccion Principal&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><select id='id_seccion_principal' name='id_seccion_principal' class='comun' <? print($usr->checkUserRights(-1)); ?>><? $sql = "select id, titulo FROM secciones_padre WHERE activo='S' order by menu_orden"; $rs = $conn->execute($sql); while(!$rs->eof) { echo "<option value='".$rs->field(0)."' ".($id_seccion_principal==$rs->field(0)?"selected":"").">".$rs->field(1)."</option>"; $rs->movenext();} ?></select></td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Seccion Padre&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><select id='id_seccion_padre' name='id_seccion_padre' class='comun' <? print($usr->checkUserRights(-1)); ?>><? $sql = "select 0 as id, '-----------' as nombre_seccion union select id, nombre_seccion FROM secciones WHERE activo='S' AND id_seccion_padre = 0 ORDER BY nombre_seccion"; $rs = $conn->execute($sql); while(!$rs->eof) { echo "<option value='".$rs->field(0)."' ".($id_seccion_padre==$rs->field(0)?"selected":"").">".$rs->field(1)."</option>"; $rs->movenext();} ?></select></td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Nombre seccion&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='nombre_seccion'  name='nombre_seccion' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($nombre_seccion,ENT_QUOTES):"")?>'>
								</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Nombre seccion en menu&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='nombre_seccion_menu'  name='nombre_seccion_menu' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($nombre_seccion_menu,ENT_QUOTES):"")?>'>
								</td>
                                </tr>
                                
  
								<tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Orden&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='orden_seccion'  name='orden_seccion' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($orden_seccion,ENT_QUOTES):"")?>'>
								</td>
                                </tr>


								<tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Link menu (solo completar cuando no sea el link default)&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='link_menu'  name='link_menu' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($link_menu,ENT_QUOTES):"")?>'>
								</td>
                                </tr>

                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Volanta&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='volanta'  name='volanta' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($volanta,ENT_QUOTES):"")?>'>
								</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Subtitulo&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='subtitulo'  name='subtitulo' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($subtitulo,ENT_QUOTES):"")?>'>
								</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Copete&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><textarea  id='copete' name='copete' class='comun'><?=(isset($id)?$copete:"")?></textarea>
								</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Cuerpo&nbsp;<img src="images/arrow_derecha.gif">
									<br />
									@fotoW@<br />
									@fotoF@<br />
									@fotoD@<br />
									<br />
									(S)subtitulo(S)<br />
									<br />
									@videoW@<br />
									<br />
									@modulo@<br />
									<br />
									(DEST)...(DEST2)...(DEST2)(DEST)<br />
									<br />
									@CJS@<br />
									@CJS2@<br />
									</td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><?
											$oFCKeditor = new CKeditor() ;
											$oFCKeditor->config['height'] = 500;
											$oFCKeditor->config['width'] = 750;
											$oFCKeditor->editor('cuerpo',(isset($_GET["id"])?$cuerpo:"")) ;
										 ?>
										 </td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Fotos del contenido&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
									<table id='fotos_dst_select_tabla' cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												<select id="fotos_dst_select" name="fotos_dst_select[]" size="6" class="comun" style="width: 225px;" multiple="multiple" onDblClick="SacarElemento(this.options[this.selectedIndex].value, 'fotos_dst_select');">
												<?
												if(isset($id)) {
												 
													$record_rel_destination = $conn->execute("select secciones_fotos.id_foto, advf.advTitulo, epigrafe from secciones_fotos left join advf on(advf.advID = secciones_fotos.id_foto) where secciones_fotos.id_seccion = '" . $id . "' ");

													$array_muestra = array();

													while(!$record_rel_destination->eof){
														//array_push($array_muestra , array('id'=>'".$record_rel_destination->field('id_foto','epigrafe'=>$record_rel_destination->field('epigrafe'))));
														array_push($array_muestra , array("id"=>$record_rel_destination->field('id_foto'),"epigrafe"=>$record_rel_destination->field('epigrafe'),"tipo"=>"F"));

														print("<option value='" . $record_rel_destination->field('id_foto') . "'>" . $record_rel_destination->field('advTitulo') . " (" . $record_rel_destination->field('id_foto') . ")</option>");

														$record_rel_destination->movenext();
													}
												}
												?>
												</select>
											</td>
											<td width='1%' style='padding:3px;'>
											<img src='images/boton_arriba.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("fotos_dst_select","arriba");'>
											<img src='images/boton_abajo.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("fotos_dst_select","abajo");'>
											</td>
											<td class="arial11" style="padding-left:15px;">
												<table cellpadding="0" width="80%" cellspacing="0" border="0">
												<tr>
													<td class="arial12" style="background-image:url(images/separador_h3.gif); background-repeat:repeat-x; background-position:bottom; padding-bottom:10px;"><a href="javascript: openMedia('<?=(isset($_GET["id"]))? $fotos:"";?>','F','fotos_dst_select', 1);" class="arial13"><img src="images/arrow_izquierda.gif" border="0">&nbsp;<img border="0" src="images/examinar.gif" align="absmiddle">&nbsp;<strong>Agregar fotos</strong></a></td>
													</tr>
													<tr>
													<td style="padding-top:7px;" class="arial11">El primer elemento de esta lista será el principal en la web.<br><span style="color:#999999;"> Para eliminar un item de esta lista haga doble click o elimínela desde las miniaturas</span></td>
												</tr>
												</table>	
											</td>
										</tr>
                                        <!-- Previews -->
                                        <?if(count($array_muestra) > 0){?>
                                        <tr>
                                            <td colspan="3">
                                                <table>
                                                    <tr id="fotos_dst_select_tr_previews">
                                                        <?for($i = 0; $i < count($array_muestra) ; $i++ ){?>
                                                            <td style="padding-top:5px;">
                                                            <div id="preview_img_fotos_dst_select_<?=$array_muestra[$i]['id']?>">
                                                                <img src='thumbs.php?w=50&h=50&id=<?=$array_muestra[$i]['id']?>' width='50' height='50' style='border:1px solid #AEAEAE'>
                                                                <br /><span class="arial11">(<?=$array_muestra[$i]['id']?>)</span>
                                                                <img src='images/eliminar.gif' border="0" style="cursor:pointer;padding-top:7px;" onclick="SacarElemento(<?=$array_muestra[$i]['id']?>, 'fotos_dst_select');" valign="top">&nbsp;&nbsp;
                                                            </div>
                                                            </td>
                                                        <?}?>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?}?>
                                        <?if(count($array_muestra) > 0){?>
                                        <tr>
                                            <td colspan="3">
                                                <?for($i = 0; $i < count($array_muestra) ; $i++ ){?>
                                                    <div id="ep_fotos_dst_select_<?=$array_muestra[$i]['id']?>">
                                                        <span class="arial11">Epigrafe <?=$array_muestra[$i]['id']?>:</span>&nbsp;<input type='text' class='comun' name='ep_fotos_dst_select_<?=$array_muestra[$i]['id']?>' value='<?=$array_muestra[$i]['epigrafe']?>'>
                                                    </div>
                                                <?}?>
                                            </td>
                                        </tr>
                                        <?}?>
                                        <!-- Fin Previews -->
                                    </table>
									</td>
                                </tr>
                                
                                
                                
                                <tr >
                                    <td  class="dispatcher_toggle arial12" target="dis_videos_relacionados" width='20%' align="right" valign="top"  style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Videos&nbsp;<img src="images/arrow_derecha.gif"><br /><br />
                                        <span class="arial11">Los videos se insertan en el cuerpo del contenido mediante<br />
                                            @videoW@<br /></span>
                                    </td>
                                    <td width="80%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <table cellpadding="2" cellspacing="0" border="0">
                                            <tr>
                                                <td><span class="masinfo">Ingrese el epigrafe del video:</span></td>
                                            </tr>
                                            <tr>
                                                <td><input id="videos_src_input_namevideo" name="videos_src_input_namevideo" type="text" class="comun" style="width:600px;" ></td>
                                            </tr>
                                            <tr style="padding-top:5px;">
                                                <td><span class="masinfo">Ingrese el código del video (Ej: http://www.youtube.com/watch?v=<font color='red'>XYa6v7uBrH4</font>&amp;feature=related):</span></td>
                                            </tr>

                                            <tr style="padding-top:5px;">
                                                <td><input id="videos_src_input" name="videos_src_input" type="text" class="comun" style="width:200px;" >
                                                    &nbsp;<span class="masinfo">Seleccione el medio:</span>&nbsp;
                                                    <select id="videos_medio_video" name="videos_medio_video" >
                                                        <option value='YOUTUBE' selected="selected">YouTube</option>
                                                        <!--<option value='VIMEO' >Vimeo</option>-->
                                                    </select>
                                                    <img id='btn_agregar_video' src="images/btn_agregar.gif" style="cursor:pointer;" align="absmiddle" onClick="addRelationFromInputVideo('videos_src_input', 'videos_dst_select', 'videos_src_input_namevideo', 'videos_medio_video');">
                                                    <img id='btn_guardar_video' style='display:none;cursor:pointer;' src='images/btn_agregar.gif'  align='absmiddle' onClick="GuardarEditVideo('videos');">
                                                </td>
                                            </tr>


                                        </table>
                                        <table cellpadding="0" cellspacing="0" border="0" style="padding-top:10px;">
                                            <tr>
                                                <td>
                                                    <select id="videos_dst_select" name="videos_dst_select[]" size="6" class="comun" style="width: 500px;" multiple="multiple" onDblClick="this.remove(this.selectedIndex);">
                                                        <?
                                                        if (isset($id)) {
                                                            $record_rel_destination = $conn->execute("select
															id_video,
															advf.advTitulo as titulo,
															secciones_videos.video_codigo,
															secciones_videos.video_descripcion ,
															video_medio
														from secciones_videos
														Left join advf on advID = id_video
														where
														secciones_videos.id_seccion = '" . $id . "'
														order by orden");

                                                            while (!$record_rel_destination->eof) {
                                                                if (intval($record_rel_destination->field('id_video')) > 0) {
                                                                    echo "<option value='" . $record_rel_destination->field('titulo') . "||" . $record_rel_destination->field('id_video') . "||PROPIO' >" . $record_rel_destination->field('titulo') . " (" . $record_rel_destination->field('id_video') . ") - PROPIO </option>";
                                                                } else {
                                                                    echo "<option value='" . $record_rel_destination->field('video_descripcion') . "||" . $record_rel_destination->field('video_codigo') . "||" . $record_rel_destination->field('video_medio') . "'>" . $record_rel_destination->field('video_descripcion') . " (" . $record_rel_destination->field('video_codigo') . ") - " . $record_rel_destination->field('video_medio') . " " . "" . " </option>";
                                                                }


                                                                $record_rel_destination->movenext();
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td width='1%' style='padding:3px;'>

                                                    <img src='images/boton_arriba.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("videos_dst_select", "arriba");'>
                                                    <img src='images/boton_abajo.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("videos_dst_select", "abajo");'>
                                                </td>
                                                <td class="arial11" style="padding-left:15px;">
                                                    <table cellpadding="0" width="80%" cellspacing="0" border="0">
                                                        <tr>
                                                            <td>
                                                                <input type="button" name="editar"  value="Editar Video" onClick="EditRelationVideos('videos')">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:7px;" class="arial11">
                                                                El primer elemento de esta lista será el principal en la web.<br><span style="color:#999999;"> Para eliminar un item de la lista haga doble click en el listado</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Archivos Relacionados&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
									<table id='archivos_dst_select_tabla' cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												<select id="archivos_dst_select" name="archivos_dst_select[]" size="6" class="comun" style="width: 225px;" multiple="multiple" onDblClick="SacarElemento(this.options[this.selectedIndex].value, 'archivos_dst_select');">
												<?
												if(isset($id)) {
													$record_rel_destination = $conn->execute(
                                                        "select secciones_archivos.id_archivo, 
                                                                advf.advTitulo,
                                                                advf.advLink,
                                                                secciones_archivos.epigrafe from secciones_archivos 
                                                                left join advf on(advf.advID = secciones_archivos.id_archivo) where secciones_archivos.id_seccion = '" . $id . "' order by orden");

													$array_muestra = array();

													while(!$record_rel_destination->eof){
														array_push($array_muestra , 
                                                            array(
                                                                "id"=>$record_rel_destination->field('id_archivo'),
                                                                "epigrafe"=>$record_rel_destination->field('epigrafe'),
                                                                "advLink"=>$record_rel_destination->field('advLink'),
                                                            )
                                                        );

														print("<option value='" . $record_rel_destination->field('id_archivo') . "'>" . $record_rel_destination->field('advTitulo') . " (" . $record_rel_destination->field('id_archivo') . ")</option>");

														$record_rel_destination->movenext();
													}
												}
												?>
												</select>
											</td>
											<td width='1%' style='padding:3px;'>
											<img src='images/boton_arriba.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("archivos_dst_select","arriba");'>
											<img src='images/boton_abajo.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("archivos_dst_select","abajo");'>
											</td>
											<td class="arial11" style="padding-left:15px;">
												<table cellpadding="0" width="80%" cellspacing="0" border="0">
												<tr>
													<td class="arial12" style="background-image:url(images/separador_h3.gif); background-repeat:repeat-x; background-position:bottom; padding-bottom:10px;"><a href="javascript: openMedia('<?=(isset($_GET["id"]))? $archivos:"";?>','D','archivos_dst_select', 1);" class="arial13"><img src="images/arrow_izquierda.gif" border="0">&nbsp;<img border="0" src="images/examinar.gif" align="absmiddle">&nbsp;<strong>Agregar archivos</strong></a></td>
													</tr>
													<tr>
													<td style="padding-top:7px;" class="arial11">El primer elemento de esta lista será el principal en la web.<br><span style="color:#999999;"> Para eliminar un item de esta lista haga doble click o elimínela desde las miniaturas</span></td>
												</tr>
												</table>	
											</td>
										</tr>
										<!-- Previews -->
                                        <?if(count($array_muestra) > 0){?>
                                        <tr>
                                            <td colspan="3">
                                                <table>
                                                    <tr id="archivos_dst_select_tr_previews">
                                                        <?for($i = 0; $i < count($array_muestra) ; $i++ ){
                                                            $linkAlDocumento = '/' . $array_muestra[$i]['advLink'];
                                                            ?>
                                                            <td style="padding-top:5px;">
                                                            <div id="preview_img_archivos_dst_select_<?=$array_muestra[$i]['id']?>">
                                                                <a href="<?php echo $linkAlDocumento; ?>" target="_blank">
                                                                    <img src="/admin/images/iconoDocumento.png" width='50' height='50' style='border:1px solid #AEAEAE'>&nbsp;&nbsp;
                                                                </a>
                                                                <br /><span class="arial11">(<?=$array_muestra[$i]['id']?>)</span>
                                                                <img src='images/eliminar.gif' border="0" style="cursor:pointer;padding-top:7px;" onclick="SacarElemento(<?=$array_muestra[$i]['id']?>, 'archivos_dst_select');" valign="top">&nbsp;&nbsp;
                                                            </div>
                                                            </td>
                                                        <?}?>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?}?>
                                        <?if(count($array_muestra) > 0){?>
                                        <tr>
                                            <td colspan="3">
                                                <?for($i = 0; $i < count($array_muestra) ; $i++ ){?>
                                                    <div id="ep_archivos_dst_select_<?=$array_muestra[$i]['id']?>">
                                                        <span class="arial11">Epigrafe <?=$array_muestra[$i]['id']?>:</span>&nbsp;<input type='text' class='comun' name='ep_archivos_dst_select_<?=$array_muestra[$i]['id']?>' value='<?=$array_muestra[$i]['epigrafe']?>'>
                                                    </div>
                                                <?}?>
                                            </td>
                                        </tr>
                                        <?}?>
                                        <!-- Fin Previews -->
									</table>
									</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Secciones Relacionadas&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td><span class="masinfo">Ingrese los ID:</span></td>
										</tr>
										<tr>
											<td><input id="contenidosrelacionados_src_input" name="contenidosrelacionados_src_input" type="text" class="comun" style="width:35px;" value="0" onkeyup="regexp = /^[0-9]+$/; if(!regexp.test(this.value)) { this.value = 0; }" onkeydown="regexp = /^[0-9]+$/; if(!regexp.test(this.value)) { this.value = 0; }">&nbsp;<img src="images/btn_agregar.gif" align="absmiddle" style="cursor:pointer;" onClick="addRelationFromInput('contenidosrelacionados_src_input','contenidosrelacionados_src_select','contenidosrelacionados_dst_select');"></td>
										</tr>
										<tr>
											<td>
												<span class="masinfo">ó directamente seleccione los contenidosrelacionados correspondientes:</span><br>
												<select id="contenidosrelacionados_src_select" name="contenidosrelacionados_src_select" class="comun" style="width: 156px;">
													<option value="0">Seleccionar</option>

									
													<?
														$record_rel_source = $conn->execute("select id, nombre_seccion from secciones where activo = 'S' and estado = 'A'");

														while(!$record_rel_source->eof) {
															print("<option value='" . $record_rel_source->field('id') . "'>" . $record_rel_source->field('nombre_seccion') . " (" . $record_rel_source->field('id') . ")</option>");

															$record_rel_source->movenext();
														}
													?>
									
												</select>
												<img src="images/btn_agregar.gif" style="cursor:pointer;" align="absmiddle" onClick="addRelationFromSelect('contenidosrelacionados_src_select','contenidosrelacionados_dst_select');">
											</td>
										</tr>
																															<tr>
													<td style='padding-top:7px;' class='arial11'>
													</span></td>
												</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="padding-top:5px;">
										<tr>
											<td valign="top">
												<select id="contenidosrelacionados_dst_select" name="contenidosrelacionados_dst_select[]" size="6" class="comun" style="width: 225px;" multiple="multiple" onDblClick="this.remove(this.selectedIndex);">
												<?
												if(isset($id)) {
													$record_rel_destination = $conn->execute(
													"select secciones_secciones.id_seccion_rel, 
													secciones.nombre_seccion
													 from secciones_secciones 
													 left join secciones 
													 on(secciones.id = secciones_secciones.id_seccion_rel)
															 where secciones_secciones.id_seccion = '" . $id . "' 
															 and secciones_secciones.tipo = '' order by secciones_secciones.orden");
								
													while(!$record_rel_destination->eof){
														print("<option value='" . $record_rel_destination->field('id_seccion_rel') . "'>" . $record_rel_destination->field('nombre_seccion') . " (" . $record_rel_destination->field('id_seccion_rel') . ")</option>");

														$record_rel_destination->movenext();
													}
												}
												?>
												</select>
											</td>
											<td width='1%' style='padding:3px;'>
											<img src='images/boton_arriba.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("contenidosrelacionados_dst_select","arriba");'>
											<img src='images/boton_abajo.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("contenidosrelacionados_dst_select","abajo");'>
											</td>
											<td class="arial11" style="padding-left:15px;">
												<table cellpadding="0" width="80%" cellspacing="0" border="0">
												
												<tr>
													<td style="padding-top:7px;" class="arial11">
														El primer elemento de esta lista será el principal en la web.<br><span style="color:#999999;"> Para eliminar un item de la lista haga doble click en el listado o elimínela desde las miniaturas</span>
													</td>
												</tr>
												</table>
											</td>
										</tr>
									</table>
									</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Enlaces relacionados&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td><span class="masinfo">Ingrese el título del enlace:</span></td>
										</tr>
										<tr>
											<td><input id="enlaces_src_input_enlacetitulo" name="enlaces_src_input_enlacetitulo" type="text" class="comun" style="width:400px;" ></td>
										</tr>
										<tr>
											<td><span class="masinfo">Tipo de ventana</span></td>
										</tr>
										<tr>
											<td><span class="masinfo"><select id="enlaces_src_input_enlacetarget" name="enlaces_src_input_enlacetarget" class="comun" ><option value='_blank'>Ventana Nueva</option><option value='_self'>Misma Ventana</option></select</span></td>
										</tr>
										<tr>
											<td><span class="masinfo">Ingrese el enlace completo (con http://):</span></td>
										</tr>
										<tr>
											<td><input id="enlaces_src_input" name="enlaces_src_input" type="text" class="comun" style="width:400px;" >&nbsp;<img src="images/btn_agregar.gif" style="cursor:pointer;" align="absmiddle" onClick="addRelationFromInputEnlaces('enlaces_src_input','enlaces_dst_select','enlaces_src_input_enlacetitulo','enlaces_src_input_enlacetarget');"></td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="padding-top:5px;">
										<tr>
											<td>
												<select id="enlaces_dst_select" name="enlaces_dst_select[]" size="6" class="comun" style="width: 355px;" multiple="multiple" onDblClick="this.remove(this.selectedIndex);">
												<?
												if(isset($id)) {
													$record_rel_destination = $conn->execute("select secciones_enlaces.enlace,secciones_enlaces.enlace_titulo,secciones_enlaces.enlace_target from secciones_enlaces where secciones_enlaces.id_seccion = '" . $id . "' order by secciones_enlaces.orden");
								
													while(!$record_rel_destination->eof){
														print("<option value='" . $record_rel_destination->field('enlace_titulo') . '||' . $record_rel_destination->field('enlace'). '||' . $record_rel_destination->field('enlace_target') . "'>" . $record_rel_destination->field('enlace_titulo') . " (".$record_rel_destination->field('enlace').") </option>");

														$record_rel_destination->movenext();
													}
												}
												?>
												</select>
											</td>
											<td width='1%' style='padding:3px;'>
											<img src='images/boton_arriba.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("enlaces_dst_select","arriba");'>
											<img src='images/boton_abajo.gif' style='cursor: pointer' border='0' onclick='EscalarElemento("enlaces_dst_select","abajo");'>
											</td>
											<td class="arial11" style="padding-left:15px;">
												<table cellpadding="0" width="80%" cellspacing="0" border="0">
												<tr>
													<td>
														<input type="button" name="editar"  value="Editar Enlace" onClick="editRelationEnlaces('enlaces_dst_select')">
													</td>
												</tr>
												<tr>
													<td style="padding-top:7px;" class="arial11">
														El primer elemento de esta lista será el principal en la web.<br><span style="color:#999999;"> Para eliminar una foto haga doble click en el listado</span>
													</td>
												</tr>
												</table>
											</td>
										</tr>
									</table>
									</td>
                                </tr>
                                
 
							    <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Codigo JS1&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><textarea  id='codigo_js' name='codigo_js' class='comun'><?=(isset($id)?$codigo_js:"")?></textarea>
								</td>
                                </tr>
                                


                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Codigo JS2&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><textarea  id='codigo_js2' name='codigo_js2' class='comun'><?=(isset($id)?$codigo_js2:"")?></textarea>
								</td>
                                </tr>
                                

                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Modulo especial - Color fondo&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='modulo_fondo'  name='modulo_fondo' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($modulo_fondo,ENT_QUOTES):"")?>'>
								</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Modulo especial - Icono&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
								<?
									$image_name ="";
									if($modulo_icono>0)
									{
										// Quiere decir que hay algun numero, asi que lo mostramos
										$sql = "select advID,advLink,advTitulo from advf where advID = ".$modulo_icono;
										$rs = $conn->execute($sql);

										$image_name = $rs->field("advTitulo");
									}
								?>
									<input type='text' class='comun' name='modulo_icono_image' readonly value='<?=$image_name;?>'>					
									<input type='hidden' name='modulo_icono' value='<?=(isset($_GET["id"])?$modulo_icono:"")?>'>
									<a href="javascript: openMedia('<?=(isset($_GET["id"]))? $modulo_icono:"";?>','F','modulo_icono','0');"><img border="0" src="images/examinar.gif"></a>
									<a href="javascript: clearMedia('F','modulo_icono');"><img border="0" src="images/eliminar.gif"></a>
									<?
									if($modulo_icono>0)
									{
									?>
										<div id='preview_image_modulo_icono' name='preview_image_modulo_icono' style='padding:3px;'>
										<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
										</div>
									<?
									}
									?>
									</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Modulo especial - Titulo&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='modulo_titulo'  name='modulo_titulo' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($modulo_titulo,ENT_QUOTES):"")?>'>
								</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Modulo especial - Cuerpo&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><?
											$oFCKeditor = new CKeditor() ;
											$oFCKeditor->config['height'] = 500;
											$oFCKeditor->config['width'] = 750;
											$oFCKeditor->editor('modulo_cuerpo',(isset($_GET["id"])?$modulo_cuerpo:"")) ;
										 ?>
										 </td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Activo&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input type="hidden" value="activo" name="columnRights[16]"><select id='activo' name='activo' class='comun' <? print($usr->checkUserRights(16)); ?>><option value='S' <?=($activo=='S' or ((count($_GET) == 0) and '1' == '1'))?"selected":"";?>>Si</option><option value='N' <?=($activo=='N' or ((count($_GET) == 0) and '' == '1'))?"selected":"";?>>No</option></select></td>
                                </tr>
                                
                                
                                
                            </table></td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
                    </tr>
                </table>
                <br>
                <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
                    <tr>
                        <td width="5" align="left" valign="top"><img src="images/corner_si2.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td width="5" align="right" valign="top"><img src="images/corner_sd2.gif" width="5" height="5"></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                        <td align="center" bgcolor="#e5e5e5"><table width='50%' border="0" cellpadding="0" cellspacing="5">
                                <tr>
                                    <td align="left"><?=($usr->checkUserRights(2) != 'disabled')? '<img onclick=\'javascript:document.location.href="' . $_SERVER["PHP_SELF"] . '";\' src="images/btn_nuevo.gif" style="cursor:pointer;" border="0">':'';?>
                                    </td>
                                    <td align='center' style="padding-right:15px;"><img border="0" onclick='document.location = "secciones.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" style="cursor:pointer;">
                                    </td>
                                    <td align='center' style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" onclick="javascript:set_type(0);" src="images/btn_guardar.gif">'); ?></td>
                                    <td align='center'><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" src="images/btn_guardaryvolver.gif" onclick="javascript:set_type(1);" value=\'Guardar y volver al listado\'>'); ?></td>
                                </tr>
                            </table></td>
                        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
                    </tr>
                </table>    
            </form>
            <br />
            <br />
        </div>
    </body>
</html>
