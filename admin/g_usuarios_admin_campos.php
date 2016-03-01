<?
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");


// Cortemos la ejecucion si alguno de los parametros necesarios no viene
if(!isset($_GET["id"])) 
{
	header("Location: g_usuarios_admin.php");
	die();
}

// Chequeamos los permisos de usuario para poder ver si puede o noacceder a esta seccion de la pagina.

// Si el usuario se va a editar a el mismo, no puede hacerlo salvo que sea root, aunque eso no tiene demaciado sentido.
$usuarioEditando = usuario::ObtenerTipo($_GET["id"]);

if($usr->_grupodesc=="Root")
{
	// No deberiamos hacer nada, si es root, podria editar a todos
	// Como el sistema no da la posibilidad de que existan otros roots, no hay problema con esto...
}
if($usr->_grupodesc=="Administrador")
{
	if(
		($usuarioEditando == "Administrador" && $_GET["id"]!=$usr->_id)		// Si es administrador editando administrador
		||
		($usuarioEditando=="Root")											// Si es administrador editando root
		||
		($usuarioEditando == "Usuario" && usuario::ObtenerPadre($_GET["id"])!=$usr->_id)	// Si es administrador editando usuarios de otro administrador
	  )
	{
		header("Location: g_usuarios_admin.php");
		die();
	}
}
if($usr->_grupodesc=='Usuarios')
{
	// Si es usuario se tiene que ir de aca, los usuarios no pueden editar los campos que pueden editar.
	header("Location: g_usuarios_admin.php");
	die();
}

if(isset($_POST)&&count($_POST)>0)
{
	$sql = "delete from admin_usuarios_campo where usuario_id = ".$_POST["usuario_id"];
	//echo $sql."<br>";
	$conn->execute($sql);

	if(count($_POST["campos"])>0)
	{
		foreach($_POST["campos"] as $key => $value)
		{
			$buf = split("_",$value);
			if(count($buf)==2)
			{
				// Calculo que es un permiso.
				$sql = "insert into admin_usuarios_campo (usuario_id,campo_id,visible,editable) values (".$_POST["usuario_id"].",".$buf[1].",'N','S') ";
				//echo $sql."<br>";
				$conn->execute($sql);
			}
		}
	}

	header("Location: ".$_SERVER["PHP_SELF"]."?id=".$_POST["id"]);
	die();
}

?><HTML>
<HEAD>
  <Title><?=$TITULO_SITE?></Title>
  <link rel="stylesheet" href="css/stylo.css" type="text/css">
  <script language="javascript" src="../includes/validar_datos.js"></script>
</HEAD>
<BODY class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll="yes">
<? include_once("menu.php"); ?>
	<DIV class="why" id="outerDiv">
	<table class="tablaGrande" cellspacing="0" cellpadding="0"  align=center>
        <tr valign="top"> 
          <td width="10" height="15" class="norepeatx" background="images/tile_combos_i.gif"><img src="images/curvita_combos_d_top.gif" width="10" height="31"></td>
          <td height="15" background="images/tile_combos_top.gif" class="norepeaty" valign="middle"> 
            <div align="center"> 
              <p class="titulooferta">Permisos Usuarios</p>
            </div>
          </td>
          <td width="10" height="15" background="images/tile_combos_d.gif" class="norepeatx"> 
            <div align="right"><img src="images/curvita_combos_i_top.gif" width="10" height="31"></div>
          </td>
        </tr>
        <tr> 
          <td background="images/tile_combos_i.gif" height="272" width="10" class="norepeatx">&nbsp;</td>
          <td  valign="top"> 
            	<br>
				<!--Este es el contenido-->
				<form method="post" id="frmID" action='<?=$_SERVER["PHP_SELF"];?>'>
				<input type='hidden' name='id' value='<?=$_GET["id"];?>'>
				<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#637EAD">
				  <tr>
				    <td class="tablaOscuro">
						<input type='button' onclick='document.location="g_usuarios_admin.php";' value='Volver' style='font-family:verdana; font-size:9px; color:black;border: solid 1px black; background-color:#eeefff;'  >
					  </td>
					  <td align="right" class="tablaOscuro" height=25>
						<input type='submit' value='Guardar' style='font-family:verdana; font-size:9px; color:black;border: solid 1px black; background-color:#eeefff;'  >
					  </td>
					 </tr>
				</table>
				<br><br>
<style>
TD
{
	font-family: verdana;
	font-size: 10px;
}
TD.titulo
{
	font-family:verdana;
	font-size:12px;

/*	font-weight: bold;*/
	text-align:center;
}

TD.titulo_tabla
{
	font-family:verdana;
	font-size:12px;
	font-weight: bold;
	background-color: #d0d0d0;
	border-bottom: solid 1px #000000;
}

TD.campos_tabla
{
	font-family:verdana;
	font-size:10px;
	background-color: #f0f0f0;
}

TABLE.tabla_tablas
{
	border: solid 1px #000000;
}

INPUT.enviar
{
	font-family:verdana;
	font-size: 9px;

	border: solid 1px #000000;
	background-color: #f0f0f0;

	width:250px;
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
<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#666699">
<tr>
  <td>
<?
$columnas = 3;

$sql = "select * from admin_usuarios where id = ".$_GET["id"];
$rs = $conn->execute($sql);

$apellido = $rs->field("apellido");
$nombre = $rs->field("nombre");
?>
<form method='POST' action='<?=$_SERVER["PHP_SELF"];?>'>
<input type='hidden' name='usuario_id' value='<?=$_GET["id"];?>'>
<table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
<tr><td colspan='<?=$columnas;?>' class='titulo'>Campos <b>NO</b> editables por <?=$apellido;?>, <?=$nombre;?></td></tr>
<tr><td colspan='<?=$columnas;?>'>&nbsp;</td></tr>
<tr><td colspan='<?=$columnas;?>' align='center'><input type='submit' value='Setear' class='enviar'></td></tr>
<tr><td colspan='<?=$columnas;?>'>&nbsp;</td></tr>
<?
$i=0;

// Traigo en un array todos los eleme
$arrCampos = array();

$sql = "select * from admin_usuarios_campo where usuario_id = ".$_GET["id"];
$rs = $conn->execute($sql);

while(!$rs->eof)
{
	array_push($arrCampos,$rs->field("campo_id"));
	$rs->movenext();
}

$sql = "select * from admin_tablas order by nombre";
$rs = $conn->execute($sql);
?>
<tr>
<?
	for($col=0;$col<$columnas;$col++)
	{
?>

  <td width='<?=floor(100/$columnas);?>%' valign='top' align='center'>
<?
		for(;$i<(floor($rs->numrows/$columnas)*($col+1));$i++)
		{
?>
    <table width='95%' cellspacing='0' cellpadding='0' border='0' class='tabla_tablas' >
	<tr>
	  <td class='titulo_tabla' width='20'><input type='checkbox' name='campos[]' value='<?=$rs->field("id");?>' onclick='Chk(this);'></td>
	  <td class='titulo_tabla' colspan='2'>&nbsp;<?=$rs->field("nombre");?></td>
	</tr>
<?
			$sql = "select * from admin_campos where tabla_id = ".$rs->field("id")." order by nombre";
			$rs2 = $conn->execute($sql);

			while(!$rs2->eof)
			{
?>
	<tr>
	  <td class='campos_tabla' width='20'>&nbsp;</td>
	  <td class='campos_tabla' width='20'><input type='checkbox' name='campos[]' value='<?=$rs->field("id");?>_<?=$rs2->field("id");?>' <?=(in_array($rs2->field("id"),$arrCampos)?"checked":"");?>></td>
	  <td class='campos_tabla'>&nbsp;<?=$rs2->field("nombre");?></td>
	</tr>
<?
				$rs2->movenext();
			}
?>
	</table>&nbsp;
<?
			$rs->movenext();
		}
	}
?>
  </td>
</tr>
</table>

	</td>
  </tr>
</table>

</form>

				<!--Hasta aca este es el contenido-->		
				<br><br>
				<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#637EAD">
				  <tr>
				    <td class="tablaOscuro">
						<input type='button' onclick='document.location="g_usuarios_admin.php";' value='Volver' style='font-family:verdana; font-size:9px; color:black;border: solid 1px black; background-color:#eeefff;'  >
					  </td>
					  <td align="right" class="tablaOscuro" height=25>
						<input type='submit' value='Guardar' style='font-family:verdana; font-size:9px; color:black;border: solid 1px black; background-color:#eeefff;'  >
					  </td>
					 </tr>
				</table>
				</form>				      
           </td>
           <td background="images/tile_combos_d.gif" height="272" width="10" class="norepeatx"><br></td>
        </tr>
        <tr valign="top" > 
          <td height="2" width="10" class="norepeatx"><img src="images/curvita_combos_d_abajo.gif" width="10" height="11"></td>
          <td height="2" background="images/tile_combos_abajo.gif" width="610" class="norepeaty"></td>
          <td  height="2" width="10" class="norepeatx"> 
            <div align="right"><img src="images/curvita_combos_i_abajo.gif" width="10" height="11"></div>
          </td>
        </tr>
      </table>
      <br>
</div>
</BODY>
</HTML>