<?php
include_once '../includes/DB_Conectar.php';
if (isset($_GET["tabla"])) {
    $tabla = trim($_GET["tabla"]);
} else {
    $tabla = "contenidos";
}
$tabla = strtolower($tabla);
$destino = strtolower(trim($_GET["destino"]));


if (isset($_REQUEST["buscador"])) {
    if ($_GET["for_tags"] == 1) {
        $vTags = explode(",", $busqueda);
    } else {
        $busqueda = addslashes($_POST["input_buscar"]);
    }

    $orContenido = "";
    if ($_POST["buscar_en"]["en_copete"] == 1) {
        $orContenido .= " or copete like '%" . $busqueda . "%'";
    }

    if ($_POST["buscar_en"]["en_cuerpo"] == 1) {
        $orContenido .= " or cuerpo like '%" . $busqueda . "%'";
    }

    if ($_POST["buscar_en"]["en_tags"] == 1) {
        $orContenido .= " or tags like '%" . $busqueda . "%'";
    }

    if ($_POST["buscar_en"]["en_id"] == 1) {
        $orContenido .= " or id = '" . ($busqueda) . "'";
    }

    switch ($tabla) {
        case "contenidos":
            $label = "Títulos contenidos";
            $titulo_buscador = "Contenidos";

            $query = "SELECT id, titulo FROM " . $tabla . " WHERE (titulo like '%" . $busqueda . "%' " . $orContenido . ")
		AND activo='S' AND estado='A' ORDER BY id DESC";
            break;
        case "en_contenidos":
            $label = "Títulos contenidos inglés";
            $titulo_buscador = "Contenidos Inglés";

            $query = "SELECT id, titulo FROM " . $tabla . " WHERE (titulo like '%" . $busqueda . "%' " . $orContenido . ")
		AND activo='S' AND estado='A' ORDER BY id DESC";
            break;
        case "pt_contenidos":
            $label = "Títulos contenidos portugués";
            $titulo_buscador = "Contenidos Portugués";

            $query = "SELECT id, titulo FROM " . $tabla . " WHERE (titulo like '%" . $busqueda . "%' " . $orContenido . ")
		AND activo='S' AND estado='A' ORDER BY id DESC";
            break;
        default:
            break;
    }

    $rds = $conn->execute($query);
}
?>
<html>
    <head>
        <link rel="stylesheet" href="css/stylo.css" type="text/css">
        <title>Buscador</title>
    </head>
    <body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll="yes" >
        <div id="outerDiv" align="center" class="why">
            <form method="post" name="form1" action="<?= $_SERVER['PHP_SELF'] ?>?tabla=<?= $tabla ?>&destino=<?= $destino ?>&for_tags=<?= $_GET['for_tags'] ?>">
                <table border="0" width="400">
                    <caption class="title">Buscador de <?= $titulo_buscador ?></caption>
                    <?php if (empty($_GET["for_tags"])) { ?>
                        <tr class="tablaOscuro">
                            <td width="50%" align="center">
                                <label style="font-family:arial; font-size:13px; font-weight:bold; color:#333333;">Búsqueda:</label>
                                <input type="text" name="input_buscar" id="input_buscar" value="<?= $_POST["input_buscar"] ?>" />
                            </td>
                            <td>
                                <label><input checked="checked" type="checkbox" value="1" name="buscar_en[en_titulo]"  />
                                    Titulo</label></br>
                                <label><input <?php if ($_POST["buscar_en"]["en_id"]) echo 'checked="checked"' ?> type="checkbox"  value="1" name="buscar_en[en_id]"  />
                                    Id</label></br>
                                <label><input <?php if ($_POST["buscar_en"]["en_copete"]) echo 'checked="checked"' ?> type="checkbox" value="1" name="buscar_en[en_copete]"  />
                                    Copete</label></br>
                                <label><input <?php if ($_POST["buscar_en"]["en_cuerpo"]) echo 'checked="checked"' ?>  type="checkbox" value="1" name="buscar_en[en_cuerpo]" />
                                    Cuerpo</label></br>
                                <label><input <?php if ($_POST["buscar_en"]["en_tags"]) echo 'checked="checked"' ?>  type="checkbox" value="1" name="buscar_en[en_tags]" />
                                    Tags</label></br>
                            </td>
                            <td align="center">
                                <input type="image" src="images/btn_buscar3.gif" name="submit_bsc" value="Buscar" />
                                <input type="hidden" value="1" name="buscador" />
                            </td>
                        </tr>
                    <?php } ?>
                    <tr  class="tablaOscuro">
                        <td colspan="3">
                            <select style="width:690px;height:300px;" multiple="multiple" name="slt_rtdo_busqueda" id="slt_rtdo_busqueda" >
                                <optgroup label="<?= $label ?>">
                                    <?php
                                    if ($rds) {
                                        while (!$rds->eof) {
                                            ?>
                                            <option value="<?= $rds->field("id") ?>"><?= cString($rds->field("titulo"), 100) ?> </option>
                                            <?php
                                            $rds->movenext();
                                        }
                                    }
                                    ?>
                                </optgroup>
                            </select>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <img align="absmiddle" onclick="agregar('<?= $destino ?>');"  style="cursor: pointer;" src="images/btn_agregar.gif"/>
                        </td>
                        <td align="right">
                            <input type="image" src="images/btn_agregar_cerrar.gif"onclick="agregar('<?= $destino ?>');
                                    window.close();" value="Agregar y cerrar"  name="boton" />
                        </td>

                    </tr>
                </table>
            </form>
        </div>
        <script type="text/javascript">
                                function agregar(destino) {
                                    var slt = document.getElementById("slt_rtdo_busqueda");
                                    var dst = "window.parent.opener.document.formedit." + destino + "_dst_select";
                                    var slt_dst = eval(dst);
                                    var indice_dst = slt_dst.options.length;
                                    for (i = 0; i < slt.length; i++)
                                    {
                                        if (slt.options[i].selected)
                                        {
                                            var opt = new Option(slt.options[i].text, slt.options[i].value)
                                            slt_dst.options[indice_dst] = opt;
                                            indice_dst++;
                                        }
                                    }
                                }
        </script>
    </body>
</html>