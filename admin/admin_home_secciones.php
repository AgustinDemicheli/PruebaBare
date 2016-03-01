<?php
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");
$id_seccion = intval($_GET["id_seccion"]);
$id_tema = intval($_GET["id_tema"]);
$nombre_seccion = $_REQUEST["nombre_seccion"];

if ($id_seccion > 0) {
    $contenidos = GetContenidosPorSeccionYTema($id_seccion, $id_tema);
}


if (!empty($_POST["id_seccion"])) {

    if (intval($_POST["contenido_destacado_activo"]) == 0) {
        $contenido_1 = 0;
    } else {
        $contenido_1 = intval($_POST["contenido_1"]);
    }

    if (intval($_POST["modulo_3_notas_activo"] == 0)) {
        $contenido_2 = 0;
        $contenido_3 = 0;
        $contenido_4 = 0;
    } else {
        $contenido_2 = intval($_POST["contenido_2"]);
        $contenido_3 = intval($_POST["contenido_3"]);
        $contenido_4 = intval($_POST["contenido_4"]);
    }


    $id_seccion = intval($_POST["id_seccion"]);
    $id_tema = intval($_POST["id_tema"]);

    $q = "DELETE FROM home_secciones WHERE id_seccion = {$id_seccion} and id_tema = {$id_tema}";
    $conn->getRecordset($q);

    $q = "INSERT INTO home_secciones SET 
					id_seccion = {$id_seccion},
                    id_tema = {$id_tema},
					id_contenido_1 = {$contenido_1},
					id_contenido_2 = {$contenido_2},
					id_contenido_3 = {$contenido_3},
					id_contenido_4 = {$contenido_4}";
    $conn->getRecordset($q);


    /*   */
    include_once("generador_contenidos.php");
    if ($id_seccion > 0) {
        generarPortadaCategoria($id_seccion, $id_tema);
    }


    header("Location: admin_home_secciones.php?id_seccion=" . $id_seccion . "&id_tema=" . $id_tema ."&menu=" . $_GET["menu"] ."&nombre_seccion=" . $nombre_seccion);
    die();
}
if ($id_seccion > 0) {
    $contenido_seccion = GetContenidosSeteadosAdminPortadaSeccion($id_seccion, $id_tema);
}
?>
<html>
    <title><?= $TITULO_SITE ?> - Admin Home Secciones</title>
    <head>
        <script type='text/javascript' src='../includes/lib/jQuery/jquery.js'></script>        
        <script type="text/javascript" src="../includes/lib/DOMWindow/jquery.DOMWindow.js"></script>
        <script type='text/javascript' src='contenidos_edit.js'></script>
        <link rel="stylesheet" href="css/stylo.css" type="text/css">
    </head>
    <body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll='no'>
        <? include_once("_barra.php"); ?>
        <div class="why" id="outerDiv">  <br>
            <form method="post" name="formedit" action='<?= $_SERVER["PHP_SELF"]; ?>?menu=<?= $usr->_menu; ?>&id_seccion=<? echo $id_seccion ?>&id_tema=<?php echo $id_tema;?>&nombre_seccion=<?php echo $nombre_seccion; ?>'>
                <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" class="Title" style="padding-bottom:10px;"><?= $TITULO_SITE ?> - Admin Home <strong style="color: #0a1d6f"><?php echo $nombre_seccion ?></strong></td>
                    </tr>
                </table>
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
                                    <td align="left">
                                    </td>
                                    <td align='center' style="padding-right:15px;">
                                            <img onclick='goBack();' src="images/btn_volver.gif" border="0" style="cursor:pointer;">
                                    </td>
                                    <td align='center' style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><? if (($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" onclick="javascript:set_type(0);" src="images/btn_guardar.gif">'); ?></td>
                                    <td align='center'></td>
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
                <!-- TABLA CAMPOS -->

                <input id='id_seccion' name='id_seccion' type='hidden' value='<?= ($id_seccion) ?>'>
                <input id='id_seccion' name='id_tema' type='hidden' value='<?= ($id_tema) ?>'>
                <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
                    <tr>
                        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#ffffff"></td>
                        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
                    </tr>

                    <!-- Activar Contenido Destacado -->
                    <?php
                    $displayDestacado = 'none';
                    $checkedDestacado = '';
                    if (intval($contenido_seccion["id_contenido_1"]) > 0) {
                        $displayDestacado = '';
                        $checkedDestacado = 'checked="checked"';
                    }
                    ?>
                    <tr>
                        <td align="center" bgcolor="#ffffff">&nbsp;</td>
                        <td align="center" bgcolor="#FFFFFF" class="arial12 separadorLineaPunteada">
                            <label>Activar contenido destacado:</label>
                            <input type="checkbox" <?= $checkedDestacado ?> id="contenido_destacado_activo" name='contenido_destacado_activo' value="1" />
                        </td>
                        <td align="center" bgcolor="#ffffff"></td>
                    </tr>
                    <!-- Fin Contenido Destacado -->
                    <!-- Contenido Destacado -->
                    <tr id="tbl_contenido_destacado" >
                        <td align="center" bgcolor="#ffffff">&nbsp;</td>
                        <td align="center" bgcolor="#FFFFFF">
                            <table   width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse;">
                                <tr>
                                    <td align="center" class='arial12 separadorLineaPunteada' >
                                        <table  width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse;">
                                            <tr>
                                                <td width='40%' align="right" valign="top" class='arial12' >Contenido Destacado&nbsp;<img src="images/arrow_derecha.gif"></td>
                                                <td width="15%" class='arial11' >
                                                    <select id="contenido_1" name="contenido_1">
                                                        <option value="">Seleccione</option>
                                                        <?php for ($i = 0; $i < count($contenidos); $i++) { ?>
                                                            <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_1"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="45%" class='arial11' >
                                                    <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=contenido_1" class="openBuscador">Buscar</a>
                                                    &nbsp;
                                                    <a href="#0" onclick="OpenPopUpEdit($('#contenido_1').val())">Quick Edit</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>

                    <!-- Activar Modulo 3 notas -->
                    <?php
                    $displayModNotas = 'none';
                    $checkedModNotas = '';
                    if (intval($contenido_seccion["id_contenido_2"]) > 0
                            && intval($contenido_seccion["id_contenido_3"]) > 0
                            && intval($contenido_seccion["id_contenido_4"]) > 0) {
                        $displayModNotas = '';
                        $checkedModNotas = 'checked="checked"';
                    }
                    ?>
                    <tr>
                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="center" bgcolor="#FFFFFF" class="arial12 separadorLineaPunteada">
                            <label>Activar módulo 3 Notas:</label>
                            <input type="checkbox"<?= $checkedModNotas ?> name="modulo_3_notas_activo" id="modulo_3_notas_activo" value="1"  />
                        </td>
                        <td align="center" bgcolor="#ffffff"></td>
                    </tr>
                    <!-- Fin Activar Modulo 3 notas -->
                    <!-- Contenido 2 -->
                    <tr class="tbl_modulo_3_notas" >

                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="center" bgcolor="#FFFFFF">
                            <table style="border-collapse: collapse;" width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse;">
                                <tr>
                                    <td id="td_contenido_dest2" width='40%' align="right" valign="top" class='arial12 separadorLineaPunteada' >
                                        Contenido Auxiliar 1<img src="images/arrow_derecha.gif">
                                    </td>
                                    <td width="15%" class='arial11 separadorLineaPunteada' >
                                        <select id="contenido_2" name="contenido_2">
                                            <option value="">Seleccione</option>
                                            <?php for ($i = 0; $i < count($contenidos); $i++) { ?>
                                                <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_2"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td width="45%" class='arial11 separadorLineaPunteada'>
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=contenido_2" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#contenido_2').val())">Quick Edit</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>

                    <!-- Contenido 3 -->
                    <tr class="tbl_modulo_3_notas" >
                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="center" bgcolor="#FFFFFF">
                            <table style="border-collapse: collapse;" width="100%" border="0" cellspacing="0" cellpadding="3" >
                                <tr>
                                    <td width='40%' align="right" valign="top" class='arial12 separadorLineaPunteada' >Contenido Auxiliar 2&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11 separadorLineaPunteada' >
                                        <select id="contenido_3" name="contenido_3">
                                            <option value="">Seleccione</option>
                                            <?php for ($i = 0; $i < count($contenidos); $i++) { ?>
                                                <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_3"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                            <?php } ?>
                                        </select></td>
                                    <td width="45%" class='arial11 separadorLineaPunteada'>
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=contenido_3" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#contenido_3').val())">Quick Edit</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>

                    <!-- Contenido 4 -->
                    <tr class="tbl_modulo_3_notas" >
                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="center" bgcolor="#FFFFFF">
                            <table style="border-collapse: collapse;" width="100%" border="0" cellspacing="0" cellpadding="3" >
                                <tr>
                                    <td width='40%' align="right" valign="top" class='arial12 separadorLineaPunteada'>Contenido Auxiliar 3&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11 separadorLineaPunteada' >
                                        <select id="contenido_4" name="contenido_4">
                                            <option value="">Seleccione</option>
                                            <?php for ($i = 0; $i < count($contenidos); $i++) { ?>
                                                <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_4"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td width="45%" class='arial11 separadorLineaPunteada' >
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=contenido_4" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#contenido_4').val())">Quick Edit</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>
                    <!-- Fin Contenido 4 -->
                    <!-- Temas -->
                    <?php
                    if (intval($id_tema) ==  0) {
                        $temasRelacionados = GetTemasRelacionadosPorSeccion($id_seccion);
                        if (!empty($temasRelacionados)) {
                            ?>
                            <tr >
                                <td align="center" bgcolor="#ffffff"></td>
                                <td align="center" bgcolor="#FFFFFF">
                                    <table style="border-collapse: collapse;" width="100%" border="0" cellspacing="0" cellpadding="3" >
                                        <tr>
                                            <td width='40%' align="right" valign="top" class='arial12 separadorLineaPunteada' style="text-align: right">Temas relacionados&nbsp;<img src="images/arrow_derecha.gif"></td>
                                            <td width="60%" class='arial11 separadorLineaPunteada' >
                                                <ul>
                                                    <?php
                                                                            
                                                    for ($i = 0; $i < sizeof($temasRelacionados); ++$i) {
                                                        ?>
                                                        <li><a href="admin_home_secciones.php?id_seccion=<?php echo $id_seccion; ?>&id_tema=<?php echo $temasRelacionados[$i]['idTema']; ?>&nombre_seccion=<?php echo $temasRelacionados[$i]['nombre'];?>" ><?php echo $temasRelacionados[$i]["nombre"] ?></a></li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                            </tr>
                            <?php
                        } //cierre !empty temasRelacionados
                    }//cierre id_tema = 0
                    ?>
                    <!-- Fin Temas -->

                    <tr>
                        <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
                    </tr>
                </table>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".openBuscador").openDOMWindow({ 
                    eventType:'click',
                    height:550, 
                    width:800, 
                    positionType:'centered', 
                    windowSource:'iframe', 
                    windowPadding:0, 
                    loader:1, 
                    loaderHeight:16, 
                    loaderWidth:17
                });
            });

            function cerrarPopUp(){
                //(function(){
                $(".openBuscador").closeDOMWindow();
                //}());
            }

            function SeleccionarContenido(id,destino){
                $("#" + destino).val(id);
                $.closeDOMWindow();
            }

            function SwitchTemplate(tipo){
                if(tipo == 2){
                    $("#td_contenido_dest2").html('Contenido Destacado 2 <img src="images/arrow_derecha.gif">');
                    $(".tr_toggle").hide();
                }else{
                    $("#td_contenido_dest2").html('Contenido Auxiliar 1 <img src="images/arrow_derecha.gif">');
                    $(".tr_toggle").show();
                }
	
            }
            function OpenPopUpEdit(id){
                if(id === null || id == '') return false;
                $.openDOMWindow({ 
                    height:600, 
                    width:700, 
                    positionType:'centered', 
                    windowSource:'iframe', 
                    windowPadding:0, 
                    loader:1, 
                    loaderImagePath:'animationProcessing.gif', 
                    loaderHeight:16, 
                    windowSourceURL: '_iframe_edit_contenido_home.php?id=' + id,
                    loaderWidth:17
                });
            }
            /*
            $('#contenido_destacado_activo').click(function(){
                if($(this).is(':checked')) {
                    $('#tbl_contenido_destacado').show();
                } else {
                    $('#tbl_contenido_destacado').hide();
                }
            });
            $('#modulo_3_notas_activo').click(function(){
                if($(this).is(':checked')) {
                    $('.tbl_modulo_3_notas').show();
                } else {
                    $('.tbl_modulo_3_notas').hide();
                }
            });
            */
        function goBack(){
            if(0 == <?php echo intval($id_tema); ?>) {
                window.history.go(-1);
            } else {
                document.location = '<?php echo $_SERVER["HTTP_REFERER"]; ?>';
            }
          
        }
        </script>
    </body>
</html>