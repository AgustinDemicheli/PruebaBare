<?php 
require_once '../includes/DB_Conectar.php';
require_once 'includes/funciones_multimedia.php';
$mostrar_galeria = false;
if($_REQUEST["relation"] == 1){
	$mostrar_galeria = true;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/multimedia.css" rel="stylesheet" type="text/css" />
<link href="css/reset.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../includes/lib/jQuery/jquery.js"></script>
<script type="text/javascript" src="multimedia.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CMS :: Multimedia</title>

</head>

<body style="background-color: transparent;">
<div class="container_multimedia" style="width:830px;">
<!-- FILTROS Y BUSCADOR -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="filtros" >
  <tr>
	<td colspan="2" align="right"><a href="#" onclick="javascript:parent.cerrarOpenMedia();"><img src="images/btn_cerrar.gif" /></a></td>
  </tr>
  <tr>
    <td><h3>Mostrar elementos por categor&iacute;a</h3></td>
    <td width="220"><h3>Buscar un elemento</h3></td>
  </tr>
  <tr>
    <td>
    <table border="0" cellspacing="0" cellpadding="0" class="selectores">
      <tr>
        <td>
          <select name="categoria_padre" id="categoria_padre" onchange="GetContenido($(this).val(),0)">
            	<?php echo GetCategoriasMultimedia(0,0)?>
          </select>
        </td>
        <td>          
       <!-- 
        <select name="categoria_hija" id="categoria_hija" onchange="BuscarCategoria(this,'','categoria_padre')">
            <option>Todos</option>
        </select>
         -->
        </td>
        
      </tr>
    </table></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <label for="buscador"></label>
          <input type="text" name="input_buscar" id="input_buscar" class="search" />
        </td>
        <td><input onclick="BuscarContenido(0)" type="image" name="imageField" id="imageField" src="images/btn_multimedia_buscar.gif" /></td>
      </tr>
    </table></td>
  </tr>
</table><!-- / FILTROS Y BUSCADOR -->
<div id="div_contenedor_fotos">
	<?php 
	require_once '_layout_fotos.php';?>
</div>
<?php if($mostrar_galeria){?>
<!-- LIGHTBOX -->
<div class="lightbox floatFix toggle_muestra_upload">
	<h3>Archivos listas para agregar al contenido</h3>
    <div class="thumbs">
        <ul id="ul_galeria_imagenes" class="floatFix"><!-- / CADA 8 LI ABRIR UN UL NUEVO -->
		<!-- Imagenes galeria -->        
    	 </ul>
    </div>
        <div class="boton"><a href="javascript:UtilizarGaleria()"><img src="images/btn_confirmar.gif" width="122" height="51" /></a></div>
</div><!-- / LIGHTBOX -->
<?php } ?>
</div>
</body>
</html>
