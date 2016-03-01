<?php

/**
 * Registra una acción en la auditoría.
 * @global usuario $usr
 * @global DB_MySQL $conn
 * @param int $id
 * @param int $accion la acción a registrar (4 = update, 2 = insert)
 */
function registrarAuditoria($id, $accion) {
    global $usr, $conn;
    if (!isset($usr))
        die('No existe el objeto $usr');
    $sql = "insert into admin_auditoria SET
        menu_id = " . $usr->_menu . ",
        usuario_id = " . $usr->_id . ",
        contenido_id = '" . $id . "',
        ip = '" . $_SERVER["REMOTE_ADDR"] . "',
        fecha = now(),
        accion =  $accion";
    $conn->execute($sql);
}


/**
 * Limpia los titulos de los cables, elimina los tabs y el primer segmento del titulo.
 * Sólo va a limpiar los títulos que tengan 2 y sólo 2 segmentos.
 * @param string $titulo el titulo a limpiar
 * @return string el titulo limpio
 */
function limpiarTitulo($titulo) {
    $tituloLimpio = "";
    $vPartes = explode("/", trim($titulo, "\t"));
    if (count($vPartes) === 2) {
        $tituloLimpio = $vPartes[1];
        $tituloLimpio = trim(preg_replace('/\s*\([^)]*\)/', '', $tituloLimpio));
    }
    return $tituloLimpio;
}

/**
 * Para el módulo 'Más noticias en esta sección' de las portadas de sección y temas
 */
function GetMasNoticiasPorSeccion($id_seccion = 0, $id_tema = 0) {
    global $conn;
    if (intval($id_seccion) == 0)
        return array();
    if (intval($id_tema) > 0) {
        $filtroTema = " AND id_tema != " . $id_tema;
    }
    $sql = 'SELECT
        id,
        volanta,
        copete,
        copete_home,
        titulo,
        titulo_home
        FROM contenidos WHERE id_categoria_raiz = ' . $id_seccion . $filtroTema . '  AND activo = "S" AND estado = "A" ORDER BY fecha desc LIMIT 3';
    $rds = $conn->getRecordset($sql);
    return $rds;
}

function GenerarSubMenuTemas($id_seccion = 0) {
    global $conn;
    if (intval($id_seccion) == 0)
        return false;
    $temas = GetTemasPorSeccion($id_seccion);
    $nombres_seccion_padre = GetNombresSeccion($id_seccion);
    $header = '<div class="secs clearfix">
                 <p><a href="/' . $nombres_seccion_padre['descripcion_readonly'] . '">' . strtolower($nombres_seccion_padre['descripcion']) . '</a><span>&raquo;<span></p>
                <ul class="clearfix">';
    $sizeOfTemas = sizeof($temas);
    for ($i = 0; $i < $sizeOfTemas; ++$i) {
        $header .='<li>
                    <a id="link_tema_' . $id_seccion . '_' . $temas[$i]["id"] . '"
                        href="/temas/' . $temas[$i]["id"] . '-' . urlAmigable($temas[$i]["nombre"]) . '/">
                        ' . $temas[$i]["nombre"] . '
                    </a>
                </li>';
    }
    $header .='</ul>
            </div>';

    file_put_contents('../submenu_portadas/_submenu_portada_' . $nombres_seccion_padre['descripcion_readonly'] . '.php', $header);
}

function GetNombresSeccion($id_seccion = 0) {
    global $conn;
    if (intval($id_seccion) == 0)
        return '';
    $sql = "SELECT descripcion, descripcion_readonly FROM categorias_contenidos WHERE id = {$id_seccion} LIMIT 1";
    $rds = $conn->getRecordset($sql);
    return $rds[0];
}

function GetTemasPorSeccion($id_seccion = 0) {
    global $conn;
    if (intval($id_seccion) == 0)
        return array();
    $sql = 'SELECT t.id, t.nombre FROM categorias_temas c
        INNER JOIN temas t ON t.id = c.idTema
        WHERE c.idCategoria = ' . $id_seccion . ' ORDER BY c.orden';
    $rds = $conn->getRecordset($sql);
    return $rds;
}

function getMasLeidasComentadas($tipo = "") {
    global $conn;
    if ($tipo == "L") {
        $orderBy = " hits DESC";
    } elseif ($tipo == "C") {
        $orderBy = " cant_comentarios DESC";
    }
    $q = "SELECT c.id,k.descripcion as categoria, hits, cant_comentarios, titulo, fecha FROM contenidos c
		LEFT JOIN categorias_contenidos k ON k.id = c.id_categoria_raiz
		WHERE c.activo = 'S' AND c.estado = 'A' AND DATEDIFF(NOW(),fecha) <= 2 ORDER BY " . $orderBy . " LIMIT 5";
    $rds = $conn->getRecordset($q);
    return $rds;
}

function getTagsContenidos($id_categoria = 0) {
    global $conn;
    if ($id_categoria > 0) {
        $filtro = " AND id_categoria = {$id_categoria} ";
    }
    $q = "SELECT id as value, nombre as name from tags
	WHERE 1=1  $filtro AND activo = 'S' AND estado = 'A'";
    $rds = $conn->getRecordset($q);

    for ($i = 0; $i < count($rds); $i++)
        $rds[$i]['name'] = utf8_encode($rds[$i]['name']);

    return $rds;
}

function getTagsSeleccionados($id) {
    global $conn;
    $rds = array();
    if (intval($id) > 0) {
        $q = "SELECT t.id as value, t.nombre as name FROM contenidos_tags c
				INNER JOIN tags t ON t.id = c.id_tag
			WHERE c.id_contenido = {$id}";
        $rds = $conn->getRecordset($q);

        for ($i = 0; $i < count($rds); $i++)
            $rds[$i]['name'] = utf8_encode($rds[$i]['name']);
    }
    return $rds;
}

function crearArbol($parent, $prefix, $space, $def = "", $lang = "", $id_categoria = 0) {
    /* Armar query */
    global $conn;
    $tbl_prefix = '';
    if (!empty($lang)) {
        $tbl_prefix = $lang . '_';
    }
    $sql = "select  id, descripcion, orden from " . $tbl_prefix . "categorias_contenidos
       WHERE activo='S' and id_padre = $parent order by id_padre, orden";

    $padres = $conn->getRecordset($sql);
    if ($parent > 0) {
        $prefix = $prefix . " " . $prefix;
        $space = $space . $space . $space;
    }
    if ($padres) {
        for ($i = 0; $i < count($padres); $i++) {
            $disabled = '';
            if ($id_categoria == $padres[$i]["id"]) {
                $disabled = 'disabled';
            }
            $selected = $def == $padres[$i]["id"] ? "selected='selected'" : "";
            ?>
            <option <?php echo $disabled . $selected; ?> value="<?php echo $padres[$i]["id"] ?>" >
            <strong><?php echo $prefix . $padres[$i]["descripcion"] ?></strong>
            </option>
            <?php
            crearArbol($padres[$i]["id"], $prefix, $space, $def, $lang, $id_categoria);
        }
    }
}

function crearArbolCategoriasADVF($parent, $prefix, $space, $def = "") {
    /* Armar query */
    global $conn;
    $sql = "select  id, nombre from advf_categorias WHERE activo='S' and padre = $parent order by padre";
    $padres = $conn->getRecordset($sql);

    if ($parent > 0) {
        $prefix = $prefix . " " . $prefix;
        $space = $space . $space . $space;
    }
    if ($padres) {
        for ($i = 0; $i < count($padres); $i++) {
            ?>

            <option <?php
            if ($def == $padres[$i]["id"]) {
                echo "selected='selected'";
            }
            ?> value="<?php echo $padres[$i]["id"] ?>" >
            <strong><?php echo $prefix . $padres[$i]["nombre"] ?></strong>
            </option>
            <?php
            crearArbolCategoriasADVF($padres[$i]["id"], $prefix, $space, $def);
        }
    }
}

function GetRutaArchivoAnioMes($directorio_propio = "", $nombre_archivo = "") {
    global $app_path;
    $v = array();

    $ruta_abs = $app_path . "advf/" . $directorio_propio;
    $ruta_rel = "advf/" . $directorio_propio;
    $ext = strtolower(substr($nombre_archivo, strpos($nombre_archivo, ".") + 1));

    if (!is_dir($ruta_abs . "/" . date("Y"))) {
        mkdir($ruta_abs . "/" . date("Y"));
    }
    if (!is_dir($ruta_abs . "/" . date("Y") . "/" . date("m"))) {
        mkdir($ruta_abs . "/" . date("Y") . "/" . date("m"));
    }

    $uniqid = uniqid("");

    $ruta_abs .= "/" . date("Y") . "/" . date("m") . "/" . $uniqid . "." . $ext;
    $ruta_rel .= "/" . date("Y") . "/" . date("m") . "/" . $uniqid . "." . $ext;

    $v["ruta_abs"] = $ruta_abs;
    $v["ruta_rel"] = $ruta_rel;
    return $v;
}

function UploadPreview($files = array()) {
    //$_FILES
    $tmp_name = $files["tmp_name"];
    $name = $files["name"];
    $error = $files["error"];
    $size = $files["size"];

    if ($error > 0) {
        return false;
    }

    //validaciones
    if ($size > 2097152) {
        return false;
    }

    if (exif_imagetype($tmp_name) != IMAGETYPE_GIF && exif_imagetype($tmp_name) != IMAGETYPE_JPEG && exif_imagetype($tmp_name) != IMAGETYPE_PNG) {
        return false;
    }

    $rutas = GetRutaArchivoAnioMes("imagenes", $name);

    if (move_uploaded_file($tmp_name, $rutas["ruta_abs"])) {
        Multimedia::GetImagenStatic(100, 100, $rutas["ruta_rel"]);
        return $rutas["ruta_rel"];
    }
}

function UploadFoto($files = array(), $post = array()) {
    global $conn;

    //$_FILES
    $tmp_name = $files["tmp_name"];
    $name = $files["name"];
    $error = $files["error"];
    $size = $files["size"];
    //$_POST
    $titulo = addslashes($post["titulo_foto"]);
    $descripcion = addslashes($post["descripcion_foto"]);
    $id_categoria = intval($post["id_categoria_foto"]);
    $credito = $post["credito"];
    $id_fotografo = intval($post["id_fotografo"]);

    if ($error > 0) {
        return "1";
    }

    //validaciones
    if ($size > 4297152) {
        return "2";
    }
    if (exif_imagetype($tmp_name) != IMAGETYPE_GIF && exif_imagetype($tmp_name) != IMAGETYPE_JPEG && exif_imagetype($tmp_name) != IMAGETYPE_PNG) {
        return "3";
    }

    $rutas = GetRutaArchivoAnioMes("imagenes", $name);

    if (move_uploaded_file($tmp_name, $rutas["ruta_abs"])) {
        $sql = "INSERT INTO advf SET
		 advTipo = 'F' ,
		 advTitulo = '" . $titulo . "',
		 advTexto = '" . $descripcion . "' ,
		 advfecha = '" . date("Y-m-d H:i:s") . "' ,
		 advLink = '" . $rutas["ruta_rel"] . "' ,
		 catID = '" . $id_categoria . "',
		 advBytes = '" . $size . "',
		 advfechaCaptura = NOW() ,
		 advAutor = '" . $_SESSION["sessID"] . "',
		 id_fotografo = '" . $id_fotografo . "'";
        $conn->execute($sql);

        //thumb
        Multimedia::GetImagenStatic(100, 100, $rutas["ruta_rel"]);

        return "0";
    }
    return "4";
}

function setImageSignature($image, $id_fotografo) {
    global $conn;
    global $app_path;

    list($upload_width, $upload_height) = getimagesize($image);

    if ($upload_width > 400) {

        //Selecciono la firma del fotografo
        $q = "SELECT f.id_imagen, a.advLink FROM fotografos f LEFT JOIN advf a ON a.advID = f.id_imagen WHERE f.activo = 'S' AND f.estado = 'A' AND f.id = '" . $id_fotografo . "' ";
        $r = $conn->getRecordset($q);
        $img_firma = $r[0]["advLink"];

        switch (exif_imagetype($image)) {
            case IMAGETYPE_GIF:
                $im = imagecreatefromgif($image);
                break;
            case IMAGETYPE_JPEG:
                $im = imagecreatefromjpeg($image);
                break;
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($image);
                break;
        }

        $firma = imagecreatefrompng($app_path . $r[0]["advLink"]);
        $margen_dcho = 10;
        $margen_inf = 10;
        $sx = imagesx($firma);
        $sy = imagesy($firma);

        imagecopymerge($im, $firma, imagesx($im) - $sx - $mÃ¡rgen_dcho, imagesy($im) - $sy - $mÃ¡rgen_inf, 0, 0, imagesx($firma), imagesy($firma), 50);

        // Guardar la imagen en el mismo archivo y libero memoria
        imagepng($im, $image);
        imagedestroy($im);
    }
}

function UploadVideoYouTube($files, $post) {

}

function UploadVideo($files = array(), $post = array()) {

    global $conn;
    //$_POST
    $tipo_video = $post["tipo_video"];
    $codigo_yt = $post["codigo_YouTube"];
    $advLink_preview = $post["advLink_preview"];
    $titulo = addslashes($post["titulo_video"]);
    $descripcion = addslashes($post["descripcion_video"]);
    $id_categoria = intval($post["id_categoria_video"]);

    //$_FILES SI ES VIDEO PROPIO entra al if
    if (isset($files["tmp_name"]) && $tipo_video == 'REG') {
        $tmp_name = $files["tmp_name"];
        $name = $files["name"];
        $error = $files["error"];
        $size = $files["size"];
        $mime_type = $files["type"];

        if ($error > 0) {
            return "1";
        }


        //validaciones
        if ($size > 157286400) {
            return "2";
        }
		
        $upload_allow = array('flv', 'mpg', 'avi', 'wmv', 'f4v','webm', 'mp4', 'ogg', 'ogv');
        $ext = strtolower(substr($name, -3));
		//se agrego la comparación contra las ultimas 4 para poder subir videos con la extencion webm
        $ext2 = strtolower(substr($name, -4));
		if (!in_array($ext, $upload_allow) && !in_array($ext2, $upload_allow)) 
		{
		  return "3";
		}

        $rutas = GetRutaArchivoAnioMes("videos", $name);
        if (move_uploaded_file($tmp_name, $rutas["ruta_abs"])) {
            $sql = "INSERT INTO advf SET
		 advTipo = 'V' ,
		 advTitulo = '" . $titulo . "',
		 advTexto = '" . $descripcion . "' ,
		 advfecha = '" . date("Y-m-d H:i:s") . "' ,
		 advLink = '" . $rutas["ruta_rel"] . "' ,
                 advLinkPreview = '" . $advLink_preview . "',
		 catID = '" . $id_categoria . "',
		 advBytes = '" . $size . "',
		 advfechaCaptura = NOW() ,
		 advAutor = '" . $_SESSION["sessID"] . "'";
            //echo $sql."<br/>";die;
            $conn->execute($sql);
            return "0";
        }
    } else {
        
		if($tipo_video=="YT")
		{
			$sql = "INSERT INTO advf SET
			 advTipo = 'V' ,
			 advTitulo = '" . $titulo . "',
			 advTexto = '" . $descripcion . "' ,
			 advfecha = '" . date("Y-m-d H:i:s") . "' ,
			 youtube_code = '" . $codigo_yt . "',
			 advLinkPreview = 'http://img.youtube.com/vi/" . $codigo_yt . "/0.jpg',
					 catID = '" . $id_categoria . "',
			 advBytes = '0',
			 advfechaCaptura = NOW() ,
			 advAutor = '" . $_SESSION["sessID"] . "'";
			$conn->execute($sql);
			return "0";
		}

		if($tipo_video=="VI")
		{
			$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$codigo_yt.".php"));
			
			$sql = "INSERT INTO advf SET
			 advTipo = 'V' ,
			 advTitulo = '" . $titulo . "',
			 advTexto = '" . $descripcion . "' ,
			 advfecha = '" . date("Y-m-d H:i:s") . "' ,
			 vimeo_code = '" . $codigo_yt . "',
			 advLinkPreview = '".$hash[0]['thumbnail_medium']."',
			 catID = '" . $id_categoria . "',
			 advBytes = '0',
			 advfechaCaptura = NOW() ,
			 advAutor = '" . $_SESSION["sessID"] . "'";
			$conn->execute($sql);
			return "0";
		}
    }

    return "4";
}

function UploadDocumento($files = array(), $post = array()) {
    global $conn;

    //$_FILES
    $tmp_name = $files["tmp_name"];
    $name = $files["name"];
    $error = $files["error"];
    $size = $files["size"];
    //$_POST
    $titulo = addslashes($post["titulo_doc"]);
    $descripcion = addslashes($post["descripcion_doc"]);
    $id_categoria = intval($post["id_categoria_doc"]);
    if ($error > 0) {
        return "1";
    }

    //validaciones
    if ($size > 10485760) {
        return "2";
    }

    $upload_allow = array('xls',
        'pdf',
        'ppt',
        'pps',
        'doc',
        'zip',
        'rar',
        'odt',
        'txt',
        'rtf',
        'swf');

    $ext = strtolower(substr($name, -3));
    if (!in_array($ext, $upload_allow)) {
        return "3";
    }

    $rutas = GetRutaArchivoAnioMes("documentos", $name);

    if (move_uploaded_file($tmp_name, $rutas["ruta_abs"])) {
        $sql = "INSERT INTO advf SET
		 advTipo = 'D' ,
		 advTitulo = '" . $titulo . "',
		 advTexto = '" . $descripcion . "' ,
		 advfecha = '" . date("Y-m-d H:i:s") . "' ,
		 advLink = '" . $rutas["ruta_rel"] . "' ,
		 catID = '" . $id_categoria . "',
		 advBytes = '" . $size . "',
		 advfechaCaptura = NOW() ,
		 advAutor = '" . $_SESSION["sessID"] . "'";

        $conn->execute($sql);


        //thumb
        return "0";
    }
    return "4";
}

function UploadAudio($files = array(), $post = array()) {
    global $conn;

    //$_FILES
    $tmp_name = $files["tmp_name"];
    $name = $files["name"];
    $error = $files["error"];
    $size = $files["size"];
    //$_POST
    $titulo = addslashes($post["titulo_audio"]);
    $descripcion = addslashes($post["descripcion_audio"]);
    $id_categoria = intval($post["id_categoria_audio"]);
    if ($error > 0) {
        return "1";
    }

    //validaciones
    if ($size > 83886080) {
        return "2";
    }

    $upload_allow = array('wav', 'mp3');

    $ext = strtolower(substr($name, -3));
    if (!in_array($ext, $upload_allow)) {
        return "3";
    }



    $rutas = GetRutaArchivoAnioMes("audios", $name);

    if (move_uploaded_file($tmp_name, $rutas["ruta_abs"])) {
        $sql = "INSERT INTO advf SET
		 advTipo = 'A' ,
		 advTitulo = '" . $titulo . "',
		 advTexto = '" . $descripcion . "' ,
		 advfecha = '" . date("Y-m-d H:i:s") . "' ,
		 advLink = '" . $rutas["ruta_rel"] . "' ,
		 catID = '" . $id_categoria . "',
		 advBytes = '" . $size . "',
		 advfechaCaptura = NOW() ,
		 advAutor = '" . $_SESSION["sessID"] . "'";
        $conn->execute($sql);
        //thumb
        return "0";
    }
    return "4";
}

function GetCategoriasContenidosParent() {
    global $conn;
    $q = "SELECT id, descripcion FROM categorias_contenidos WHERE activo = 'S' AND estado = 'A' AND id_padre = 0";
    $r = $conn->getRecordset($q);
    return $r;
}

function GetContenidosPorCategoriaHome($id_cat = 0) {

    global $conn;
    if ($id_cat > 0) {
        $sFiltroCat = " AND id_categoria_raiz = {$id_cat} ";
    }
    $q = "SELECT id, titulo_home, titulo FROM contenidos
		WHERE titulo <> '' AND titulo IS NOT NULL  $sFiltroCat  AND activo = 'S' AND estado = 'A'
		ORDER BY fecha DESC, hora DESC LIMIT 150";
    $r = $conn->getRecordset($q);
    for ($i = 0; $i < count($r); $i++) {
        $v[$i]["id"] = $r[$i]["id"];
        $v[$i]["titulo"] = utf8_encode(htmlentities($r[$i]["id"] . " - " . $r[$i]["titulo_home"]));
    }
    return $v;
}

function GetVideosPorCategoriaHome($id_cat = 0) {

    global $conn;
    if ($id_cat > 0) {
        $sFiltroCat = " AND id_categoria = {$id_cat} ";
    }
    $q = "SELECT id, titulo FROM videos
		WHERE titulo <> '' AND titulo IS NOT NULL  $sFiltroCat  AND activo = 'S' AND estado = 'A'
		order by id DESC";
    $r = $conn->getRecordset($q);
    for ($i = 0; $i < count($r); $i++) {
        $v[$i]["id"] = $r[$i]["id"];
        $v[$i]["titulo"] = utf8_encode(htmlentities($r[$i]["id"] . " - " . $r[$i]["titulo"]));
    }
    return $v;
}

function GetTagsPorCategoriaHome($id_cat = 0) {

    global $conn;
    if ($id_cat > 0) {
        $sFiltroCat = " AND T.id_categoria = {$id_cat} ";
    } else {
        $sLimite = " LIMIT 1000";
    }
    $q = "SELECT T.id, T.nombre as titulo, C.descripcion as categoria FROM tags T
		LEFT JOIN categorias_contenidos C ON C.id = T.id_categoria
		WHERE T.nombre <> '' AND T.nombre IS NOT NULL  $sFiltroCat  AND T.activo = 'S' AND T.estado = 'A'
		order by T.nombre, C.descripcion_readonly, T.id $sLimite";
    $r = $conn->getRecordset($q);
    for ($i = 0; $i < count($r); $i++) {
        $v[$i]["id"] = $r[$i]["id"];
        $v[$i]["titulo"] = utf8_encode(htmlentities($r[$i]["titulo"]) . " - " . htmlentities($r[$i]["categoria"] . " - " . htmlentities($r[$i]["id"])));
    }
    return $v;
}

function GetContenidosPorDefaultAdminHome() {
    global $conn;
    $q = "SELECT id, titulo, titulo_home FROM contenidos
		WHERE activo = 'S' AND estado = 'A'
		ORDER BY id DESC LIMIT 1000";
		//fecha DESC, hora DESC
    $r = $conn->getRecordset($q);
    return $r;
}

function GetContenidosPorDefaultAdminSubsitio($id_subsitio) {
    global $conn;
    $q = "SELECT c.id, c.titulo, c.titulo_home FROM contenidos c
		WHERE c.activo = 'S' AND c.estado = 'A'
		ORDER BY c.fecha DESC, c.id DESC 
		LIMIT 300";
		//AGREGAR DESC
		//fecha DESC, hora DESC
    $r = $conn->getRecordset($q);
    return $r;
}

function GetVideosPorDefaultAdminHome() {
    global $conn;
    $q = "SELECT id, titulo FROM videos
		WHERE activo = 'S' AND estado = 'A'
		AND titulo <> ''
		order by id DESC";
    $r = $conn->getRecordset($q);
    return $r;
}

function GetContenidosPorDefaultAdminHomeSubportales($id_portal) {
    global $conn;
    $q = "SELECT id, titulo, titulo_home FROM contenidos
		WHERE activo = 'S' AND estado = 'A'
		AND titulo_home <> '' AND id_categoria_raiz = '" . $id_portal . "'
		order by id DESC";

    $r = $conn->getRecordset($q);
    return $r;
}

function GetDataContenidoAdminHome($id = 0) {
    //es para prueba
    global $conn;
    $q = "SELECT id, titulo, titulo_home, volanta, copete, fotohome, fotolist
		FROM contenidos
		WHERE id = {$id} AND activo = 'S' AND estado = 'A'
		LIMIT 1";
    $r = $conn->getRecordset($q);
    return $r[0];
}

function GetContenidosHome($lang) {
    global $conn;
    $sql = 'SELECT * from home_idiomas WHERE lang = "' . $lang . '"';
}

function GetContenidosPorIdiomas($lang) {
    global $conn;
    if (empty($lang))
        return array();
    $sql = 'SELECT id, titulo FROM ' . $lang . '_contenidos WHERE activo = "S" AND estado = "A" ORDER BY fecha DESC, hora DESC';
    $rds = $conn->getRecordset($sql);
    return $rds;
}

function GetContenidosPorSeccionYTema($id_seccion = 0, $id_tema = 0) {
    global $conn;
    if (intval($id_seccion) == 0) {
        return array();
    }
    $id_tema = intval($id_tema);

    if ($id_tema > 0)
        $where_tema = ' AND id_tema = ' . $id_tema;
    else
        $where_tema = ' AND id_categoria_raiz = ' . $id_seccion;

    /* $q = "SELECT id, titulo FROM contenidos WHERE
      (
      id_categoria_raiz = {$id_seccion} OR
      id_categoria_raiz IN (SELECT id FROM categorias_contenidos WHERE id_padre = {$id_seccion})
      ) AND id_tema = {$id_tema}
      AND titulo <> '' ORDER BY fecha DESC";
     */
    $q = "SELECT id, titulo FROM contenidos WHERE 1=1 $where_tema AND titulo <> '' AND ( fotohome > 0 OR videohome <> '') AND estado = 'A' AND activo = 'S' ORDER BY id DESC LIMIT 100";
//echo $q;
    $r = $conn->getRecordset($q);
    return $r;
}

function GetContenidosPorIdioma($lang, $texto = "", $filtros = array()) {
    global $conn;
    if (!empty($texto)) {
        $texto = addslashes($texto);

        $orContenido = "";
        if ($filtros["en_copete"] == 1) {
            $orContenido .= " OR copete LIKE '%" . $texto . "%'";
        }

        if ($filtros["en_cuerpo"] == 1) {
            $orContenido .= " OR cuerpo LIKE '%" . $texto . "%'";
        }

        if ($filtros["en_tags"] == 1) {
            $orContenido .= " OR tags LIKE '%" . $texto . "%'";
        }

        $buscar = " AND (titulo like '%{$texto}%' {$orContenido})";
    }


    $q = "SELECT id, titulo FROM {$lang}_contenidos
        WHERE titulo <> ''  AND DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= fecha $buscar ORDER BY fecha DESC";
    $r = $conn->getRecordset($q);
    return $r;
}

function GetContenidosPorSeccion($id_seccion = 0, $texto = "", $filtros = array()) {
    global $conn;
    if (!empty($texto)) {
        $texto = addslashes($texto);

        $orContenido = "";
        if ($filtros["en_copete"] == 1) {
            $orContenido .= " OR copete LIKE '%" . $texto . "%'";
        }

        if ($filtros["en_cuerpo"] == 1) {
            $orContenido .= " OR cuerpo LIKE '%" . $texto . "%'";
        }

        if ($filtros["en_tags"] == 1) {
            $orContenido .= " OR tags LIKE '%" . $texto . "%'";
        }

        $buscar = " AND (titulo like '%{$texto}%' {$orContenido})";
    }


    $q = "SELECT id, titulo FROM contenidos WHERE
	(id_categoria_raiz = {$id_seccion} OR
	id_categoria_raiz IN (SELECT id FROM categorias_contenidos WHERE id_padre = {$id_seccion}))
	AND titulo <> ''  AND DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= fecha $buscar ORDER BY fecha DESC";
    $r = $conn->getRecordset($q);
    return $r;
}

function GetContenidosSeteadosAdminPortadaSeccionIdiomas($lang) {
    global $conn;
    if (empty($lang))
        return array();
    $q = "SELECT * FROM home_idiomas
        WHERE lang = '{$lang}' LIMIT 1";
    $r = $conn->getRecordset($q);
    return $r[0];
}

function GetContenidosSeteadosAdminPortadaSeccion($id_seccion, $id_tema = 0) {
    global $conn;
    if (intval($id_seccion) == 0)
        return array();
    $id_tema = intval($id_tema);
    $q = "SELECT * FROM home_secciones
        WHERE id_seccion = {$id_seccion}
        AND id_tema = {$id_tema} LIMIT 1";
    $r = $conn->getRecordset($q);
    return $r[0];
}

function GetTemasRelacionadosPorSeccion($id_seccion) {
    global $conn;
    if (intval($id_seccion) == 0)
        return array();
    $sql = 'SELECT c.idTema, t.nombre FROM categorias_temas c
            INNER JOIN temas t ON t.id = c.`idTema`
            WHERE c.idCategoria = ' . $id_seccion . '
            ORDER BY c.orden';
    $rds = $conn->getRecordset($sql);
    return $rds;
}

function GetNotasRapidasPorUsuario() {
    global $conn;

    if (intval($_SESSION['sessID']) <= 0)
        return false;

    $q = "SELECT NM.id, CONCAT(NM.nota,'(',AU.usuario,')') AS nota FROM notas_margen NM LEFT JOIN admin_usuarios AU ON NM.id_usuario = AU.id ORDER BY fecha DESC";
    $r = $conn->getRecordset($q);
    return $r;
}

function GetPermisosDashboard() {
    global $conn, $usr;
    //6 => id Principal
    $sql = "select * from admin_menu where activo='S' and visible = 'S' AND link LIKE 'contenidos.php%'  and id in (" . $usr->listapaginas(true) . ") order by seccion_id";

    $r = $conn->getRecordset($sql);
    return $r;
}
?>