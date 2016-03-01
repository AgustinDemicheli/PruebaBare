<?

function reemplazarSubtitulos($cuerpo) {
    $patron = "/\(S\)(.*)\(S\)/";
    preg_match_all($patron, $cuerpo, $encontrados);
    for ($i = 0; $i < count($encontrados[0]); $i++) {
        $h3 = "<h3>" . $encontrados[1][$i] . "</h3>";
        $cuerpo = str_ireplace($encontrados[0][$i], $h3, $cuerpo);
    }
    return $cuerpo;
}

function reemplazarListas($cuerpo) {
    $patron = "/\(L\)(.*)\(L\)/";
    preg_match_all($patron, $cuerpo, $encontrados);
    for ($i = 0; $i < count($encontrados[0]); $i++) {
        list($nro, $texto) = explode("|", $encontrados[1][$i]);
        $h3 = "<div class='title-big clearfix'><span>" . $nro . "</span><p>" . $texto . "</p></div>";
        $cuerpo = str_ireplace($encontrados[0][$i], $h3, $cuerpo);
    }
    return $cuerpo;
}

function reemplazarPreguntas($cuerpo) {
    $patron = "/\(P\)(.*)\(P\)/";
    preg_match_all($patron, $cuerpo, $encontrados);
    for ($i = 0; $i < count($encontrados[0]); $i++) {
        list($nro, $texto) = explode("|", $encontrados[1][$i]);
        $h3 = "<div class='question'><span>" . $nro . "</span><p>" . $texto . "</p></div>";
        $cuerpo = str_ireplace($encontrados[0][$i], $h3, $cuerpo);
    }
    return $cuerpo;
}

function reemplazarRespuestas($cuerpo) {
    $patron = "/\(R\)(.*)\(R\)/";
    preg_match_all($patron, $cuerpo, $encontrados);
    for ($i = 0; $i < count($encontrados[0]); $i++) {
        list($nro, $texto) = explode("|", $encontrados[1][$i]);
        $h3 = "<div class='answer'><span>" . $nro . "</span><p>" . $texto . "</p></div>";
        $cuerpo = str_ireplace($encontrados[0][$i], $h3, $cuerpo);
    }
    return $cuerpo;
}

function reemplazarFrasesWide($cuerpo) {
    $patron = "/\(DEST\)(.*)\(DEST\)/";
    preg_match_all($patron, $cuerpo, $encontrados);
    for ($i = 0; $i < count($encontrados[0]); $i++) {
        $patron2 = "/\(DEST2\)(.*)\(DEST2\)/";
        preg_match_all($patron2, $encontrados[1][$i], $autor);

        if ($autor[1][0] <> "")
            $h3 = "</div><div class='highlight-content-wrapper'><div class='main-box'><div class='highlight-content'><p>" . str_ireplace($autor[0][0], "", $encontrados[1][$i]) . ($autor[1][0] <> "" ? "<span>" . $autor[1][0] . "</span>" : "");
        else
            $h3 = "</div><div class='highlight-content-wrapper'><div class='main-box'><div class='highlight-content'><p>" . $encontrados[1][$i];

        $h3 .= '</p></div></div></div><div class="main-box">';

        $cuerpo = str_ireplace($encontrados[0][$i], $h3, $cuerpo);
    }
    return $cuerpo;
}

function reemplazarModulo($cuerpo,$mod_color,$mod_icono,$mod_titulo,$mod_cuerpo)
{
	global $conn;
	$aux = $conn->getRecordset("SELECT * FROM advf WHERE advID = '".$mod_icono."'");

	$bloque = '																
		<div class="info-block-content right">
			<div class="ribbon">
				<img src="/'.$aux[0]["advLink"].'">
			</div>
			<h3>'.$mod_titulo.'</h3>
			'.$mod_cuerpo.'
		</div>
	';

	$cuerpo = preg_replace('@\@modulo\@@si', $bloque, $cuerpo, 1);

	return $cuerpo;

}


function dateFormat($tag, $date) {
    list($year, $month, $day) = split('-', $date);

    switch ($tag) {
        case 'd':
            return $day;
            break;

        case 'M':
            switch ($month) {
                case '1': return 'ENE';
                    break;
                case '2': return 'FEB';
                    break;
                case '3': return 'MAR';
                    break;
                case '4': return 'ABR';
                    break;
                case '5': return 'MAY';
                    break;
                case '6': return 'JUN';
                    break;
                case '7': return 'JUL';
                    break;
                case '8': return 'AGO';
                    break;
                case '9': return 'SEP';
                    break;
                case '10': return 'OCT';
                    break;
                case '11': return 'NOV';
                    break;
                case '12': return 'DIC';
                    break;
            }
            break;
    }
}

//RECIBE UN ARRAY CON TODOS LOS VIDEOS
function SetTagsVideo($lisVideosRel, $cuerpo, $id_portal = 0) {
    global $var_url;

    $anchoVideoW = 515;

    preg_match_all('@\@video[a-zA-Z]\@@si', $cuerpo, $encontrados);

    for ($i = 0; $i < count($lisVideosRel); $i++) {
        $patron = "";
        if ($i <= count($encontrados[0])) {

            if ($encontrados[0][$i] == "@videoW@") {
                $patron = '@\@videoW\@@si';

                if (strtoupper($lisVideosRel[$i]['MEDIO']) == "VIMEO") {
                    $bloque = '<div class="image">
										<iframe src="http://player.vimeo.com/video/' . $lisVideosRel[$i]['CODIGO'] . '?title=0&amp;byline=0&amp;portrait=0" width="' . $anchoVideoW . '" height="328" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
								   </div>';

                    if (trim($lisVideosRel[$i]['DESCRIPCION']) != "") {
                        $bloque .= '<div class="epigrafe">' . $lisVideosRel[$i]['DESCRIPCION'] . '</div>';
                    }
                } else {
                    $bloque = '
					</div>
					<div class="wrapper-image-outside-box">
						<div class="image"><iframe src="http://www.youtube.com/embed/' . $lisVideosRel[$i]['CODIGO'] . '" frameborder="0" allowfullscreen width="1080" height="600"></iframe></div>
						<p class="epigrafe">' . $lisVideosRel[$i]['DESCRIPCION'] . '</p>
					</div>
					<div class="main-box">';
                }
            }

            if ($patron != "") {
                $cuerpo = preg_replace($patron, $bloque, $cuerpo, 1);
            }
        }
    }

    //si queda algo lo saco
    $cuerpo = preg_replace('@\@video[a-zA-Z]\@@si', "", $cuerpo, 1);

    return $cuerpo;
}

/**
 * reemplaza los tags (@foto@ | RECIBE UN ARRAY CON TODAS LAS FOTOS
 *
 * @param unknown_type $cuerpo
 */
function SetTagsFoto($lisFotosRel, $cuerpo, $id_portal = 0) {
    global $var_url;
	global $conn;

    $anchoFotoW = ($id_portal == 0) ? 538 : 621;

    //saco el roden de los tags
    preg_match_all('@\@foto[a-zA-Z]\@@si', $cuerpo, $encontrados);
    for ($i = 0; $i < count($lisFotosRel); $i++) {
        $patron = "";
        if ($i <= count($encontrados[0])) {

            if ($encontrados[0][$i] == "@fotoF@") {
                $patron = '@\@fotoF\@@si';
                
				$aux = $conn->getRecordset("SELECT * FROM imagenes_responsivas WHERE id_imagen = '".$lisFotosRel[$i]["ID_FOTO"]."' AND activo = 'S' LIMIT 1");

				if( count($aux)==1 )
					$alternativa = '<img src="/'.Multimedia::GetImagenStaticById(800, 0, $aux[0]["id_imagen_responsive"]).'" class="mobile">';
				else
					$alternativa = "";
				
				$bloque = '
				</div>
				<div class="wrapper-full-graph">
					<div class="main-box">
						<div class="graph">
							<img src="/'.Multimedia::GetImagenStatic(1024, 0, $lisFotosRel[$i]["LINK"]).'" class="desktop">
							'.$alternativa.'
						</div>
					</div>
				</div>
				<div class="main-box">';

	
            }

            if ($encontrados[0][$i] == "@fotoW@") {
                $patron = '@\@fotoW\@@si';

				$aux = $conn->getRecordset("SELECT * FROM imagenes_responsivas WHERE id_imagen = '".$lisFotosRel[$i]["ID_FOTO"]."' AND activo = 'S' LIMIT 1");
				if(count($aux)==1)
					$alternativa = '<img src="/'.Multimedia::GetImagenStaticById(800, 0, $aux[0]["id_imagen_responsive"]).'" class="mobile">';
				else
					$alternativa = "";

                $bloque = '
				</div>
				<div class="wrapper-image-outside-box">
					<div class="image">
						<img src="/'.Multimedia::GetImagenStatic(1078, 0, $lisFotosRel[$i]["LINK"]).'">
						'.$alternativa.'
					</div>
					<p class="epigrafe">' . $lisFotosRel[$i]['EPIGRAFE'] . '</p>
				</div>
				<div class="main-box">';
            }

            if ($encontrados[0][$i] == "@fotoD@") {
                $patron = '@\@fotoD\@@si';
                $bloque = '
					<div class="wrapper-image-right">
	 					<div class="image"><img src="/'.Multimedia::GetImagenStatic(479, 0, $lisFotosRel[$i]["LINK"]).'" /></div>
	 					<p class="epigrafe">' . $lisFotosRel[$i]['EPIGRAFE'] . '</p>
	 				</div>';

            }
            if ($patron != "") {
                $cuerpo = preg_replace($patron, $bloque, $cuerpo, 1);
            }
        }
    }

    //si queda algo lo saco
    $cuerpo = preg_replace('@\@foto[a-zA-Z]\@@si', "", $cuerpo, 1);

    return $cuerpo;
}


function SetTagTextoDestacado($texto_destacado, $cuerpo) {
    $bloque = '<div class="megaphone">
                <p>' . $texto_destacado . '</p>
               </div>';
    $cuerpo = preg_replace('@\@TD\@@si', $bloque, $cuerpo, 1);
    return $cuerpo;
}

function SetTagCodigoJS($codigo_js, $cuerpo) {
    $cuerpo = preg_replace('@\@CJS\@@si', "<div class='main-box'>".$codigo_js."</div>", $cuerpo, 1);
    return $cuerpo;
}

function SetTagCodigoJS2($codigo_js, $cuerpo) {
    $cuerpo = preg_replace('@\@CJS2\@@si',  "<div class='main-box'>".$codigo_js."</div>", $cuerpo, 1);
    return $cuerpo;
}

function SetTagCodigoJS3($codigo_js, $cuerpo) {
    $cuerpo = preg_replace('@\@CJS3\@@si',  "<div class='codigo_js'>".$codigo_js."</div>", $cuerpo, 1);
    return $cuerpo;
}

function SetVideoHD($videoid, $cuerpo, $anchoVideo = 515, $heightVideo = 328) {
    global $conn;
    $sql = "select * from videos where id = " . $videoid;
    $rs = $conn->execute($sql);
	$codigo_video = "";
	if ($rs->numrows>0)
	{
		$imageprev = "/".Multimedia::GetImagenStaticById($anchoVideo,0,$rs->field("id_preview"));
		$codigo_video = Multimedia::CargarVideoHdSd($videoid, $rs->field("link_sd"), $rs->field("link_hd"), $anchoVideo, $heightVideo, $imageprev);
	}
    $cuerpo = preg_replace('@\@videoHD\@@si', $codigo_video, $cuerpo, 1);
    return $cuerpo;
}


function generar_thumb($width, $height, $advid, $admin = 0) {
    global $conn;

    $sql = "select * from advf where advID = '" . $advid . "'";
    $rs = $conn->execute($sql);
    $widthX = $width;
    $heightY = $height;
    $filename = $rs->field("advLink");
    $bufFilename = split("\.", $filename);
    $filename = ($admin == 1 ? "../" : "") . $rs->field("advLink");

    // Antes que nada nos fijamos si el thumb ya existe

    list($widthor, $heightor, $type, $attr) = getimagesize($filename);

    if ($widthor > $heightor) {
        $height = (int) ($heightor * ($width / $widthor));
    } else {
        if ($height == 0)
            $height = (int) ($heightor * ($width / $widthor));
        else
            $width = (int) ($widthor * ($height / $heightor));
    }

    $thumbname = ($admin == 1 ? "../" : "") . $bufFilename[0] . "_" . $widthX . "x" . $heightY . "." . $bufFilename[1];

    if (!is_file($thumbname)) {
        if (preg_match("/png/", $filename)) {
            $im = imagecreatetruecolor($width, $height);
            $or = imagecreatefrompng($filename);
            imagecopyresampled($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
        }
        if (preg_match("/jpg/", $filename)) {
            $im = imagecreatetruecolor($width, $height);
            $or = imagecreatefromjpeg($filename);
            imagecopyresampled($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
        }
        if (preg_match("/gif/", $filename)) {
            $im = imagecreate($width, $height);
            $or = imagecreatefromgif($filename);
            imagecopyresized($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
        }

        if (preg_match("/(jpg|png|gif)/", $filename)) {
            $im2 = imagecreatetruecolor($width, $height);
            imagecopy($im2, $im, 0, 0, 0, 0, $width, $height);

            imagejpeg($im2, $thumbname, 95);
            imagedestroy($im);
            imagedestroy($or);
            imagedestroy($im2);
        }
        return $thumbname;
    } else {
        return $thumbname;
    }
}

function removeEvilAttributes($tagSource) {
    $stripAttrib = '/ (style|class)="(.*?)"/i';
    //$stripAttrib = '/ (style)="(.*?)"/i'; 
    $tagSource = stripslashes($tagSource);
    $tagSource = preg_replace($stripAttrib, '', $tagSource);
    return $tagSource;
}

function removeEvilTags($source) {
    $allowedTags = '<a><br><b><h1><h2><h3><h4><i><em>' .
            '<li><ol><p><strong><table>' .
            '<tr><td><th><u><ul><img><div>';

    $source = stripslashes(strip_tags($source, $allowedTags));

    return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
}

function removeEvilTagsSpecial($source) {
    $allowedTags = '<a><br><b><h1><h2><h3><h4><i><em>' .
            '<strong>';
    $source = stripslashes(strip_tags($source, $allowedTags));
    return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
}

function getMetaKeywordsFromString($string) {
    $preposiciones = array('a', 'ante', 'bajo', 'cabe', 'con', 'contra', 'de', 'desde', 'en', 'entre', 'hacia', 'hasta', 'para', 'por', 'seg˙n', 'sin', 'so', 'sobre', 'tras', 'excepto', 'mediante', 'durante', 'salvo', 'vÌa');
    $articulos = array('y', 'las', 'los', 'la', 'el', 'un', 'una', 'unas', 'unos', 'del');
    $varios = array('"', "'", "/", "\\", ":", ".", ";", " es ");

    for ($i = 0; $i < count($preposiciones); $i++)
        $preposiciones[$i] = " " . $preposiciones[$i] . " ";

    for ($i = 0; $i < count($articulos); $i++)
        $articulos[$i] = " " . $articulos[$i] . " ";

    $ret = str_ireplace($varios, "", $string);
    $ret = str_ireplace($preposiciones, " ", $ret);
    $ret = str_ireplace($articulos, " ", $ret);

    return $ret;
}

function cString($string, $length) {
    $tString = explode(' ', $string);
    $rString = '';
    $sString = 0;

    for ($index = 0; $index < count($tString); $index++) {
        if (strlen($rString . chr(32) . $tString[$index]) < $length) {
            $rString .= chr(32) . $tString[$index];
        } else {
            $sString = 1;

            break;
        }
    }

    if ($sString == 0) {
        return $rString;
    } else {
        return $rString . '...';
    }
}

function cString_1($string, $length, $completar = true) {
    $string = html_entity_decode($string);
    if (strlen($string) > $length) {

        if ($completar == true) {
            return substr($string, 0, $length) . "...";
        } else {
            return substr($string, 0, $length);
        }
    } else {
        return $string;
    }
}

function thumbs($width, $height, $advid) {
    global $conn;

    $sql = "select * from advf where advID = " . $advid;
    $rs = $conn->execute($sql);
    $widthX = $width;
    $heightY = $height;
    $filename = $rs->field("advLink");
    $bufFilename = split("\.", $filename);
    $filename = "../" . $rs->field("advLink");

    // Antes que nada nos fijamos si el thumb ya existe

    list($widthor, $heightor, $type, $attr) = getimagesize($filename);

    if ($widthor > $heightor) {
        $height = (int) ($heightor * ($width / $widthor));
    } else {
        $width = (int) ($widthor * ($height / $heightor));
    }

    $thumbname = "../" . $bufFilename[0] . "_" . $widthX . "x" . $heightY . "." . $bufFilename[1];

    if (!is_file("$thumbname")) {
        if (preg_match("/png/", $filename)) {
            $im = imagecreatetruecolor($width, $height);
            $or = imagecreatefrompng($filename);
            imagecopyresampled($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
        }
        if (preg_match("/jpg/", $filename)) {
            $im = imagecreatetruecolor($width, $height);
            $or = imagecreatefromjpeg($filename);
            imagecopyresampled($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
        }
        if (preg_match("/gif/", $filename)) {
            $im = imagecreate($width, $height);
            $or = imagecreatefromgif($filename);
            imagecopyresized($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
        }

        if (preg_match("/(jpg|png|gif)/", $filename)) {
            $im2 = imagecreatetruecolor($width, $height);
            imagecopy($im2, $im, 0, 0, 0, 0, $width, $height);

            imagejpeg($im2, $thumbname, 75);
            imagedestroy($im);
            imagedestroy($or);
            imagedestroy($im2);
        }
    }
}

function randomkeys($length) {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for ($i = 0; $i < $length; $i++) {
        $key .= $pattern{rand(0, 35)};
    }
    return $key;
}

function mysql_a_normal($fecha) {
    preg_match("/([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})/", $fecha, $mifecha);
    $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
    return $lafecha;
}

function mysql_a_timestamp($fecha) {

    if (strlen($fecha) > 10)
        preg_match("/([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2})/", $fecha, $mifecha);
    else
        preg_match("/([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})/", $fecha, $mifecha);

    $hora = (count($mifecha) > 4) ? $mifecha[4] : '0';
    $minu = (count($mifecha) > 5) ? $mifecha[5] : '0';
    return mktime($hora, $minu, 0, $mifecha[2], $mifecha[3], $mifecha[1]);
}

function normal_a_mysql($fecha) {
    preg_match("#([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})#", $fecha, $mifecha);
    $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
    return $lafecha;
}


function ver_Icono($doc) {
    global $var_path;

    $ext = StrToLower(SubStr($doc, StrRPos($doc, ".") + 1));

    switch ($ext) {
        case "doc":
            echo "<img alt='Documento de MS Word' src='" . $var_path . "admin/images/icon_doc.gif' border=0 >";
            break;
        case "xls":
            echo "<img alt='Planilla de MS Excel' src='" . $var_path . "admin/images/icon_xls.gif' border=0 >";
            break;
        case "pdf":
            echo "<img alt='Documento de Adobe Acrobat' src='" . $var_path . "admin/images/icon_pdf.gif' border=0 >";
            break;
        case "gif":
            echo "<img src='" . $var_path . "admin/images/icon_gif.gif' border=0 >";
            break;
        case "jpeg":
        case "jpg":
            echo "<img src='" . $var_path . "admin/images/icon_jpg.gif' border=0 >";
            break;
        case "mid":
        case "wav":
        case "mp3":
            echo "<img src='" . $var_path . "admin/images/icon_audio.gif' border=0 >";
            break;
        case "avi":
        case "mov":
        case "mpg":
            echo "<img src='" . $var_path . "admin/images/icon_video.gif' border=0 >";
            break;
        case "zip":
            echo "<img src='" . $var_path . "admin/images/icon_zip.gif' border=0 >";
            break;
        default:
            echo "<img alt='Archivo de formato No Identificado' src='" . $var_path . "admin/images/icon_no.gif' border=0 >";
            break;
    }
}

function CreateFolder($path, $folder) {
    $ret_value = 'false';
    if (!file_exists($path . '/' . $folder)) {
        if (mkdir($path . '/' . $folder, 0777)) {
            $ret_value = 'true';
        }
    }
    return $ret_value;
}

function ansiToplain($texto) {
    $texto = str_replace('<BR>', '<BR />', $texto);
    $texto = str_replace('<STRONG>', '<b>', $texto);
    $texto = str_replace('</STRONG>', '</b>', $texto);
    $texto = str_replace('–∞', 'a', $texto);
    $texto = str_replace('–±', 'a', $texto);
    $texto = str_replace('–¥', 'a', $texto);
    $texto = str_replace('–∏', 'e', $texto);
    $texto = str_replace('–π', 'e', $texto);
    $texto = str_replace('–ª', 'e', $texto);
    $texto = str_replace('–º', 'i', $texto);
    $texto = str_replace('–Ω', 'i', $texto);
    $texto = str_replace('–ø', 'i', $texto);
    $texto = str_replace('—.', 'o', $texto);
    $texto = str_replace('—.', 'o', $texto);
    $texto = str_replace('—.', 'o', $texto);
    $texto = str_replace('—.', 'u', $texto);
    $texto = str_replace('—.', 'u', $texto);
    $texto = str_replace('—.', 'u', $texto);
    $texto = str_replace('—?', 'n', $texto);
    $texto = str_replace("'", '"', $texto);
    return $texto;
}

// Genero las carpetas con el HTML
function wwwcopy($link, $file, $lang = "") {
    global $app_path, $var_url;
    $fp = @fopen($link, "r");
    while (!feof($fp)) {
        $cont.= fread($fp, 1024);
    }
    fclose($fp);

    $fp2 = @fopen($file, "w");
    fwrite($fp2, $cont);
    fclose($fp2);
}

function dia_semana($dia) {
    $semanaArray = array(
        "Mon" => "Lunes",
        "Tue" => "Martes",
        "Wed" => "Miercoles",
        "Thu" => "Jueves",
        "Fri" => "Viernes",
        "Sat" => "S·bado",
        "Sun" => "Domingo",
    );
    return $semanaArray[$dia];
}

function dia_semana2($dia) {
    $semanaArray = array(
        "1" => "Lunes",
        "2" => "Martes",
        "3" => "Miercoles",
        "4" => "Jueves",
        "5" => "Viernes",
        "6" => "S√°bado",
        "7" => "Domingo",
    );
    return $semanaArray[$dia];
}

function CutString($string, $maxchars) {
    $content = substr($string, 0, $maxchars);
    $pos = strrpos($content, " ");
    if ($pos > 0) {
        $content = substr($content, 0, $pos);
    }
    if (strlen($content) < strlen($string))
        $content .= "...";
    return $content;
}

function Href($page = '', $parameters = '') {

    if (!$page)
        die(PrintError('Error, no determino la pagina'));


    if ($parameters) {

        #Si es array separo los nombres y valores y los uno en la direccion web

        if (is_array($parameters)) {

            $link .= $page . '?';

            $nombre = array_keys($parameters);

            $valor = array_values($parameters);

            //print_r($valor);exit();
            //$link .= $nombre[0] .'='. OutputString($valor[0]);

            for ($i = 0; $i < count($nombre); $i++) {


                if (is_array($valor[$i])) {

                    $subArray = $nombre[$i];

                    for ($j = 0; $j < count($valor[$i]); $j++) {

                        $subValue = $valor[$i][$j];

                        $link .= '&' . $subArray . '%5B%5D=' . OutputString($subValue);
                    }
                } else {

                    $link .= ($i ? '&' : '') . $nombre[$i] . '=' . OutputString($valor[$i]);
                }
            }
        }
        else
            $link .= $page . '?' . OutputString($parameters);

        $separator = '&';
    }else {

        $link .= $page;
        $separator = '?';
    }


    # Si tiene al final de la direccion un caracter & o ?, lo saco

    while ((substr($link, -1) == '&') || (substr($link, -1) == '?'))
        $link = substr($link, 0, -1);

    //$link = str_replace("&","&amp;",$link);
    return $link;
}

function ParseInputData($data, $parse) {

    return strtr(trim($data), $parse);
}

function OutputString($string, $translate = false) {

    if ($translate == false)
        return ParseInputData($string, array('"' => '&quot;'));
    else
        return ParseInputData($string, $translate);
}

function MostrarFecha($fecha, $idioma = "", $estilo = "completa") {

    if ($fecha != "") {
        list($dia, $mes, $ano) = split("/", $fecha);

        if (!$ano) {
            list($ano, $mes, $dia) = split("-", $fecha);
            ;
        }

        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
        $diaSemana = date("w", $timestamp);

        if ($idioma == "") {

            $nombreDia = GetNombreDia($diaSemana, $idioma);
            $nombreMes = GetNombreMes($mes, $idioma);
            switch ($estilo) {
                case "completa":return $dia . " de " . $nombreMes . " " . $ano;
                    break;
                case "medio":return $dia . " " . $nombreMes;
                    break;
                case "simple":return $dia . "." . $mes . "." . $ano;
                    break;
            }
        } else if ($idioma == "_pt") {
            $nombreDia = GetNombreDia($diaSemana, $idioma);
            $nombreMes = GetNombreMes($mes, $idioma);
            switch ($estilo) {
                case "completa":return $dia . " de " . $nombreMes . " de " . $ano;
                    break;
                case "simple":return $dia . "." . $mes . "." . $ano;
                    break;
            }
        } else {
            $nombreDia = date("l", $timestamp);
            $nombreMes = date("F", $timestamp);
            switch ($estilo) {
                case "completa":return $nombreMes . " " . $dia . ", " . $ano;
                    break;
                case "simple":return $dia . "." . $mes . "." . $ano;
                    break;
            }
        }
    }
}

//end MostrarFecha

/**
 * retorna las dos fechas formateadas
 */
function FormatoFecha($fecha1, $fecha2 = "", $lang = "") {

    //print '<pre>sss'; print_r($lang); print '</pre>';

    list($anio, $mes, $dia) = split("-", $fecha1);
    $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
    $diaSemana = date("w", $timestamp);

    list($anio1, $mes1, $dia1) = split("-", $fecha2);
    $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
    $diaSemana1 = date("w", $timestamp1);

    if ($lang == "" || $lang == "_pt") {

        $nombreDia = GetNombreDia($diaSemana, $lang);
        $nombreMes = GetNombreMes($mes, $lang);

        $nombreDia1 = GetNombreDia($diaSemana1, $lang);
        $nombreMes1 = GetNombreMes($mes1, $lang);

        if ($fecha2 != "") {

            if ($mes == $mes1) {

                if ($anio == $anio1) {

                    if (date("Y") == $anio) {

                        if ($dia == $dia1) {
                            //2008-05-01 2008-05-01
                            $sfecha = $dia . " de " . $nombreMes;
                        } else {
                            //2008-05-01 2008-05-03
                            $sfecha = $dia . " al " . $dia1 . " de " . $nombreMes;
                        }
                    } else {
                        if ($dia == $dia1) {
                            //2008-05-01 2008-05-01
                            $sfecha = $dia . " de " . $nombreMes . " del " . $anio;
                        } else {
                            //2008-05-01 2008-05-03
                            $sfecha = $dia . " al " . $dia1 . " de " . $nombreMes . " del " . $anio;
                        }
                    }
                } else {
                    //2008-05-01 2009-05-01
                    $sfecha = $dia . " de " . $nombreMes . " del " . $anio . " al " . $dia1 . " de " . $nombreMes1 . " del " . $anio1;
                }
            } else {

                if ($anio == $anio1) {
                    if (date("Y") == $anio) {
                        //2008-05-01 2008-06-01
                        $sfecha = $dia . " de " . $nombreMes . " al " . $dia1 . " de " . $nombreMes1;
                    } else {
                        //2009-05-01 2009-06-01
                        $sfecha = $dia . " de " . $nombreMes . " al " . $dia1 . " de " . $nombreMes1 . " del " . $anio;
                    }
                } else {
                    //2008-05-01 2009-06-01
                    $sfecha = $dia . " de " . $nombreMes . " del " . $anio . " al " . $dia1 . " de " . $nombreMes1 . " del " . $anio1;
                }
            }
        } else {

            if (date("Y") == $anio) {
                $sfecha = $dia . " de " . $nombreMes;
            } else {
                $sfecha = $dia . " de " . $nombreMes . " del " . $anio;
            }
        }
    } else {

        $nombreDia = date("l", $timestamp);
        $nombreMes = date("F", $timestamp);

        $nombreDia1 = date("l", $timestamp1);
        $nombreMes1 = date("F", $timestamp1);


        if ($fecha2 != "") {

            if ($mes == $mes1) {

                if ($anio == $anio1) {

                    if (date("Y") == $anio) {

                        if ($dia == $dia1) {
                            //2008-05-01 2008-05-01
                            $sfecha = $nombreMes . " " . $dia;
                        } else {
                            //2008-05-01 2008-05-03
                            $sfecha = $dia . " to " . $dia1 . " of " . $nombreMes;
                        }
                    } else {
                        if ($dia == $dia1) {
                            //2008-05-01 2008-05-01
                            $sfecha = $nombreMes . " " . $dia . "," . $anio;
                        } else {
                            //2008-05-01 2008-05-03
                            $sfecha = $dia . " to " . $dia1 . " of " . $nombreMes . ", " . $anio;
                        }
                    }
                } else {
                    //2008-05-01 2009-05-01
                    $sfecha = $nombreMes . " " . $dia . ", " . $anio . " to " . $nombreMes1 . " " . $dia1 . ", " . $anio1;
                }
            } else {

                if ($anio == $anio1) {
                    if (date("Y") == $anio) {
                        //2008-05-01 2008-06-01
                        $sfecha = $nombreMes . " " . $dia . " to " . $nombreMes1 . " " . $dia1;
                    } else {
                        //2009-05-01 2009-06-01
                        $sfecha = $nombreMes . " " . $dia . " to " . $nombreMes1 . " " . $dia1 . ", " . $anio;
                    }
                } else {
                    //2008-05-01 2009-06-01
                    $sfecha = $nombreMes . " " . $dia . ", " . $anio . " to " . $nombreMes1 . " " . $dia1 . ", " . $anio1;
                }
            }
        } else {

            if (date("Y") == $anio) {
                $sfecha = $nombreMes . " " . $dia;
            } else {
                $sfecha = $nombreMes . " " . $dia . ", " . $anio;
            }
        }
    }

    return $sfecha;
}

function GetNombreDia($dia, $lang = "_es") {

    if ($lang == "_es" || $lang == "") {

        switch ($dia) {
            case "0": return "Domingo";
                break;
            case "1": return "Lunes";
                break;
            case "2": return "Martes";
                break;
            case "3": return "Mi&#233;rcoles";
                break;
            case "4": return "Jueves";
                break;
            case "5": return "Viernes";
                break;
            case "6": return "S&#225;bado";
                break;
            case "7": return "Domingo";
                break;
        }
    }

    if ($lang == "_pt") {

        switch ($dia) {

            case "0": return "Domingo";
                break;
            case "1": return "Segunda-feira";
                break;
            case "2": return "TerÁa-feira";
                break;
            case "3": return "Quarta-feira";
                break;
            case "4": return "Quinta-feira";
                break;
            case "5": return "Sexta-feira";
                break;
            case "6": return "S&#225;bado";
                break;
        }
    }
}

function GetNombreMes($mes, $lang = "_es") {

    if ($lang == "_es" || $lang == "") {

        switch ($mes) {
            case "1": return "Enero";
                break;
            case "2": return "Febrero";
                break;
            case "3": return "Marzo";
                break;
            case "4": return "Abril";
                break;
            case "5": return "Mayo";
                break;
            case "6": return "Junio";
                break;
            case "7": return "Julio";
                break;
            case "8": return "Agosto";
                break;
            case "9": return "Septiembre";
                break;
            case "10": return "Octubre";
                break;
            case "11": return "Noviembre";
                break;
            case "12": return "Diciembre";
                break;
        }
    }

    if ($lang == "_pt") {

        switch ($mes) {
            case "1": return "Janeiro";
                break;
            case "2": return "Fevereiro";
                break;
            case "3": return "MarÁo";
                break;
            case "4": return "Abril";
                break;
            case "5": return "Maio";
                break;
            case "6": return "Junho";
                break;
            case "7": return "Julho";
                break;
            case "8": return "Agosto";
                break;
            case "9": return "Setembro";
                break;
            case "10": return "Outubro";
                break;
            case "11": return "Novembro";
                break;
            case "12": return "Dezembro";
                break;
        }
    }

    if ($lang == "_en") {

        switch ($mes) {
            case "1": return "January";
                break;
            case "2": return "February";
                break;
            case "3": return "March";
                break;
            case "4": return "April";
                break;
            case "5": return "May";
                break;
            case "6": return "June";
                break;
            case "7": return "July";
                break;
            case "8": return "August";
                break;
            case "9": return "September";
                break;
            case "10": return "October";
                break;
            case "11": return "November";
                break;
            case "12": return "December";
                break;
        }
    }
}

/**
 * GDF 2007
 * Retorna el numero del mes.
 *
 * @param unknown_type $mes
 */
function GetNumeroMes($mes) {

    $mes = strtolower($mes);

    switch ($mes) {

        case "jan": return 1;
            break;
        case "feb": return 2;
            break;
        case "mar": return 3;
            break;
        case "apr": return 4;
            break;
        case "may": return 5;
            break;
        case "jun": return 6;
            break;
        case "jul": return 7;
            break;
        case "aug": return 8;
            break;
        case "sep": return 9;
            break;
        case "oct": return 10;
            break;
        case "nov": return 11;
            break;
        case "dec": return 12;
            break;

        default: return 1;
    }
}




/**
 * reemplaza el class o el estilo que viene en el 
 * Tabien reeplaza si viene en algun lado del string
 * /spaw/'<a></>'
 */
function GetClassLink($string, $class = "trebuchet_11_gris_Links") {

    //para que no inyecten un class
    $string = preg_replace('@<a([^>]*)>([\W\w\s\<\>\/]*?)</a>@si', '<a $1 class="' . $class . '">$2</a>', $string);

    //se detecta que el editor html de los administradores agrega /spaw/
    $string = str_replace("spaw/", "", $string);

    return $string;
}

// Borrowed from the PHP Manual user notes. Convert entities, while
// preserving already-encoded entities:
function htmlentities_arg($myHTML) {
    //$translation_table=get_html_translation_table (HTML_ENTITIES,ENT_QUOTES);
    $translation_table[chr(38)] = '&';
    return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/", "&amp;", strtr($myHTML, $translation_table));
}

// Borrowed from the PHP Manual user notes. Convert entities, while
// preserving already-encoded entities:
function htmlentities_dir($myHTML) {

    $myHTML = strtolower($myHTML);
    $myHTML = trim($myHTML);
    $myHTML = str_replace(" - ", "-", $myHTML);
    $myHTML = str_replace(" ", "-", $myHTML);
    $myHTML = str_replace(array("·", "È", "Ì", "Û", "˙", "Ò"), array("a", "e", "i", "o", "u", "n"), $myHTML);
    $allowed = "/[^a-z0-9\\-]/i";
    $myHTML = preg_replace($allowed, "", $myHTML);

    return $myHTML;
}

function limpiarCaracteres($myHTML) {
    $myHTML = str_replace("\'", '', $myHTML);
    $portugues = "‡‚„ÁËÍÚÙı¿¬√… »”‘’«";

    $allowed = "/[^a-z0-9\-Ò—·¡È…ÌÕÛ”˙⁄‰ƒÎÀÔœˆ÷¸‹*&%\$:;,\.∫+-@#\"[:punct:]' '\°\ø" . $portugues . "]/i";
    $myHTML = preg_replace($allowed, "", $myHTML);
    return $myHTML;
}

function esUnMailValido($email) {
    //"first.last@domain.co.uk";
    return (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email));
}

function esMultiplo($numero, $multiploDe) {
    return ($numero % $multiploDe == 0);
}


function cortar_string($string, $largo) {
    $marca = "<!--corte-->";

    if (strlen($string) > $largo) {

        $string = wordwrap($string, $largo, $marca);
        $string = explode($marca, $string);
        $string = $string[0];
    }
    return $string;
}



?>