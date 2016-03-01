<?php

include_once("../includes/DB_Conectar.php");
	
	if($_POST['enviado']==1){
		
		
		$elementos = substr($_POST['ids'],1);
		$vElementos = split(",",$elementos); 
		
		$conn->Execute("update advf set catID='".$_POST['chgcategory']."' where advID IN ('".$elementos."')");
		//borro
		$conn->Execute("DELETE FROM advf_vinculante where rel_advID IN ('".$elementos."')");
		//ingreso las categorias
		if (count($_POST['category_rel'])){
			//ids
			if (count($vElementos)>0){
				for ($j=0; $j<count($vElementos); $j++){	
						
					//mas categorias
					for($i=0; $i<count($_POST['category_rel']); $i++){
						$conn->Execute("INSERT INTO advf_vinculante (rel_advID,rel_catAdv) VALUES ('".$vElementos[$j]."','".$_POST['category_rel'][$i]."')");
					}
				
				}
			}
		}
		echo "<script language='javascript'>window.parent.restoreFormUpLoad();</script>"; 
	}

	//include_once("../includes/lib/auth.php");
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
	
	function buildTree_rel($parent_id, $selected_id = "", $VcatRel1 = array()) {
					global $conn, $exclude_array, $depth_tree;
					
					$rs_childnodes = $conn->execute("select id, nombre, padre from advf_categorias where activo = 'S' and estado = 'A' and padre = '" . $parent_id . "' order by nombre");
					
					while (!$rs_childnodes->eof) {
						
						if (array_search($rs_childnodes->field('id'),$VcatRel1)){
						$selected = "SELECTED";
						}else {
						$selected = "";
						}	
						
						if($rs_childnodes->field('id') != $rs_childnodes->field('padre')) {
							@$temp_tree	.= "<option value=\"" . $rs_childnodes->field('id') . "\" ".$selected."> ".str_repeat("--", ($depth_tree + 1)) . " " . $rs_childnodes->field('nombre') . "</option>"; 

							$depth_tree++;
							@$temp_tree	.= buildTree_rel($rs_childnodes->field('id'),"",$VcatRel1);
							$depth_tree--;

							array_push($exclude_array, $rs_childnodes->field('id'));
						}

						$rs_childnodes->movenext();
					}

					return $temp_tree;
				}
				
if(isset($_REQUEST['id'])) {
	
	
	$record_content		= $conn->execute("select advTitulo,advTitulo_en,advTitulo_pt, advTexto,advTexto_en,advTexto_pt, catID, advAutor_archivo, advAutor_archivo_en, advAutor_archivo_pt,advPreview,advAutor_archivo_link from advf where advID = '" . $_REQUEST['id'] . "'");

	$upload_id		= $_REQUEST['id'];

	$media_title		= $record_content->field("advTitulo");
	$media_title_en		= $record_content->field("advTitulo_en");
	$media_title_pt		= $record_content->field("advTitulo_pt");
	$media_description	= $record_content->field("advTexto");
	$media_description_en	= $record_content->field("advTexto_en");
	$media_description_pt	= $record_content->field("advTexto_pt");
	$media_category		= $record_content->field("catID");
	$media_autor		= $record_content->field("advAutor_archivo");
	$media_autor_en		= $record_content->field("advAutor_archivo_en");
	$media_autor_pt		= $record_content->field("advAutor_archivo_pt");
	$media_autor_link		= $record_content->field("advAutor_archivo_link");
	$advPreview	= $record_content->field("advPreview");
	
	//ME FIJO LAS CATEGORIAS RELACIONADAS Y LA GUARDO EN UN VECTOR.
	$rs_cate = $conn->Execute("select * from advf_vinculante WHERE rel_advID = '".$upload_id."'");
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
<html>
<head>
<link rel="stylesheet" href="css/stylo.css" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">		

		<script language="javascript">
		function changeContent() {
			if(document.getElementById('chgcategory').value == "0") {
				alert("Debe seleccionar una categoria");
				return false;
			}else{				
				document.formularioCat.submit();
			}
			
		}
		</script>
		<form method="POST" name='formularioCat'>
		<table align="center" valign="center" width="100%" height="80%">
			<tr>
				<td align="center">
					<table align="center" border="1" bordercolor="#666699" cellpadding="0" cellspacing="0" width="400">
						<tr class="Title" height="20" bgcolor="#7A7A7A">
							<td align="center" colspan="2">Cambio de categoria de archivos</td>
						</tr>
						<tr class="tablaOscuro" height="90">
							<td class="tituloOferta" align="center" valign="center" colspan="2">
								Eliga la categoria a cambiar<br>
								<input type="hidden" value="1" name="enviado">
								<input type="hidden" name="ids" value="<?=$_GET['ids']?>">
								<input type="hidden" value="<?=$_GET['id']?>" name="id">
								<select id="chgcategory" name="chgcategory" class="comun" style="width: 200px;">
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

										if($exclude_status == 1) {
											if ($media_category==$rs_categoria->field('id')){
												$selected = "SELECTED";
											}else{
												$selected = "";
											}
											
											print("<option value=\"" . $rs_categoria->field('id') . "\" ".$selected.">" . $rs_categoria->field('nombre') . "</option>");

											array_push($exclude_array, $rs_categoria->field('id'));

											print(buildTree($rs_categoria->field('id'),$media_category));
										}

										$rs_categoria->movenext();
									}
?>
								</select>
								<br /><br>
								<!--
								Otras categorías
								<br>
								<select name="category_rel[]"  class="comun" style="width: 200px; height:150px" multiple="multiple">
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
										if (array_search($rs_categoria->field('id'),$VcatRel)){
											
											$selected="SELECTED";
										}else{
											$selected="";
										}
					
										if($exclude_status == 1) {
											print("<option value=\"" . $rs_categoria->field('id') . "\" ".$selected."> ". $rs_categoria->field('nombre') . "</option>");
					
											array_push($exclude_array, $rs_categoria->field('id'));
					
											print(buildTree_rel($rs_categoria->field('id'),"",$VcatRel));
										}
					
										$rs_categoria->movenext();
									}
									?>
								</select>-->
								<br />
								<br />
								<div id="chgstatus" name="chgstatus" align="center" style="display: none;"><br><img src="images/loading.gif" width="20" height="20"><br>Cambiando categoria...</div>
							</td>
						</tr>
						<tr class="tablaOscuro" height="30">
							<td class="tituloOferta" align="center" valign="center">
								<input type="image" src="images/btn_cerrar.gif" value="Cerrar" onclick="window.parent.restoreForm();">
							</td>
							<td class="tituloOferta" align="center" valign="center">
								<input type="image" src="images/btn_guardar.gif" value="Cambiar" onclick="changeContent();">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</form>
		<body>
		</html>