<?

if($_REQUEST["id"]==1)
{
	header("Location: index.php");
	die();
}

include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");

if(isset($_POST)&&count($_POST)>0)
{

	$sql = "delete from admin_usuarios_menu where usuario_id = ".$_POST["id"];
	$conn->execute($sql);

	$conn->execute("insert into admin_usuarios_menu (menu_id,usuario_id,activo) values (1,".$_POST["id"].",'S') ");

	if(count($_POST["permiso"])>0)
	{
		foreach($_POST["permiso"] as $key => $value)
		{
			$buf = split("_",$value);
			if(count($buf)==2)
			{
				// Calculo que es un permiso.
				$sql = "insert into admin_usuarios_menu (menu_id,usuario_id,activo) values (".$buf[1].",".$_POST["id"].",'S') ";
				$conn->execute($sql);
			}
		}
	}

	if(isset($_POST['access'])) {
		foreach($_POST['access'] as $index => $array) {
			$conn->execute("delete from admin_usuarios_permisos where menu_id = '" . $index . "' and usuario_id = '" . $_POST['id'] . "'");
			$conn->execute("insert into admin_usuarios_permisos (menu_id, usuario_id, permisos) values ('" . $index . "', '" . $_POST['id'] . "', '" . array_sum($array) . "')");
		}
	}

	header("Location: ".$_SERVER["PHP_SELF"]."?id=".$_POST["id"]);
	die();
}

?>
<HTML>
<HEAD>
  <Title><?=$TITULO_SITE?></Title>
  <link rel="stylesheet" href="css/stylo.css" type="text/css">
  <script language="javascript" src="../includes/validar_datos.js"></script>
  <style>
	TD.whiteText
	{
		color: #ffffff;
		font-weight: bold;
		font-family:verdana;
	}
	TD.blackText
	{
		color: #000000;
		font-family:verdana;
		font-size:9px;

	}
</style>
<script language='Javascript'>
	function Chk(chk)
	{
		var elementos = document.getElementById("frmID").elements;
		for(i=0;i<elementos.length;i++)
		{
			if(elementos[i].type=='checkbox')
			{
				buf = elementos[i].value.split("_");
				if(buf[0]==chk.value&&buf.length>1)
				{
					elementos[i].checked = chk.checked;
				}
			}
		}
	}
</script>
</HEAD>
<BODY class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll='no'>
<?include_once("_barra.php");?>
	<DIV class="why" id="outerDiv">
	<br />
	<form method="post" id="frmID" action='<?=$_SERVER["PHP_SELF"];?>'>
	<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Permisos Usuarios Administrativos</td>
      </tr>
    </table>
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#e5e5e5"></td>
        <td width="196" align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5" colspan="3"><img onclick='document.location="g_usuarios_admin.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;<input name="imageField2" type="image" id="imageField2"  src="images/btn_guardar.gif"></td>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
	  <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
	<br />
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#e5e5e5"></td>
        <td width="196" align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
        <tr> 
          <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
          <td  valign="top" colspan="3" bgcolor="#e5e5e5"> 
				<!--Este es el contenido-->
	            <table width="100%" border="1" style="border-collapse: collapse;" cellspacing="0" cellpadding="3" bordercolor="#000000">
						<?
						$sql = "
						select 
							ads.nombre as seccion, 
							ads.id as seccion_id, 
							IF(aum2.id>0,'true','false') as permiso, 
							IF(aup.permisos is null, '0', aup.permisos) as permisos, 
							am.* 
						from 
							admin_secciones ads 
							left join admin_menu am on ( am.seccion_id = ads.id )
							left join admin_usuarios_menu aum on ( aum.menu_id = am.id  ) 
							left join admin_usuarios_menu aum2 on ( aum2.menu_id = am.id and aum2.usuario_id = ".$_GET["id"]." ) 	
							left join admin_usuarios_permisos aup on ( aup.menu_id = aum.menu_id and aup.usuario_id = ".$_GET["id"].")	
						where
							aum.usuario_id = ".$usr->_id."
							and am.visible = 'S'
							and am.activo = 'S'
							group by am.id, am.link
							order by 
							ads.orden, am.orden, am.nombre
						";

						$rs = $conn->execute($sql);

						$secc = "";

						while(!$rs->eof)
						{
							if($rs->field("seccion_id")!=$secc)
							{
								// Es una seccion nueva
						?>
							<tr>
							  <td class="titulooferta" width='20'><input onclick='Chk(this);' type='checkbox' name='permiso[]' value='<?=$rs->field("seccion_id");?>'></td>
							  <td class="titulooferta" colspan='2'><b><?=$lang->t($rs->field("seccion"));?></b></td>
								  <td class="titulooferta" colspan='6'><b>Permisos</b></td>
							</tr>
						<?
								$secc = $rs->field("seccion_id");
							}
							// Mostramos cada pagina posible de seleccionar
						?>
							<tr>
							  <td >&nbsp;</td>
							  <td width='20'><input type='checkbox' name='permiso[]' value='<?=$rs->field("seccion_id");?>_<?=$rs->field("id");?>' <?=($rs->field("permiso")=='true'?"checked":"");?>></td>
							  <td class='blackText'><?=$lang->t($rs->field("nombre"));?></td>
							  <?
							  if($rs->field("opciones") == 'S') {
									$permisos	= strrev(decbin($rs->field('permisos')));
								  ?>
							  <input type="hidden" name="access[<?=$rs->field('id');?>][]" value="0">
							  <td width='70' class='blackText' align='center'><div <?=($rs->field('id') == '4')? "style=\"display: none;\"":"";?>>Ver<br><input name="access[<?=$rs->field('id');?>][]" type="checkbox" value="1" <?=($permisos[0] == '1')? "checked":"";?>></div></td>
								  <td width='70' class='blackText' align='center'><div>Crear<br><input name="access[<?=$rs->field('id');?>][]" type="checkbox" value="2" <?=($permisos[1] == '1')? "checked":"";?>></div></td>
							  <td width='70' class='blackText' align='center'><div>Modificar<br><input name="access[<?=$rs->field('id');?>][]" type="checkbox" value="4" <?=($permisos[2] == '1')? "checked":"";?>></div></td>
								  <td width='70' class='blackText' align='center'><div>Eliminar<br><input name="access[<?=$rs->field('id');?>][]" type="checkbox" value="8" <?=($permisos[3] == '1')? "checked":"";?>></div></td>
							  <td width='70' class='blackText' align='center'><div <?=($rs->field('id') == '4')? "style=\"display: none;\"":"";?>>Activar<br><input name="access[<?=$rs->field('id');?>][]" type="checkbox" value="16" <?=($permisos[4] == '1')? "checked":"";?>></div></td>
								  <td width='70' class='blackText' align='center'><div <?=($rs->field('id') == '4')? "style=\"display: none;\"":"";?>>Aprobar<br><input name="access[<?=$rs->field('id');?>][]" type="checkbox" value="32" <?=($permisos[5] == '1')? "checked":"";?>></div></td>
								  <?
								  }else{
								  ?>
								  <td>&nbsp;<br>&nbsp;</td>
								  <td>&nbsp;<br>&nbsp;</td>
								  <td>&nbsp;<br>&nbsp;</td>
								  <td>&nbsp;<br>&nbsp;</td>
								  <td>&nbsp;<br>&nbsp;</td>
								  <td>&nbsp;<br>&nbsp;</td>
								  <?
								  }
								  ?>
							</tr>
						<?
								$rs->movenext();
						}

						?>		
					</table>
				<!--Hasta aca este es el contenido-->				      
           </td>
           <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        </tr>
       <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
	<br />
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#e5e5e5"></td>
        <td width="196" align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5" colspan="3"><img onclick='document.location="g_usuarios_admin.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;<input name="imageField2" type="image" id="imageField2"  src="images/btn_guardar.gif"></td>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
	  <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
	</form>
</div>
</BODY>
</HTML>