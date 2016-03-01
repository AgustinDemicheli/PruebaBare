<?php 
require_once '../includes/DB_Conectar.php';
include_once("../includes/lib/auth.php");
require_once 'includes/funciones_multimedia.php';
$mostrar_galeria = false;
if($_REQUEST["relation"] == 1){
	$mostrar_galeria = true;
}

?>
<html >
<head>
	<title><?=$TITULO_SITE?> - Fotos</title>
<link href="css/multimedia.css" rel="stylesheet" type="text/css" /> 
<link href="css/reset.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/stylo.css" type="text/css"/>
<script type="text/javascript" src="../includes/lib/jQuery/jquery.js"></script>
<script type="text/javascript" src="../includes/lib/DOMWindow/jquery.DOMWindow.js"></script>
<script type="text/javascript" src="multimedia.js"></script>
<script language="javascript" src="../includes/validar_datos.js"></script>
<script language="JavaScript">

	function goLite(FRM,BTN)
	{
	   window.document.forms[FRM].elements[BTN].style.color = "#AA3300";
	   window.document.forms[FRM].elements[BTN].style.backgroundImage = "url(back06b.gif)";
	}
	
	function goDim(FRM,BTN)
	{
	   window.document.forms[FRM].elements[BTN].style.color = "#775555";
	   window.document.forms[FRM].elements[BTN].style.backgroundImage = "url(back06a.gif)";
	}

</script>
<style>
.dispatcher_toggle{
	cursor:pointer;
	
}
.solapas{
	width:100%;
	height:23px;
	padding:0;
	margin:auto;
	text-align:left;
}

.solapas ul{
	margin:10px 0 0 10px;
	padding:0;
}

.solapas ul li{
	height:23px;
	width:100px;
	margin-right:5px;
	background-color:#333;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14;
	font-weight:bold;
	color:#FFF;
	float:left;
	list-style:none;
	text-align:center;
	vertical-align:middle;
	-webkit-border-top-left-radius: 7px;
    -moz-border-top-left-radius: 7px;
    border-top-left-radius: 7px;
	-webkit-border-top-right-radius: 7px;
    -moz-border-top-right-radius: 7px;
    border-top-right-radius: 7px;
	padding:3px 10px 0 10px;
	display:block;	
}
.solapas ul li a{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14;
	font-weight:bold;
	color:#FFF;	
}
.solapas ul li.selected{
	background-color:#fff;
	color:#333;
}

.solapas ul li.selected a{
	color:#333;
}

.solapas ul li a span{
	font-weight:normal;
	color:#C00;
}
.titulo_row{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	font-weight:bold;
	color:cc0000;
}

*+html .floatFix{display:inline-block;}
*+html .floatFix{display:block;}
* html .floatFix {height:1%}
</style>

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body" >

<?include_once("_barra.php");?>
<div class="why" id="outerDiv">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="filtros" align="center" >
<tr>
<td align="center" width="100%">
<div class="container_multimedia" style="align:center;">
<!-- FILTROS Y BUSCADOR -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="filtros" align="center" >
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
	$editarTitulo = 1;
    //$noAgregar = 1;//seteo la[ variable que se usará en _layout_fotos.php
    $_REQUEST["tipo"] = $_GET['tipo_multimedia']; //hack para enviar el parámetro tipo para que  traiga los videos
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
</td>
</tr>
</table>
</div>
</body>
</html>
