<?php
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");

if(isset($_REQUEST['media'])) {
	switch($_REQUEST['media']) {
		case 'A':
			$upload_path	= "../advf/audios/";
			$upload_dbpath	= "advf/audios/";
			$upload_maxsize	= "80000000";
			$upload_allow	= array('1' => 'wav', '2' => 'mp3');
		break;

		case 'D':
			$upload_path	= "../advf/documentos/";
			$upload_dbpath	= "advf/documentos/";
			$upload_maxsize	= "10000000";
			$upload_allow	= array('1' => 'xls', '2' => 'pdf', '3' => 'ppt', '4' => 'pps', '5' => 'doc', '6' => 'zip', '7' => 'rar', '8' => 'odt', '9' => 'txt', '10' => 'rtf');
		break;

		case 'V':
			$upload_path	= "../advf/videos/";
			$upload_dbpath	= "advf/videos/";
			$upload_maxsize	= "150000000";
			$upload_allow	= array('1' => 'flv', '2' => 'mpg', '3' => 'avi', '4' => 'wmv','5' => 'webm','6' => 'mp4','7' => 'ogg','8' => 'ogv');
		break;

		case 'F':
			$upload_path	= "../advf/imagenes/";
			$upload_dbpath	= "advf/imagenes/";
			$upload_maxsize	= "2000000";
			$upload_allow	= array('1' => 'jpg', '2' => 'gif', '3' => 'png');
		break;
	}
}else{
	die();
}

if(isset($_FILES['files'])) {
	$upload_status		= "0";
	$upload_message		= "";

	$upload_id		= $_POST['id'];
	$upload_title		= $_POST['title'];
	$upload_title_en	= $_POST['title_en'];
	$upload_title_pt	= $_POST['title_pt'];
	$upload_description	= $_POST['description'];
	$upload_description_en	= $_POST['description_en'];
	$upload_description_pt	= $_POST['description_pt'];
	$upload_media		= $_REQUEST['media'];
	$upload_category	= $_POST['category'];
	$upload_autor	= $_POST['autor'];
	$upload_autor_en	= $_POST['autor_en'];
	$upload_autor_pt	= $_POST['autor_pt'];
	$upload_autor_link	= $_POST['autor_link'];
	$upload_preview	= $_POST['advPreview'];

	$upload_temp_uniqid	= uniqid('');
	$upload_temp_filename	= $_FILES['files']['name'];
	$upload_temp_extension	= preg_replace("/[^\]+\./i", "", $upload_temp_filename);

	$upload_temp_name	= $_FILES['files']['tmp_name'];
	$upload_temp_size	= $_FILES['files']['size'];
	$upload_temp_error	= $_FILES['files']['error'];
	$upload_temp_date	= date("Y-m-d h:m:s", mktime());

	if(!($upload_temp_error > 0 && $upload_id != 0)) {
		if($upload_temp_size > $upload_maxsize) {
			$upload_status	= "1";
			$upload_message = "<font color=\"black\">Error:</font> El tamaño del archivo no puede superar el maximo de $upload_maxsize";
		}

		if(!array_search(strtolower($upload_temp_extension), $upload_allow)) {
			$upload_status	= "1";
			$upload_message = "<font color=\"black\">Error:</font> El tipo de archivo no esta permitido";
		}

		$record_exist = $conn->execute("select count(*) as check_exist from advf where advTitulo = '" . $upload_title . "' and advBytes = '" . $upload_temp_size . "'");

		if($record_exist->field('check_exist') != 0) {
			$upload_status	= "1";
			$upload_message = "<font color=\"black\">Error:</font> Ya hay un archivo similar en la Base de Datos";		
		}
	}

	if($upload_status == "0") {
		if($upload_id == "0") {
			if($_REQUEST['media'] == 'F') {
				list($upload_width, $upload_height) = getimagesize($upload_temp_name);

				$conn->execute("insert into advf (advTipo, advTitulo,advTitulo_en,advTitulo_pt, advTexto,advTexto_en,advTexto_pt, advFecha, advLink, catID, advWidth, advHeight, advBytes, advFechaCaptura, advAutor_archivo,advAutor_archivo_en,advAutor_archivo_pt,advPreview,advAutor_archivo_link) values ('" . $upload_media . "', '" . $upload_title . "','" . $upload_title_en . "','" . $upload_title_pt . "', '". $upload_description . "', '". $upload_description_en . "', '". $upload_description_pt . "', '" . $upload_temp_date . "', '" . $upload_dbpath . $upload_temp_uniqid . "." . strtolower($upload_temp_extension) . "', '" . $upload_category . "','" . $upload_width . "', '" . $upload_height . "', '" . $upload_temp_size . "', '" . $upload_temp_date . "', '".$upload_autor."', '".$upload_autor_en."', '".$upload_autor_pt."','".$upload_preview."','".$upload_autor_link."')");
			}else{
				$conn->execute("insert into advf (advTipo, advTitulo,advTitulo_en,advTitulo_pt, advTexto,advTexto_en,advTexto_pt, advFecha, advLink, catID, advBytes, advFechaCaptura, advAutor_archivo, advAutor_archivo_en, advAutor_archivo_pt,advPreview,advAutor_archivo_link) values ('" . $upload_media . "', '" . $upload_title . "','" . $upload_title_en . "','" . $upload_title_pt . "', '". $upload_description . "', '". $upload_description_en . "', '". $upload_description_pt . "', '" . $upload_temp_date . "', '" . $upload_dbpath . $upload_temp_uniqid . "." . strtolower($upload_temp_extension) . "', '" . $upload_category . "', '" . $upload_temp_size . "', '" . $upload_temp_date . "', '".$upload_autor."', '".$upload_autor_en."', '".$upload_autor_pt."','".$upload_preview."','".$upload_autor_link."')");
			}

			// Insert en tabla de auditoria
			$record_id = $conn->UltimoId(); 

			$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $record_id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '2')");

			move_uploaded_file($upload_temp_name, $upload_path . $upload_temp_uniqid . "." . strtolower($upload_temp_extension));
			
			//guardo las demas categorias relacionadas
			//ingreso las categorias
				if (count($_POST['category_rel'])){
					
					for($i=0; $i<count($_POST['category_rel']); $i++){
						$conn->Execute("INSERT INTO advf_vinculante (rel_advID,rel_catAdv) VALUES ('".$record_id."','".$_POST['category_rel'][$i]."')");
					}
					
				} 

			$upload_message	= "Se ha subido el archivo satisfactoriamente";

			header("Location: multimedia_upload.php?success=1&id=".$record_id."&media=".$upload_media."&categoria=".$upload_category);
			die();
		}else{
			if($upload_temp_error > 0) {
				if($_REQUEST['media'] == 'F') {
					list($upload_width, $upload_height) = getimagesize($upload_temp_name);

					$conn->execute("update advf set advTitulo = '" . $upload_title . "', advTitulo_en = '" . $upload_title_en . "',advTitulo_pt = '" . $upload_title_pt . "', advTexto = '" . $upload_description . "', advTexto_en = '" . $upload_description_en . "',advTexto_pt = '" . $upload_description_pt . "',catID = '" . $upload_category . "', advWidth = '" . $upload_width . "', advHeight = '" . $upload_height . "', advFecha = '" . $upload_temp_date . "', advAutor_archivo = '".$upload_autor."', advAutor_archivo_en = '".$upload_autor_en."',advAutor_archivo_pt = '".$upload_autor_pt."',advPreview='".$upload_preview."',advAutor_archivo_link='".$upload_autor_link."' where advID = '" . $upload_id . "'");
				}else{
					$conn->execute("update advf set advTitulo = '" . $upload_title . "', advTitulo_en = '" . $upload_title_en . "',advTitulo_pt = '" . $upload_title_pt . "', advTexto = '" . $upload_description . "', advTexto_en = '" . $upload_description_en . "',advTexto_pt = '" . $upload_description_pt . "',catID = '" . $upload_category . "', advFecha = '" . $upload_temp_date . "', advAutor_archivo = '".$upload_autor."',advAutor_archivo_en = '".$upload_autor_en."',advAutor_archivo_pt = '".$upload_autor_pt."',advPreview='".$upload_preview."',advPreview='".$upload_preview."',advAutor_archivo_link='".$upload_autor_link."' where advID = '" . $upload_id . "'");
				}

					// Insert en tabla de auditoria
					$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $upload_id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '4')");

				//$upload_message	= "Se ha modificado el archivo satisfactoriamente";
				header("Location: multimedia_upload.php?success=2&id=".$upload_id."&media=".$_REQUEST['media']."&categoria=".$upload_category);
				die();
			}else{
				$record_physical_delete = $conn->execute("select advLink from advf where advID = '" . $upload_id . "'");
				@unlink("../" . $record_physical_delete->field('advLink'));

				if($_REQUEST['media'] == 'F') {
					list($upload_width, $upload_height) = getimagesize($upload_temp_name);

					$conn->execute("update advf set advTitulo = '" . $upload_title . "',advTitulo_en = '" . $upload_title_en . "',advTitulo_pt = '" . $upload_title_pt . "', advTexto = '" . $upload_description . "', advTexto_en = '" . $upload_description_en . "',advTexto_pt = '" . $upload_description_pt . "', catID = '" . $upload_category . "', advWidth = '" . $upload_width . "', advHeight = '" . $upload_height . "', advFecha = '" . $upload_temp_date . "', advLink = '" . $upload_dbpath . $upload_temp_uniqid . "." . $upload_temp_extension . "', advBytes = '" . $upload_temp_size . "', advAutor_archivo = '".$upload_autor."',advAutor_archivo_en = '".$upload_autor_en."',advAutor_archivo_pt = '".$upload_autor_pt."',advPreview='".$upload_preview."',advAutor_archivo_link='".$upload_autor_link."' where advID = '" . $upload_id . "'");
				}else{
					$conn->execute("update advf set advTitulo = '" . $upload_title . "',advTitulo_en = '" . $upload_title_en . "',advTitulo_pt = '" . $upload_title_pt . "', advTexto = '" . $upload_description . "', advTexto_en = '" . $upload_description_en . "',advTexto_pt = '" . $upload_description_pt . "', catID = '" . $upload_category . "', advFecha = '" . $upload_temp_date . "', advLink = '" . $upload_dbpath . $upload_temp_uniqid . "." . $upload_temp_extension . "', advBytes = '" . $upload_temp_size . "', advAutor_archivo = '".$upload_autor."',advAutor_archivo_en = '".$upload_autor_en."',advAutor_archivo_pt = '".$upload_autor_pt."',advPreview='".$upload_preview."',advAutor_archivo_link='".$upload_autor_link."' where advID = '" . $upload_id . "'");
				}

				// Insert en tabla de auditoria
				$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $upload_id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '4')");

				move_uploaded_file($upload_temp_name, $upload_path . $upload_temp_uniqid . "." . $upload_temp_extension);
				
				//categorias relacionadas
				
					//borro
					$conn->Execute("DELETE FROM advf_vinculante where rel_advID='".$upload_id."'");
					//ingreso las categorias
					if (count($_POST['category_rel'])){
						
						for($i=0; $i<count($_POST['category_rel']); $i++){
							$conn->Execute("INSERT INTO advf_vinculante (rel_advID,rel_catAdv) VALUES ('".$upload_id."','".$_POST['category_rel'][$i]."')");
						}
						
					} 
					
				$upload_message	= "Se ha modificado el archivo satisfactoriamente";
				header("Location: multimedia_upload.php?success=2&id=".$upload_id."&media=".$_REQUEST['media']."&categoria=".$upload_category);
				die();
			}
		}
		
		
		
	}
}


if(isset($_REQUEST['id']) || $id_subido > 0) {

	$record_content		= $conn->execute("select advTitulo,advTitulo_en,advTitulo_pt, advTexto,advTexto_en,advTexto_pt, catID, advAutor_archivo, advAutor_archivo_en, advAutor_archivo_pt,advPreview,advAutor_archivo_link, advLink from advf where advID = '" . $_REQUEST['id'] . "'");

	$upload_id		= $_REQUEST['id'];

	$media_title		= $record_content->field("advTitulo");
	$media_title_en		= $record_content->field("advTitulo_en");
	$media_title_pt		= $record_content->field("advTitulo_pt");
	$media_description	= $record_content->field("advTexto");
	$media_link			= $record_content->field("advLink");
	$media_description_en	= $record_content->field("advTexto_en");
	$media_description_pt	= $record_content->field("advTexto_pt");
	$media_category		= $record_content->field("catID");
	$media_autor		= $record_content->field("advAutor_archivo");
	$media_autor_en		= $record_content->field("advAutor_archivo_en");
	$media_autor_pt		= $record_content->field("advAutor_archivo_pt");
	$media_autor_link		= $record_content->field("advAutor_archivo_link");
	$advPreview	= $record_content->field("advPreview");
	
	//ME FIJO LAS CATEGORIAS RELACIONADAS Y LA GUARDO EN UN VECTOR.
	$rs_cate = $conn->Execute("select * from advf_vinculante WHERE rel_advID='".$upload_id."'");
	$i=1;
	$VcatRel= array();
	$VcatRel[0]="";
	while (!$rs_cate->eof){
		
		$VcatRel[$i] = $rs_cate->field('rel_catAdv');
		$i++;
		
		$rs_cate->MoveNext();
	}
}else {
	$media_category = $_GET['categoria'];
	
}


?>
<link rel="stylesheet" href="css/stylo.css" type="text/css">
<script language="javascript">
				function openMedia(id,media,object)
				{
					window.open('multimedia.php?menu=4&id=' + id + '&media=' + media + '&object=' + object, 'Multimedia','width=650,height=415');
				}

				function insertMedia(id,url,media,object)
				{
					switch(media) {
						/*case 'A':
							eval('document.formedit.' + object + '_audio.value = url');
							eval('document.formedit.' + object + '.value = id');
						break;

						case 'D':
							eval('document.formedit.' + object + '_document.value = url');
							eval('document.formedit.' + object + '.value = id');
						break;

						case 'V':
							eval('document.formedit.' + object + '_video.value = url');
							eval('document.formedit.' + object + '.value = id');
						break;*/

						case 'F':
							eval('document.upload.' + object + '_image.value = url');
							eval('document.upload.' + object + '.value = id');
						break;
					}
				}

				function clearMedia(media, object)
				{
					switch(media) {
						/*case 'A':
							eval('document.formedit.' + object + '_audio.value = ""');
							eval('document.formedit.' + object + '.value = ""'); 
						break;

						case 'D':
							eval('document.formedit.' + object + '_document.value = ""');
							eval('document.formedit.' + object + '.value = ""'); 
						break;

						case 'V':
							eval('document.formedit.' + object + '_video.value = ""');
							eval('document.formedit.' + object + '.value = ""'); 
						break;*/

						case 'F':
							eval('document.upload.' + object + '_image.value = ""');
							eval('document.upload.' + object + '.value = ""'); 
							eval('document.upload.preview_image_' + object + '.style.display = "none"');
						break;
					}
				}

function checkUpload() {
	if(document.getElementById('title').value == "") {
		alert("Debe ingresar un titulo");
		return false;
	}

	if(document.getElementById('id').value == "0" && document.getElementById('files').value == "") {
		alert("Debe elegir un archivo");
		return false;
	}

	if(document.getElementById('category').value == "0") {
		alert("Debe seleccionar una categoria");
		return false;
	}

	document.upload.submit();
}
</script>
<?
switch($_GET["success"])
{
	case "1":
		$upload_message = '<img src="images/status_A.gif" border="0">&nbsp;&nbsp;<font color="#C50000"><b>Se ha subido el archivo correctamente</b></font>';
		break;
	case "2":
		$upload_message = '<img src="images/status_A.gif" border="0">&nbsp;&nbsp;<font color="#C50000"><b>Se ha modificado el archivo correctamente</b></font>';
		break;
	case "3":
		$upload_message = '<img src="images/status_A.gif" border="0">&nbsp;&nbsp;<font color="#C50000"><b>Se ha modificado el archivo correctamente</b></font>';
		break;
}
?>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="upload" action="multimedia_upload.php" method="post" enctype="multipart/form-data">
<table width="500" height="415" border="1" bordercolor="#666699" cellpadding="0" cellspacing="0" align="center">
	<tr height="30" class="Title" bgcolor="#7A7A7A">
		<td align="center"colspan="3"><?=($upload_id)? "Modificación de archivos multimedia":"Upload de archivos multimedia";?></td>
	</tr>
	<tr height="30" class="tablaOscuro">
		<td align="center"colspan="3" class="tituloOferta"><?=($upload_message)? $upload_message:"Complete los datos del formulario";?></td>
	</tr>
	<tr class='tablaOscuro' valign="top">
		<td width="270" class="arial12" valign="middle" style="padding-left: 10px">
			<input type="hidden" id="id" name="id" value="<?=($_REQUEST['id'])? $_REQUEST['id']:0;?>">
			<input type="hidden" id="media" name="media" value="<?=$_REQUEST['media'];?>">
			<b>Titulo del archivo</b><br>
			<input type="text" name="title" id="title" class="comun" style="width: 180px;" value="<?=($media_title)?htmlentities($media_title):"";?>">
			<br />
			<b>Titulo del archivo (EN)</b><br>
			<input type="text" name="title_en" id="title_en" class="comun" style="width: 180px;" value="<?=($media_title_en)?htmlentities($media_title_en):"";?>">
			<br />
			<b>Descripción</b><br>
			<textarea name="description" id="description" class="comun" style="width: 180px; height: 50px"><?=($media_description)? $media_description:"";?></textarea>
			<br />
			<b>Descripción (EN)</b><br>
			<textarea name="description_en" id="description_en" class="comun" style="width: 180px; height: 50px"><?=($media_description_en)? $media_description_en:"";?></textarea>
			<br />
			<b>Autor</b>
			<br />
			<input type="text" name="autor" id="autor" class="comun" style="width: 180px;" value="<?=($media_autor)? htmlentities($media_autor):"";?>">
			<br />
			<b>Link del autor </b>
			<br />
			<input type="text" name="autor_link" id="autor_link" class="comun" style="width: 180px;" value="<?=($media_autor_link)? htmlentities($media_autor_link):"";?>">
			<br />
		</td>
		<td width="515" align="left" valign="middle" class='titulooferta' style="padding-left: 10px">
			<table width="100%" cellpadding="0" cellspacing="0">
			<?if ($_REQUEST['media']=="V"){?>
			<!--<tr>
				<td height="100" class='titulooferta'>
				Preview<br><br>				
				<?
					$image_name ="";
					if($advPreview>0)
					{
					// Quiere decir que hay algun numero, asi que lo mostramos
					$sql = "select advID,advLink,advTitulo from advf where advID = ".$advPreview;
					$rs = $conn->execute($sql);
					$image_name = $rs->field("advTitulo");
					}
				?>
				<input type='text'  size="25" name='advPreview_image' readonly value='<?=$image_name;?>'>					
				<input type='hidden' name='advPreview' value='<?=($advPreview?$advPreview:"")?>'>
				<a href="javascript: openMedia('<?=($advPreview? $advPreview:"");?>','F','advPreview');"><img border="0" src="images/examinar.gif"></a>
				<a href="javascript: clearMedia('F','advPreview');"><img border="0" src="images/eliminar.gif"></a>
				<?
				if($advPreview>0)
				{
				?>
				<div id='preview_image_advPreview' name='preview_image_advPreview' style='padding:3px;'>
				<a href='../<?=$rs->field("advLink")?>' target='_blank'><img src='thumbs.php?w=50&h=50&id=<?=$rs->field("advID")?>' width='50' height='50' style='border:1px solid #AEAEAE'></a>
				</div>
				<?
				}
				?>
				</td>
			</tr>-->
			<?}?>
			
			<tr>
			<td colspan="3" class='titulooferta'>
			
			<b>Ubicación del archivo</b><br><br><input type="file" name="files" id="files" class="comun" style="width: 180px;"><br><br>
			
			<?if ($_SESSION['sessTipo_id']==1){?>
			<b>Categoria</b><br><br>
			<select name="category" id="category" class="comun" style="width: 200px;">
				<option value="0">--- Seleccione ---</option>
				<?
				function buildTree($parent_id, $selected_id = "") {
					global $conn, $exclude_array, $depth_tree;
					
					$rs_childnodes = $conn->execute("select id, nombre, padre from advf_categorias where activo = 'S' and estado = 'A' and padre = '" . $parent_id . "' order by nombre");
					
					while (!$rs_childnodes->eof) {
						
						if ($rs_childnodes->field('id')==$selected_id){
						$selected = "SELECTED";
						}else {
							$selected = "";
						}
						if($rs_childnodes->field('id') != $rs_childnodes->field('padre')) {
							@$temp_tree	.= "<option value=\"" . $rs_childnodes->field('id') . "\" ".$selected.">" . str_repeat("--", ($depth_tree + 1)) . " " . $rs_childnodes->field('nombre') . "</option>"; 

							$depth_tree++;
							@$temp_tree	.= buildTree($rs_childnodes->field('id'),$selected_id);
							$depth_tree--;

							array_push($exclude_array, $rs_childnodes->field('id'));
						}

						$rs_childnodes->movenext();
					}

					return $temp_tree;
				}
				
				
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
			<?}else {?>
			<input type="hidden" id="category" name="category" value="<?=(!$media_category?$_REQUEST['category']:$media_category)?>" />
			<?}?>
			<br />
			<br />
			<br />
			<br />
			<br />
			Permitidos:
			<?
			for ($i=1; $i<=count($upload_allow); $i++){
			echo $upload_allow[$i].",";
			}
			?>
			</td>
			</tr>
			
			</table>
		</td>
	</tr>
	<tr height="30" class='tablaOscuro'>
		<td align="center">
			<!--<input type="button" class="boton" value="Cerrar" onclick="window.parent.restoreForm();">-->
			<input type="image" src="images/btn_cerrar.gif" value="Cerrar" onClick="window.parent.restoreFormUpLoad();">
		</td>
		<td align="center">
			
			<input type="image" src="images/btn_guardar.gif" value="Upload" onClick="return checkUpload();">
		</td>
	</tr>

</table>
</form>
</body>