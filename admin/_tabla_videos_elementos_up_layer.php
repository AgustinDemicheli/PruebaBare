<!-- TABLA VIDEOS -->
<?php require_once 'includes/funciones_multimedia.php'; ?>

<table  id="tabla_upload" width="100%" cellspacing="0" cellpadding="3">
    <!-- Titulos -->	 
    <tr class="tablaOscuro"> 
        <td width="3%">&nbsp;</td>
        <td class="titulooferta" align="center">&nbsp;</td>
        <td class="titulooferta" align="center">&nbsp;</td>
        <td class="titulooferta" align="center">Titulo</td>
        <td class="titulooferta" align="center">Categor&iacute;a</td>
        <td class="titulooferta" align="center"></td>
        <td width="3%">&nbsp;</td>
    </tr>
    <!-- /Titulos -->
    <!-- Tr para clonar -->
    <tr id="tr_clone" style="display:none;"> 
        <td width="3%">&nbsp;</td>
        
        <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
            Tipo: <select name="tipo_video[]">
				<option selected="selected" value="YT">You Tube</option>
                <option value="REG">Propio</option>
            </select>
            <br/>
			Video: <input type="file" name="file_video[]" value=""  />
        </td>
        <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
            Cod YouTube: <input type="text" name="codigo_YouTube[]" value="" />
            <br/>
            Preview: <input type="file" name="file_preview_video[]" value=""/>
        </td>
        <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
            <input type="text" name="titulo_video[]" class="comun_libre" />
        </td>
        <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
            <select class="comun" name="id_categoria_video[]" style="width:140px;"><?php GetCategoriasMultimedia(0, $cat_id, false) ?></select>
        </td>
        <td width="3%" class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
            <a href=javascript:void(0);" onclick="EliminarTr(this)"><img src="images/delete.png" alt="Eliminar Linea" title="Eliminar Linea" /></a>
        </td>
    </tr>
    <!-- /Tr para clonar -->
    <!-- Campos -->
    <?php for ($i = 0; $i < 2; $i++) { ?>
        <tr > 
            <td width="3%">&nbsp;</td>
            <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                Tipo: <select name="tipo_video[]">
                    <option selected="selected" value="YT">You Tube</option>
					<option value="VI">Vimeo</option>
					<option value="REG">Propio</option>
                </select>
                <br/>
				Video: <input type="file" name="file_video[]" value="" />
            </td>
            <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                 Cod YouTube/Vimeo: <input type="text" name="codigo_YouTube[]" value="" style="width:60px;" />
                <br/>
                Preview: <input type="file" name="file_preview_video[]" value="" />
            </td>
            <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                <input type="text" name="titulo_video[]"  maxlength="255" class="comun_libre">
            </td>
            <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                <select class="comun" name="id_categoria_video[]" style="width:140px;"><?php GetCategoriasMultimedia(0, $cat_id, false) ?></select>
            </td>
            <td width="3%" class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                <a href=javascript:void(0);" onclick="EliminarTr(this)"><img src="images/delete.png" alt="Eliminar Linea" title="Eliminar Linea" /></a></td>
        </tr>
    <?php } ?>
    <!-- /Campos -->
</table>
<!-- /VIDEOS -->