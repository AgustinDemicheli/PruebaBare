<!-- TABLA FOTOS -->
<?php require_once 'includes/funciones_multimedia.php';?>

<table  id="tabla_upload" width="100%" cellspacing="0" cellpadding="3">
	 <!-- Titulos -->	 
		 <tr class="tablaOscuro"> 
		    <td width="3%">&nbsp;</td>
		    <td class="titulooferta" align="center">Imagen</td>
		    <td class="titulooferta" align="center">Titulo</td>
		    <td class="titulooferta" align="center">Categor&iacute;a</td>
		   <td width="3%">&nbsp;</td>
		 </tr>
	<!-- /Titulos -->
		<!-- Tr para clonar -->
		<tr id="tr_clone" style="display:none;"> 
		   <td width="3%">&nbsp;</td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="file" name="file_audio[]" value=""/>
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="text" name="titulo_audio[]" class="comun_libre" />
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   	<select class="comun" name="id_categoria_audio[]"><?php  GetCategoriasMultimedia(0, $cat_id, false)?></select>
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
		   		<input type="file" name="file_audio[]" value=""/>
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   		<input type="text" name="titulo_audio[]" class="comun_libre" />
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		    	<input name="descripcion_audio[]" type="text" maxlength="255" class="comun_libre">
		   </td>
		   <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		   			<select class="comun" name="id_categoria_audio[]"><?php  GetCategoriasMultimedia(0, $cat_id, false)?></select>
		   </td>
		  	<td width="3%" class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
		  	<a href=javascript:void(0);" onclick="EliminarTr(this)"><img src="images/delete.png" alt="Eliminar Linea" title="Eliminar Linea" /></a></td>
		 </tr>
		<?php }?>
	<!-- /Campos -->
</table>
<!-- /FOTOS -->