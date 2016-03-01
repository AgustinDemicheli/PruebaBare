<?php

//Si "id_contenido" es numerico, entonces el contenido está en la tabla "contenidos"
//si no es numerico, el id_contenido traerá explicitada la tabla "espiando", "hits", "topfive" o "contenidos"

function crear_selectores_contenido($tipo_modulo, $id_modulo, $id_contenido_sel = 0, $id_categoria_sel = 0, $para_envio = false){
	global $conn;
	global $NotasSeleccionadas;
	$name = ($para_envio) ? "modulo[".$tipo_modulo."][".$id_modulo."][value][0]" : "contenidos_".$id_modulo."_".$tipo_modulo;
	?>
	<select class="combo_mh_flash"  style="width: 90px;"  name="null" id="slt_cont_categoria_<?php echo $id_modulo?>" onchange="MostrarContenidosPorCategoria($(this).val(),'<?php echo $id_modulo?>','<?php echo $tipo_modulo?>');">
		<option value="0" <?=$id_categoria_sel == 0 ? "selected='selected'":"" ?>>Todos</option>
	<?php 
		  $categorias = GetCategoriasContenidosParent();
		  for($i=0;$i<count($categorias);$i++){
	?>
		<option value="<?php echo $categorias[$i]["id"]?>" <?=$categorias[$i]["id"] == $id_categoria_sel ? "selected='selected'":"" ?>><?php echo $categorias[$i]["descripcion"]?></option>
	<?php }?>
	</select>
	<select style="width: <?=( $tipo_modulo=="nota_destacada" || $tipo_modulo=="nota_destacada2" || $tipo_modulo == "nota_doble1" || $tipo_modulo == "nota_doble2" || $tipo_modulo == "opinion_destacada" || $tipo_modulo == "nota_menu"? "350" : "190" )?>px;" id="contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>" name="<?php echo $name; ?>" class="combo_mh_flash" >
	<?php
	$cont_def   = GetContenidosPorDefaultAdminHome();
	for($i=0;$i<count($cont_def);$i++){ 
		//if(!isset($NotasSeleccionadas[$cont_def[$i]["id"]])){
	?>
		<option value="<?php echo $cont_def[$i]["id"] ?>" <?=$cont_def[$i]["id"] == $id_contenido_sel ? "selected='selected'":""?> ><?php echo $cont_def[$i]["id"] . " - " . $cont_def[$i]["titulo_home"]?></option>
	<?php 
		//}
	} ?>
	</select>
	<?if($tipo_modulo == "nota_destacada2" || $tipo_modulo == "nota_destacada" || $tipo_modulo == "nota_doble1" || $tipo_modulo == "nota_doble2" || $tipo_modulo == "opinion_destacada" ){?>
		<span class="arial12" onclick="OpenPopUpEdit($('#contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>').val());" style="cursor:pointer;">Editar</span>
	<?}?>
<?php }

function crear_selectores_videos($tipo_modulo, $id_modulo, $id_contenido_sel = 0, $id_categoria_sel = 0, $para_envio = false){
	global $conn;
	global $NotasSeleccionadas;
	$name = ($para_envio) ? "modulo[".$tipo_modulo."][".$id_modulo."][value][0]" : "contenidos_".$id_modulo."_".$tipo_modulo;
	?>
	<select class="combo_mh_flash"  style="width: 90px;"  name="null" id="slt_cont_categoria_<?php echo $id_modulo?>" onchange="MostrarVideosPorCategoria($(this).val(),'<?php echo $id_modulo?>','<?php echo $tipo_modulo?>');">
		<option value="0" <?=$id_categoria_sel == 0 ? "selected='selected'":"" ?>>Todos</option>
	<?php 
		  $categorias = GetCategoriasContenidosParent();
		  for($i=0;$i<count($categorias);$i++){
	?>
		<option value="<?php echo $categorias[$i]["id"]?>" <?=$categorias[$i]["id"] == $id_categoria_sel ? "selected='selected'":"" ?>><?php echo $categorias[$i]["descripcion"]?></option>
	<?php }?>
	</select>
	<select style="width: 190px;" id="contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>" name="<?php echo $name; ?>" class="combo_mh_flash" >
	<?php
	$cont_def   = GetVideosPorDefaultAdminHome();
	for($i=0;$i<count($cont_def);$i++){ 
		if(!isset($NotasSeleccionadas[$cont_def[$i]["id"]])){?>
		<option value="<?php echo $cont_def[$i]["id"] ?>" <?=$cont_def[$i]["id"] == $id_contenido_sel ? "selected='selected'":""?> ><?php echo $cont_def[$i]["id"] . " - " . $cont_def[$i]["titulo"]?></option>
	<?php 
		}
	} ?>
	</select>
<?php }

function crear_elemento($id_modulo, $tipo_modulo, $valor1_modulo , $valor2_modulo , $valor3_modulo, $valor4_modulo, $orden)
{
	global $conn, $array_modulos; 
	global $NotasSeleccionadas;

	switch($tipo_modulo)
	{
		case "nota_destacada":
		case "nota_destacada2":
			?>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					   <tr>
							<td width="94%" class="arial13" style="padding-left:5px;"><strong><?php if($tipo_modulo == "nota_destacada"){?>Nota Foto Gigante: <?php }else{?> Nota Especial 3 Columnas (Foto 2 columnas - Texto a la derecha) <?php }?> </strong></td>
							<td width="6%" align="right" class="arial12" style="padding-left:5px;">&nbsp;</td>
					  	</tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
              	<?php 
              	    if(intval($valor2_modulo) > 0){
                    	$q = "SELECT id_categoria FROM contenidos WHERE id = {$valor2_modulo} LIMIT 1";
                    	$r = $conn->getRecordset($q);
                    }
                    $id_categoria_sel = intval($r[0]["id_categoria"]);
                 	crear_selectores_contenido($tipo_modulo,$id_modulo, $valor2_modulo, $id_categoria_sel, true); ?>
					
				  
				  <br /><span class="arial12">Activo: </span>
				  <label><input type="radio" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "N" || empty($valor4_modulo)) ? 'checked="checked"':''?> value="N" /> <span class="arial12">No</span> </label>
				  <label><input type="radio" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "S") ? 'checked="checked"':''?> value="S" /> <span class="arial12">Si</span> </label>
				</td>
			  </tr>
			</table>			
			<?
			break;
		case "nota_doble":
			?>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					   <tr>
							<td width="94%" class="arial13" style="padding-left:5px;"><strong>Nota 2 Columnas + Nota 1 Columna</strong></td>
							<td width="6%" align="right" class="arial12" style="padding-left:5px;">&nbsp;</td>
					  	</tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
              	<?php 
              	    if(intval($valor2_modulo) > 0){
                    	$q = "SELECT id_categoria FROM contenidos WHERE id = {$valor2_modulo} LIMIT 1";
                    	$r = $conn->getRecordset($q);
                    }
                    $id_categoria_sel = intval($r[0]["id_categoria"]);
                 	crear_selectores_contenido($tipo_modulo."1",$id_modulo, $valor2_modulo, $id_categoria_sel, true);
				?><br />
              	<?php 
              	    if(intval($valor3_modulo) > 0){
                    	$q = "SELECT id_categoria FROM contenidos WHERE id = {$valor3_modulo} LIMIT 1";
                    	$r = $conn->getRecordset($q);
                    }
                    $id_categoria_sel = intval($r[0]["id_categoria"]);
                 	crear_selectores_contenido($tipo_modulo."2",$id_modulo, $valor3_modulo, $id_categoria_sel, true);
				?>					
				  
				  <br /><span class="arial12">Activo: </span>
				  <label><input type="radio" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "N" || empty($valor4_modulo)) ? 'checked="checked"':''?> value="N" /> <span class="arial12">No</span> </label>
				  <label><input type="radio" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "S") ? 'checked="checked"':''?> value="S" /> <span class="arial12">Si</span> </label>
				</td>
			  </tr>
			</table>			
			<?
			break;
		case "opinion_destacada":
			?>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					   <tr>
							<td width="94%" class="arial13" style="padding-left:5px;"><strong>Opinion 3 columnas:  </strong></td>
							<td width="6%" align="right" class="arial12" style="padding-left:5px;">&nbsp;</td>
					  	</tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
              	<?php 
              	    if(intval($valor2_modulo) > 0){
                    	$q = "SELECT id_categoria FROM contenidos WHERE id = {$valor2_modulo} LIMIT 1";
                    	$r = $conn->getRecordset($q);
                    }
                    $id_categoria_sel = intval($r[0]["id_categoria"]);
                 	crear_selectores_contenido($tipo_modulo,$id_modulo, $valor2_modulo, $id_categoria_sel, true); ?>

				  <br /><span class="arial12">Activo: </span>
				  <label><input type="radio" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "N" || empty($valor4_modulo)) ? 'checked="checked"':''?> value="N" /> <span class="arial12">No</span> </label>
				  <label><input type="radio" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "S") ? 'checked="checked"':''?> value="S" /> <span class="arial12">Si</span> </label>
				</td>
			  </tr>
			</table>			
			<?
			break;
		case "notas_notelopierdas":
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
                   	<div style="width:100%; margin:0; padding:0; border:0; display:block; position:relative; ">     
						<div>
                            <p class="arial13" style="margin:3px 0 0 0; padding:0; float:left;"><strong>No te lo pierdas - Notas</strong></p>
                        </div>                       
                    </div>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				    <td colspan="2">
				        <table>
				            <tr>
			                    <td width="50%" style="padding:5px" class="arial12">ID: 
			                        <input class="posicion_mh" style="width:55px" type="text" size="2" name="<?php echo $id_modulo . "_" . $tipo_modulo ?>_addById" id="contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>_addById"  />
			                    </td>
                                <td width="50%" align="left"><img src="images/btn_agregar.gif" onClick="optionAddById('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel', true);" border="0"></td>
                            </tr>     
				        </table>
				    </td>
				 </tr>
				 <tr>
					<td colspan="2">
                    <?php crear_selectores_contenido($tipo_modulo,$id_modulo);	?>&nbsp;
					<img src="images/btn_agregar.gif" onClick="optionAdd('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel');" border="0">
					</td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" class="arial11"><table cellpadding="0" cellspacing="0">
				  <tbody>
					<tr>
					  <td>
					  <select size="5" class="multi_mh slt_seleccionar_todos" id="slt_<?=$id_modulo?>_sel" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][value][]" multiple="multiple" ondblclick="OpenPopUpEdit($('#slt_<?=$id_modulo?>_sel').val());">
						  <?php
					  	$q = "SELECT 
									   C.id , 
									   C.titulo_home as titulo 
								FROM home_aux HA
								INNER JOIN contenidos C ON HA.valor1 = C.id
								WHERE HA.id_home = '".$id_modulo."'
								ORDER BY HA.orden";

							$recordset = $conn->getRecordset($q);
							
							for($i=0;$i<count($recordset);$i++){
								print("<option value='".$recordset[$i]['id']."'>".$recordset[$i]['id']." - ".$recordset[$i]['titulo']."</option>");
							}
							?>
						</select>
					  </td>
					  <td style="padding-left:5px;" valign="top">
					    <img src="images/delete-icon.png"  width="16" height="16"  style="cursor: pointer;" onClick="optionDelete('slt_<?=$id_modulo?>_sel');" border="0"> <br /><br />
						<img src="images/boton_arriba2.gif" style="cursor: pointer;" onClick="EscalarElemento('arriba','slt_<?=$id_modulo?>_sel');" border="0"> <br>
						<img src="images/boton_abajo2.gif" style="cursor: pointer;" onClick="EscalarElemento('abajo','slt_<?=$id_modulo?>_sel');" border="0" vspace="3">
					  </td>
					</tr>
				  </tbody>
				</table>
				 <strong>&nbsp;Doble click</strong> para <strong>editar</strong> un elemento de la lista </td>
			  </tr>
			</table>
			<?
			break;
		case "notas_opinion":
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
                   	<div style="width:100%; margin:0; padding:0; border:0; display:block; position:relative; ">     
						<div>
                            <p class="arial13" style="margin:3px 0 0 0; padding:0; float:left;"><strong>Notas Opinion</strong></p>
                        </div>                       
                    </div>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				    <td colspan="2">
				        <table>
				            <tr>
			                    <td width="50%" style="padding:5px" class="arial12">ID: 
			                        <input class="posicion_mh" style="width:55px" type="text" size="2" name="<?php echo $id_modulo . "_" . $tipo_modulo ?>_addById" id="contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>_addById"  />
			                    </td>
                                <td width="50%" align="left"><img src="images/btn_agregar.gif" onClick="optionAddById('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel', true);" border="0"></td>
                            </tr>     
				        </table>
				    </td>
				 </tr>
				 <tr>
					<td colspan="2">
                    <?php crear_selectores_contenido($tipo_modulo,$id_modulo);	?>&nbsp;
					<img src="images/btn_agregar.gif" onClick="optionAdd('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel');" border="0">
					</td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" class="arial11"><table cellpadding="0" cellspacing="0">
				  <tbody>
					<tr>
					  <td>
					  <select size="5" class="multi_mh slt_seleccionar_todos" id="slt_<?=$id_modulo?>_sel" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][value][]" multiple="multiple" ondblclick="OpenPopUpEdit($('#slt_<?=$id_modulo?>_sel').val());">
						  <?php
					  	$q = "SELECT 
									   C.id , 
									   C.titulo_home as titulo 
								FROM home_aux HA
								INNER JOIN contenidos C ON HA.valor1 = C.id
								WHERE HA.id_home = '".$id_modulo."'
								ORDER BY HA.orden";

							$recordset = $conn->getRecordset($q);
							
							for($i=0;$i<count($recordset);$i++){
								print("<option value='".$recordset[$i]['id']."'>".$recordset[$i]['id']." - ".$recordset[$i]['titulo']."</option>");
							}
							?>
						</select>
					  </td>
					  <td style="padding-left:5px;" valign="top">
					    <img src="images/delete-icon.png"  width="16" height="16"  style="cursor: pointer;" onClick="optionDelete('slt_<?=$id_modulo?>_sel');" border="0"> <br /><br />
						<img src="images/boton_arriba2.gif" style="cursor: pointer;" onClick="EscalarElemento('arriba','slt_<?=$id_modulo?>_sel');" border="0"> <br>
						<img src="images/boton_abajo2.gif" style="cursor: pointer;" onClick="EscalarElemento('abajo','slt_<?=$id_modulo?>_sel');" border="0" vspace="3">
					  </td>
					</tr>
				  </tbody>
				</table>
				 <strong>&nbsp;Doble click</strong> para <strong>editar</strong> un elemento de la lista </td>
			  </tr>
			</table>
			<?
			break;
		case "slider":
		case "lo_ultimo":
		case "destacadas_foto_grande":
		case "destacadas_sin_foto":
		case "destacadas_sin_foto_chica":
		case "destacadas_foto_chica":
		case "mininotas_horizontales":
		case "nota_principal":
		case "galeria_videos":
			?>
			  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td align="left">
					<a href="/admin/index_modelo.php" target="_blank"><input type="button" value="Testeador de titulos" ></a>
				</td>
			  </tr>
			  <tr>
				<td class="encabezado_mh">
                   	<div style="width:100%; margin:0; padding:0; border:0; display:block; position:relative; ">
                        <? 
                        if($tipo_modulo=="nota_principal"){
                        $rsNPVersion = $conn->execute("SELECT version_nr, version_fecha_hora, CONCAT(HOUR(version_fecha_hora), ':', MINUTE(version_fecha_hora)) AS hora, id_usuario, usuario 
                        FROM home_version AS hv
                        INNER JOIN admin_usuarios AS au ON hv.id_usuario = au.id
                        WHERE module='nota_principal'");
                        ?>
                    
                        <p class="arial12" id="spnVersion_nota_principal" style="float:left; padding:0; margin:2px 0;">
                            Ultima versión: <?=$rsNPVersion->field("version_nr")?> (por <?=$rsNPVersion->field("usuario")?> el <?=mysql_a_normal($rsNPVersion->field("version_fecha_hora"))?> a las <?=$rsNPVersion->field("hora")?>)
                        </p>
                        
                        <div style="float:right;">
                        <a href="javascript:guardarContenidoMultipleVersionadoAjax('nota_principal', 'slt_<?=$id_modulo?>_sel', 'modulo[nota_principal][<?=$id_modulo?>][columnas][0]', '<?=$rsNPVersion->field("version_nr")?>', '<?=$rsNPVersion->field("id_usuario")?>')"><img src="images/btn_guardar.gif" border="0" /></a>
                        </div>
                                                   
                        <div style="clear:both"></div>
                        
                        <? } ?>
                        
						<div>
                            <? if($tipo_modulo<>"nota_principal"){ ?>
                                <p class="arial12" style="float:left; margin:0; padding:0; ">Pos. <br/><span style="font-size:10px;">(Cant Notas)</span></p>
                                <input type="text" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh" style="float:left; margin:3px 5px 0 5px;">
                                <? } ?>
                                <p class="arial13" style="margin:3px 0 0 0; padding:0; float:left;"><strong>M&oacute;dulo <?=($tipo_modulo=="nota_principal"?"Notas Principales":$array_modulos[$valor1_modulo][$tipo_modulo])?></strong></p>


                                <? if($tipo_modulo<>"nota_principal"){ ?>
                                <div style="float:right;" ><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></figure>					  
                            <? }?>
                        </div>                      
                    
                    </div>
				</td>
			  </tr>
              
			  <tr>
				<td style="padding:5px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				    <td colspan="2">
				        <table>
				            <tr>
			                    <td width="50%" style="padding:5px" class="arial12">ID: 
			                        <input class="posicion_mh" style="width:55px" type="text" size="2" name="<?php echo $id_modulo . "_" . $tipo_modulo ?>_addById" id="contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>_addById"  />
			                    </td>
                                <td width="50%" align="left"><img src="images/btn_agregar.gif" onClick="optionAddById('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel', true);" border="0"></td>
                            </tr>     
				        </table>
				    </td>
				 </tr>
				 <tr>
					<td colspan="2">
                    <?php 
                    if($tipo_modulo == "galeria_videos"){
                    	crear_selectores_videos($tipo_modulo,$id_modulo);                    	
                    }else{
                    	crear_selectores_contenido($tipo_modulo,$id_modulo);
					}		?>&nbsp;
					<img src="images/btn_agregar.gif" onClick="optionAdd('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel');" border="0">
					</td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" class="arial11"><table cellpadding="0" cellspacing="0">
				  <tbody>
					<tr>
					  <td>
					  <select size="10" class="multi_mh slt_seleccionar_todos" id="slt_<?=$id_modulo?>_sel" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][value][]" multiple="multiple" ondblclick="OpenPopUpEdit($('#slt_<?=$id_modulo?>_sel').val());">
						  <?php
					  if($tipo_modulo == "galeria_videos"){
					  	$q = "SELECT 
									   C.id , 
									   C.titulo 
								FROM home_aux HA
								INNER JOIN videos C ON HA.valor1 = C.id
								WHERE HA.id_home = '".$id_modulo."'
								ORDER BY HA.orden";
					  }else{
					  	$q = "SELECT 
									   C.id , 
									   C.titulo_home as titulo 
								FROM home_aux HA
								INNER JOIN contenidos C ON HA.valor1 = C.id
								WHERE HA.id_home = '".$id_modulo."'
								ORDER BY HA.orden";
					  }
						   
							$recordset = $conn->getRecordset($q);
							
							for($i=0;$i<count($recordset);$i++){
								print("<option value='".$recordset[$i]['id']."'>".$recordset[$i]['id']." - ".$recordset[$i]['titulo']."</option>");
							}
							?>
						</select>
					  </td>
					  <td style="padding-left:5px;" valign="top">
					    <img src="images/delete-icon.png"  width="16" height="16"  style="cursor: pointer;" onClick="optionDelete('slt_<?=$id_modulo?>_sel');" border="0"> <br /><br />
						<img src="images/boton_arriba2.gif" style="cursor: pointer;" onClick="EscalarElemento('arriba','slt_<?=$id_modulo?>_sel');" border="0"> <br>
						<img src="images/boton_abajo2.gif" style="cursor: pointer;" onClick="EscalarElemento('abajo','slt_<?=$id_modulo?>_sel');" border="0" vspace="3"><br /><br />
						<?php if($tipo_modulo == "nota_principal"){

							$aux1 = $conn->execute("SELECT id FROM home WHERE tipo_modulo = 'cascada1' AND id_portal = 0");
							$v1 = $aux1->field("id");
							$aux2 = $conn->execute("SELECT id FROM home WHERE tipo_modulo = 'cascada2' AND id_portal = 0");
							$v2 = $aux2->field("id");
						?>
						<input type="button" class="comun" value="Pasar a Cascada Izq" onClick="optionChange('slt_<?=$id_modulo?>_sel','slt_<?=$v1?>_sel');" style="width:150px;" border="0">
						<br /><br />
						<input type="button" class="comun" value="Pasar a Cascada Der" onClick="optionChange('slt_<?=$id_modulo?>_sel','slt_<?=$v2?>_sel');" style="width:150px;" border="0">
						<?}?>
					  </td>
					</tr>
				  </tbody>
				</table>
				 <strong>&nbsp;Doble click</strong> para <strong>editar</strong> un elemento de la lista </td>
			  </tr>
			</table>
			<?
			break;
		case "titulares_verticales":
		case "columnistas":
			?>
			  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
                   	<div style="width:100%; margin:0; padding:0; border:0; display:block; position:relative; ">
                        <? 
                        if($tipo_modulo=="nota_principal"){
                        $rsNPVersion = $conn->execute("SELECT version_nr, version_fecha_hora, CONCAT(HOUR(version_fecha_hora), ':', MINUTE(version_fecha_hora)) AS hora, id_usuario, usuario 
                        FROM home_version AS hv
                        INNER JOIN admin_usuarios AS au ON hv.id_usuario = au.id
                        WHERE module='nota_principal'");
                        ?>
                    
                        <p class="arial12" id="spnVersion_nota_principal" style="float:left; padding:0; margin:2px 0;">
                            Ultima versión: <?=$rsNPVersion->field("version_nr")?> (por <?=$rsNPVersion->field("usuario")?> el <?=mysql_a_normal($rsNPVersion->field("version_fecha_hora"))?> a las <?=$rsNPVersion->field("hora")?>)
                        </p>
                        
                        <div style="float:right;">
                        <a href="javascript:guardarContenidoMultipleVersionadoAjax('nota_principal', 'slt_<?=$id_modulo?>_sel', 'modulo[nota_principal][<?=$id_modulo?>][columnas][0]', '<?=$rsNPVersion->field("version_nr")?>', '<?=$rsNPVersion->field("id_usuario")?>')"><img src="images/btn_guardar.gif" border="0" /></a>
                        </div>
                                                   
                        <div style="clear:both"></div>
                        
                        <? } ?>
                        
						<div>
                            <? if($tipo_modulo<>"nota_principal"){ ?>
                                <p class="arial12" style="float:left; margin:0; padding:0; ">Pos. <br/><span style="font-size:10px;">(Cant Notas)</span></p>
                                <input type="text" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh" style="float:left; margin:3px 5px 0 5px;">
                                <? } ?>
                                <p class="arial13" style="margin:3px 0 0 0; padding:0; float:left;"><strong>M&oacute;dulo <?=($tipo_modulo=="nota_principal"?"Notas Principales":$array_modulos[$valor1_modulo][$tipo_modulo])?></strong></p>


                                <? if($tipo_modulo<>"nota_principal"){ ?>
                                <div style="float:right;" ><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></figure>					  
                            <? }?>
                        </div>                      
                    
                    </div>
				</td>
			  </tr>
              
			  <tr>
				<td style="padding:5px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				    <td colspan="2">
				        <table>
                            <tr>
								<td class="arial12" style="padding:5px;">T&iacute;tulo</td>
								<td style="padding:5px;"><input  style="width:200px;" value="<?echo htmlentities($valor2_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][titulo]" ></td>
						  	</tr>   
				            <tr>
			                    <td width="50%" style="padding:5px" class="arial12">ID: 
			                        <input class="posicion_mh" style="width:55px" type="text" size="2" name="<?php echo $id_modulo . "_" . $tipo_modulo ?>_addById" id="contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>_addById"  />
			                    </td>
                                <td width="50%" align="left"><img src="images/btn_agregar.gif" onClick="optionAddById('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel', true);" border="0"></td>
                            </tr>  
				        </table>
				    </td>
				 </tr>
				 <tr>
					<td colspan="2">
                    <?php 
                    crear_selectores_contenido($tipo_modulo,$id_modulo);?>&nbsp;
					<img src="images/btn_agregar.gif" onClick="optionAdd('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel');" border="0">
					</td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" class="arial11"><table cellpadding="0" cellspacing="0">
				  <tbody>
					<tr>
					  <td>
					  <select size="10" class="multi_mh slt_seleccionar_todos" id="slt_<?=$id_modulo?>_sel" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][value][]" multiple="multiple" ondblclick="OpenPopUpEdit($('#slt_<?=$id_modulo?>_sel').val());">
						  <?php
						   $q = "SELECT 
									   C.id , 
									   C.titulo_home as titulo 
								FROM home_aux HA
								INNER JOIN contenidos C ON HA.valor1 = C.id
								WHERE HA.id_home = '".$id_modulo."'
								ORDER BY HA.orden";
							$recordset = $conn->getRecordset($q);
							
							for($i=0;$i<count($recordset);$i++){
								print("<option value='".$recordset[$i]['id']."'>".$recordset[$i]['id']." - ".$recordset[$i]['titulo']."</option>");
							}
							?>
						</select>
					  </td>
					  <td style="padding-left:5px;" valign="top">
					    <img src="images/delete-icon.png"  width="16" height="16"  style="cursor: pointer;" onClick="optionDelete('slt_<?=$id_modulo?>_sel');" border="0"> <br /><br />
						<img src="images/boton_arriba2.gif" style="cursor: pointer;" onClick="EscalarElemento('arriba','slt_<?=$id_modulo?>_sel');" border="0"> <br>
						<img src="images/boton_abajo2.gif" style="cursor: pointer;" onClick="EscalarElemento('abajo','slt_<?=$id_modulo?>_sel');" border="0" vspace="3"><br /><br />
						<?php if($tipo_modulo == "nota_principal"){

							$aux1 = $conn->execute("SELECT id FROM home WHERE tipo_modulo = 'cascada1' AND id_portal = 0");
							$v1 = $aux1->field("id");
							$aux2 = $conn->execute("SELECT id FROM home WHERE tipo_modulo = 'cascada2' AND id_portal = 0");
							$v2 = $aux2->field("id");
						?>
						<input type="button" class="comun" value="Pasar a Cascada Izq" onClick="optionChange('slt_<?=$id_modulo?>_sel','slt_<?=$v1?>_sel');" style="width:150px;" border="0">
						<br /><br />
						<input type="button" class="comun" value="Pasar a Cascada Der" onClick="optionChange('slt_<?=$id_modulo?>_sel','slt_<?=$v2?>_sel');" style="width:150px;" border="0">
						<?}?>
					  </td>
					</tr>
				  </tbody>
				</table>
				 <strong>&nbsp;Doble click</strong> para <strong>editar</strong> un elemento de la lista </td>
			  </tr>
			  <?php if($tipo_modulo == "nota_principal"){?>
				<tr>
						 <td style="padding:5px;" >
						 <strong>Columnas que ocupa: </strong>
						  <label><input type="radio" name="modulo[nota_principal][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "1" || empty($valor4_modulo)) ? 'checked="checked"':''?> value="1" /> 1 </label>
						  <label><input type="radio" name="modulo[nota_principal][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "2") ? 'checked="checked"':''?> value="2" /> 2 </label>
						  <label><input type="radio" name="modulo[nota_principal][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "3") ? 'checked="checked"':''?> value="3" /> 3 </label>
						</td>
						</tr>
				<?php } ?>
			</table>
			<?
			break;
		case "nota_menu":
			?>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td colspan="2" class="encabezado_mh">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="70%" class="arial13" style="padding-left:5px;"><strong>Nota Fixed Menu</strong></td>
					  </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
              	<?php 
              	    if(intval($valor2_modulo) > 0){
                    	$q = "SELECT id_categoria FROM contenidos WHERE id = {$valor2_modulo} LIMIT 1";
                    	$r = $conn->getRecordset($q);
                    }
                    $id_categoria_sel = intval($r[0]["id_categoria"]);
                 crear_selectores_contenido($tipo_modulo,$id_modulo, $valor2_modulo, $id_categoria_sel, true); ?>
				</td>
				<td width="5%">
				    <!--<img src="images/btn_editar.gif" style="cursor: pointer;" onClick="OpenPopUpEdit($('#contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>').val());" border="0">-->
                </td>
			  </tr>

			</table>
			<?
			break;
		case "modulosSimples":
			?>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td colspan="2" class="encabezado_mh">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="18%" class="arial12">Pos.<br/><span style="font-size:10px;">(Cant Notas)</span></td>
						<td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
						<td width="70%" class="arial13" style="padding-left:5px;"><strong><?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong></td>
						<td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					  </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
              	<?php 
              	    if(intval($valor2_modulo) > 0){
                    	$q = "SELECT id_categoria FROM contenidos WHERE id = {$valor2_modulo} LIMIT 1";
                    	$r = $conn->getRecordset($q);
                    }
                    $id_categoria_sel = intval($r[0]["id_categoria"]);
                 crear_selectores_contenido($tipo_modulo,$id_modulo, $valor2_modulo, $id_categoria_sel, true); ?>
				</td>
				<td width="5%">
				    <img src="images/btn_editar.gif" style="cursor: pointer;" onClick="OpenPopUpEdit($('#contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>').val());" border="0">
                </td>
			  </tr>

			</table>
			<?
			break;
		case "opinion":
		case "opinion2":
			?>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td colspan="2" class="encabezado_mh">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="18%" class="arial12">Pos.<br/><span style="font-size:10px;">(Cant Notas)</span></td>
						<td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
						<td width="70%" class="arial13" style="padding-left:5px;"><strong><?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong></td>
						<td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					  </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
              	<?php 
              	    if(intval($valor2_modulo) > 0){
                    	$q = "SELECT id_categoria FROM contenidos WHERE id = {$valor2_modulo} LIMIT 1";
                    	$r = $conn->getRecordset($q);
                    }
                    $id_categoria_sel = intval($r[0]["id_categoria"]);
                 crear_selectores_contenido($tipo_modulo,$id_modulo, $valor2_modulo, $id_categoria_sel, true); ?>
				</td>
				<td width="5%">
				    <img src="images/btn_editar.gif" style="cursor: pointer;" onClick="OpenPopUpEdit($('#contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>').val());" border="0">
                </td>
			  </tr>

			</table>
			<?
			break;
		case "banner_header":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
				<tr>
					<td class="encabezado_mh">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="70%" class="arial13" style="padding-left:5px;"><strong>Banner Header</strong></td>
								<td width="6%" align="right" class="arial12" style="padding-left:5px;">&nbsp;</td>
							</tr>
						</table>
					</td>
			  </tr>
			  <tr>
					<td style="padding:10px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" rows="4" style="width:100%;border-color:#AAAAAA" class="textarea_mh"><?=$valor3_modulo?></textarea></td>
			  </tr>
			</table>
			<?php 
				break;
		case "cita_textual_autor":
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="18%" class="arial12">Pos.<br/><span style="font-size:10px;">(Cant Notas)</span></td>
					  <td width="6%"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo <?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong></td>
					  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Autor:</td>
				<td style="padding:5px;"><input  style="width:200px;" value="<?echo htmlentities($valor2_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" ></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:200px;"value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Cita:</td>
				<td style="padding:5px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][2]" rows="4" class="textarea_mh"><?=$valor3_modulo?></textarea></td>
			  </tr>
			</table>
		
		<?
			break;	
		case "banner_saborido":
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="18%" class="arial12">Pos.<br/></td>
					  <td width="6%"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo <?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong></td>
					  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Fecha:</td>
				<td style="padding:5px;"><input  style="width:200px;" value="<?echo htmlentities($valor2_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" ></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Titulo:</td>
				<td style="padding:5px;"><input  style="width:200px;"value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Codigo Video:</td>
				<td style="padding:5px;"><input  style="width:200px;"value="<?echo htmlentities($valor3_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][2]" >
				</td>
			  </tr>
			</table>
		
		<?
		break;	
		case "especiales":
			list($volanta,$titulo) = explode("||",$valor2_modulo)
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>Especiales</strong></td>
					</tr>
				</table></td>
			  </tr>
			  <input type="hidden" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][5]" value="<?=$valor1_modulo?>" />
			  <tr>
				<td class="arial12" style="padding:5px;">Volanta</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($volanta);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" ></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Titulo:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($titulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Copete:</td>
				<td style="padding:5px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][2]" rows="4" class="textarea_mh" style="width:400px;"><?=$valor3_modulo?></textarea></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][3]" ></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Imagen:</td>
				<td style="padding:5px;">
	
				<?
					$f = $conn->getRecordset("SELECT * FROM home WHERE id = '".$id_modulo."'");
					$id_foto = $f[0]["id_portal"];
					$idHtml = 'fotoID'.$id_modulo;
					$image_name ="";
					if($id_foto>0)
					{
						// Quiere decir que hay algun numero, asi que lo mostramos
						$sql = "select advID,advLink,advTitulo from advf where advID = ".$id_foto;
						$rs = $conn->execute($sql);

						$image_name = $rs->field("advTitulo");
					}
				?>
					<input type='text' class='comun' name='<?=$idHtml?>_image' id='<?=$idHtml?>_image' readonly value='<?=$image_name;?>' style="width:200px;">				
					<input type='hidden' id="<?=$idHtml?>" name='modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][4]' value='<?=($id_foto>0?$id_foto:"")?>'>
					<a href="javascript: openMedia('<?=($id_foto>0)? $id_foto:"";?>','F','<?=$idHtml?>','0');"><img border="0" src="images/examinar.gif"></a>
					<a href="javascript: clearMedia('F','<?=$idHtml?>');"><img border="0" src="images/eliminar.gif"></a>
					<?
					if($id_foto>0)
					{
					?>
						<div id='preview_image_id_foto' name='preview_image_id_foto' style='padding:3px;'>
						<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
						</div>
					<?
					}
					?>

				</td>
			  </tr>
			</table>
		
		<?
		break;	
		case "especialesPortada":
			list($volanta,$titulo) = explode("||",$valor2_modulo)
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>Especiales</strong></td>
					   <td width="18%" class="arial12" align="right">Pos.</td>
					  <td width="6%" align="left"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					</tr>
				</table></td>
			  </tr>
			  <input type="hidden" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][5]" value="<?=$valor1_modulo?>" />
			  <tr>
				<td class="arial12" style="padding:5px;">Volanta</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($volanta);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" ></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Titulo:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($titulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Copete:</td>
				<td style="padding:5px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][2]" rows="4" class="textarea_mh" style="width:400px;"><?=$valor3_modulo?></textarea></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][3]" ></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Imagen:</td>
				<td style="padding:5px;">
	
				<?
					$f = $conn->getRecordset("SELECT * FROM home_especiales WHERE id = '".$id_modulo."'");
					$id_foto = $f[0]["id_portal"];
					$idHtml = 'fotoID'.$id_modulo;
					$image_name ="";
					if($id_foto>0)
					{
						// Quiere decir que hay algun numero, asi que lo mostramos
						$sql = "select advID,advLink,advTitulo from advf where advID = ".$id_foto;
						$rs = $conn->execute($sql);

						$image_name = $rs->field("advTitulo");
					}
				?>
					<input type='text' class='comun' name='<?=$idHtml?>_image' id='<?=$idHtml?>_image' readonly value='<?=$image_name;?>' style="width:200px;">				
					<input type='hidden' id="<?=$idHtml?>" name='modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][4]' value='<?=($id_foto>0?$id_foto:"")?>'>
					<a href="javascript: openMedia('<?=($id_foto>0)? $id_foto:"";?>','F','<?=$idHtml?>','0');"><img border="0" src="images/examinar.gif"></a>
					<a href="javascript: clearMedia('F','<?=$idHtml?>');"><img border="0" src="images/eliminar.gif"></a>
					<?
					if($id_foto>0)
					{
					?>
						<div id='preview_image_id_foto' name='preview_image_id_foto' style='padding:3px;'>
						<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
						</div>
					<?
					}
					?>

				</td>
			  </tr>
			</table>
		
		<?
		break;				
		case "cita_textual":	
		case "publicidad_1":
		case "publicidad_2":
		case "publicidad_3":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="18%" class="arial12">Pos.<br/><span style="font-size:10px;">(Cant Notas)</span></td>
					  <td width="6%"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo <?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong></td>
					  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" rows="4" class="textarea_mh"><?=$valor3_modulo?></textarea></td>
			  </tr>
			</table>
			<?
			break;
		case "dakar":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="6%" class="arial12">Pos.</td>
					  <td width="6%"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					  <td width="80%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo DAKAR</strong></td>
					  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
					<select id="multimedia_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
						<option value="0">Seleccione</option>
					  <?						
							$recordset	= $conn->Execute("
								SELECT 'V' AS tipo, id , CONCAT( titulo, ' ' ) AS titulo2, fecha  FROM videos WHERE activo = 'S' AND estado = 'A' AND link_sd <> ''
								ORDER BY fecha DESC
								LIMIT 80
							");
						
							while(!$recordset->eof) {
								$id_com		= $recordset->field('id');
								$tipo		= $recordset->field('tipo');
								$com_titulo	= $recordset->field('titulo2');
								?>
								<option value="<?=$id_com?>" <?=($id_com == $valor3_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
								<?
								$recordset->movenext();
							}
						?>
					</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "link_externo_1":
		case "link_externo_2":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="18%" class="arial12">Pos.<br/><span style="font-size:10px;">(Cant Notas)</span></td>
					  <td width="6%"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo <?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong></td>
					  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					</tr>
				</table></td>
			  </tr>

			  <tr>
				<td class="arial12" style="padding:5px;">Foto:</td>
				<td style="padding:5px;">								
								<?
									$advID = htmlentities($valor2_modulo);
									$idHtml = 'fotoID'.$id_modulo;
									$image_name ="";
									if($advID>0)
									{
										// Quiere decir que hay algun numero, asi que lo mostramos
										$sql = "select advID,advLink,advTitulo from advf where advID = ".$advID;
										$rs = $conn->execute($sql);

										$image_name = $rs->field("advTitulo");
									}
								?>
									<input type='text' class='comun' id='<?=$idHtml?>_image' name='<?=$idHtml?>_image' readonly value='<?=$image_name;?>' style="width:150px;">					
									<input type='hidden' id="<?=$idHtml?>" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" value='<?=$advID?>'>
									<a href="javascript: openMedia('<?=$advID?>','F','<?=$idHtml?>','0');"><img border="0" src="images/examinar.gif"></a>
									<a href="javascript: clearMedia('F','<?=$idHtml?>');"><img border="0" src="images/eliminar.gif"></a>
									<?php if($advID>0)
									{
									?>
									   <a href="javascript:void(0);" onclick='javascript:window.open("phpimageeditor/index.php?idFoto=<?=$advID?>&launcher=<?=$idHtml?>", "mywindow", "location=0,status=0,scrollbars=1,width=1200,height=800");';>
									   		<img src="images/btn_editar.gif" alt="" width="18" height="18" />
									   </a>
                                    	<div id='preview_image_<?=$idHtml?>' name='preview_image_<?=$idHtml?>' style='padding:3px;'>
										<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
										</div>
									<?
									}
									?></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:200px;"value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Target:</td>
				<td style="padding:5px;"><input  style="width:200px;"value="<?echo htmlentities($valor3_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][2]" ></td>
			  </tr>
			</table>
			<?
			break;
		case "horoscopo":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			 <tr>
				<td class="encabezado_mh" colspan="2">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="8%" class="arial12">Pos.</td>
						  <td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
						  <td width="80%" class="arial13" style="padding-left:5px;"><strong><?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong> </td>
						  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
						</tr>
					</table>
					<input type="hidden" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" value="1">
				</td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Imagen</td>
				<td style="padding:5px;">								
								<?
									$advID = htmlentities($valor2_modulo);
									$idHtml = 'fotoID'.$id_modulo;
									$image_name ="";
									if($advID>0)
									{
										// Quiere decir que hay algun numero, asi que lo mostramos
										$sql = "select advID,advLink,advTitulo from advf where advID = ".$advID;
										$rs = $conn->execute($sql);

										$image_name = $rs->field("advTitulo");
									}
								?>
									<input type='text' class='comun' id='<?=$idHtml?>_image' name='<?=$idHtml?>_image' readonly value='<?=$image_name;?>' style="width:150px;">					
									<input type='hidden' id="<?=$idHtml?>" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" value='<?=$advID?>'>
									<a href="javascript: openMedia('<?=$advID?>','F','<?=$idHtml?>','0');"><img border="0" src="images/examinar.gif"></a>
									<a href="javascript: clearMedia('F','<?=$idHtml?>');"><img border="0" src="images/eliminar.gif"></a>
									<?php if($advID>0)
									{
									?>
									   <a href="javascript:void(0);" onclick='javascript:window.open("phpimageeditor/index.php?idFoto=<?=$advID?>&launcher=<?=$idHtml?>", "mywindow", "location=0,status=0,scrollbars=1,width=1200,height=800");';>
									   		<img src="images/btn_editar.gif" alt="" width="18" height="18" />
									   </a>
                                    	<div id='preview_image_<?=$idHtml?>' name='preview_image_<?=$idHtml?>' style='padding:3px;'>
										<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
										</div>
									<?
									}
									?></td>
			  </tr>
			</table>
			<?
			break;
		case "bannerlinkinterno":
		case "bannerlinkexterno":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			 <tr>
				<td class="encabezado_mh" colspan="2">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="8%" class="arial12">Pos.</td>
						  <td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
						  <td width="80%" class="arial13" style="padding-left:5px;"><strong><?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong> </td>
						  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
						</tr>
					</table>
					<input type="hidden" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" value="1">
				</td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Imagen</td>
				<td style="padding:5px;">								
								<?
									$advID = htmlentities($valor2_modulo);
									$idHtml = 'fotoID'.$id_modulo;
									$image_name ="";
									if($advID>0)
									{
										// Quiere decir que hay algun numero, asi que lo mostramos
										$sql = "select advID,advLink,advTitulo from advf where advID = ".$advID;
										$rs = $conn->execute($sql);

										$image_name = $rs->field("advTitulo");
									}
								?>
									<input type='text' class='comun' id='<?=$idHtml?>_image' name='<?=$idHtml?>_image' readonly value='<?=$image_name;?>' style="width:150px;">					
									<input type='hidden' id="<?=$idHtml?>" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" value='<?=$advID?>'>
									<a href="javascript: openMedia('<?=$advID?>','F','<?=$idHtml?>','0');"><img border="0" src="images/examinar.gif"></a>
									<a href="javascript: clearMedia('F','<?=$idHtml?>');"><img border="0" src="images/eliminar.gif"></a>
									<?php if($advID>0)
									{
									?>
									   <a href="javascript:void(0);" onclick='javascript:window.open("phpimageeditor/index.php?idFoto=<?=$advID?>&launcher=<?=$idHtml?>", "mywindow", "location=0,status=0,scrollbars=1,width=1200,height=800");';>
									   		<img src="images/btn_editar.gif" alt="" width="18" height="18" />
									   </a>
                                    	<div id='preview_image_<?=$idHtml?>' name='preview_image_<?=$idHtml?>' style='padding:3px;'>
										<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
										</div>
									<?
									}
									?></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:200px;"value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
			  </tr>
			</table>
			<?
			break;
		case "bannerdestacado3columnas":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			 <tr>
				<td class="encabezado_mh" colspan="2">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="80%" class="arial13" style="padding-left:5px;"><strong>Banner destacado 3 columnas</strong> </td>
						</tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Imagen</td>
				<td style="padding:5px;">								
								<?
									$advID = htmlentities($valor2_modulo);
									$idHtml = 'fotoID'.$id_modulo;
									$image_name ="";
									if($advID>0)
									{
										// Quiere decir que hay algun numero, asi que lo mostramos
										$sql = "select advID,advLink,advTitulo from advf where advID = ".$advID;
										$rs = $conn->execute($sql);

										$image_name = $rs->field("advTitulo");
									}
								?>
									<input type='text' class='comun' id='<?=$idHtml?>_image' name='<?=$idHtml?>_image' readonly value='<?=$image_name;?>' style="width:350px;">					
									<input type='hidden' id="<?=$idHtml?>" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" value='<?=$advID?>'>
									<a href="javascript: openMedia('<?=$advID?>','F','<?=$idHtml?>','0');"><img border="0" src="images/examinar.gif"></a>
									<a href="javascript: clearMedia('F','<?=$idHtml?>');"><img border="0" src="images/eliminar.gif"></a>
									<?php if($advID>0)
									{
									?>
									   <a href="javascript:void(0);" onclick='javascript:window.open("phpimageeditor/index.php?idFoto=<?=$advID?>&launcher=<?=$idHtml?>", "mywindow", "location=0,status=0,scrollbars=1,width=1200,height=800");';>
									   		<img src="images/btn_editar.gif" alt="" width="18" height="18" />
									   </a>
                                    	<div id='preview_image_<?=$idHtml?>' name='preview_image_<?=$idHtml?>' style='padding:3px;'>
										<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
										</div>
									<?
									}
									?></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Posicion:</td>
				<td style="padding:5px;">
					<select name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][2]">
						<option value="1" <?=($valor3_modulo==1?"selected":"")?> >Arriba de modulo 3 columnas</option>
						<option value="2" <?=($valor3_modulo==2?"selected":"")?> >Abajo de modulo 3 columnas / arriba especial</option>
						<option value="3" <?=($valor3_modulo==3?"selected":"")?> >Abajo de modulo 3 columnas / abajo especial</option>
					<select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "minutoaminuto":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			 <tr>
				<td class="encabezado_mh" colspan="2">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="80%" class="arial13" style="padding-left:5px;"><strong>Minuto a minuto</strong> </td>
						</tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Activo:</td>
				<td style="padding:5px;">
					<select name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][1]">
						<option value="N" <?=($valor4_modulo=="N"?"selected":"")?> >No</option>
						<option value="S" <?=($valor4_modulo=="S"?"selected":"")?> >Si</option>
					<select>
				</td>
			  </tr>
			  <tr>
				<td  class="arial12" style="padding:5px;">Posicion:</td>
				<td style="padding:5px;">
					<select name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][2]">
						<option value="1" <?=($valor3_modulo==1?"selected":"")?> >Arriba de todo</option>
						<option value="2" <?=($valor3_modulo==2?"selected":"")?> >Arriba de modulo 3 columnas</option>
						<option value="3" <?=($valor3_modulo==3?"selected":"")?> >Abajo de modulo 3 columnas</option>
					<select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "especial_horizontal":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Lista Destacada (Especial Horizontal)</strong></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="humor_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
					<option value="0">Seleccione</option>
					<?						
						$recordset	= $conn->Execute("
							SELECT id , CONCAT( volanta, ' ', titulo ) AS titulo2  FROM listas_destacadas WHERE activo = 'S' AND estado = 'A' 
							ORDER BY id DESC
							LIMIT 100
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$id_com?>" <?=($id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "especial_home_destacado":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Modulo destacado Especial</strong></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="humor_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
					<option value="0">Seleccione</option>
					<?						
						$recordset	= $conn->Execute("
							SELECT id , titulo  AS titulo2  FROM destacados_especiales_home WHERE activo = 'S' AND estado = 'A' 
							ORDER BY id DESC
							LIMIT 30
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$id_com?>" <?=($id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "especial_23":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo HTML Destacado</strong></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" rows="4" class="textarea_mh" style="width:500px;height:75px;"><?=$valor3_modulo?></textarea></td>
			  </tr>
			  <tr>
				 <td style="padding:5px;" >
				 <strong>Columnas que ocupa: </strong>
				  <label><input type="radio" name="modulo[especial_23][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "1" || empty($valor4_modulo)) ? 'checked="checked"':''?> value="1" /> 1 </label>
				  <label><input type="radio" name="modulo[especial_23][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "2") ? 'checked="checked"':''?> value="2" /> 2 </label>
				  <label><input type="radio" name="modulo[especial_23][<?php echo $id_modulo?>][columnas][0]" <?=($valor4_modulo == "3") ? 'checked="checked"':''?> value="3" /> 3 </label>
				</td>
				</tr>
			</table>
			<?
			break;
		case "vivo":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="80%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo En Vivo</strong></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" valign="top">
					<table width="100%">
						<tr>
							<td class="arial12" valign="top" style="padding-right:25px;">
								Activo <br /><br />
								<select name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][activo][0]">
									<option value="N" <?=($valor2_modulo=="N"?" selected":"")?>>No</option>
									<option value="S" <?=($valor2_modulo=="S"?" selected":"")?>>Si (cerrado)</option>
									<option value="A" <?=($valor2_modulo=="A"?" selected":"")?>>Si (abierto)</option>
								</select>
							</td>
							<td>
								<span class="arial12">Titulo: </span><input style="width:500px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ><br />
								<span class="arial12">Embed: </span><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" rows="4" class="textarea_mh" style="width:900px;height:50px;"><?=$valor3_modulo?></textarea>
							</td>
						</tr>
					</table>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "vivomundial":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="80%" class="arial13" style="padding-left:5px;"><strong>M&oacute;dulo En Vivo Mundial</strong></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" valign="top">
					<table width="100%">
						<tr>
							<td class="arial12" valign="top" style="padding-right:25px;">
								Activo <br />
								<select name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][activo][0]">
									<option value="N" <?=($valor2_modulo=="N"?" selected":"")?>>No</option>
									<option value="S" <?=($valor2_modulo=="S"?" selected":"")?>>Si)</option>
								</select>
							</td>
							<td>
								<span class="arial12">Titulo: </span><input style="width:500px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ><br />
							</td>
						</tr>
					</table>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "cablera_horizontal":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="80%" class="arial13" style="padding-left:5px;"><strong>Cablera Horizontal</strong></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" valign="top">
					<table width="100%">
						<tr>
							<td class="arial12" valign="top" style="padding-right:25px;">
								Activa&nbsp;&nbsp;
								<select name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][activo][0]">
									<option value="S" <?=($valor2_modulo=="S"?" selected":"")?>>Si</option>
									<option value="N" <?=($valor2_modulo=="N"?" selected":"")?>>No</option>
								</select>
							</td>
						</tr>
					</table>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "duelo":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="80%" class="arial13" style="padding-left:5px;"><strong>Luto</strong></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" valign="top">
					<table width="100%">
						<tr>
							<td class="arial12" valign="top" style="padding-right:25px;">
								Activo&nbsp;&nbsp;
								<select name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][activo][0]">
									<option value="N" <?=($valor2_modulo=="N"?" selected":"")?>>No</option>
									<option value="S" <?=($valor2_modulo=="S"?" selected":"")?>>Si</option>						
								</select>
							</td>
						</tr>
					</table>
				</td>
			  </tr>
			</table>
			<?
			break;			
		case "vivoTV":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="80%" class="arial13" style="padding-left:5px;"><strong>Vivo TV</strong></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" valign="top">
					<table width="100%">
						<tr>
							<td class="arial12" valign="top" style="padding-right:25px;">
								Activo&nbsp;&nbsp;
								<select name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][activo][0]">
									<option value="N" <?=($valor2_modulo=="N"?" selected":"")?>>No</option>
									<option value="S" <?=($valor2_modulo=="S"?" selected":"")?>>Si</option>						
								</select>
							</td>
						</tr>
					</table>
				</td>
			  </tr>
			</table>
			<?
			break;											
		case "cablera_vertical":
		case "agenda":
		case "video":
		case "fotos":
		case "loteria":
		case "memoria":
		case "vuelos":
		case "facebook":
        case "twitter":
		case "audios":
		case "estadisticas_futbol":
		case "resultados":
		case "indices_economia":
		case "top5":
		case "cartelera":
		case "minuto_a_minuto":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="8%" class="arial12">Pos.</td>
					  <td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					  <td width="80%" class="arial13" style="padding-left:5px;"><strong><?php echo $array_modulos[$valor1_modulo][$tipo_modulo]?></strong> </td>
					  <td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					</tr>
				</table>
				<input type="hidden" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" value="1">
				</td>
			  </tr>
			</table>
			<?
			break;
		case "video":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="8%" class="arial12">Pos.</td>
					<td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Video Home</strong></td>
					<td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="video_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
				  <?						
						$recordset	= $conn->Execute("
							SELECT id , titulo AS titulo2  FROM videos WHERE activo = 'S' AND estado = 'A'
							ORDER BY fecha DESC, id DESC
							LIMIT 100
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$id_com?>" <?=($id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "fotodeldia":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Foto del dia</strong></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="fotodeldia_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
				  <?						
						$recordset	= $conn->Execute("
							SELECT id , CONCAT(titulo, ' ',DATE_FORMAT(fecha,'%d/%m/%Y')) AS titulo2  FROM fotodeldia WHERE activo = 'S' AND estado = 'A'
							ORDER BY fecha DESC, id DESC
							LIMIT 50
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$id_com?>" <?=($id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "humor":
		case "humor_3":
		case "humor_wide":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<?if($tipo_modulo<>"humor_wide"){?>
					<td width="8%" class="arial12">Pos.</td>
					<td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					<?}?>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Humor <?if($tipo_modulo=="humor_wide"){?>3 Columnas<?}?></strong></td>
					<?if($tipo_modulo<>"humor_wide"){?>
					<td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
					<?}?>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="humor_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
					<option value="0">Seleccione</option>
					<?						
						if($tipo_modulo=="humor" || $tipo_modulo=="humor_3") $id_tira = 1;
						if($tipo_modulo=="humor_wide") $id_tira = 2;

						$recordset	= $conn->Execute("
							SELECT id , CONCAT( titulo, ' ', DATE_FORMAT(fecha,'%d-%m-%Y') ) AS titulo2  FROM humor WHERE activo = 'S' AND estado = 'A' AND id_tira = '".$id_tira."'
							ORDER BY fecha DESC, id DESC
							LIMIT 30
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$id_com?>" <?=($id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "suplementos":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="8%" class="arial12">Pos.</td>
					<td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Suplementos</strong></td>
					<td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="videos_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
					<option value="1" <?=(1 == $valor2_modulo?" selected ":"")?>>Reporte Nacional</option>
					<option value="2" <?=(2 == $valor2_modulo?" selected ":"")?>>Suplemento Literario</option>
					<option value="3" <?=(3 == $valor2_modulo?" selected ":"")?> >Historietas Nacionales</option>
					<option value="3" <?=(4 == $valor2_modulo?" selected ":"")?> >Mundial</option>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "videos_destacados":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="8%" class="arial12">Pos.</td>
					<td width="6%"><input type="text" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Videos destacados</strong></td>
					<td width="6%" align="right" class="arial12" style="padding-left:5px;"><a href="javascript:eliminar(<?=$id_modulo?>);"><img src="images/eliminar.gif" width="16" height="16" border="0"></a></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="videos_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
					<option value="15" <?=(15 == $valor2_modulo?" selected ":"")?>>Noticias</option>
					<option value="17" <?=(17 == $valor2_modulo?" selected ":"")?>>Guias</option>
					<option value="1" <?=(1 == $valor2_modulo?" selected ":"")?> >Columnas</option>	
					<option value="18" <?=(18 == $valor2_modulo?" selected ":"")?> >Recuerdos infancia</option>	
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "transito":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Tránsito</strong></td>	
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="humor_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
					<option value="0">Seleccione</option>
					<?						

						$recordset	= $conn->Execute("
							SELECT id , CONCAT( titulo, ' ', DATE_FORMAT(hora,'%d-%m-%Y') ) AS titulo2  FROM alertas_transito WHERE activo = 'S' AND estado = 'A' 
							ORDER BY hora DESC, id DESC
							LIMIT 30
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$id_com?>" <?=($id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "multimedia_home_izq":
		case "multimedia_home_der":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="8%" class="arial12"></td>
					<td width="6%"></td>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>No te lo pierdas - Multimedia</strong></td>
					<td width="6%" align="right" class="arial12" style="padding-left:5px;"></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="multimedia_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
				  <?						
						$recordset	= $conn->Execute("
							SELECT 'V' AS tipo, id , CONCAT( titulo, ' VIDEO' ) AS titulo2, fecha  FROM videos WHERE activo = 'S' AND estado = 'A' 
							UNION
							SELECT 'G' AS tipo, id , CONCAT( titulo, ' GALERIA' ) AS titulo2, fecha  FROM galerias WHERE activo = 'S' AND estado = 'A'
							ORDER BY fecha DESC
							LIMIT 120
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$tipo		= $recordset->field('tipo');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$tipo?>|<?=$id_com?>" <?=($tipo."|".$id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
		case "videodestacado_izq":
		case "videodestacado_der":
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="8%" class="arial12"></td>
					<td width="6%"></td>
					<td width="80%" class="arial13" style="padding-left:5px;"><strong>Videos Destacados</strong></td>
					<td width="6%" align="right" class="arial12" style="padding-left:5px;"></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<select id="multimedia_<?=$id_modulo?>" name="modulo[<?=$tipo_modulo?>][<?=$id_modulo?>][value][0]" class="combo_mh">
						<option value="0">Seleccione</option>
				  <?						
						$recordset	= $conn->Execute("
							SELECT 'V' AS tipo, id , CONCAT( titulo, ' ' ) AS titulo2, fecha  FROM videos WHERE activo = 'S' AND estado = 'A' AND link_sd <> ''
							ORDER BY fecha DESC
							LIMIT 80
						");
					
						while(!$recordset->eof) {
							$id_com		= $recordset->field('id');
							$tipo		= $recordset->field('tipo');
							$com_titulo	= $recordset->field('titulo2');
							?>
							<option value="<?=$id_com?>" <?=($id_com == $valor2_modulo?" selected ":"")?>><?=$id_com . ". " . $com_titulo?></option>
							<?
							$recordset->movenext();
						}
					?>
				</select>
				</td>
			  </tr>
			</table>
			<?
			break;
        case "ultimo_momento":
        	?>
            <table width="45%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
              <tr>
                <td class="encabezado_mh" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                       <tr>
                            <td width="94%" class="arial13" style="padding-left:5px;"><strong>Ultimo momento</strong></td>
                            <td width="6%" align="right" class="arial12" style="padding-left:5px;">&nbsp;</td>
                        </tr>
                    </table>
                </td>
              </tr>
              <tr>
                <td style="padding:5px;">
					<input type="text" value="<?echo htmlentities($valor3_modulo);?>" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" class="position_mh" class="arial12" style="width:450px">
				</td>
				<td>
					<select name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][activo][0]">
						<option value="AHORA" <?=($valor2_modulo=="AHORA"?" selected":"")?>>Ahora</option>
						<option value="ATENCION" <?=($valor2_modulo=="ATENCION"?" selected":"")?>>Atencion</option>
						<option value="ALERTA" <?=($valor2_modulo=="ALERTA"?" selected":"")?>>Alerta</option>
					</select>
				</td>
              </tr>
            </table>
        	<?php 
        	break;
        case "nota_header":
      		?>
        	<table width="45%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
        		<tr>
        	    	<td class="encabezado_mh" colspan="2">
        	        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        	            	<tr>
        	                	<td width="94%" class="arial13" style="padding-left:5px;"><strong>Nota header</strong></td>
        	                    	<td width="6%" align="right" class="arial12" style="padding-left:5px;">&nbsp;</td>
        	               	</tr>
        	            </table>
        	        </td>
        	    </tr>
        	    <tr>
        	    	<td class="arial12" style="padding:5px;">T&iacute;tulo:</td>
        	    	<td style="padding:5px;">
        	    		<input type="text" value="<?echo htmlentities($valor2_modulo);?>" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" class="position_mh" class="arial12" style="width:560px">
        	    	</td>
        		</tr>
        	    <tr>
        	    	<td class="arial12" style="padding:5px;">Link:</td>
        	    	<td style="padding:5px;">
        	    	<input type="text" value="<?echo htmlentities($valor4_modulo);?>" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" class="position_mh" class="arial12" style="width:560px"></td>
        		</tr>
        	</table>
        	<?php 
        	break;
        case "cascada1":
		case "cascada2":
			?>
			  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh">
                
                <div>
					  <?
					  $rsCacadaVersion = $conn->execute("SELECT version_nr, version_fecha_hora, CONCAT(HOUR(version_fecha_hora), ':', MINUTE(version_fecha_hora)) AS hora, id_usuario, usuario 
									        			  FROM home_version AS hv
									        			  INNER JOIN admin_usuarios AS au ON hv.id_usuario = au.id
									        			  WHERE module='$tipo_modulo'");
					  ?>                
					  <p style="float:left; margin:0 0 5px 0;" class="arial12"  id="spnVersion_<?=$tipo_modulo?>">
					  	Ultima versión: <?=$rsCacadaVersion->field("version_nr")?> (por <?=$rsCacadaVersion->field("usuario")?> el <?=mysql_a_normal($rsCacadaVersion->field("version_fecha_hora"))?> a las <?=$rsCacadaVersion->field("hora")?>)
					  </p> 
                      
                      <div style="clear:both"></div>               
                
					  <p class="arial13" style="float:left; margin:0; padding:0;"><strong>M&oacute;dulo Notas Cascada</strong></p>
					  <div style="float:right;"><a href="javascript:guardarContenidoMultipleVersionadoAjax('<?=$tipo_modulo?>', 'slt_<?=$id_modulo?>_sel', '', '<?=$rsCacadaVersion->field("version_nr")?>', '<?=$rsCacadaVersion->field("id_usuario")?>')"><img src="images/btn_guardar.gif" border="0" /></a></div>

				</div>
                
                </td>
			  </tr>
			  <tr>
				<td style="padding:5px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				    <td colspan="2">
				        <table>
				            <tr>
			                    <td width="50%" style="padding:5px" class="arial12">ID: 
			                        <input class="posicion_mh" style="width:55px" type="text" size="2" name="<?php echo $id_modulo . "_" . $tipo_modulo ?>_addById" id="contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>_addById"  />
			                    </td>
                                <td width="50%" align="left"><img src="images/btn_agregar.gif" onClick="optionAddById('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel', true);" border="0"></td>
                            </tr>     
				        </table>
				    </td>
				 </tr>
				 <tr>
					<td width="91%">
                    <?php 
                    crear_selectores_contenido($tipo_modulo,$id_modulo);?>
                    </td>
					<td width="9%"><img src="images/btn_agregar.gif" onClick="optionAdd('contenidos_<?=$id_modulo?>_<?=$tipo_modulo?>','slt_<?=$id_modulo?>_sel');" border="0"></td>
				  </tr>
				</table></td>
			  </tr>
			  <tr>
				<td style="padding:5px;" class="arial11"><table cellpadding="0" cellspacing="0">
				  <tbody>
					<tr>
					  <td>
					  <select size="20" class="multi_mh slt_seleccionar_todos" id="slt_<?=$id_modulo?>_sel" name="modulo[<?=$tipo_modulo?>][<?php echo $id_modulo ?>][value][]" multiple="multiple" ondblclick="OpenPopUpEdit($('#slt_<?=$id_modulo?>_sel').val());">
						  <?php
						   $q = "SELECT 
									   C.id , 
									   C.titulo_home as titulo 
								FROM home_aux HA
								INNER JOIN contenidos C ON HA.valor1 = C.id
								WHERE HA.id_home = '".$id_modulo."'
								ORDER BY HA.orden";
							$recordset = $conn->getRecordset($q);
							
							for($i=0;$i<count($recordset);$i++){
								print("<option value='".$recordset[$i]['id']."'>".$recordset[$i]['id']." - ".$recordset[$i]['titulo']."</option>");
							}
							?>
						</select>
					  </td>
					  <td style="padding-left:5px;" valign="top">
						<?
							$aux1 = $conn->execute("SELECT id FROM home WHERE tipo_modulo = 'cascada1' AND id_portal = 0");
							$v1 = $aux1->field("id");
							$aux2 = $conn->execute("SELECT id FROM home WHERE tipo_modulo = 'cascada2' AND id_portal = 0");
							$v2 = $aux2->field("id");

						?>
						<img src="images/flechadoble.png" style="cursor: pointer;" width="20" height="20" onClick="optionChange('slt_<?=($tipo_modulo=="cascada1"?$v1:$v2)?>_sel','slt_<?=($tipo_modulo=="cascada1"?$v2:$v1)?>_sel');" border="0">
						<br /><br />				    
						<img src="images/delete-icon.png"  width="16" height="16" style="cursor: pointer;" onClick="optionDelete('slt_<?=$id_modulo?>_sel');" border="0">
						<br /><br />
						<img src="images/boton_arriba2.gif" style="cursor: pointer;" onClick="EscalarElemento('arriba','slt_<?=$id_modulo?>_sel');" border="0"> <br>
						<img src="images/boton_abajo2.gif" style="cursor: pointer;" onClick="EscalarElemento('abajo','slt_<?=$id_modulo?>_sel');" border="0" vspace="3"> 
						<br />

					</td>
					</tr>
				  </tbody>
				</table>
				 <strong>&nbsp;Doble click</strong> para <strong>editar</strong> un elemento de la lista </td>
			  </tr>
			</table>
			<?
			break;
        default:
	     echo "<strong>Error al cargar el modulo.</strong>";
        break;
	}
}

function GetContenidosAdminHomePrincipal($total = false, $limit = 0, $offset = 0){
	global $conn;
	if(!$total){
		$sLimit = " LIMIT 10 OFFSET 0";
		
	}
	$q = "SELECT id, titulo FROM contenidos WHERE titulo != '' {$sLimit}";
	$r = $conn->getRecordset($q);
	return $r;
}

function AgregarModuloColumnaHome($tipoModulo, $columna = 1){
	global $conn;
	
	
	$orden = 0;
	$orden2 = 1;
	$aux = $conn->execute("SELECT MAX(orden) AS maximo FROM home WHERE valor1_modulo = '".intval($columna)."'");
	$orden = $aux->field("maximo") + 10;
			
	/*$aux2 = $conn->execute("SELECT MAX(valor1_modulo) AS maximo FROM home WHERE tipo_modulo = '".$tipoModulo."'");
	$orden2 = $aux2->field("maximo") + 1;*/
	$orden2="";
	
	$q = "INSERT INTO home(tipo_modulo,valor1_modulo,valor2_modulo,orden) VALUES('".$tipoModulo."','".intval($columna)."','".$orden2."', '".$orden."')";
	$conn->execute($q);
}

function GuardarNotaPrincipalAdminHome($colspan , $id_contenido, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = 'nota_principal', valor1_modulo = '0', valor4_modulo = '{$colspan}', valor2_modulo = '{$id_contenido}', orden = '0', id_portal = '".$id_portal."'";
	$conn->execute($q);
}

function GuardarVivoAdminHome($activo , $titulo,  $id_contenido, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = 'vivo', valor1_modulo = 0,  valor2_modulo = '".$activo."', valor3_modulo = '{$id_contenido}', valor4_modulo = '{$titulo}', orden = '0', id_portal = '".$id_portal."'";
	$conn->execute($q);
}
function GuardarVivoMundialAdminHome($activo , $titulo,  $id_contenido, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = 'vivomundial', valor1_modulo = 0,  valor2_modulo = '".$activo."', valor3_modulo = '{$id_contenido}', valor4_modulo = '{$titulo}', orden = '0', id_portal = '".$id_portal."'";
	$conn->execute($q);
}
function GuardarDueloAdminHome($activo , $titulo,  $id_contenido, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = 'duelo', valor1_modulo = 0,  valor2_modulo = '".$activo."', valor3_modulo = '{$id_contenido}', valor4_modulo = '{$titulo}', orden = '0', id_portal = '".$id_portal."'";
	$conn->execute($q);
}
function GuardarVivoTVAdminHome($activo , $titulo,  $id_contenido, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = 'vivoTV', valor1_modulo = 0,  valor2_modulo = '".$activo."', valor3_modulo = '{$id_contenido}', valor4_modulo = '{$titulo}', orden = '0', id_portal = '".$id_portal."'";
	$conn->execute($q);
}
function GuardarCableraAdminHome($activo , $titulo,  $id_contenido, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = 'cablera_horizontal', valor1_modulo = 0,  valor2_modulo = '".$activo."', valor3_modulo = '{$id_contenido}', valor4_modulo = '{$titulo}', orden = '0', id_portal = '".$id_portal."'";
	$conn->execute($q);
}
function GuardarContenidoSimpleAdminHome($tipo_modulo, $columna , $id_contenido, $orden, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$id_contenido}', orden = '{$orden}', id_portal = '".$id_portal."'";
	$conn->execute($q);
}

function GuardarContenidoSimpleAdminHomeExtra($tipo_modulo, $columna , $id_contenido, $valor4_modulo, $orden, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$id_contenido}', valor4_modulo = '{$valor4_modulo}', orden = '{$orden}', id_portal = '".$id_portal."'";
	$conn->execute($q);
}
function GuardarContenidoNotaDoble($tipo_modulo, $columna , $id_contenido, $valor3_modulo, $valor4_modulo, $orden, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$id_contenido}', valor3_modulo = '{$valor3_modulo}', valor4_modulo = '{$valor4_modulo}', orden = '{$orden}', id_portal = '".$id_portal."'";
	$conn->execute($q);
}
function GuardarContenidosMultiplesAdminHome($tipo_modulo,$columna,$ids_contenidos, $orden , $id_portal, $valor4=""){
	global $conn;
	$cant = count($ids_contenidos);

	$q = "INSERT INTO home SET tipo_modulo = '$tipo_modulo' , valor1_modulo = '{$columna}', valor3_modulo = '".$cant."', valor4_modulo = '".$valor4."', orden = '".intval($orden)."', id_portal = '".$id_portal."'";
	$conn->execute($q);
	$ultimo_id = $conn->UltimoId();
	for($i=0; $i <count($ids_contenidos);$i++){
		$q = "INSERT INTO home_aux SET id_home = {$ultimo_id}, valor1 = '".$ids_contenidos[$i]."', orden = '".($i+1)."'";
		$conn->execute($q);
	}
}

function GuardarContenidosMultiplesAdminHomeConTitulo($tipo_modulo,$columna,$ids_contenidos, $orden , $id_portal, $valor4="", $valor2=""){
	global $conn;
	$cant = count($ids_contenidos);

	$q = "INSERT INTO home SET tipo_modulo = '$tipo_modulo' , valor1_modulo = '{$columna}', valor2_modulo = '".$valor2."',  valor3_modulo = '".$cant."', valor4_modulo = '".$valor4."', orden = '".intval($orden)."', id_portal = '".$id_portal."'";
	$conn->execute($q);
	$ultimo_id = $conn->UltimoId();
	for($i=0; $i <count($ids_contenidos);$i++){
		$q = "INSERT INTO home_aux SET id_home = {$ultimo_id}, valor1 = '".$ids_contenidos[$i]."', orden = '".($i+1)."'";
		$conn->execute($q);
	}
}


function GuardarContenidosMultiplesCascadaAdminHome($tipo_modulo,$columna,$ids_contenidos, $orden , $id_portal, $valor4=""){
	global $conn;
	$cant = count($ids_contenidos);

	$q = "SELECT id FROM home WHERE tipo_modulo = '$tipo_modulo' AND id_portal = 0";
	$aux = $conn->execute($q);
	$ultimo_id = $aux->field("id");

	$conn->execute("DELETE FROM home_aux WHERE id_home = '".$ultimo_id."'");

	for($i=0; $i <count($ids_contenidos);$i++){
		$q = "INSERT INTO home_aux SET id_home = {$ultimo_id}, valor1 = '".$ids_contenidos[$i]."', orden = '".($i+1)."'";
		$conn->execute($q);
	}
}

function GuardarContenidoTextoAdminHome($tipo_modulo, $columna , $texto , $orden, $id_portal, $columnas=0){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor3_modulo = '".$texto."', valor4_modulo = '".$columnas."', orden = '{$orden}', id_portal = '".$id_portal."'";
	$conn->execute($q);
}

function GuardarContenidoUMAdminHome($tipo_modulo, $columna , $texto, $tipo , $orden, $id_portal, $columnas=0){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '".$tipo."', valor3_modulo = '".$texto."', valor4_modulo = '".$columnas."', orden = '{$orden}', id_portal = '".$id_portal."'";
	$conn->execute($q);
}

function GuardarNotaHeaderAdminHome($tipo_modulo, $texto, $link ){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '0', valor2_modulo = '".$texto."', valor4_modulo = '".$link."', orden = '0', id_portal = '0'";
	$conn->execute($q);
}
function GuardarCitaTextualAutor($tipo_modulo, $columna, $cita, $autor, $link, $orden){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$autor}', valor3_modulo = '{$cita}', valor4_modulo = '{$link}' , orden = '{$orden}', id_portal = 0";
	$conn->execute($q);

}
function GuardarEspeciales($tipo_modulo, $columna, $volanta, $titulo, $copete, $link, $imagen){
	global $conn;
	$valor2 = $volanta."||".$titulo;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$valor2}', valor3_modulo = '{$copete}', valor4_modulo = '{$link}' , orden = '{$orden}', id_portal = '".$imagen."'";
	$conn->execute($q);

}

function GuardarEspecialesEspecial($tipo_modulo, $columna, $volanta, $titulo, $copete, $link, $imagen, $orden){
	global $conn;
	$valor2 = $volanta."||".$titulo;
	$q = "INSERT INTO home_especiales SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$valor2}', valor3_modulo = '{$copete}', valor4_modulo = '{$link}' , orden = '{$orden}', id_portal = '".$imagen."'";
	$conn->execute($q);

}

function GuardarContenidoSimpleAdminHomeEspecial($tipo_modulo, $columna , $id_contenido, $orden, $id_portal){
	global $conn;
	$q = "INSERT INTO home_especiales SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$id_contenido}', orden = '{$orden}', id_portal = '".$id_portal."'";
	$conn->execute($q);
}

function GetNumeroColumna($nombre_modulo) {
      global $array_modulos;
      for($i=1;$i<=3;$i++){
      	foreach($array_modulos[$i] as $key => $value){
      		if($key == $nombre_modulo){
      			return $i;
      		}
      	}
      }
  return null;
}

// Borrowed from the PHP Manual user notes. Convert entities, while
// preserving already-encoded entities:

/*
function GetAjaxContenidoPorId($id = 0){
    global $conn;
    $id = intval($id);
    $q = "SELECT id, titulo FROM contenidos WHERE id = '{$id}' AND activo = 'S' LIMIT 1";
    $r = $conn->getRecordset($q);
    return $r[0];
}*/