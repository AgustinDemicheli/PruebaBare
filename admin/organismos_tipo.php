<? 
				include_once("../includes/DB_Conectar.php");
				include_once("../includes/lib/auth.php");
			

	// Tomamos la data del paginado.
	$cant = 20;


	// Tenemos que tomar la eliminacion de los elementos.
	if(is_array($_POST["delete"])&&count($_POST["delete"])>0)
	{
		foreach($_POST["delete"] as $idtodelete)
		{
			$sql = "update organismos_tipo set estado = 'E' where id = $idtodelete";
			$conn->execute($sql);

			// Insert en tabla de auditoria
			$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $idtodelete . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '8')");
		}
	}
	
	if($_GET["p"] < 1) $page = 1;
	else $page = $_GET["p"];

	$current = ( $page - 1 ) * $cant;

	// Vemos si quizo cambiar algun dato de los que se podian cambiar desde la pagina del listado.
	if(isset($_GET["c"])&&isset($_GET["id"])&&isset($_GET["v"]))
	{
		if(isset($_GET["r"]) && ($_GET["r"])) {
			switch($_GET["r"]) {
				case 16:
					$record_rights = $conn->execute("select " . $_GET["c"] . " from organismos_tipo where id = " . $_GET["id"]);

					if($record_rights->field($_GET["c"]) != $_GET["v"]) {
						// Insert en tabla de auditoria
						$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $_GET["id"] . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '16')");
					}
				break;

				case 32:
					$record_rights = $conn->execute("select " . $_GET["c"] . " from organismos_tipo where id = " . $_GET["id"]);

					if($record_rights->field($_GET["c"]) != $_GET["v"]) {
						// Insert en tabla de auditoria
						if($_GET["v"] == 'E') {
							$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $_GET["id"] . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '8')");
						}else{
							$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $_GET["id"] . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '32')");
						}
					}
				break;
			}
		}

		// deberiamos hacer el update y un location a php_self
		$sql = "update organismos_tipo set ".$_GET["c"]."='".$_GET["v"]."' where id = ".$_GET["id"];
		$conn->execute($sql);

		header("Location: ".$_SERVER["PHP_SELF"]."?orden=".$_GET["orden"]."&p=".$page);
		die();
	}

	if(isset($_GET["a"]))
	{
		if(preg_match("/Desc/",$_GET["a"]))
		{
			$buf = explode("Desc",$_GET["a"]);
			// Me tengo que fijar si $buf[0] esta en $orden, si es asi, le tengo que cambiar el asc por el desc
			if(preg_match("/".$buf[0]."/",$_GET["orden"]))
			{
				// Esta
				$_GET["orden"] = preg_replace("/".$buf[0]."(Asc|)/","/".$buf[0]."Desc/",$_GET["orden"]);
			}else
			{
				// No esta
				$_GET["orden"] .= $_GET["a"];
			}
		}elseif(preg_match("/Asc/",$_GET["a"]))
		{
			$buf = explode("Asc",$_GET["a"]);
			if(preg_match("/".$buf[0]."/",$_GET["orden"]))
			{
				// Esta
				$_GET["orden"] = preg_replace("/".$buf[0]."Desc/",$buf[0]."Asc",$_GET["orden"]);
			}else
			{
				// No esta
				$_GET["orden"] .= $_GET["a"];
			}
		}else
		{
			// Viene algo, asi que deberiamos sacarlo del orden
			$_GET["orden"] = str_replace($_GET["a"]."Asc","",$_GET["orden"]);
		}
	}

	$orden = str_replace("Asc"," asc,",$_GET["orden"]);
	$orden = str_replace("Desc"," desc,",$orden);

	if(substr($orden,strlen($orden)-1,1)==',')
	{
		$orden = substr($orden,0,strlen($orden)-1);
	}

	if(isset($_GET["filtros"])&&$_GET["filtros"]!="")
	{
		// Hay que ver los filtros de aplicarlos
		$campos = array("tipo");
		$buf = explode(" ",$_GET["filtros"]);
		$cond = "";
		switch($_GET["filtros_p"])
		{
			case "T":
				$len = 3;
				foreach($campos as $campo)
				{
					$cond .= " ( ";
					foreach($buf as $palabra)
					{
						$cond .= " ( $campo like '%$palabra%' ) and";
					}
					$cond = substr($cond,0,strlen($cond)-$len);
					$cond .=" ) or ";
				}
				break;

			case "A":
				$len = 2;
				foreach($campos as $campo)
				{
					$cond .= " ( ";
					foreach($buf as $palabra)
					{
						$cond .= " ( $campo like '%$palabra%' ) or";
					}
					$cond = substr($cond,0,strlen($cond)-$len);
					$cond .=" ) or ";
				}
				$len = 3;
				break;

			case "F":
				$len = 2;
				foreach($campos as $campo)
				{
					$cond .= " ( $campo like '%".$_GET["filtros"]."%' ) or";
				}
				break;
		}
		$filtro = "";
		$cond = substr($cond,0,strlen($cond)-$len);
	}

	// Si tenemos permiso para ver todos los records
	if($usr->checkUserRights('1') == 'disabled')
	{
		$rights_select = " and " . $usr->_id . " in (select admin_auditoria.usuario_id from admin_auditoria where admin_auditoria.menu_id = '" . $usr->_menu . "' and admin_auditoria.contenido_id = organismos_tipo.id and admin_auditoria.fecha in (select max(fecha) from admin_auditoria where admin_auditoria.menu_id = '" . $usr->_menu . "' and admin_auditoria.contenido_id = organismos_tipo.id))";
	}else{
		$rights_select = "";
	}

	// Si tenemos permiso para modificar el estado, vemos los eliminados
	if($usr->checkUserRights('32') == 'disabled')
	{
		$rights_select .= " and organismos_tipo.estado != 'E'";
	}else{
		$rights_select .= "";
	}

	// Sacamos la cantidad de elementos que tiene esta tabla.
	$sql = "select count(*) as cuantos from organismos_tipo where 1=1 ".($cond!=""?"and $cond":"").($rights_select!=""?"$rights_select":"");
	$rs = $conn->execute($sql);

	$element_count = $rs->field("cuantos");

	if(strlen($orden) == 0)
		$orden = " id DESC ";

	// Preparamos ahora el query para sacar el listado
	$sql = "select id,tipo,activo from organismos_tipo where 1=1 ".($cond!=""?"and $cond":"").($rights_select!=""?"$rights_select":"")." ".(strlen($orden)>0?"order by ".$orden:"")." limit $current,$cant ";
	$rs = $conn->execute($sql);

	

	// Paginacion
	if($current+$cant<=$element_count) $next_page = $page + 1;
	else $next_page = $page;

	if($current-$cant>=0)
	{
		$prev_page = $page - 1 ;
	}else
	{
		$prev_page = $page;
	}

	$mostrar=6;

	$primera  = $page-abs($mostrar/2);
	$ultima   = $page+abs($mostrar/2);
	$numpages = ceil($element_count/$cant);

	if($primera < 1)
	{
		$primera=1;
		if( $numpages > $mostrar) $ultima=$mostrar; 
		else $ultima=$numpages;
	}

	if($ultima > $numpages)
	{
		$ultima=$numpages;
		$primera=$ultima-$mostrar;
		if($primera < 1)
		{
			$primera=1;
		}
	}

	$paginado = "";

	if($page==$primera)
	{ $prepage = "<font class='nolink'>&lt;&lt;</font> "; }
	else
	{ $prepage = "<a class='link_paginas' href='".$_SERVER["PHP_SELF"]."?p=".$prev_page."&orden=".$_GET["orden"]."&a=$elem&filtros=".$_GET["filtros"]."&filtros_p=".$_GET["filtros_p"]."'>&lt;&lt;</a> "; }

	if($page==$ultima)
	{ $pospage = "<font class='nolink'>&gt;&gt;</font> "; }
	else
	{ $pospage = "<a class='link_paginas' href='".$_SERVER["PHP_SELF"]."?p=".$next_page."&orden=".$_GET["orden"]."&a=$elem&filtros=".$_GET["filtros"]."&filtros_p=".$_GET["filtros_p"]."'>&gt;&gt;</a> "; }
	
	for($i=$primera;$i<=$ultima;$i++)
	{		
		$ir=($i-1)*$cant;
		if ($page==$i)
		{ $paginado .= "<span class='arial14_rojo'> $i</span> . "; }
		else 
		{ $paginado .= "<a class='arial11' href='".$_SERVER["PHP_SELF"]."?p=".$i."&orden=".$_GET["orden"]."&a=$elem&filtros=".$_GET["filtros"]."&filtros_p=".$_GET["filtros_p"]."'>".$i." .</a> "; }
	}		

	$paginado = $prepage.$paginado.$pospage;
?>
		<html>
<head>
	<title><?=$TITULO_SITE?> - Tipos de Organismos</title>
	<link rel="stylesheet" href="css/stylo.css" type="text/css">
	<script language="javascript" src="../includes/validar_datos.js"></script>
	<script language="JavaScript">
	function Chg()
	{
		if(document.getElementById("selTodo").checked==true) value = true;
		else value=false;

		var i=0;
		while(eval("document.frmBorraTodo.chk_" + i))
		{
			eval("document.frmBorraTodo.chk_" + i + ".checked=" + value + ";");
			i++;
		}
	}
	function ir()
	{
		if(document.getElementById('ira').value > <?=$numpages?> || document.getElementById('ira').value < 1 )
		{
			alert("El numero de pagina ingresado no es valido");
			return false;
		}
		document.location.href= "<?=$_SERVER['PHP_SELF']?>?p=" + document.getElementById('ira').value + "&orden=<?=$_GET['orden']?>&a=<?=$elem?>&filtros=<?=$_GET['filtros']?>&filtros_p=<?=$_GET['filtros_p']?>";
	}
	
			function Cambiar(campo,valor,fila,permiso)
			{
				document.location="<?=$_SERVER["PHP_SELF"];?>?orden=<?=$_GET["orden"];?>&p=<?=$page;?>&id="+fila+"&c="+campo+"&v="+valor+"&r="+permiso;
			}
			

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
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body">
<?include_once("_barra.php");?>
<div class="why" id="outerDiv">
	<br>
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Tipos de Organismos</td>
      </tr>
    </table>
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#EEEEEE"></td>
        <td width="196" align="center" bgcolor="#FFFFFF"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
      <tr>
        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5">
				<table width="100%" border="0" style="border-collapse: collapse;" cellspacing="0" cellpadding="5">
				<form method="GET" action="<?=$_SERVER["PHP_SELF"];?>">
				<input type='hidden' name='orden' value='<?=$_GET["orden"];?>'>
				<input type='hidden' name='p' value='1'>
				  
				  <tr> 
					<td height="24" class="arial11" style="padding-bottom:8px;"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="2" class="arial12" style="padding-bottom:4px;"><strong>Buscar por:</strong> Tipo Organismo,</td>
                          </tr>
                          <tr>
                            <td width="144"><input id ='filtros' class="form2" type="textbox" name="filtros" <?=($_GET["filtros"]!=""?"value='".$_GET["filtros"]."'":"");?>></td>
                            <td width="589" style="padding-left:7px;"><input type="image" name="imageField" id="imageField" src="images/btn_buscar.gif"></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="arial11"><input id ='filtros_p' type="radio" name="filtros_p" value='T' <?=($_GET["filtros_p"]=='T'?"checked":(!isset($_GET["filtros_p"])?"checked":""));?>> Todas las palabras 
						<input id ='filtros_p' type="radio" name="filtros_p" value='A' <?=($_GET["filtros_p"]=='A'?"checked":"");?>> Alguna palabra
					<input id ='filtros_p' type="radio" name="filtros_p" value='F' <?=($_GET["filtros_p"]=='F'?"checked":"");?>> Frase exacta</td>
                          </tr>
                        </table>
                        </td>
			      </tr>
				</form>
				</table>
				</td>
        <td align="left" bgcolor="#EEEEEE" style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><input name="imageField" type="image" id="imageField" onclick='document.location="?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif">
          <br /><br />
          <?=($usr->checkUserRights(2) != 'disabled')? '<input name="imageField" type="image" id="imageField" onclick=\'document.location="organismos_tipo_edit.php";\' src="images/btn_nuevo.gif">':''; ?></td>
        <td align="center" bgcolor="#FFFFFF" class="arial11" style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left;"><table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center" class="arial11" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding-bottom:5px;">&nbsp;<strong>P&aacute;gina</strong>  <?=$page;?>  de  <?=ceil($element_count/$cant);?> &nbsp; - <strong> <?=$element_count;?> </strong> elementos</td>
            </tr>
          </table>
            <table width="80%" border="0" cellspacing="0" cellpadding="0">
              <form name="go" method="GET" action="javascript:return ir();">
                <tr>
                  <td align="right" class="arial11" style="padding-top:5px;">Ir a p&aacute;gina:&nbsp;
                      <input class="form" name="ira" id="ira" type="textbox" style="width:25px;">
                    &nbsp;</td>
                  <td align="center" style="padding-top:5px;"><input name="imageField" type="image" id="imageField" onClick="javascript: ir();" src="images/btn_ir.gif"></td>
                </tr>
              </form>
            </table>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" style="padding-top:4px;"> <?=$paginado;?>  </td>
              </tr>
          </table></td>
        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#EEEEEE"></td>
        <td align="center" bgcolor="#FFFFFF"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
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
    <td align="center" bgcolor="#ffffff" style="background-image:url(images/back_bloquecont_border.gif); background-repeat:repeat-x; background-position:top;">&nbsp;</td>
    <td align="center"><table cellspacing="0" cellpadding="0" width="770" align="center" class="tablaGrande">
      <tr>
        <td width="780" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
            <tr class="tablaOscuro">
              <td height="24" class="titulooferta"><div align="center">
                  <input id='selTodo' type='checkbox' onclick='Chg();' >
              </div></td>
              <td height="24" class="titulooferta">&nbsp;</td>
              
              
              <td height="24" align="center" class="arial12"><div align="center"><strong><?
						$elem = "";
						$imaOrden = "";

					if(preg_match("/tipo"."Desc/",$_GET["orden"]))
					{
						$elem = "tipo"."Asc";
						$imaOrden = "<img src='images/flecha_down.gif' >";

					}elseif(preg_match("/tipo"."Asc/",$_GET["orden"]))
					{
						$elem = "tipo";
						$imaOrden = "<img src='images/flecha_up.gif'>";
					}else
					{
						$elem .= "tipo"."Desc";
					}

					?><a href='<?=$_SERVER["PHP_SELF"];?>?orden=<?=$_GET["orden"];?>&a=<?=$elem;?>&filtros=<?=$_GET["filtros"];?>&filtros_p=<?=$_GET["filtros_p"];?>'>Tipo Organismo</a>&nbsp;<?=$imaOrden;?></strong></div></td>
              
              
              
              <td height="24" align="center" class="arial12"><div align="center"><strong><?
						$elem = "";
						$imaOrden = "";

					if(preg_match("/activo"."Desc/",$_GET["orden"]))
					{
						$elem = "activo"."Asc";
						$imaOrden = "<img src='images/flecha_down.gif' >";

					}elseif(preg_match("/activo"."Asc/",$_GET["orden"]))
					{
						$elem = "activo";
						$imaOrden = "<img src='images/flecha_up.gif'>";
					}else
					{
						$elem .= "activo"."Desc";
					}

					?><a href='<?=$_SERVER["PHP_SELF"];?>?orden=<?=$_GET["orden"];?>&a=<?=$elem;?>&filtros=<?=$_GET["filtros"];?>&filtros_p=<?=$_GET["filtros_p"];?>'>Activo</a>&nbsp;<?=$imaOrden;?></strong></div></td>
              
              
            </tr>
          <form method='POST' action='<?=$_SERVER["PHP_SELF"];?>?menu=<?=$usr->_menu;?>' name='frmBorraTodo'>
          
          <!-- Inicio de la generacion  -->
          <? if($rs->numrows>0) $chek=0; while(!$rs->eof) { ?>
          <? if($usr->_menuRights != '-1') { switch($rs->field('estado')) { case 'A': $status_color = '#BBEEBB'; break; case 'P': $status_color = '#AADDEE'; break; case 'D': $status_color = '#FFBBBB'; break; case 'E': $status_color = '#BBBBBB'; break; } print('<tr style="background-image:url(\'images/separador_h1.gif\'); background-repeat:repeat-x; background-position:bottom;">'); }else{ print('<tr style="background-image:url(\'images/separador_h1.gif\'); background-repeat:repeat-x; background-position:bottom;">'); } ?>
			
          <td height="19" class="textooferta"><div align="center">
            <input type='checkbox' id="chk_<?=$chek;?>" name='delete[<?=$chek;?>]' value='<?=$rs->field("id");?>'>
          </div></td>
            <td height="19" class="textooferta" align='center'><a href='organismos_tipo_edit.php?menu=<?=$usr->_menu;?>&id=<?=$rs->field("id");?>' class="textomediano"> <img alt='Edit' border=0 src="images/icon_editar.gif"> </a> </td>
            
            
            <td class="textooferta" align="center"><span class="textomediano"><?=($rs->field("tipo")<>""?$rs->field("tipo"):"&nbsp;");?></span> </td>
            
            
            
            <td class="textooferta" align="center"><span class="textomediano"><select onchange='Cambiar("activo",document.getElementById("activo<?=$rs->field("id");?>").options[document.getElementById("activo<?=$rs->field("id");?>").options.selectedIndex].value,<?=$rs->field("id");?>,-1);' id='activo<?=$rs->field("id");?>' name='activo<?=$rs->field("id");?>' class='comun' <? print($usr->checkUserRights(-1)); ?>><option value='S' <?=($rs->field("activo") == 'S')?"selected":"";?>>Si</option><option value='N' <?=($rs->field("activo") == 'N')?"selected":"";?>>No</option></select></span> </td>
            
            
          </tr>
          <? $rs->movenext(); $chek++; } ?>
          <!-- Finalizacion de la generacion de campos mostrados -->
          <tr>
            <td class="textooferta" colspan="11" style="padding-top:6px;"><?=($usr->checkUserRights(8) != 'disabled')? '<input name="imageField" type="image" id="imageField" title="Eliminar Seleccionados" src="images/btn_eliminar2.gif">':''; ?>
            </td>
          </tr>
          </form>
          
          </table>
           </td>
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
<!--  Paginacion -->
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
  <tr>
    <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
    <td width="480" align="center" bgcolor="#e5e5e5"></td>
    <td width="94" align="center" bgcolor="#EEEEEE"></td>
    <td width="196" align="center" bgcolor="#FFFFFF"></td>
    <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
    <td align="center" bgcolor="#e5e5e5">
				<table width="100%" border="0" style="border-collapse: collapse;" cellspacing="0" cellpadding="5">
				<form method="GET" action="<?=$_SERVER["PHP_SELF"];?>">
				<input type='hidden' name='orden' value='<?=$_GET["orden"];?>'>
				<input type='hidden' name='p' value='1'>
				  
				  <tr> 
					<td height="24" class="arial11" style="padding-bottom:8px;"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="2" class="arial12" style="padding-bottom:4px;"><strong>Buscar por:</strong> Tipo Organismo,</td>
                          </tr>
                          <tr>
                            <td width="144"><input id ='filtros' class="form2" type="textbox" name="filtros" <?=($_GET["filtros"]!=""?"value='".$_GET["filtros"]."'":"");?>></td>
                            <td width="589" style="padding-left:7px;"><input type="image" name="imageField" id="imageField" src="images/btn_buscar.gif"></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="arial11"><input id ='filtros_p' type="radio" name="filtros_p" value='T' <?=($_GET["filtros_p"]=='T'?"checked":(!isset($_GET["filtros_p"])?"checked":""));?>> Todas las palabras 
						<input id ='filtros_p' type="radio" name="filtros_p" value='A' <?=($_GET["filtros_p"]=='A'?"checked":"");?>> Alguna palabra
					<input id ='filtros_p' type="radio" name="filtros_p" value='F' <?=($_GET["filtros_p"]=='F'?"checked":"");?>> Frase exacta</td>
                          </tr>
                        </table>
                        </td>
			      </tr>
				</form>
				</table>
				</td>
    <td align="left" bgcolor="#EEEEEE" style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><input name="imageField2" type="image" id="imageField2" onclick='document.location="?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif">
      <br /><br />
      <?=($usr->checkUserRights(2) != 'disabled')? '<input name="imageField" type="image" id="imageField" onclick=\'document.location="organismos_tipo_edit.php";\' src="images/btn_nuevo.gif">':''; ?></td>
    <td align="center" bgcolor="#FFFFFF" class="arial11" style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left;"><table width="95%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" class="arial11" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding-bottom:5px;">&nbsp;<strong>P&aacute;gina</strong>  <?=$page;?>  de  <?=ceil($element_count/$cant);?> &nbsp; - <strong> <?=$element_count;?> </strong> elementos</td>
      </tr>
    </table>
        <table width="80%" border="0" cellspacing="0" cellpadding="0">
          <form name="go" method="GET" action="javascript:return ir();">
            <tr>
              <td align="right" class="arial11" style="padding-top:5px;">Ir a p&aacute;gina:&nbsp;
                  <input class="form" name="ira2" id="ira2" type="textbox" style="width:25px;">
                &nbsp;</td>
              <td align="center" style="padding-top:5px;"><input name="imageField2" type="image" id="imageField2" onClick="javascript: ir();" src="images/btn_ir.gif"></td>
            </tr>
          </form>
        </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" style="padding-top:4px;"> <?=$paginado;?>  </td>
          </tr>
      </table></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
    <td align="center" bgcolor="#e5e5e5"></td>
    <td align="center" bgcolor="#EEEEEE"></td>
    <td align="center" bgcolor="#FFFFFF"></td>
    <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
  </tr>
</table>
<!-- Fin Paginacion -->
</div>
</body>
</html>
