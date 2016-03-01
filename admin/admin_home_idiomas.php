<?php
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");

$langPrefix = $_GET['idioma'];
$contenidos = GetContenidosPorIdiomas($langPrefix);
$totalContenidos = sizeOf($contenidos);
if (!empty($_POST)) {
    if (intval($_POST["contenido_destacado_activo"]) == 0) {
        $contenido_ppal = 0;
    } else {
        $contenido_ppal = intval($_POST["contenido_ppal"]);
    }

    if (intval($_POST["modulo_3_notas_activo"] == 0)) {
        $contenido_1 = 0;
        $contenido_2 = 0;
        $contenido_3 = 0;
    } else {
        $contenido_1 = intval($_POST["contenido_1"]);
        $contenido_2 = intval($_POST["contenido_2"]);
        $contenido_3 = intval($_POST["contenido_3"]);
    }

    $q = "UPDATE home_idiomas SET
            id_contenido_principal = {$contenido_ppal},
            id_contenido_1 = {$contenido_1},
            id_contenido_2 = {$contenido_2},
            id_contenido_3 = {$contenido_3}
        WHERE lang = '$langPrefix'";
    $conn->getRecordset($q);

    /*   */

    if (!empty($langPrefix) > 0) {
        include_once("../LibProy/PortadaIdioma.php");
        $portada = new PortadaIdioma();
        $portada->setIdioma($langPrefix);
        $portada->generarSubMenuCategorias();
        $portada->generarPortadaIdioma();
        //generarPortadaIdioma($langPrefix);
    }
    header("Location: admin_home_idiomas.php?idioma=" . $langPrefix . "&menu=" . $_GET["menu"]);
    die();
}
$contenido_seccion = GetContenidosSeteadosAdminPortadaSeccionIdiomas($langPrefix);
?>
<html>
    <title><?= $TITULO_SITE ?> - Admin Home Idiomas</title>
    <head>
        <script type='text/javascript' src='../includes/lib/jQuery/jquery.js'></script>
        <script type="text/javascript" src="../includes/lib/DOMWindow/jquery.DOMWindow.js"></script>
        <script type='text/javascript' src='contenidos_edit.js'></script>
        <link rel="stylesheet" href="css/stylo.css" type="text/css">
    </head>
    <body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll='no'>
        <? include_once("_barra.php"); ?>
        <div class="why" id="outerDiv">  <br>
            <form method="post" name="formedit" action='<?= $_SERVER["PHP_SELF"]; ?>?idioma=<?= $langPrefix ?>&menu=<?= $usr->_menu; ?>'>
                <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" class="Title" style="padding-bottom:10px;"><?= $TITULO_SITE ?> - Admin Home <?= $langPrefix == 'en' ? 'Inglés' : 'Portugués'; ?><strong style="color: #0a1d6f"><?php echo $nombre_seccion ?></strong></td>
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
                    if (intval($contenido_seccion["id_contenido_principal"]) > 0) {
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
                                                    <select id="contenido_ppal" name="contenido_ppal">
                                                        <option value="">Seleccione</option>
                                                        <?php for ($i = 0; $i < $totalContenidos; $i++) { ?>
                                                            <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_principal"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="45%" class='arial11' >
                                                    <a href="buscador_contenidos_idiomas.php?lang=<?php echo $langPrefix; ?>&destino=contenido_ppal" class="openBuscador">Buscar</a>
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
                    if (intval($contenido_seccion["id_contenido_1"]) > 0 && intval($contenido_seccion["id_contenido_2"]) > 0 && intval($contenido_seccion["id_contenido_3"]) > 0) {
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
                    <!-- Contenido 1 -->
                    <tr class="tbl_modulo_3_notas" >

                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="center" bgcolor="#FFFFFF">
                            <table style="border-collapse: collapse;" width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse;">
                                <tr>
                                    <td id="td_contenido_dest2" width='40%' align="right" valign="top" class='arial12 separadorLineaPunteada' >
                                        Contenido Auxiliar 1<img src="images/arrow_derecha.gif">
                                    </td>
                                    <td width="15%" class='arial11 separadorLineaPunteada' >
                                        <select id="contenido_1" name="contenido_1">
                                            <option value="">Seleccione</option>
                                            <?php for ($i = 0; $i < $totalContenidos; $i++) { ?>
                                                <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_1"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td width="45%" class='arial11 separadorLineaPunteada'>
                                        <a href="buscador_contenidos_idiomas.php?lang=<?php echo $langPrefix; ?>&destino=contenido_1" class="openBuscador">Buscar</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>

                    <!-- Contenido 2 -->
                    <tr class="tbl_modulo_3_notas" >
                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="center" bgcolor="#FFFFFF">
                            <table style="border-collapse: collapse;" width="100%" border="0" cellspacing="0" cellpadding="3" >
                                <tr>
                                    <td width='40%' align="right" valign="top" class='arial12 separadorLineaPunteada' >Contenido Auxiliar 2&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11 separadorLineaPunteada' >
                                        <select id="contenido_2" name="contenido_2">
                                            <option value="">Seleccione</option>
                                            <?php for ($i = 0; $i < $totalContenidos; $i++) { ?>
                                                <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_2"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                            <?php } ?>
                                        </select></td>
                                    <td width="45%" class='arial11 separadorLineaPunteada'>
                                        <a href="buscador_contenidos_idiomas.php?lang=<?php echo $langPrefix; ?>&destino=contenido_2" class="openBuscador">Buscar</a>

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
                                    <td width='40%' align="right" valign="top" class='arial12 separadorLineaPunteada'>Contenido Auxiliar 3&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11 separadorLineaPunteada' >
                                        <select id="contenido_3" name="contenido_3">
                                            <option value="">Seleccione</option>
                                            <?php for ($i = 0; $i < $totalContenidos; $i++) { ?>
                                                <option <?php if ($contenidos[$i]["id"] == $contenido_seccion["id_contenido_3"]) echo "selected='selected'" ?> value="<?php echo $contenidos[$i]["id"] ?>"><?php echo "(" . $contenidos[$i]["id"] . ") - " . $contenidos[$i]["titulo"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td width="45%" class='arial11 separadorLineaPunteada' >
                                        <a href="buscador_contenidos_idiomas.php?lang=<?php echo $langPrefix; ?>&destino=contenido_3" class="openBuscador">Buscar</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>
                    <!-- Fin Contenido 4 -->
                    <tr>
                        <td align="left" valign="bottom"><img src="images/corner_ii2.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#ffffff"></td>
                        <td align="right" valign="bottom"><img src="images/corner_id2.gif" width="5" height="5"></td>
                    </tr>
                </table>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".openBuscador").openDOMWindow({
                    eventType: 'click',
                    height: 550,
                    width: 800,
                    positionType: 'centered',
                    windowSource: 'iframe',
                    windowPadding: 0,
                    loader: 1,
                    loaderHeight: 16,
                    loaderWidth: 17
                });
            });

            function cerrarPopUp() {
                $(".openBuscador").closeDOMWindow();
            }

            function SeleccionarContenido(id, destino) {
                $("#" + destino).val(id);
                $.closeDOMWindow();
            }

            function goBack() {
                document.location = '<?php echo $_SERVER["HTTP_REFERER"]; ?>';


            }
        </script>
    </body>
</html>