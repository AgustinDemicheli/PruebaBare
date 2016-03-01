<?
include_once("class.usuario.php");
include_once("class.lenguaje.php");

$usr = new usuario();
session_start();

if($_POST["usrLogin"]!=""&&$_POST["passLogin"]!="")
{
	// Hay que chequear si el usuario existe.
	$usr->login($_POST["usrLogin"],$_POST["passLogin"],"admin");

	if($usr->logueado("admin"))
	{
		$_SESSION["sessLogueado"]=true;
		$_SESSION["sessUsuario"]=$_POST["usrLogin"];
		$_SESSION["sessPassword"]=$_POST["passLogin"];
		$_SESSION["sessID"]=$usr->_id;
		$_SESSION["sessTipo"]=$usr->_grupodesc;
		$_SESSION["sessTipo_id"]=$usr->_grupo;
		$_SESSION["sessemail"]=$usr->_email;

		/*if($_SERVER["REQUEST_URI"]=="")
			$go=$_SERVER["PHP_SELF"];
		else
			$go=$_SERVER["REQUEST_URI"];
		*/
		$go = "../admin/index.php";

		header("Location: ".$go);
		die();
	}
}


if($_SESSION["sessLogueado"]!=true&&$cancelLogin!=true)
{
	if($_SERVER["REQUEST_URI"]=="")
		$go=$_SERVER["PHP_SELF"];
	else
		$go=$_SERVER["REQUEST_URI"];	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMS <?=$_TITULO_SITE?> :: Ingresar</title>
<link href="css/stylo.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="document.form1.usrLogin.focus();">
<div id="access">
    <div class="container">
    	<h1><img src="images/logo.png"  height="100" /></h1>
        <form name="form1" method="post" action="<?=$go?>">
          <ul>
                <li>
                <label>Usuario:</label>
                <input name="usrLogin" type="text" class="form" id="usrLogin">                                    
                </li>
                <li>
                <label>Contrase&ntilde;a:</label>
                <input name="passLogin" type="password" class="form" id="passLogin">                                    
              </li>
                <li>
                <input name="imageField" type="submit" id="imageField" value="Iniciar sesión" >
              </li>
          </ul>
        </form>
    </div>
</div>
</body>
</html>
<?
	die();
}

// Esta logueado, asi que traemos los permisos para esta pagina, y nos fijamos si puede ir a donde esta , sino reenviamos hacia la homa peage.
$usr->login($_SESSION["sessUsuario"],$_SESSION["sessPassword"],"admin");

if(!ereg($usr->listapaginas(false,"|"),$_SERVER["PHP_SELF"]))
{	
	// No tiene permiso para estar aca, asi que le matamos
?>
<html>
<head><title>>CMS <?=$_TITULO_SITE?> :: Ingresar</title></head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td width="100%" align="center" valign="middle" style="background-image:url(../../images/background_left.jpg); background-position:left top; background-repeat:repeat-y;" >
	<table width="368" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="127" align="left" valign="middle" bgcolor="#FFFFFF" style="padding:20px 10px 20px 10px;"></td>
        <td width="241" valign="middle" style="background-image:url(images/fondo_form.gif); background-repeat:repeat-y; background-position:left; padding:15px 0 15px 0">No tiene permisos para ver esta pagina. Vuela a loguearse <a href="index.php">click aqui</a></td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?
	session_destroy();
	die();
}
?>