<?php
require_once dirname(__FILE__) . '/../../includes/DB_Conectar.php';

function GetCategoriasMultimedia($parent_id = 0, $selected_id = 0) {
    global $conn;
    $q = "SELECT id, nombre FROM advf_categorias WHERE padre = {$parent_id}";
    $r = $conn->getRecordset($q);
    echo count($r);
    ?>
    <option value="0" >Todos</option>
    <?php
    for ($i = 0; $i < count($r); $i++) {
        $data = $r[$i];
        $selected = $data["id"] == $selected_id ? 'selected="selected"' : '';
        ?>
        <option value="<?php echo $data["id"] ?>" <?php echo $selected ?>  ><?php echo utf8_encode($data["nombre"]) ?></option>
        <?php
        print(buildTree($data["id"], $selected_id));
    }
}

function buildTree($parent_id, $selected_id = "") {
    global $conn, $exclude_array, $depth_tree;
    if(!isset($exclude_array)) {
        $exclude_array = array();
    }
    $rs_childnodes = $conn->execute("select id, nombre, padre from advf_categorias where activo = 'S' and estado = 'A' and padre = '" . $parent_id . "' order by nombre");

    while (!$rs_childnodes->eof) {
        if ($rs_childnodes->field('id') != $rs_childnodes->field('padre')) {

            if ($selected_id == $rs_childnodes->field('id')) {
                $selected = "SELECTED";
            } else {
                $selected = "";
            }


            @$temp_tree .= "<option value=\"" . $rs_childnodes->field('id') . "\" " . $selected . ">" . str_repeat("--", ($depth_tree + 1)) . " " . utf8_encode($rs_childnodes->field('nombre')) . "</option>";

            $depth_tree++;
            @$temp_tree .= buildTree($rs_childnodes->field('id'), $selected_id);
            $depth_tree--;
            array_push($exclude_array, $rs_childnodes->field('id'));
        }

        $rs_childnodes->movenext();
    }

    return $temp_tree;
}

function GetContenidosPorCategoriaMultimedia($cat_id = 0, $pag = 0, $tipo = "F") {
    global $conn;

    $filtro_cat = "";
    $limite = 10;
    $offset = ($pag * $limite ) - $limite;

    if ($cat_id > 0) {
        $filtro_cat = " AND catID = {$cat_id} ";
    }
    $q = "SELECT advTipo, advTitulo, advLink, advLinkPreview, advID , advTexto, youtube_code, vimeo_code, catID FROM advf 
			WHERE advTipo = '{$tipo}' $filtro_cat ORDER BY advID DESC LIMIT $limite OFFSET {$offset}";
    $r = $conn->getRecordset($q);
    return $r;
}

function GetContenidosPorCategoriaTotal($cat_id = 0, $tipo = "F") {
    global $conn;
    $filtro_cat = "";
    if ($cat_id > 0) {
        $filtro_cat = " AND catID = {$cat_id} ";
    }
    $q = "SELECT count(*) as cant FROM advf 
			WHERE advTipo = '{$tipo}' $filtro_cat";
    $r = $conn->getRecordset($q);
    return $r[0]["cant"];
}

function GetFotografos() {
    global $conn;
    $q = "SELECT id, nombre FROM fotografos WHERE activo = 'S' AND estado = 'A' ORDER BY nombre";
    $r = $conn->getRecordset($q);
    ?>
    <option value="0" >Ninguno</option>
    <?php
    for ($i = 0; $i < count($r); $i++) {
        $data = $r[$i];
        $selected = $data["id"] == $selected_id ? 'selected="selected"' : '';
        ?>
        <option value="<?php echo $data["id"] ?>" <?php echo $selected ?>  ><?php echo $data["nombre"] ?></option>
    <?php
    }
}

function GetBreadCrumb($cat_id) {
    global $conn;
    if (intval($cat_id) == 0) {
        return "Todos &gt; Todos";
    }
    $q = "SELECT c.nombre as hijo, c2.nombre as padre FROM advf_categorias c 
	LEFT JOIN advf_categorias c2 ON c.padre = c2.id
	WHERE c.id = {$cat_id}";
    $r = $conn->getRecordset($q);
    $breadcrumb = "";
    if (!empty($r[0]["padre"])) {
        $breadcrumb .= utf8_encode($r[0]["padre"]) . " &gt; ";
    }
    $breadcrumb .= utf8_encode($r[0]["hijo"]);
    return $breadcrumb;
}

function GetPaginado($selected = 1, $imgs_total = 0) {
    $IMGS_POR_PAGINA = 10;
    $pags_totales = ceil($imgs_total / $IMGS_POR_PAGINA);
    $fotos_desde = (($selected * $IMGS_POR_PAGINA ) - $IMGS_POR_PAGINA) + 1;
    $fotos_hasta = ($selected * $IMGS_POR_PAGINA) > $imgs_total ? $imgs_total : ($selected * $IMGS_POR_PAGINA);

    $paginasPorUl = 10;
    $cantidadUls = ceil($pags_totales / $paginasPorUl);

    if ($pags_totales > 1) {
        ?><!-- PAGINADO -->
        <div class="paginador floatFix toggle_muestra_upload">
            <div class="paginas">
                Contenidos <span><?php echo $fotos_desde ?></span> a <span><?php echo $fotos_hasta ?></span> de <strong><?php echo $imgs_total ?></strong>
            </div>
            <div class="paginado">
                <?php
                for ($i = 1; $i <= $cantidadUls; $i++) {
                    $inicio = (($i * $paginasPorUl) - $paginasPorUl ) + 1;
                    $corte = ($i * $paginasPorUl) > $pags_totales ? $pags_totales : ($i * $paginasPorUl);
                    ?>
                    <ul class="ul_paginador <?= ($selected >= $inicio && $selected <= $corte) ? "show" : "hidden" ?>" data-inicio="<?php echo $inicio ?>" data-fin="<?php echo $corte ?>">
                        <?php if ($selected > 1) { ?>
                            <a href="javascript:void(0);" onclick="CambiarPagina(this);" data-id="pag_anterior" ><li><span><</span></li></a>
                        <?php } ?>
                        <?php for ($j = $inicio; $j <= $corte; $j++) {
                            if ($selected != $j) {
                                ?><a href="javascript:void(0)" onclick="CambiarPagina(this);" class="link_pagina"><?php } ?>
                                <li nro="<?php echo $j ?>" <?php echo $selected == $j ? 'class="selected"' : "" ?>><?php echo ($j) ?></li>
                            <?php if ($selected != $j) { ?></a><?php } ?>
                        <?php } ?>
                        <?php if ($selected < $pags_totales) { ?>
                            <a href="javascript:void(0);" onclick="CambiarPagina(this);" data-id="pag_siguiente" ><li><span>></span></li></a>
                    <?php } ?>
                    </ul>
        <?php } ?>
            </div>
        </div>
        <?php
    }// if pags_totales
}

function BuscarContenido($texto, $pag = 0, $tipo = 'F') {
    global $conn;

    $limit = 10;
    $offset = ($pag * $limit ) - $limit;
    if (is_numeric($texto)) {
        $q = "SELECT advID, advTexto, advTitulo, advLink, advTipo, youtube_code, vimeo_code, catID FROM advf WHERE advID = " . intval($texto) . " AND advTipo = '{$tipo}' LIMIT 1";
    } else {
        $q = "SELECT advID, advTexto, advTitulo, advLink, advLinkPreview, advTipo, youtube_code, vimeo_code, catID FROM advf WHERE (advTexto LIKE '%" . $texto . "%' OR advTitulo LIKE '%" . $texto . "%') AND advTipo = '{$tipo}' ORDER BY advID DESC LIMIT {$limit} OFFSET {$offset}";
    }
    $r = $conn->getRecordset($q);
    return $r;
}

function BuscarContenidoTotal($texto, $tipo = 'F') {
    global $conn;
    if (is_numeric($texto)) {
        $q = "SELECT COUNT(*) as cant FROM advf WHERE advID = " . intval($texto) . " AND advTipo = '{$tipo}' LIMIT 1";
    } else {
        $q = "SELECT COUNT(*) as cant FROM advf WHERE (advTexto LIKE '%" . $texto . "%' OR advTitulo LIKE '%" . $texto . "%') AND advTipo = '{$tipo}'";
    }
    $r = $conn->getRecordset($q);
    return $r[0]["cant"];
}

