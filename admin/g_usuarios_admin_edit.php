<? 
if($_REQUEST["id"]==1)
{
	header("Location: index.php");
	die();
}
			include_once("../includes/DB_Conectar.php"); 
			include_once("../includes/lib/auth.php");
//			include_once ("calendar/cal_class.php");
			
			if(is_array($_POST)&&count($_POST)>1) 
			{ 
				if(isset($_POST["id"])&&is_numeric($_POST["id"])) 
				{
					// Checkeo si hubo un cambio de Activo o Estado. ( Si el usuario no puede cambiar el Activo o Estado este se vuelve 
					if($usr->_menuRights != '-1' and isset($_POST["columnRights"])) {
						foreach($_POST["columnRights"] as $key => $value) {
							switch($key) {
								case 16:
									if($usr->_menuRights[4] == 1) {
									}else{
										$_POST[$value]	= 'N';
									}
								break;

								case 32:
									if($usr->_menuRights[5] == 1) {
									}else{
										$_POST[$value]	= 'P';
									}
								break;
							}
						}
					}

					$sql = "update admin_usuarios set grupo_id='".$_POST['grupo_id']."',usuario = '".$_POST['usuario']."',clave = '".$_POST['clave']."',nombre = '".$_POST['nombre']."',apellido = '".$_POST['apellido']."',email = '".$_POST['email']."',activo = '".$_POST['activo']."' where id = ".$_POST["id"];
					$conn->execute($sql); 
					$id = $_POST["id"];

					// Insert en tabla de auditoria
					$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '4')");
				} 
				else 
				{ 
					$sql = "insert into admin_usuarios (grupo_id,usuario,clave,nombre,apellido,email,activo) values 
													   ('".$_POST['grupo_id']."','".$_POST['usuario']."','".$_POST['clave']."','".$_POST['nombre']."','".$_POST['apellido']."','".$_POST['email']."','".$_POST['activo']."')"; 
					$conn->execute($sql); 
					$rs = $conn->execute("select last_insert_id()"); 
					$id = $rs->field(0);

					// Insert en tabla de auditoria
					$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '2')");
				}
				
				if($_POST["save_type"]=="1")
				{
					header("Location: g_usuarios_admin.php");
					die();
				}
				else
				{
					header("Location: ".$_SERVER["PHP_SELF"]."?id=$id"); 
					die(); 
				}
			} 


			
			if(isset($_GET["id"])) 
			{ 
				$sql = "select * from admin_usuarios where id = ".$_GET["id"]; 
				$rs = $conn->execute($sql); 
				foreach($rs->recordset() as $key => $value) 
				{ 
					$$key = $value; 
				} 
			} 
			?><html>
<head>
	<title><?=$TITULO_SITE?> - Usuarios Administrativos</title>
	<link rel="stylesheet" href="css/stylo.css" type="text/css">
	<script language='Javascript' src='../includes/validar_datos.js'></script>
	<script language='Javascript'>
	function Chk()
	{
		
		return true;
	}
	</script>
	<script language="Javascript">
	function set_type(valor)
	{
		document.getElementById("save_type").value = valor;
	}
	</script>
	<script language="javascript" type="text/javascript" src="calendar/calendar.core.js"></script>
</head>
<body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll='no'>
<?include_once("_barra.php");?>
<div class="why" id="outerDiv">  <br>
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Usuarios Administrativos</td>
      </tr>
    </table>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
		<!--
		<tr valign="top">
		  <td height="15" width="610" align="center" class="arial11" valign="middle">  </td>
		</tr>
		-->
  </table>
    <form method="post" name="formedit" action='<?=$_SERVER["PHP_SELF"];?>?menu=<?=$usr->_menu;?>' onsubmit='return Chk();'>
	  <input type="hidden" name="save_type" id="save_type" value="0">
      <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si2.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd2.gif" width="5" height="5"></td>
      </tr>
      <tr>
        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5"><table width='50%' border="0" cellpadding="0" cellspacing="5">
          <tr>
            <td align="left"><?=($usr->checkUserRights(2) != 'disabled')? '<img onclick=\'javascript:document.location.href="' . $_SERVER["PHP_SELF"] . '";\' src="images/btn_nuevo.gif" style="cursor:pointer;" border="0">':'';?>
            </td>
            <td align='center' style="padding-right:15px;"><img onclick='document.location="g_usuarios_admin.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">
            </td>
			<td align='center' style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" onclick="javascript:set_type(0);" src="images/btn_guardar.gif">'); ?></td>
            <td align='center'><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" src="images/btn_guardaryvolver.gif" onclick="javascript:set_type(1);" value=\'Guardar y volver al listado\'>'); ?></td>
          </tr>
        </table></td>
        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
      </tr>
    </table>
	<br>
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#ffffff"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
      <tr>
        <td align="center" bgcolor="#ffffff">&nbsp;</td>
        <td align="center" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse;">
          
          <input id='id' name='id' type='hidden' value='<?=(isset($id)?$id:"")?>'>
  
  
<!--
 <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Perfil&nbsp;<img src="images/arrow_derecha.gif"></td>
    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
    	<?
    	$rs = $conn->Execute("SELECT * FROM admin_grupo_usuarios")
    	?>
    	<select name="grupo_id" id="grupo_id">
    		<?
    		while (!$rs->eof) {
    				
				?><option value="<?=$rs->Field("id")?>" <?=($grupo_id==$rs->Field("id")?"SELECTED='selected'":"")?> ><?=$rs->Field("nombre")?></option><?
    			$rs->MoveNext();
    		}
    		?>
    		
    	</select>
    </td>
  </tr>         
 -->
 <input type="hidden" name="grupo_id" value="1" />
  <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Usuario&nbsp;<img src="images/arrow_derecha.gif"></td>
    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='usuario' name='usuario' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($usuario,ENT_QUOTES):"")?>'></td>
  </tr>
          
  
          
  <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Password&nbsp;<img src="images/arrow_derecha.gif"></td>
    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='clave' name='clave' type='password' class='comun' value='<?=(isset($id)?htmlentities($clave,ENT_QUOTES):"")?>'></td>
  </tr>
          
  
          
  <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Nombre&nbsp;<img src="images/arrow_derecha.gif"></td>
    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='nombre' name='nombre' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($nombre,ENT_QUOTES):"")?>'></td>
  </tr>
          
  
          
  <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Apellido&nbsp;<img src="images/arrow_derecha.gif"></td>
    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='apellido' name='apellido' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($apellido,ENT_QUOTES):"")?>'></td>
  </tr>
          
  
          
  <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">EMail&nbsp;<img src="images/arrow_derecha.gif"></td>
    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='email' name='email' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($email,ENT_QUOTES):"")?>'></td>
  </tr>
          
  
          
  <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Activo&nbsp;<img src="images/arrow_derecha.gif"></td>
    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input type="hidden" value="activo" name="columnRights[16]"><select id='activo' name='activo' class='comun' <? print($usr->checkUserRights(16)); ?>><option value='S' <?=($activo=='S' or ((count($_GET) == 0) and '1' == '1'))?"selected":"";?>>Si</option><option value='N' <?=($activo=='N' or ((count($_GET) == 0) and '1' == '1'))?"selected":"";?>>No</option></select></td>
  </tr>
          
  
  
        </table></td>
        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#ffffff"></td>
        <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
      </tr>
    </table>
    <br>
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
    <tr>
      <td width="5" align="left" valign="top"><img src="images/corner_si2.gif" width="5" height="5"></td>
      <td align="center" bgcolor="#e5e5e5"></td>
      <td width="5" align="right" valign="top"><img src="images/corner_sd2.gif" width="5" height="5"></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      <td align="center" bgcolor="#e5e5e5"><table width='50%' border="0" cellpadding="0" cellspacing="5">
          <tr>
            <td align="left"><?=($usr->checkUserRights(2) != 'disabled')? '<img onclick=\'javascript:document.location.href="' . $_SERVER["PHP_SELF"] . '";\' src="images/btn_nuevo.gif" style="cursor:pointer;" border="0">':'';?>
            </td>
            <td align='center' style="padding-right:15px;"><img border="0" onclick='document.location="g_usuarios_admin.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" style="cursor:pointer;">
            </td>
            <td align='center' style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" onclick="javascript:set_type(0);" src="images/btn_guardar.gif">'); ?></td>
            <td align='center'><? if(($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" src="images/btn_guardaryvolver.gif" onclick="javascript:set_type(1);" value=\'Guardar y volver al listado\'>'); ?></td>
          </tr>
      </table></td>
      <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
      <td align="center" bgcolor="#e5e5e5"></td>
      <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
    </tr>
  </table>    
  </form>
</div>
</body>
</html>
