<?
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");
//harcodeo el id de sección de opinión
$id_seccion = 10;
if (is_array($_POST) && count($_POST) > 1 && isset($_POST["save_type"])) {
    if (isset($_POST["destacada"]) && is_numeric($_POST["destacada"])) {
        //borro lo que había
        $sql = "DELETE FROM home_opinion";
        $conn->execute($sql);

        $sql = "insert into home_opinion (destacada, modulo_destacado_1,modulo_destacado_2,modulo_destacado_3,modulo_destacado_4,humor_ilustracion,humor_apocalipsis_tv,humor_hay_polemica) 
            values ('" . $_POST['destacada'] . "','" . $_POST['modulo_destacado_1'] . "','" . $_POST['modulo_destacado_2'] . "','" . $_POST['modulo_destacado_3'] . "','" . $_POST['modulo_destacado_4'] . "','" . $_POST['humor_ilustracion'] . "','" . $_POST['humor_apocalipsis_tv'] . "','" . $_POST['humor_hay_polemica'] . "')";
        $conn->execute($sql);

        // Insert en tabla de auditoria
        $conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $id . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '2')");
        require_once 'generador_contenidos.php';
        generarPortadaOpinion();
    }

    header("Location: admin_home_opinion.php");
    die();
}



$sql = "select * from home_opinion";
$rs = $conn->execute($sql);
foreach ($rs->recordset() as $key => $value) {
    $$key = $value;
}
?><html>
    <head>
        <title><?= $TITULO_SITE ?> - Portada opinión</title>
        <link rel="stylesheet" href="css/stylo.css" type="text/css">
        <script language='Javascript' src='../includes/validar_datos.js'></script>
        <script language='Javascript'>
            function Chk()
            {
		
                return true;
            }

            function set_type(valor)
            {
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
        <style>
            select {
                width: 350px;
            }
        </style>
    </head>
    <body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll='no'>
        <? include_once("_barra.php"); ?>
        <div class="why" id="outerDiv">  <br>
            <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" class="Title" style="padding-bottom:10px;"><?= $TITULO_SITE ?> - Portada opinión</td>
                </tr>
            </table>
            <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
                <!--
                <tr valign="top">
                  <td height="15" width="610" align="center" class="arial11" valign="middle">  </td>
                </tr>
                -->
            </table>
            <form method="post" name="formedit" action='<?= $_SERVER["PHP_SELF"]; ?>?menu=<?= $usr->_menu; ?>' onsubmit='return Chk();'>
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
                                    <td align="left">
                                    </td>
                                    <td align='center' style="padding-right:15px;"><img onclick='document.location="index.php?=menu=<?= $usr->_menu; ?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">
                                    </td>
                                    <td align='center' style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><? if (($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" onclick="javascript:set_type(0);" src="images/btn_guardar.gif">'); ?></td>
                                    
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



                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Destacada&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='destacada' name='destacada' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, CONCAT(titulo,' (', id, ')') as titulo FROM contenidos 
        WHERE activo='S' and estado='A' AND id_categoria_raiz = 10 AND fotohome > 0 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($destacada == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
                                    <td width="45%" class='arial11' >
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=destacada" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#destacada').val())">Quick Edit</a>
                                    </td>
                                </tr>



                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Módulo Destacada #1&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='modulo_destacado_1' name='modulo_destacado_1' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, CONCAT(titulo,' (', id, ')') as titulo FROM contenidos 
        WHERE activo='S' and estado='A' AND id_categoria_raiz = 10 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($modulo_destacado_1 == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
                                    <td width="45%" class='arial11' >
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=modulo_destacado_1" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#modulo_destacado_1').val())">Quick Edit</a>
                                    </td>
                                </tr>



                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Módulo Destacada #2&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='modulo_destacado_2' name='modulo_destacado_2' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, CONCAT(titulo,' (', id, ')') as titulo FROM contenidos 
        WHERE activo='S' and estado='A' AND id_categoria_raiz = 10 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($modulo_destacado_2 == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
                                    <td width="45%" class='arial11' >
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=modulo_destacado_2" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#modulo_destacado_2').val())">Quick Edit</a>
                                    </td>
                                </tr>



                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Módulo Destacada #3&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td width="15%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='modulo_destacado_3' name='modulo_destacado_3' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, CONCAT(titulo,' (', id, ')') as titulo FROM contenidos 
        WHERE activo='S' and estado='A' AND id_categoria_raiz = 10 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($modulo_destacado_3 == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
                                    <td width="45%" class='arial11' >
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=modulo_destacado_3" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#modulo_destacado_3').val())">Quick Edit</a>
                                    </td>
                                </tr>
                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Módulo Destacada #4&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td  width="15%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='modulo_destacado_4' name='modulo_destacado_4' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, CONCAT(titulo,' (', id, ')') as titulo FROM contenidos 
        WHERE activo='S' and estado='A' AND id_categoria_raiz = 10 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($modulo_destacado_4 == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
                                    <td width="45%" class='arial11' >
                                        <a href="buscador_contenidos_seccion.php?id_seccion=<?php echo $id_seccion ?>&destino=modulo_destacado_4" class="openBuscador">Buscar</a>
                                        &nbsp;
                                        <a href="#0" onclick="OpenPopUpEdit($('#modulo_destacado_4').val())">Quick Edit</a>
                                    </td>
                                </tr>


                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Humor "Ilusrtación"&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td colspan="2" width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='humor_ilustracion' name='humor_ilustracion' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, titulo FROM humor 
        WHERE activo='S' and estado='A' AND id_tira = 3 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($humor_ilustracion == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
                                </tr>



                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Humor "Apocalipsis TV"&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td colspan="2" width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='humor_apocalipsis_tv' name='humor_apocalipsis_tv' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, titulo FROM humor 
        WHERE activo='S' and estado='A' AND id_tira = 2 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($humor_apocalipsis_tv == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
                                </tr>



                                <tr <?= (false == true && $bilingual == false) ? "style=\"display: none;\"" : ""; ?>>
                                    <td width='40%' align="right" valign="top" class='arial12' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">Humor "Hay polémica"&nbsp;<img src="images/arrow_derecha.gif"></td>
                                    <td colspan="2" width="60%" class='arial11' style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">
                                        <select id='humor_hay_polemica' name='humor_hay_polemica' class='comun' <? print($usr->checkUserRights(-1)); ?>>
                                            <option value="0">Seleccione...</option>
                                            <?
                                            $sql = "
        SELECT id, titulo FROM humor 
        WHERE activo='S' and estado='A' AND id_tira = 1 ORDER BY fecha DESC LIMIT 100";
                                            $rs = $conn->execute($sql);
                                            while (!$rs->eof) {
                                                echo "<option value='" . $rs->field(0) . "' " . ($humor_hay_polemica == $rs->field(0) ? "selected" : "") . ">" . $rs->field(1) . "</option>";
                                                $rs->movenext();
                                            }
                                            ?></select></td>
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
                        <td  align="center" bgcolor="#e5e5e5"><table width='50%' border="0" cellpadding="0" cellspacing="5">
                                <tr>
                                    <td align="left">
                                    </td>
                                    <td align='center' style="padding-right:15px;"><img border="0" onclick='document.location="index.php?=menu=<?= $usr->_menu; ?>";' src="images/btn_volver.gif" style="cursor:pointer;">
                                    </td>
                                    <td align='center' style="background-image:url(images/separador_v.gif); background-repeat:repeat-y; background-position:left; padding-left:15px;"><? if (($usr->checkUserRights(4, $id) != 'disabled') || (!$id)) print('<input name="imageField2" type="image" id="imageField2" onclick="javascript:set_type(0);" src="images/btn_guardar.gif">'); ?></td>
                                   
                                </tr>
                            </table>
                        </td>
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

                function goBack(){
                    if(0 == <?php echo intval($id_tema); ?>) {
                        window.history.go(-1);
                    } else {
                        document.location = '<?php echo $_SERVER["HTTP_REFERER"]; ?>';
                    }
          
                }
            </script>
        </div>
    </body>
</html>
