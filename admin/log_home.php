<? 
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");

if ($_POST){

	$fecha = normal_a_mysql($_POST['fecha']);
	$fecha1 = normal_a_mysql($_POST['fecha1']);
	
	if ($fecha1<$fecha){
		$fecha1 = $fecha;
	}
	
	if ($fecha=="--"){
		$fecha="";
	}
	if ($fecha1=="--"){
		$fecha1="";
	}
	
}else {
	$fecha = "2010-02-12";
	$fecha1 = date("Y-m-d");
}


?>
<html>
<head>
	<title><?=$TITULO_SITE?> - Contenido</title>
	<link rel="stylesheet" href="css/stylo.css" type="text/css">
	<script language="javascript" src="../includes/validar_datos.js"></script>
	<script language="javascript" type="text/javascript" src="calendar/calendario.js"></script>
	<script language="javascript">
		var calendar = new CalendarPopup("calendar");
		calendar.setCssPrefix("calendario");
	</script>
	<link rel="stylesheet" href="calendar/calendario.css" type="text/css">

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body">
<?include_once("_barra.php");?>
<div class="why" id="outerDiv">
	<br>
	<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Log de Home</td>
      </tr>
    </table>
   
  <br>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
  <tr>
    <td width="5" align="left" valign="top"><img src="images/corner_si2.gif" width="5" height="5"></td>
    <td align="center" bgcolor="#e5e5e5"></td>
    <td width="5" align="right" valign="top"><img src="images/corner_sd2.gif" width="5" height="5"></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#ffffff" style="background-image:url(images/back_bloquecont_border.gif); background-repeat:repeat-x; background-position:top;">&nbsp;</td>
    <td align="center"><table cellspacing="0" cellpadding="0" width="900" align="center" class="tablaGrande">
      <tr>
        <td width="900" valign="top">
        <table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
            <tr >
              <td height="24" align="center" class="arial12" colspan="4" height="40">
			  </td>
		  </tr>
		  <form action="" method="post">
		  <tr >
              <td height="24" align="right" class="arial12" colspan="3" height="40">
              Fecha desde
              <div id="calendar" style="position: absolute; visibility: hidden; background-color: white; layer-background-color: white;"></div>
              <input id="fecha" name="fecha" value="<?=mysql_a_normal($fecha)?>" size="10" type="text" class="comun" style="width: 80px; text-align: left;">
				<a href="#" onclick="calendar.select(document.getElementById('fecha'),'anchor_fecha','dd/MM/yyyy'); return false;" name="anchor_fecha" id="anchor_fecha"><img src="calendar/ico_calendario.gif" border="0" align="absmiddle" /></a>	
			  &nbsp;&nbsp;&nbsp;Fecha Hasta
				<input id="fecha1" name="fecha1" value="<?=mysql_a_normal($fecha1)?>" size="10" type="text" class="comun" style="width: 80px; text-align: left;">
				<a href="#" onclick="calendar.select(document.getElementById('fecha1'),'anchor_fecha1','dd/MM/yyyy'); return false;" name="anchor_fecha1" id="anchor_fecha1"><img src="calendar/ico_calendario.gif" border="0" align="absmiddle" /></a>	
			 	</td>
			 	<td colspan="1" align="left">
			 	 <input type="image" src="images/btn_buscar.gif" />
			 	</td>
		  </tr>
		  </form>
		   <tr >
              <td height="24" align="center" class="arial12" colspan="4" height="40">
			  </td>
		  </tr>
		  <tr >
              <td height="24" align="left" class="arial12" colspan="4" height="40">
              <?
              if ($fecha){
              	echo "Filtro de consulta: <strong> ".mysql_a_normal($fecha)." hasta ".mysql_a_normal($fecha1)." </strong>";
              }
              ?>
			  </td>
		  </tr>
        	
        	<tr class="tablaOscuro">
              <td height="24" align="center" class="arial12"><div align="center"><strong>
					ID</strong></div>
			  </td>
			  <td height="24" align="center" class="arial12"><div align="center"><strong>
					USUARIO</div>
			  </td>
			  <td height="24" align="center" class="arial12"><div align="center"><strong>
              		ACCION</strong></div>
              </td>
              <td height="24" align="center" class="arial12"><div align="center"><strong>
              		FECHA</strong></div>
              </td>
		  </tr>

<?
	$registros	= $conn->execute("select admin_sethome_log.id, concat(admin_usuarios.nombre, ' ', admin_usuarios.apellido) as usuario, admin_sethome_log.action, admin_sethome_log.datetime from admin_sethome_log inner join admin_usuarios on(admin_usuarios.id = admin_sethome_log.user) where DATE_FORMAT(admin_sethome_log.datetime, '%Y-%m-%d') >= '".$fecha."' and DATE_FORMAT(admin_sethome_log.datetime, '%Y-%m-%d') <= '".$fecha1."'");

	if(!$registros->eof) {
		while(!$registros->eof) {
?>
          <tr style=" background-image:url('images/separador_h1.gif'); background-repeat:repeat-x; background-position:bottom;"> 
	      <td height="19" class="textooferta" valign="top">
<?=$registros->field('id');?>
              </td>
	      <td height="19" class="textooferta" valign="top">
<?=$registros->field('usuario');?>
              </td>
	      <td height="19" class="textooferta" valign="top">
<?=($registros->field('action') == 1)? 'Guardar':'Guardar & Generar';?>
              </td>
	      <td height="19" class="textooferta" valign="top">
<?=$registros->field('datetime');?>
              </td>
	  </tr>
<?
			$registros->movenext();
		}
	}else{
?>
          <tr style=" background-image:url('images/separador_h1.gif'); background-repeat:repeat-x; background-position:bottom;"> 
	      <td height="19" class="textooferta" valign="top">
			No hay datos disponibles
              </td>
	      <td height="19" class="textooferta" valign="top">
			No hay datos disponibles
              </td>
	      <td height="19" class="textooferta" valign="top">
			No hay datos disponibles
              </td>
	      <td height="19" class="textooferta" valign="top">
			No hay datos disponibles
              </td>
	  </tr>
<?
	}
?>

          </table>
           </td>
      </tr>
      <tr>
        <td width="900" valign="top" height="50">&nbsp;</td>
      </tr>
      <tr>
        <td width="900" valign="top" id="info_detall">
        </td>
      </tr>
      <tr>
        <td width="900" valign="top" height="50">&nbsp;</td>
      </tr>
      <tr>
        <td width="900" valign="top" id="info_detall_user">
        </td>
      </tr>
       <tr>
        <td width="900" valign="top" height="50">&nbsp;</td>
      </tr>
    </table></td>
    <td align="center" bgcolor="#FFFFFF" style="background-image:url(images/back_bloquecont_border.gif); background-repeat:repeat-x; background-position:top;">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
    <td align="center" bgcolor="#ffffff"></td>
    <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
  </tr>
</table>
<br>
</div>
</body>
<html>

