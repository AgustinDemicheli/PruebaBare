<?php 
require_once '../includes/DB_Conectar.php';
$cat_id = intval($_REQUEST["cat_id"]);
$tipo = $_REQUEST["tipo"];

if(isset($_POST["subir_x"])){
	if($_REQUEST["tipo"] == "F"){
		//esta variable la voy a llenar adentro de la funcion con un msj de error o con el UltimoId(). 
		$rta_upload = "";
		if(UploadFoto($_FILES, $_POST)){
			//la capturo luego con la rta de salida y lo mando por QS
			header("Location: iframe_subir_archivo_inline.php?tipo=$tipo&cat_id=$cat_id&ok=1&rta=$rta_upload");
			die();
		}
		header("Location: iframe_subir_archivo_inline.php?tipo=$tipo&cat_id=$cat_id&ok=0&rta=$rta_upload");
		die();	
	}
}
?>
<style> 
@import url("../css/stylo.css"); 
body{
    font-family:Arial, Helvetica, sans-serif; 
    font-size:13px;
}
.info, .success, .warning, .error, .validation {
    border: 1px solid;
    margin: 10px 0px;
   	padding: 5px 2px 5px 24px;
    background-repeat: no-repeat;
    background-position: 10px center;
}
.info {
    color: #00529B;
    background-color: #BDE5F8;
    /*background-image: url('info.png');*/
}
.success {
    color: #4F8A10;
    background-color: #DFF2BF;
    /*background-image:url('success.png');*/
}
.warning {
    color: #9F6000;
    background-color: #FEEFB3;
    /*background-image: url('warning.png');*/
}
.error {
    color: #D8000C;
    background-color: #FFBABA;
/*    background-image: url('error.png');*/
}
</style> 
<?php if(!empty($_GET["rta"])){?>

<div class="<?php echo $_GET["ok"] == 0 ? "error" : "success" ?>"><?php echo $_GET["rta"]?>
<a href="iframe_subir_archivo_inline.php?tipo=<? echo $tipo ?>&cat_id=<? echo $cat_id ?>">Nueva</a></div>
<?php }?>

<form method="post" enctype="multipart/form-data" action="iframe_subir_archivo_inline.php?tipo=<? echo $tipo ?>&cat_id=<? echo $cat_id ?>">
<?php if($tipo == "F"){?>
<table  width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
   	<tr class="tablaOscuro">
   		<th class="titulooferta">Archivo</th>
   		<th class="titulooferta">T&iacute;tulo</th>
   		<th class="titulooferta">Descripci&oacute;n</th>
   		<th class="titulooferta">Categor&iacute;a</th>
   	</tr>
   	<tr class="tablaOscuro">
   		<td><input type="file" name="archivo_nuevo" id="archivo_nuevo" /></td>
   		<td><input type="text" name="titulo_archivo" id="titulo_archivo" /></td>
   		<td><input type="text" name="descripcion_archivo" id="descripcion_archivo" /></td>
   		<td><select name="categoria_archivo" id="">
   		<?php crearArbolCategoriasADVF(0, "-", " ", $cat_id)?>
   		</select></td>
   	</tr>
 </table>
<?php }?>
<br/>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tbody>
      <tr>
        <td width="5" valign="top" align="left"><img width="5" height="5" src="images/corner_si.gif"></td>
        <td width="480" bgcolor="#e5e5e5" align="center"></td>
        <td width="94" bgcolor="#e5e5e5" align="center"></td>
        <td width="5" valign="top" align="right"><img width="5" height="5" src="images/corner_sd.gif"></td>
      </tr>
	    <tr>
	    <td bgcolor="#e5e5e5" align="center">&nbsp;</td>
        <td bgcolor="#e5e5e5" align="center" colspan="2"><input type="image" src="images/btn_guardar.gif"  id="subir" name="subir" value="subir"></td>
	    <td bgcolor="#e5e5e5" align="center">&nbsp;</td>
      </tr>
	  <tr>
        <td valign="bottom" align="left"><img width="5" height="5" src="images/corner_ii.gif"></td>
        <td bgcolor="#e5e5e5" align="center"></td>
        <td bgcolor="#e5e5e5" align="center"></td>
        <td valign="bottom" align="right"><img width="5" height="5" src="images/corner_id.gif"></td>
      </tr>
    </tbody></table>
</form>