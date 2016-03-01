<?php
require_once 'includes/funciones_multimedia.php';
$ulDos = false;
$cat_id = intval($_REQUEST["cat_id"]) > 0 ? $_REQUEST["cat_id"]: 0;
$pag 	= intval($_REQUEST["p"]) > 0 ? $_REQUEST["p"]: 1;
$tipo 	= !empty($_REQUEST["tipo"]) ? $_REQUEST["tipo"]:"F";
$object = $_REQUEST["object"];
$relation = intval($_REQUEST["relation"]) > 0 ? true : false;

if(!empty($_GET["buscar"])){
	$contenidos = BuscarContenido($_GET["buscar"],$pag,$tipo);
	$contenidos_total = BuscarContenidoTotal($_GET["buscar"],$tipo);
}else{
	$contenidos = GetContenidosPorCategoriaMultimedia($cat_id, $pag, $tipo);
	$contenidos_total = GetContenidosPorCategoriaTotal($cat_id, $tipo);
}

switch($tipo){
	case "F":
		$sBreadCrumb = "Fotos";
		break;
	case "V":
		$sBreadCrumb = "Videos";
		break;
	case "D":
		$sBreadCrumb = "Documentos";
		break;
	case "A":
		$sBreadCrumb = "Audios";
		break;
	default:
		$sBreadCrumb = "Fotos";
		break;
}
?>
<!-- RESULTADOS -->
<link rel="stylesheet" href="css/stylo.css" type="text/css">
<link rel="stylesheet" href="css/stylo.css" type="text/css">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="resultados">
    <tr>
        <td width="130" align="left">
        <!-- <a href="javascript:void(0)" onclick="toggleMuestraCargaContenidos();" id="btn_agregar_img" ><img src="images/btn_agregarimagen.jpg" width="133" height="22" /></a> -->	
        </td>
    	<td><span><?php echo $contenidos_total ?></span> <?php echo $sBreadCrumb ?> en <?php echo GetBreadCrumb($cat_id)?></td>
    </tr>
    
    <tr class="toggle_muestra_upload" id="iframe_subir_fotos" style="display:none;" >
        <td colspan="2">
        <!-- CON LA NUEVA VERSION DE UPLOAD MULTIPLE
        <iframe style="width:100%" frameborder="0" height="150" src="elementos_up2.php?extended_version=1&cat_id=<?php echo $cat_id?>&tipo=<?php echo $tipo?>" ></iframe></td>
        -->
        	<iframe style="width:100%" frameborder="0" height="500px" src="elementos_up2_layer.php?cat_id=<?php echo $cat_id?>&tipo=<?php echo $tipo?>" ></iframe>
        </td>
    </tr>
</table><!-- /RESULTADOS --> 

<!-- THUMBS class="thumbs toggle_muestra_upload"  -->
<div id="div_thumbs" data-relation="<? echo $relation ?>" data-target_object="<? echo $object ?>" data-cat_id="<?php echo $cat_id?>" data-tipo="<? echo $tipo ?>" >
	<ul>
	<?php 
	
	for($i=0;$i < count($contenidos);$i++){
	$contenido = $contenidos[$i];
	switch ($contenido["advTipo"]){
		case "F":
			$url_link = 'thumbs.php?id='.$contenido["advID"].'&w=130&h=100"';
			break;
		case "V":
			//falta terminar
			$url_preview_generica = "images/iconoVideo.png";
			$url_link = intval($contenido["advIdPreview"]) > 0 ? 'thumbs.php?id='.$contenido["advIdPreview"].'&w=130&h=100"': $url_preview_generica;
			break;
		case "D":
			$url_link = "images/iconoDocumento.png";
			break;
		case "A":
			$url_link = "images/iconoAudio.jpg";
            break;
			default:
				break;
				
	}
		?>
    	<li id="<?php echo $contenido["advID"] ?>"><!--Modulos Fotos -->
                <div class="thumb">
					<img src="<?php echo $url_link ?>"/>
					<p class="arial11"><?=($contenido["advTitulo"]<>""?$contenido["advTitulo"]." (".$contenido["advID"].")":"&nbsp;")?></p>
                    <div class="clr"></div>
				</div>
                
                <div class="clr" style="margin:5px 0;"></div>
                
                <div class="thumb_tools">
                    <ul>
                    	<?php if(isset($editarTitulo)){?>
                        <li><a onclick="editarTituloGaleria(this)" href="javascript:void(0)"  data-advTipo="<?php echo $contenido["advTipo"]?>" data-advLink="<?php echo $contenido["advLink"]?>" data-advID="<?php echo $contenido["advID"]?>"><img src="images/btn_zoom.gif" width="18" height="18" /></a></li>
                       <?php } ?>
                        <?php if($relation){?>
                        <li class="btn_galeria-<?php echo $contenido["advID"] ?>"><a onclick="AgregarContenidoGaleria(this)" href="javascript:void(0)"  data-advTipo="<?php echo $contenido["advTipo"]?>" data-advLink="<?php echo $contenido["advLink"]?>" data-advID="<?php echo $contenido["advID"]?>" ><img src="images/btn_mas.gif" alt="" width="18" height="18" /></a></li>        
                        <?php } ?>
                        <li style="display:none;" class="btn_galeria-<?php echo $contenido["advID"] ?>"><a onclick="QuitarContenidoGaleria(<?php echo $contenido["advID"]?>)" href="javascript:void(0)"><img src="images/btn_menos.gif" width="18" height="18" /></a></li>
                        <!-- ESTE LI REEMPLAZA AL ANTERIOR (btn_mas.gif) SI LA FOTO YA EST&aacute; EN EL LIGHTBOX<li><a href="#"><img src="images/btn_menos.gif" width="18" height="18" /></a></li> -->  
                        <?if($contenido["advTipo"]=="F"){?>
                        <li><a href="javascript:void(0);" onclick='javascript:window.open("phpimageeditor/index.php?idFoto=<?=$contenido["advID"]?>", "mywindow", "location=0,status=0,scrollbars=1,width=1200,height=800");';><img src="images/btn_editar.gif" alt="" width="18" height="18" /></a></li>   
                        <li style="display:block;" class="btn_galeria-<?php echo $contenido["advID"] ?>"><a onclick="EliminarContenidoGaleria(<?php echo $contenido["advID"]?>)" href="javascript:void(0)"><img src="images/btn_menos.gif" width="18" height="18" /></a></li>
                        <?}?>
                        <li><a href="javascript:prompt('URL Imagen', '<?=$var_url . '/' . $contenido['advLink']?>')"><img src="images/btn_link.gif" alt="" width="18" height="18" /></a></li>  
                    </ul>
                    

                </div>
</li>
     <?php } ?>


</div><!--  / THUMBS -->
<!-- PAGINADO -->
<?php GetPaginado($pag, $contenidos_total)?>
<!-- /PAGINADO -->
<script type="text/javascript">
function actualizarFoto(id_foto, launcher, advLink){
	RefreshContent();
}

function cerrarPopUp(){
	(function(){
		$.closeDOMWindow();
	}());
}
</script>
