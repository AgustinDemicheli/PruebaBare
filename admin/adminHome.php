<?
include_once("../includes/DB_Conectar.php"); 
include_once("../includes/lib/auth.php");

function GuardarEspeciales($tipo_modulo, $columna, $volanta, $titulo, $copete, $link, $imagen){
	global $conn;
	$valor2 = $volanta."||".$titulo;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$valor2}', valor3_modulo = '{$copete}', valor4_modulo = '{$link}' , orden = '{$orden}', id_portal = '".$imagen."'";
	$conn->execute($q);

}

function GuardarEspecialesEspecial($tipo_modulo, $columna, $volanta, $titulo, $copete, $link, $imagen, $orden){
	global $conn;
	//$valor2 = $volanta."||".$titulo;
	$valor2 = $titulo;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$valor2}', valor3_modulo = '{$copete}', valor4_modulo = '{$link}' , orden = '{$orden}', id_portal = '".$imagen."'";
	$conn->execute($q);

}

function GuardarContenidoSimpleAdminHomeEspecial($tipo_modulo, $columna , $id_contenido, $orden, $id_portal){
	global $conn;
	$q = "INSERT INTO home SET tipo_modulo = '{$tipo_modulo}', valor1_modulo = '{$columna}', valor2_modulo = '{$id_contenido}', orden = '{$orden}', id_portal = '".$id_portal."'";
	$conn->execute($q);
}

function crear_elemento($id_modulo, $tipo_modulo, $valor1_modulo , $valor2_modulo , $valor3_modulo, $valor4_modulo, $orden)
{
	global $conn, $array_modulos; 
	global $NotasSeleccionadas;

	switch($tipo_modulo)
	{
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
			  <!--
			  <tr>
				<td class="arial12" style="padding:5px;">Volanta</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($volanta);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" ></td>
			  </tr>
			  -->
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
			//list($volanta,$titulo) = explode("||",$valor2_modulo);
			$titulo = $valor2_modulo;
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="4">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>Slider Home</strong></td>
					   <td width="18%" class="arial12" align="right">Pos.</td>
					  <td width="6%" align="left"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					</tr>
				</table>
				</td>
			  </tr>
			  <input type="hidden" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][5]" value="<?=$valor1_modulo?>" />
			  <!--
			  <tr>
				<td class="arial12" style="padding:5px;">Volanta</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($volanta);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][0]" ></td>
			  </tr>
			  -->
			  <tr>
				<td  class="arial12" style="padding:5px;">Titulo:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($titulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
				<td class="arial12" style="padding:5px;">Copete:</td>
				<td style="padding:5px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][2]" rows="4" class="textarea_mh" style="width:400px;"><?=$valor3_modulo?></textarea></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][3]" ></td>
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

		case "especialesModulo":
			//list($volanta,$titulo) = explode("||",$valor2_modulo);
			$titulo = $valor2_modulo;
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="4">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>Modulo Sextuple</strong></td>
					   <td width="18%" class="arial12" align="right">Pos.</td>
					  <td width="6%" align="left"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					</tr>
				</table>
				</td>
			  </tr>
			  <input type="hidden" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][5]" value="<?=$valor1_modulo?>" />
			  <tr>
				<td  class="arial12" style="padding:5px;">Titulo:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($titulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
				<td class="arial12" style="padding:5px;">Copete:</td>
				<td style="padding:5px;"><textarea name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][2]" rows="4" class="textarea_mh" style="width:400px;"><?=$valor3_modulo?></textarea></td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][3]" ></td>
				<td class="arial12" style="padding:5px;">&nbsp;</td>
				<td style="padding:5px;">
				&nbsp;
				</td>
			  </tr>
			</table>
		
		<?
		break;			


		case "especialesAcceso":
			//list($volanta,$titulo) = explode("||",$valor2_modulo);
			$titulo = $valor2_modulo;
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border: 2px solid #CCCCCC;">
			  <tr>
				<td class="encabezado_mh" colspan="4">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="70%" class="arial13" style="padding-left:5px;"><strong>Accesos rapidos</strong></td>
					   <td width="18%" class="arial12" align="right">Pos.</td>
					  <td width="6%" align="left"><input type="text"name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][orden]" value="<?=$orden?>" class="posicion_mh"></td>
					</tr>
				</table>
				</td>
			  </tr>
			  <input type="hidden" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][5]" value="<?=$valor1_modulo?>" />
			  <tr>
				<td  class="arial12" style="padding:5px;">Titulo:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($titulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][1]" ></td>
				<td class="arial12" style="padding:5px;">&nbsp;</td>
				<td style="padding:5px;">&nbsp;</td>
			  </tr>
			  <tr>
				<td class="arial12" style="padding:5px;">Link:</td>
				<td style="padding:5px;"><input  style="width:400px;" value="<?echo htmlentities($valor4_modulo);?>" type="text" name="modulo[<?php echo $tipo_modulo ?>][<?php echo $id_modulo ?>][value][3]" ></td>
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

        default:
	     echo "<strong>Error al cargar el modulo.</strong>";
        break;
	}
}
 
if( count($_POST) > 0 )
{
	//echo "<pre>";print_r($_POST);echo "</pre>";die();
	if(intval($_POST["restaurar"]) == 1){
	
	}else{
		if($_POST["pos"] == "Guardar"){
	
			$conn->execute("BEGIN");
			
			//Borramos lo anterior
			$conn->execute("DELETE FROM home");
			
			foreach($_POST["modulo"] as  $tipo_modulo => $modulo){
				foreach($modulo as $id_modulo => $datos_modulo){

					switch ($tipo_modulo){
						case "especialesPortada":
						case "especialesModulo":
						case "especialesAcceso":
							GuardarEspecialesEspecial($tipo_modulo, $datos_modulo["value"][5], $datos_modulo["value"][0], $datos_modulo["value"][1], $datos_modulo["value"][2], $datos_modulo["value"][3],$datos_modulo["value"][4],$datos_modulo["orden"]);
						default:
							break;
						
					}//switch
				}// foreach modulo
			}//foreach $_POST[modulo]
			
			$conn->execute("COMMIT"); //Si llega... guarda todo.
			

		}//$_POST[guardar]
		

		header("Location: adminHome.php?saved=true");

	}
	
}


?>
<html>
<head>
	<title><?=$TITULO_SITE?> - Administrador de Home</title>
	<link rel="stylesheet" href="css/stylo.css" type="text/css">
	<script language='Javascript' src='../includes/lib/jQuery/jquery.js'></script>
	<script type="text/javascript" src="../includes/lib/DOMWindow/jquery.DOMWindow.js"></script>
	<script language='Javascript' src='contenidos_edit.js'></script>
	<script language="Javascript">

function optionAddById(elementoid, destinoid, multiple){
	var id = $("#" + elementoid + "_addById").val();
	$.ajax({
	    url: 'ajax_admin_home/GetDataContenido.php',
	    type: 'GET',
	    data: 'id=' + id,
	    success: function(rta){
	       if($.trim(rta) != ''){
		        var optionText = rta.id + ' - ' + rta.titulo;
	    	    var optionValue = rta.id;
	    	    if(multiple){ // accion para cuando es un select multiple [lo agrego al select multiple]
	    	    	  document.getElementById(destinoid).options[document.getElementById(destinoid).options.length] = new Option(optionText, optionValue);
	    	    }else{ //accion que realiza cuando es una nota individual
	    	        
	    	    }
	 	   }
	    }
	});
}


function optionAddAnterior(elementoid,destinoid) {

	var optionText	= $("#" + elementoid + " option:selected").text();
	var optionValue	= $("#" + elementoid ).val();
	if(optionValue != 0) { 
		document.getElementById(destinoid).options[document.getElementById(destinoid).options.length] = new Option(optionText, optionValue); 
	}
}


function optionAdd(elementoid,destinoid)
{
	var optionText	= $("#" + elementoid + " option:selected").text();
	var optionValue	= $("#" + elementoid ).val();

	var elSel = document.getElementById(destinoid);

	var elOptNew = document.createElement('option');
	elOptNew.text = optionText;
	elOptNew.value = optionValue;
	var elOptOld = elSel.options[0];  
	try {
	  elSel.add(elOptNew, elOptOld); // standards compliant; doesn't work in IE
	}
	catch(ex) {
	  elSel.add(elOptNew, 0); // IE only
	}

}

function optionChange(elementoid,destinoid)
{

	$('#' + elementoid + ' option:selected').remove().prependTo('#'+destinoid);

}


function optionDelete(elementoid) {
	$('#' + elementoid + ' option:selected').remove();
	//document.getElementById(elementoid).remove(document.getElementById(elementoid).selectedIndex);
}

function SeleccionarOpcionesCombos(){
	$(".slt_seleccionar_todos option").each(function(){
		//console.log($(this));
			$(this).attr("selected","selected");
		});
	$("#formedit").submit();
}

function EscalarElemento(modo,elementoid){
	
	var o = new Array();
	var n = new Array();
	var obj = document.getElementById(elementoid);
	var Op_selected=-1;
	var Op_total=0;
	
	Op_total = obj.options.length;
    
	if (Op_total > 0){

    	for (var i=0; i< Op_total; i++){
        	o[o.length] = new Option(obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected);

        	if (obj.options[i].selected == true){
        		
        		Op_selected = i;
        	}
        }
        
        if (Op_selected!=-1){
        
	        if (modo == "arriba"){
	        obj.options[Op_selected-1].text = obj.options[Op_selected].text
	        obj.options[Op_selected-1].value = obj.options[Op_selected].value
	        obj.options[Op_selected-1].selected =true;
	        		
	        obj.options[Op_selected].text = o[Op_selected-1].text;
	        obj.options[Op_selected].value = o[Op_selected-1].value;
	        obj.options[Op_selected].selected = false;
	        
	        }else{
	        obj.options[Op_selected+1].text = obj.options[Op_selected].text
	        obj.options[Op_selected+1].value = obj.options[Op_selected].value
	        obj.options[Op_selected+1].selected =true;
	        		
	        obj.options[Op_selected].text = o[Op_selected+1].text;
	        obj.options[Op_selected].value = o[Op_selected+1].value;	
	        obj.options[Op_selected].selected = false;	
	        	
	        }
        
        }else{
        	
        	  obj.options[0].selected = true
        }
    	
	}
}

function Guardar(){
	SeleccionarOpcionesCombos();
}


</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body" onLoad="document.getElementById('divHomeAdminGuardar').style.display = 'block'">
<? include_once("_barra.php") ?>
<div class="why" id="outerDiv">
	<div class="header_guardado">
    <div style="float:left; width:750px;">
		<p class="Title" style="text-align:left;"><?=$TITULO_SITE?> - Home</p>
	</div>

<div style="margin:13px 0 0 10px; height:40px; float:left; width:86px;">
<input name="tipo" type="hidden" value="HOME">
<input name="genera" type="hidden" value="1">
<input name="ret" type="hidden" value="<?=$_SERVER["REQUEST_URI"]?>">
</div>
    <div style="clear:both;"></div>
        </div>
        
	<? if($_GET["saved"]==true){ ?>
	<div class="guardado">
    	<img src="images/status_A.gif" border="0">
        <p class="arial14_rojo"><strong>El contenido se ha guardado correctamente</strong></p>
        <div style="clear:both"></div>
	</div>
<? } ?>

<table width="780" align="center"><tr><td>
<div style="width:780px; margin:0 auto; padding:0;">
	<div id="divHomeAdminGuardar" style="display: none;margin:0; height:40px; float:left; width:140px; background:url(images/bg_generarhome.png) top no-repeat;">
	    <input name="tipo" type="hidden" value="HOME">
	    <input name="ret" type="hidden" value="<?=$_SERVER["REQUEST_URI"]?>">
	    <img onClick="Guardar();" type="image" id="imageField2" style="margin:10px 0 0 35px; cursor: pointer;" src="images/btn_guardar.gif" />
	</div>
</div>
</td></tr></table>
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;" bgcolor="#EEEEEE">
      <tr>
        <td width="20%" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="385" align="center" bgcolor="#EEEEEE"></td>
        <td align="center" bgcolor="#EEEEEE"></td>
        <td width="20%" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>

 <form action="<?=$_SERVER["PHP_SELF"]?>" method="POST" name="formedit" id="formedit" >
     <input type="hidden" name="pos" value="Guardar">
     <input type="hidden" id="hidden_guardarYgenerar" name="guardarYgenerar" value="0">
     <input type="hidden" id="hidden_restaurar" name="restaurar" value="0">
	   
	   <?for($h=0;$h<4;$h++){

			$q = "SELECT * FROM home WHERE tipo_modulo = 'especialesPortada' ORDER BY orden LIMIT ".$h.",1";
			$rs = $conn->getRecordset($q); 
			$id = $rs[0]["id"];
			$valor2 = $rs[0]["valor2_modulo"];
			$valor3 = $rs[0]["valor3_modulo"];
			$valor4 = $rs[0]["valor4_modulo"];
			$orden = $rs[0]["orden"];
		         
	    ?>
       <tr>
		<td width="20%" bgcolor="#EEEEEE" align="left" valign="top">&nbsp;</td>
		<td colspan="2"> 
			<table width="100%" bgcolor="#EEEEEE" >
				<tr>
				  <td width="100%" valign="top" align="center" >
					<table width="100%" bgcolor="#EEEEEE">
						<tr>
							<td width="100%" valign="top">
								<? crear_elemento($id, 'especialesPortada', $h , $valor2,  $valor3, $valor4, $orden); ?>
							</td>
						</tr>
					</table>
				  </td>
				</tr>
			</table>
		</td>
		<td width="20%" align="left" bgcolor="#EEEEEE" valign="top">&nbsp;</td>
	  </tr>
	  <?}?>
		
	  <tr><td>&nbsp;</td></tr>

	   <?for($k=0;$k<6;$k++){

			$q = "SELECT * FROM home WHERE tipo_modulo = 'especialesModulo' ORDER BY orden LIMIT ".$k.",1";
			$rs = $conn->getRecordset($q); 
			$id = $rs[0]["id"];
			$valor2 = $rs[0]["valor2_modulo"];
			$valor3 = $rs[0]["valor3_modulo"];
			$valor4 = $rs[0]["valor4_modulo"];
			$orden = $rs[0]["orden"];
		         
	    ?>
       <tr>
		<td width="20%" bgcolor="#EEEEEE" align="left" valign="top">&nbsp;</td>
		<td colspan="2"> 
			<table width="100%" bgcolor="#EEEEEE" >
				<tr>
				  <td width="100%" valign="top" align="center" >
					<table width="100%" bgcolor="#EEEEEE">
						<tr>
							<td width="100%" valign="top">
								<? crear_elemento($id, 'especialesModulo', $k , $valor2,  $valor3, $valor4, $orden); ?>
							</td>
						</tr>
					</table>
				  </td>
				</tr>
			</table>
		</td>
		<td width="20%" align="left" bgcolor="#EEEEEE" valign="top">&nbsp;</td>
	  </tr>
	  <?}?>

	  <tr><td>&nbsp;</td></tr>

	   <?for($p=0;$p<3;$p++){

			$q = "SELECT * FROM home WHERE tipo_modulo = 'especialesAcceso' ORDER BY orden LIMIT ".$p.",1";
			$rs = $conn->getRecordset($q); 
			$id = $rs[0]["id"];
			$valor2 = $rs[0]["valor2_modulo"];
			$valor3 = $rs[0]["valor3_modulo"];
			$valor4 = $rs[0]["valor4_modulo"];
			$orden = $rs[0]["orden"];
		         
	    ?>
       <tr>
		<td width="20%" bgcolor="#EEEEEE" align="left" valign="top">&nbsp;</td>
		<td colspan="2"> 
			<table width="100%" bgcolor="#EEEEEE" >
				<tr>
				  <td width="100%" valign="top" align="center" >
					<table width="100%" bgcolor="#EEEEEE">
						<tr>
							<td width="100%" valign="top">
								<? crear_elemento($id, 'especialesAcceso', $p , $valor2,  $valor3, $valor4, $orden); ?>
							</td>
						</tr>
					</table>
				  </td>
				</tr>
			</table>
		</td>
		<td width="20%" align="left" bgcolor="#EEEEEE" valign="top">&nbsp;</td>
	  </tr>
	  <?}?>

      <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#EEEEEE"></td>
        <td align="center" bgcolor="#EEEEEE"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
    
    <br/>

	</form>
    <br>
  <br>
  <!-- Fin Paginacion -->
</div>

<script type="text/javascript">
$(function(){
	$(".slt_seleccionar_todos").mousedown(function(e) {
	    if (e.which === 3) {
	        /* Right Mousebutton was clicked! */
	       //$("#id_contenido_editar").html($(this).val());
	       var id = $(this).val()[0];
	       OpenPopUpEdit(id);
	    	return false;
	    }
	});
});


function cerrarPopUp(){
	(function(){
		$.closeDOMWindow();
	}());
}

</script>
</body>
</html>
