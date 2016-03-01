<? 

include_once("../includes/DB_Conectar.php"); 

include_once("../includes/lib/auth.php");



if(is_array($_POST)&&count($_POST)>1&&isset($_POST["save_type"])) 

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


$sql = "update organismos set id_tipo_organismo = '".$_POST['id_tipo_organismo']."',id_padre = '".$_POST['id_padre']."',nombre = '".$_POST['nombre']."',direccion = '".$_POST['direccion']."',id_comuna = '".$_POST['id_comuna']."',telefonos = '".$_POST['telefonos']."',mails = '".$_POST['mails']."',horarios = '".$_POST['horarios']."',web = '".$_POST['web']."',activo = '".$_POST['activo']."',latitud = '" . $_POST['latitud'] . "' ,longitud = '" . $_POST['longitud'] . "', mapa_tipo = '" . $_POST['mapa_tipo'] . "',mapa_zoom = '" . $_POST['mapa_zoom'] . "' where id = ".$_POST["id"];

$conn->execute($sql); 

$id = $_POST["id"];


// Insert en tabla de auditoria

$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '4')");

	} 

	else 

	{ 

$sql = "insert into organismos (id_tipo_organismo,id_padre,nombre,direccion,id_comuna,telefonos,mails,horarios,web,activo,latitud,longitud,mapa_tipo,mapa_zoom) values ('".$_POST['id_tipo_organismo']."','".$_POST['id_padre']."','".$_POST['nombre']."','".$_POST['direccion']."','".$_POST['id_comuna']."','".$_POST['telefonos']."','".$_POST['mails']."','".$_POST['horarios']."','".$_POST['web']."','".$_POST['activo']."', '" . $_POST['latitud'] . "' , '" . $_POST['longitud'] . "', '" . $_POST['mapa_tipo'] . "', '" . $_POST['mapa_zoom'] . "')"; 

$conn->execute($sql); 

$rs = $conn->execute("select last_insert_id()"); 

$id = $rs->field(0);


// Insert en tabla de auditoria

$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '2')");

	}

	

	if($_POST["save_type"]=="1")

	{

header("Location: organismos.php?p=".$_REQUEST['p']."&orden=".$_REQUEST['orden']."&filtros=".$_REQUEST['filtros']."&filtros_p=".$_REQUEST['filtros_p']);

die();

	}

	else

	{

header("Location: ".$_SERVER["PHP_SELF"]."?id=".$id."&p=".$_REQUEST['p']."&orden=".$_REQUEST['orden']."&filtros=".$_REQUEST['filtros']."&filtros_p=".$_REQUEST['filtros_p']); 

die(); 

	}

} 





if(isset($_GET["id"])) 

{ 

	$sql = "select * from organismos where id = ".$_GET["id"]; 

	$rs = $conn->execute($sql); 

	foreach($rs->recordset() as $key => $value) 

	{ 

$$key = $value; 

	} 

} 



?><html>
    <head>
        <title><?=$TITULO_SITE?> - Organismos</title>
        <link rel="stylesheet" href="css/stylo.css" type="text/css">
        <script type='text/javascript' src='../includes/lib/jQuery/jquery.js'></script>
        <script type='text/javascript' src='../includes/validar_datos.js'></script>
        <script type='text/javascript'>
            function Chk() {
                
                return true;
            }
            function set_type(valor) {
                document.getElementById("save_type").value = valor;
            }
        </script>
        

<script language='Javascript' src='../includes/lib/jQuery/jquery.js'></script>

<script language='Javascript' src='contenidos_edit.js'></script>

<script type='text/javascript' src='../includes/lib/DOMWindow/jquery.DOMWindow.js'></script>



	<script language="javascript" type="text/javascript" src="calendar/calendario.js"></script>

	<script language="javascript">

var calendar = new CalendarPopup("calendar");

calendar.setCssPrefix("calendario");

	</script>

	<link rel="stylesheet" href="calendar/calendario.css" type="text/css">

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="js/maps/googlemaps.js"></script>

    </head>
    <body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll='no'>
        <?include_once("_barra.php");?>
        <div class="why" id="outerDiv">  <br>
            <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Organismos</td>
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
                                    <td align='center' style="padding-right:15px;"><img onclick='document.location = "organismos.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">
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
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Tipo de organismo&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><select id='id_tipo_organismo' name='id_tipo_organismo' class='comun' <? print($usr->checkUserRights(-1)); ?>><? $sql = "select id, tipo FROM organismos_tipo WHERE activo='S' order by tipo"; $rs = $conn->execute($sql); while(!$rs->eof) { echo "<option value='".$rs->field(0)."' ".($id_tipo_organismo==$rs->field(0)?"selected":"").">".$rs->field(1)."</option>"; $rs->movenext();} ?></select></td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Organismo Padre&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><select id='id_padre' name='id_padre' class='comun' <? print($usr->checkUserRights(-1)); ?>><? $sql = "select 0 as id, '-----------' as nombre union select id, nombre FROM organismos WHERE activo='S' ORDER BY nombre"; $rs = $conn->execute($sql); while(!$rs->eof) { echo "<option value='".$rs->field(0)."' ".($id_padre==$rs->field(0)?"selected":"").">".$rs->field(1)."</option>"; $rs->movenext();} ?></select></td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Nombre&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='nombre'  name='nombre' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($nombre,ENT_QUOTES):"")?>'>

</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Direccion&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><textarea  id='direccion' name='direccion' class='comun'><?=(isset($id)?$direccion:"")?></textarea>

</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Comuna&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><select id='id_comuna' name='id_comuna' class='comun' <? print($usr->checkUserRights(-1)); ?>><? $sql = "select id, CONCAT(numero,' - ',descripcion) AS titulo FROM comunas WHERE activo='S' order by numero"; $rs = $conn->execute($sql); while(!$rs->eof) { echo "<option value='".$rs->field(0)."' ".($id_comuna==$rs->field(0)?"selected":"").">".$rs->field(1)."</option>"; $rs->movenext();} ?></select></td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Telefonos&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><textarea  id='telefonos' name='telefonos' class='comun'><?=(isset($id)?$telefonos:"")?></textarea>

</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Correos electronicos&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><textarea  id='mails' name='mails' class='comun'><?=(isset($id)?$mails:"")?></textarea>

</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Horarios&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><textarea  id='horarios' name='horarios' class='comun'><?=(isset($id)?$horarios:"")?></textarea>

</td>
                                </tr>
                                
                                
                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Web&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input id='web'  name='web' type='textbox' class='comun' value='<?=(isset($id)?htmlentities($web,ENT_QUOTES):"")?>'>

</td>
                                </tr>
                                
 
                            <?
                            //verifico si tengo coordenadas definidas
                            $coordenadas = array();
                            if ($latitud != "" && $longitud != "") {
                                $coordenadas[0] = $latitud; //lat
                                $coordenadas[1] = $longitud; //long
                            } else {
                                $coordenadas[0] = "-34.651285198954135"; //lat
                                $coordenadas[1] = "-58.77685546875"; //long
                                $mapa_tipo = "google.maps.MapTypeId.ROADMAP";
                                $mapa_zoom = 10;
                            }
                            ?>

                                <tr <? (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>

                                    <td width='10%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Mapa&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="90%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <script language="javascript">
    var ObjMapa;
    $(document).ready(function() {
        ObjMapa = $("#map_mapa2").mapaTelam({
            'IdBuscador': {'idBuscador': 'search', 'idBuscadorListado': 'suggest_list'},
            'beforeSelected': CargarIds,
            'onChangeZoom': ModificarZoom,
            'onChangeMapType': CargarTipoMapa,
            'zoom': <?= $mapa_zoom ?>,
            'lat': <?= $coordenadas[0] ?>,
            'long': <?= $coordenadas[1] ?>,
            'MultipleMarkers': false
        }
        );
        ObjMapa.Inicializate();
        ObjMapa.AcceptAddMarkerButton("rightclick");
<?
if ($latitud != "" && $longitud != "") {
    ?>
            ObjMapa.AddMarker(<?= $latitud ?>,<?= $longitud ?>);
    <?
}
?>

        $("#showMap").click(function() {
            ObjMapa = $("#map_mapa2").mapaTelam({
                'IdBuscador': {'idBuscador': 'search', 'idBuscadorListado': 'suggest_list'},
                'beforeSelected': CargarIds,
                'onChangeZoom': ModificarZoom,
                'onChangeMapType': CargarTipoMapa,
                'zoom': <?= $mapa_zoom ?>,
                'lat': <?= $coordenadas[0] ?>,
                'long': <?= $coordenadas[1] ?>,
                'MultipleMarkers': false,
                'RemoveMarkerClick': true,
            }
            );
            ObjMapa.Inicializate();
            ObjMapa.AcceptAddMarkerButton("rightclick");
<?
if ($latitud != "" && $longitud != "") {
    ?>
                ObjMapa.AddMarker(<?= $latitud ?>,<?= $longitud ?>);
    <?
}
?>
        });

    });

    function ModificarZoom(zoom)
    {
        $("#mapa_zoom").val(zoom);
    }

    function CargarTipoMapa(type)
    {
        $("#mapa_tipo").val(type);
    }


    function CargarIds(e, location)
    {
        $("#latitud").val(location.lat());
        $("#longitud").val(location.lng());
        console.warn(e);
        $("#mapa_zoom").val(e.getZoom());
        $("#mapa_tipo").val("google.maps.MapTypeId." + e.getMapTypeId());
    }

    function limpiarCoordenadasNota() {
        $("#latitud").val("");
        $("#longitud").val("");
        $("#mapa_zoom").val("");
        $("#mapa_tipo").val("");
    }
                                        </script>

                                        <br />
                                        Coordenadas Lat<input type="text" class='comun' name="latitud" id="latitud" value = '<?= $latitud ?>' style="width:120px" readonly> - Long<input type="text" class='comun' name="longitud" id="longitud" value = '<?= $longitud ?>' style="width:120px" readonly>
                                        &nbsp; &nbsp;Tipo<input type="text" class='comun' name="mapa_tipo" id="mapa_tipo" value = '<?= $mapa_tipo ?>' style="width:200px" readonly>
                                        &nbsp; &nbsp;Zoom<input type="text" class='comun' name="mapa_zoom" id="mapa_zoom" value = '<?= $mapa_zoom ?>' style="width:25px" readonly>
                                        &nbsp; &nbsp;<input type="button" class='comun' name="limpiar_coordenadas" id="limpiar_coordenadas" onClick="javascript:limpiarCoordenadasNota();" value="Borrar coordenadas" style="width:150px !important;">
                                        <br/>
                                        <div class="columna_contenido" id="map_mapa2"  style="display:block;position:relative;width:800px !important;height:400px !important;margin:10px 10px 10px 10px"></div><br />Click derecho para marcar la coordenada en el mapa. Click sobre el punto para eliminar la coordenada seleccionada
                                        <br />Direcci&oacute;n<input type="text" class='comun' id="search" onKeyUp="geocode()" autocomplete="off" value = '' style="width:250px;">
                                        <br/><ol id="suggest_list"></ol>
                                    </td>
                                </tr>								

                                
                                <tr <?=(false == true && $bilingual == false)? "style=\"display: none;\"":"";?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Activo&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"><input type="hidden" value="activo" name="columnRights[16]"><select id='activo' name='activo' class='comun' <? print($usr->checkUserRights(16)); ?>><option value='S' <?=($activo=='S' or ((count($_GET) == 0) and '1' == '1'))?"selected":"";?>>Si</option><option value='N' <?=($activo=='N' or ((count($_GET) == 0) and '' == '1'))?"selected":"";?>>No</option></select></td>
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
                                    <td align='center' style="padding-right:15px;"><img border="0" onclick='document.location = "organismos.php?=menu=<?=$usr->_menu;?>";' src="images/btn_volver.gif" style="cursor:pointer;">
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
            <br />
            <br />
        </div>
    </body>
</html>
