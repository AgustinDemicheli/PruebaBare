<!-- TABLA VIDEOS -->
<?php require_once 'includes/funciones_multimedia.php';?>
<script type="text/javascript">
function toggleCamposVideo(el) {
        $('.thRegular').toggle();
        $(el).parent().parent().find('.camposRegular').toggle();
        $(el).parent().parent().find('.camposYouTube').toggle();
}
</script>

<table  id="tabla_upload" width="100%" cellspacing="0" cellpadding="3">
	 <!-- Titulos -->	 
		 <tr class="tablaOscuro"> 
		    <td width="3%">&nbsp;</td>
                    <td class="titulooferta" align="center">Tipo Video</td>
		    <td class="titulooferta" align="center">Codigo YouTube</td>
                    <td class="titulooferta" align="center">Video</td>
		    <td class="titulooferta thRegular" align="center">Imagen Preview</td>
		     <td class="titulooferta" align="center">Titulo</td>
		    <td class="titulooferta" align="center">Descripcion</td>
		    <td class="titulooferta" align="center">Categor&iacute;a</td>
		   <td width="3%">&nbsp;</td>
		 </tr>
	<!-- /Titulos -->
		<!-- Tr para clonar -->
		<tr id="tr_clone" style="display:none;"> 
		   <td width="3%">&nbsp;</td>
                   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                       <select name="tipo_video[]">
                            <option value="REG">Regular</option>
                            <option selected="selected" value="YT">You Tube</option>
							<option selected="selected" value="VI">Vimeo</option>
                       </select>
		   </td>
                   <td class="titulooferta camposYouTube" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="text" name="codigo_YouTube[]" value=""/>
		   </td>
		   <td class="titulooferta camposRegular" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="file" name="file_video[]" value=""/>
		   </td>
		   <td class="titulooferta camposRegular" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="file" name="file_preview_video[]" value=""/>
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="text" name="titulo_video[]" class="comun_libre" />
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		    <input name="descripcion_video[]" type="text" maxlength="255" class="comun_libre"></td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   	<select class="comun" name="id_categoria_video[]"><?php  GetCategoriasMultimedia(0, $cat_id, false)?></select>
		   </td>
		   <td width="3%" class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<a href=javascript:void(0);" onclick="EliminarTr(this)"><img src="images/delete.png" alt="Eliminar Linea" title="Eliminar Linea" /></a>
		  	</td>
		 </tr>
		 <!-- /Tr para clonar -->
	<!-- Campos -->
	<?php for($i=0;$i<3;$i++){?>
		 <tr > 
		   <td width="3%">&nbsp;</td>
                   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                       <select name="tipo_video[]">
                           <option selected="selected" value="REG">Regular</option> 
                           <option value="YT">You Tube</option>
                       </select>
		   </td>
                   <td class="titulooferta camposYouTube" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="text" name="codigo_YouTube[]" value=""/>
		   </td>
		   <td class="titulooferta camposRegular" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="file" name="file_video[]" value=""/>
		   </td>
		    <td class="titulooferta camposRegular" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="file" name="file_preview_video[]" value=""/>
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		    	<input type="text" name="titulo_video[]"  maxlength="255" class="comun_libre">
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		    	<input type="text"  name="descripcion_video[]" maxlength="255" class="comun_libre">
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   			<select class="comun" name="id_categoria_video[]"><?php  GetCategoriasMultimedia(0, $cat_id, false)?></select>
		   </td>
		  	<td width="3%" class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		  	<a href=javascript:void(0);" onclick="EliminarTr(this)"><img src="images/delete.png" alt="Eliminar Linea" title="Eliminar Linea" /></a></td>
		 </tr>
		<?php }?>
	<!-- /Campos -->
</table>
<!-- /VIDEOS -->