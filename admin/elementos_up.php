<?
@set_time_limit(7200); //2 horas
include_once("../includes/DB_Conectar.php"); 
include_once("../includes/lib/auth.php");

if(isset($_GET['tipo'])) { $extended_version = 1; $tipo = $_REQUEST['tipo']; }else{ $extended_version = 0; $tipo = "F"; }
$media_category=$_REQUEST['categoria'];

$basedir = $app_path."/advf/imagenes/";

$resolution = "100x100";
$titulo = "Seleccione la categoría de elementos";

$col6 = "Descripción";
$titulo = "Upload de archivos al sistema";

$col4 = "Categoria";
switch($tipo){
	case 'F':
		$col1 = "Seleccione la imagen";
		$col2 = "T&iacute;tulo en la galer&iacute;a";
		$col3 = "Codigo";
		$upload_allow	= array('1' => 'jpg','2' => 'png', '3' => 'gif'); 
		$directorio = "imagenes";
		$advTipo = "F";
		$tam_maximo= 4097152;
		break;
	case 'A':
		$col1 = "Seleccione el audio";
		$col2 = "Nombre del Tema";
		$col3 = "Clave";
		$col6 = "Interprete";
		$upload_allow	= array('1' => 'wav', '2' => 'mp3');
		$directorio = "audios";
		$advTipo = "A";
		$tam_maximo= 83886080;
		break;
	case 'V':
		$col1 = "Seleccione el video";
		$col2 = "T&iacute;tulo en la galer&iacute;a";
		$col3 = "Clave";
		$upload_allow	= array('1' => 'flv', '2' => 'mpg', '3' => 'avi', '4' => 'wmv', '5' => 'f4v');
		$directorio = "videos";
		$advTipo = "V";
		$tam_maximo= 157286400;
		break;
	case 'D':
		$col1 = "Seleccione el documento";
		$col2 = "T&iacute;tulo en la galer&iacute;a";
		$col3 = "Clave";
		$upload_allow	= array('1' => 'xls', '2' => 'pdf', '3' => 'ppt', '4' => 'pps', '5' => 'doc', '6' => 'zip', '7' => 'rar', '8' => 'odt', '9' => 'txt', '10' => 'rtf');
		$directorio = "documentos";
		$advTipo = "D";
		$tam_maximo= 10485760;
		break;
		
}


$uploadDir = $app_path."advf/$directorio/";
if(!is_dir($uploadDir."/".date("Y"))){
	mkdir($uploadDir."/".date("Y"));
}
if(!is_dir($uploadDir."/".date("Y")."/".date("m"))){
	mkdir($uploadDir."/".date("Y")."/".date("m"));
}
$uploadDir = $uploadDir."/".date("Y")."/".date("m")."/";

$UploadOK = false;
$MAXBATCH = 3;

if ($_POST["Action"]=="Upload"){ 
			
	$error = "";

	for ($i=1; $i<=$MAXBATCH; $i++) {
		
		$esta = 1;
		$upload 		= $_FILES["upload".$i];
		$tmp_name 		= $_FILES["upload".$i]["tmp_name"];
		$tam 			= $_FILES["upload".$i]["size"];
		$nom 			= $_FILES["upload".$i]["name"];


		
		
		$epigraf = $_POST["epigraf".$i];
		$epigraf_en = $_POST["epigraf_en".$i];
		$codigo = $_POST["codigo".$i];
		$texto = $_POST["texto".$i];
		$texto_en = $_POST["texto_en".$i];
		$categ = $_POST["categ".$i];
	
		//imagen preview del video
		
		
		if ($tmp_name != "none" && $tmp_name != "") {

			// chequeo la extension:
			$ext = StrToLower(SubStr($nom, StrRPos($nom, ".") + 1));
			if(!array_search(strtolower($ext), $upload_allow)) 
			{
				$error .= "El archivo ".$_FILES["upload".$i]["name"]." no posee la extensión adecuada<br />";
				$esta = 0;
			}

			$uniqid = uniqid("");
			$dest = $uploadDir . $uniqid . "." . $ext;
			$site_path = "advf/".$directorio."/".date("Y")."/".date("m")."/". $uniqid . "." .$ext;
			$srcimage = $uniqid.".".$ext;
			
				//preview video
			if($_FILES["upload_preview".$i] && !$preview_module)
			{
				$upload_allow_preview	= array('1' => 'jpg');
				$upload_preview 		= $_FILES["upload_preview".$i];
				$tmp_name_preview 		= $_FILES["upload_preview".$i]["tmp_name"];
				$tam_preview 			= $_FILES["upload_preview".$i]["size"];
				$nom_preview 			= $_FILES["upload_preview".$i]["name"];
				
				if ($tmp_name_preview != "none" && $tmp_name_preview != "") {
	
				// chequeo la extension:
				$ext_preview = StrToLower(SubStr($nom_preview, StrRPos($nom_preview, ".") + 1));
				if(!array_search(strtolower($ext_preview), $upload_allow_preview)) 
				{
					$error .= "El archivo ".$_FILES["upload_preview".$i]["name"]." no posee la extensión adecuada<br />";
					$esta   = 0;
				}
	
				
				$dest 		= $uploadDir. $uniqid . "." . $ext_preview;
				$site_path 	= "advf/".$directorio."/".date("Y")."/".date("m")."/".$uniqid . "." .$ext;
				$srcimage_preview	= $uniqid.".".$ext_preview;
				@unlink($dest); //por si existe uno anterior, aunque es imposible
					if (!@copy($tmp_name_preview, $dest))
					{
						$esta=0;	
					}
				}
			}
			
			
			
			
			
			
			
			
			
			if ($categ == "0") {
				$error .= "Para el archivo ".$_FILES["upload".$i]["name"]." no se seleccionó categoría<br />";
				$esta = 0;
			}

			if($tam > $tam_maximo)
			{
				$error .= "El archivo ".$_FILES["upload".$i]["name"]." excede el tamaño permitido<br />";
				$esta = 0;
			}

				
			if($esta == 1)
			{
				
				//copio el temporal a dest:
				@unlink($dest); //por si existe uno anterior, aunque es imposible
				if (@copy($tmp_name, $dest))
				{
					
					$exito = "El archivo ".$nom." se ha subido correctamente<br />";
					
					$sql = "insert into advf (advTipo, advTitulo, advTitulo_en ,advCodigo,advTexto, advTexto_en, advfecha, advLink, advPreview, catID, advBytes, advfechaCaptura, advAutor)
						values	
						('".$advTipo."', '".$epigraf."', '".$epigraf_en."', '".$codigo."' ,'".$texto."','".$texto_en."' , '".$condate."','".$site_path."','".str_ireplace($exts,".jpg",$site_path)."', '".$categ."', '".$tam."',NOW(),'".$_SESSION["sessID"]."')";	
						
					$conn->Execute($sql);
				
					$advid = mysql_insert_id();

					//crea Thumnail si es tipo imagen
					if($advTipo=="F" && $ext!="swf" )
					{
						//thumbs(100,100,$advid);
						Multimedia::GetImagenStatic(100,100,$site_path);					
					}

					if($advTipo == "V")
					{
						
						if($preview_module)
						{	
							$flv = $app_path.$site_path;
							$exts = array(".flv",".f4v");
							$newthumb = str_replace($exts, ".jpg", $flv);
							$flvmov = new ffmpeg_movie($flv, 0);
							$flvframe = $flvmov->getFrame(500);
							if($flvframe)
							{ 
							  $flvgd = $flvframe->toGDImage();
							  imagejpeg($flvgd, $newthumb, 100);
							  imagedestroy($flvgd);
							}
							sleep(2);
						}else{
							//die("entra");
							$flv = $app_path.$site_path;
							$exts = array(".flv",".f4v");
							$newthumb = str_replace($exts, ".jpg", $flv);
							//thumbs_preview(100,100,$advid,$newthumb);
							Multimedia::GetImagenStatic(100,100,$newthumb);
							
						}
					}

					
				} 
				else{ 
					$error .= "El archivo ".$nom." NO se ha podido subir<br />";
				}
			}
		} 
	}
} 

?> 
<HTML>
<HEAD>
<Title><?=$TITULO_SITE?></Title>
<link rel="stylesheet" href="css/stylo.css" type="text/css">
<script language="JavaScript">

function ValidaFileUp() {


	document.getElementById("Action").value = "Upload";

	//document.frmFile.Action.value="Upload";
	//document.frmFile.btnsubmit.disabled=true;
	return true;
}

</script>
</HEAD>
<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body" scroll=no leftmargin="2" topmargin="2" marginwidth="0" marginheight="0">
<?
if (!$extended_version){
	
	echo '<table marginwidth="0" marginheight="0"><tr><td width="25%">';
	include_once("_barra.php");
	echo '</td></tr>
	<tr><td>';
}

?>
	<DIV class="why" id="outerDiv">
	<br>
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Multimedia Upload Múltiple</td>
      </tr>
    </table>
	<br />
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#e5e5e5"></td>
        <td width="196" align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
	  <tr>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#ffffff" colspan="3">
  <table width="100%" cellspacing="0" cellpadding="3">
						<? if($error <> ""){?>
						<tr class="tablaOscuro"> 
							<td colspan="6" align="center" valign="top">
								<table width="60%" align="center" border=0>
								<tr><td width="5%"><img src="images/error.jpg" width="25" height="25" border="0"></td>
								<td><span class="titulooferta" style="color: red"><b><?=$error?></b></span></td>
								</tr>
								</table>
							</td>
						</tr>
						<?}else {
							 if($exito <> "" && $_POST["Action"]=="Upload"){?>
							<tr class="tablaOscuro"> 
								<td colspan="6" align="center" valign="top">
									<table width="60%" align="center" border=0>
									<tr><td width="5%"><img src="images/exito.png" width="25" height="25" border="0"></td>
									<td><span class="titulooferta" style="color: green"><b><?=$exito?></b></span></td>
									</tr>
									</table>
								</td>
							</tr>
							<?}
						}
						if (!$extended_version){?>
						<form name="type" method="GET">
						<tr class="tablaOscuro"> 
							<td colspan="5">
								<span class="titulooferta">Tipo de elemento:</span>
								<select name="tipo" id="tipo" class="comun" onChange="javascript:document.type.submit();">
									<option value="-">Seleccione</option>
									<option value="F" <?=($tipo=="F"?"selected":"")?>>Imagenes</option>
									<option value="D" <?=($tipo=="D"?"selected":"")?>>Documentos</option>
									<option value="V" <?=($tipo=="V"?"selected":"")?>>Videos</option>
									<option value="A" <?=($tipo=="A"?"selected":"")?>>Audios</option>
									<!--<option value="Z" <?=($tipo=="Z"?"selected":"")?>>Archivos Comprimidos</option>-->
								</select>
								<option>
							</td>
						</tr>
						</form>
						<?}else {?>
						<input type="hidden" name="tipo" id="tipo" value="<?=$tipo?>" />
						<?}?>
					<? if(isset($tipo) && $tipo <> "-"){?>
					<FORM name="frmFile" ENCTYPE="multipart/form-data" METHOD="POST"> 
					<INPUT TYPE="HIDDEN" NAME="Action" id="Action" value="Upload">  
					<INPUT TYPE="HIDDEN" NAME="tipo" VALUE="<?=$tipo?>">  
					 
					 <tr class="tablaOscuro"> 
					    <td width="3%">&nbsp;</td>
					    <td class="titulooferta" align="center"><?=$col1?>
					      
					    </td>
					    <td class="titulooferta" align="center"><?php if($tipo=="V"){?>Imagen Preview<?php }else{?>&nbsp;<?php }?></td>
					    <td class="titulooferta" align="center"><?=$col2?></td>
					    <td class="titulooferta" align="center"><?=$col6?></td>
					    <td class="titulooferta" align="center">
					    <?if ($_SESSION['sessTipo_id']==1){?>
					    <?=$col4?>
					    <?}?>	 
					    </td>
					  </tr>
					<? for ($j=1; $j<=$MAXBATCH; $j++) { ?>
						<tr> 
						   <td width="3%">&nbsp;</td>
						   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
						   <INPUT NAME="upload<?=$j;?>" ID="upload<?=$j;?>" TYPE="FILE" class="comun_libre">
						   </td>
						   
						   <td><?php if($tipo=="V"){?><input type="file" name="upload_preview<?=$j;?>" class="comun_libre" id="upload_preview<?=$j;?>" /> <?php }else{?>&nbsp;<?php }?></td>
						   
						   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><!--ES:--> <input NAME="epigraf<?=$j;?>" TYPE="TEXT" maxlength="255" class="comun_libre"></td>
						   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><!--ES:--> <input NAME="texto<?=$j;?>" TYPE="TEXT" maxlength="255" class="comun_libre"></td>
						   <td>
						   <?if ($_SESSION['sessTipo_id']==1){?>
						   <select class="comun" NAME="categ<?=$j;?>">
								<option value="0">--- Seleccione ---</option>
								<?
								$exclude_status	= 0;
								$exclude_array	= array();
								$deapth_tree	= 1;

								$rs_categoria = $conn->execute("select id, nombre, padre from advf_categorias where activo = 'S' and padre=0 and estado = 'A' order by nombre");

								while(!$rs_categoria->eof) {
									$exclude_status		= 1;

									for($index = 0; $index < count($exclude_array); $index++) {
										if($exclude_array[$index] == $rs_categoria->field('id')) {
											$exclude_status	= 0;
											break;
										}
									}
									if ($media_category==$rs_categoria->field('id')){
										
										$selected="SELECTED";
									}else{
										$selected="";
									}

									if($exclude_status == 1) {
										print("<option value=\"" . $rs_categoria->field('id') . "\" ".$selected.">" . $rs_categoria->field('nombre') . "</option>");

										array_push($exclude_array, $rs_categoria->field('id'));

										print(buildTree($rs_categoria->field('id'),$media_category));
									}
									$rs_categoria->movenext();
								}
								?>
							</select>
							<?}else{?>
							<input type="hidden" name="categ<?=$j;?>" id="categ<?=$j;?>" value="<?=$media_category?>" />
							<?}?>
						  </td>
						 </tr>
						 <tr>
							<td width="3%">&nbsp;</td>
						   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
						   &nbsp;</td>
						   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
						   &nbsp;</td>
						   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
						   		<!--EN: <input NAME="epigraf_en<?=$j;?>" TYPE="TEXT" maxlength="255" class="comun_libre">-->
						   </td>
						   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
						   		<!--EN: <input NAME="texto_en<?=$j;?>" TYPE="TEXT" maxlength="255" class="comun_libre">-->
						   		</td>
						   
						   <td width="3%">&nbsp;</td>
						</tr>
					<? } 
					}?>
				</table>
		</td>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
	  <tr>
        <td align="center" valign="bottom" colspan="20" bgcolor="#e5e5e5" class="titulooferta" >
        	Extensiones permitidas: <?for ($i=1; $i<=count($upload_allow); $i++){?>
						   <?=$upload_allow[$i]?>,
						   <?}?>
			<br />Tamaño máximo: <?=Multimedia::getSize($tam_maximo)?>
        </td>
        
      </tr>
	  <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
	<br />
	<?if (!$extended_version){?>
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#e5e5e5"></td>
        <td width="196" align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5" colspan="3"><img onclick='document.location="index.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;<input name="imageField2" type="image" id="imageField2" onClick="javascript:document.frmFile.submit();" src="images/btn_guardar.gif"></td>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
	  <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
    <?}else {?>
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#e5e5e5"></td>
        <td width="196" align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5" colspan="3"><input name="imageField2" type="image" id="imageField2" onClick="javascript:document.frmFile.submit();" src="images/btn_guardar.gif">&nbsp;&nbsp;&nbsp;<img onclick='window.parent.restoreFormUpLoad();' src="images/btn_cerrar.gif" border="0" style="cursor:pointer;"></td>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
	  <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
    <?}?>
	<br />
   </div>
<?
if (!$extended_version){
	echo '</td></tr>
	</table>';
}
?>
</BODY>
</HTML>
