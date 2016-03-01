<?php

class Multimedia extends Constructor {

    private $tipo;
    private $ext = "";
    private $size = 0;

    public function __construct($tipo) {
        global $lang;
        $this->tipo = $tipo;
        parent::SetJoin(" LEFT JOIN fotografos f ON " . $this->str_tabla . ".id_fotografo = f.id
	 					  LEFT JOIN advf a ON f.id_imagen = a.advID
	 						");
        parent::SetCondicionInicial("advActivos='S' AND advTipo='" . $this->tipo . "'");
        parent::SetCampoId("advID");
        parent::__construct("advf", array("advID" => "id",
            "advTitulo" . $lang => "titulo",
            "advTexto" . $lang => "texto",
            "advCodigo" => "codigo",
            "advFecha" => "fecha",
            "advLink" => "link",
            "catID" => "",
            "advWidth" => "width_real",
            "advHeight" => "height_real",
            "advBytes" => "peso",
            "advPreview" => "id_preview",
            "advAutor_archivo" . $lang => "autor",
            "advAutor_archivo_link" => "autor_link",
            "id_fotografo" => "id_fotografo",
            "a.advLink" => "firma",
        ));
    }

    public function Restaurar($id = "") {
        global $app_path;
        if ($id) {
            $this->SetId($id);
        }
        parent::Restaurar();

        if (is_file($app_path . $this->GetValor("link"))) {

            $this->ext = substr($this->GetValor("link"), strrpos($this->GetValor("link"), ".") + 1);
            $this->size = filesize($app_path . $this->GetValor("link"));
        }
    }

    /**
     * Metodo accesible desde una instancia
     */
    public function GetImagen($width, $height = 0) {

        if (!$width && !$height) {
            try {
                throw new MyException("Error: Se debe especificar el ancho o el alto");
            } catch (MyException $e) {
                $e->getError();
            }
        } else {
            $this->GetImagenStatic($width, $height, $this->GetValor("link"));
        }
    }

    /**
     *
     * Si la imagen es vertical, se lleva al ancho y debe aplicarse un OVERFLOW HIDDEN para ocultar el sobrante.
     * Si es Wide, se lleva al alto deseado y la imagen debería mostrarse como BACKGROUND-POSITION CENTER en un DIV
     * @param $width_box
     * @param $height_box
     * @param $advLink
     */
    public function GetImagenStaticFitBoxCentered($width_box, $height_box, $advLink = "") {
        global $app_path;
        global $var_url;

        $filename = $advLink;

        if (!is_file($app_path . $filename)) {
            try {
                throw new MyException("Error: el archivo no exixte físicamente");
            } catch (MyException $e) {
                $e->getError();
            }
        } else {
            $bufFilename[0] = substr($filename, 0, strrpos($filename, "."));
            $bufFilename[1] = substr($filename, strrpos($filename, ".") + 1);

            $filename = $app_path . $filename;

            list($widthor, $heightor, $type, $attr) = getimagesize($filename);

            $finalW = $finalH = 0;

            if ($widthor > $heightor) {
                $finalH = (int) $height_box;
                $finalW = ceil($widthor * ($height_box / $heightor));

                if ($finalW < $width_box) {
                    $finalW = (int) $width_box;
                    $finalH = ceil($heightor * ($width_box / $widthor));
                }
            } else {
                $finalW = (int) $width_box;
                $finalH = ceil($heightor * ($width_box / $widthor));
            }

            //Arma la imagen final
            $thumbname = $app_path . $bufFilename[0] . "_" . $finalW . "x" . $finalH . "." . $bufFilename[1];

            if (!is_file($thumbname)) {
                if (preg_match("/png/", $filename)) {
                    $or = imagecreatefrompng($filename);
                    $im = imagecreatetruecolor($finalW, $finalH);
                    imagealphablending($im, false);
                    $colorTransparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
                    imagefill($im, 0, 0, $colorTransparent);
                    imagesavealpha($im, true);
                    imagecopyresampled($im, $or, 0, 0, 0, 0, $finalW, $finalH, $widthor, $heightor);
                    imagepng($im, $thumbname);

                    imagedestroy($im);
                    imagedestroy($or);
                }
                if (preg_match("/jpg/", $filename)) {
                    $im = imagecreatetruecolor($finalW, $finalH);
                    $or = imagecreatefromjpeg($filename);
                    imagecopyresampled($im, $or, 0, 0, 0, 0, $finalW, $finalH, $widthor, $heightor);
                }
                if (preg_match("/gif/", $filename)) {
                    $im = imagecreate($finalW, $finalH);
                    $or = imagecreatefromgif($filename);
                    imagecopyresized($im, $or, 0, 0, 0, 0, $finalW, $finalH, $widthor, $heightor);
                }

                if (preg_match("/(jpg|gif)/", $filename)) {
                    $im2 = imagecreatetruecolor($finalW, $finalH);
                    imagecopy($im2, $im, 0, 0, 0, 0, $finalW, $finalH);

                    imagejpeg($im2, $thumbname, 92);
                    imagedestroy($im);
                    imagedestroy($or);
                    imagedestroy($im2);
                }
            }

            return str_replace($app_path, "", $thumbname);
        }
    }

    /**
     *  Metodo estatico sin necesidad de instanciar el objeto
     */
    public function GetImagenStatic($width, $height, $advLink = "", $resize = true, $credito = 'N', $watermark = 'N') {
        global $app_path;
        global $var_url;

        $width_s = $width;
        $height_s = $height;

        //$widthX = $width;

        /* if ($advLink==""){
          $filename = $this->GetValor("link");
          }else { */
        $filename = $advLink;
        //}

        if (!is_file($app_path . $filename)) {

            try {
                throw new MyException("");
            } catch (MyException $e) {
                $e->getError();
            }
        } else {
            $bufFilename[0] = substr($filename, 0, strrpos($filename, "."));
            $bufFilename[1] = substr($filename, strrpos($filename, ".") + 1);

            if ($resize == false) {
                return $filename;
            }

            $filename = $app_path . $filename;

            // Antes que nada nos fijamos si el thumb ya existe

            list($widthor, $heightor, $type, $attr) = getimagesize($filename);

            //$height = (int)($heightor * ($width/$widthor));


            /**/
            if ($width > 0 && $height > 0) {

                /* Proporcion de Imagen */
                if ($widthor == $heightor) {
                    if ($width > $height) {
                        $width = (int) ($widthor * ($height / $heightor));
                    } else {
                        $height = (int) ($heightor * ($width / $widthor));
                    }
                }

                if ($widthor > $heightor) {
                    if ($width > $height) {
                        $height = (int) ($heightor * ($width / $widthor));
                    } else {
                        $width = (int) ($widthor * ($height / $heightor));
                    }
                }

                if ($widthor < $heightor) {
                    if ($width > $height) {
                        $width = (int) ($widthor * ($height / $heightor));
                    } else {
                        $height = (int) ($heightor * ($width / $widthor));
                    }
                }

                //para que no superen los topes
                if ($height > $height_s) {
                    $height = $height_s;
                    $width = (int) ($widthor * ($height / $heightor));
                }
                //
                if ($width > $width_s) {
                    $width = $width_s;
                    $height = (int) ($heightor * ($width / $widthor));
                }
            } else {

                if ($width > 0 && $height == 0) {
                    $height = (int) ($heightor * ($width / $widthor));
                }

                if ($width == 0 && $height > 0) {
                    $width = (int) ($widthor * ($height / $heightor));
                }
            }

            /**/



            $thumbname = $app_path . $bufFilename[0] . "_" . $width . "x" . $height . "." . $bufFilename[1];

            if (!is_file($thumbname)) {
                if (preg_match("/png/", $filename)) {
                    $or = imagecreatefrompng($filename);
                    $im = imagecreatetruecolor($width, $height);
                    imagealphablending($im, false);
                    $colorTransparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
                    imagefill($im, 0, 0, $colorTransparent);
                    imagesavealpha($im, true);
                    imagecopyresampled($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
                    imagepng($im, $thumbname);

                    imagedestroy($im);
                    imagedestroy($or);
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

                if (preg_match("/(jpg|gif)/", $filename)) {
                    $im2 = imagecreatetruecolor($width, $height);
                    imagecopy($im2, $im, 0, 0, 0, 0, $width, $height);

                    imagejpeg($im2, $thumbname, 92);
                    imagedestroy($im);
                    imagedestroy($or);
                    imagedestroy($im2);
                }

				//Agregar Marca de Agua
				if($watermark == 'S') Multimedia::AddWaterMark($thumbname);


            }


            return str_replace($app_path, "", $thumbname);
        }
    }

    /**
     *  Metodo estatico sin necesidad de instanciar el objeto
     * Creo la imagen con la firma en la esquina inferior derecha de la imagen
     */
    public function GetImagenStaticById($width, $height, $advID, $resize = true, $credito = "N", $watermark = "N") {
        global $conn;
        global $app_path;
        global $var_url;

        $width_s = $width;
        $height_s = $height;

        $rs = $conn->getRecordset("SELECT * FROM advf WHERE advID = '" . $advID . "' AND advTipo = 'F'");

        if ($rs[0]["advID"] > 0) {

            if (!is_file($app_path . $rs[0]["advLink"])) {

                try {
                    throw new MyException("Error: el archivo no exixte físicamente");
                } catch (MyException $e) {
                    $e->getError();
                }
            } else {

                $filename = $rs[0]["advLink"];

                ///////

                $bufFilename[0] = substr($filename, 0, strrpos($filename, "."));
                $bufFilename[1] = substr($filename, strrpos($filename, ".") + 1);

                if ($resize == false) {
                    return $filename;
                }

                $filename = $app_path . $filename;

                list($widthor, $heightor, $type, $attr) = getimagesize($filename);

                /**/
                if ($width > 0 && $height > 0) {

                    /* Proporcion de Imagen */
                    if ($widthor == $heightor) {
                        if ($width > $height) {
                            $width = (int) ($widthor * ($height / $heightor));
                        } else {
                            $height = (int) ($heightor * ($width / $widthor));
                        }
                    }

                    if ($widthor > $heightor) {
                        if ($width > $height) {
                            $height = (int) ($heightor * ($width / $widthor));
                        } else {
                            $width = (int) ($widthor * ($height / $heightor));
                        }
                    }

                    if ($widthor < $heightor) {
                        if ($width > $height) {
                            $width = (int) ($widthor * ($height / $heightor));
                        } else {
                            $height = (int) ($heightor * ($width / $widthor));
                        }
                    }

                    //para que no superen los topes
                    if ($height > $height_s) {
                        $height = $height_s;
                        $width = (int) ($widthor * ($height / $heightor));
                    }
                    //
                    if ($width > $width_s) {
                        $width = $width_s;
                        $height = (int) ($heightor * ($width / $widthor));
                    }
                } else {

                    if ($width > 0 && $height == 0) {
                        $height = (int) ($heightor * ($width / $widthor));
                    }

                    if ($width == 0 && $height > 0) {
                        $width = (int) ($widthor * ($height / $heightor));
                    }
                }

                /**/

                $thumbname = $app_path . $bufFilename[0] . "_" . $width . "x" . $height . "." . $bufFilename[1];

                // Antes que nada nos fijamos si el thumb ya existe
                if (!is_file($thumbname)) {
                    if (preg_match("/png/", $filename)) {
                        $or = imagecreatefrompng($filename);
                        $im = imagecreatetruecolor($width, $height);
                        imagealphablending($im, false);
                        $colorTransparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
                        imagefill($im, 0, 0, $colorTransparent);
                        imagesavealpha($im, true);
                        imagecopyresampled($im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
                        imagepng($im, $thumbname);

                        imagedestroy($im);
                        imagedestroy($or);
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

                    if (preg_match("/(jpg|gif)/", $filename)) {
                        $im2 = imagecreatetruecolor($width, $height);
                        imagecopy($im2, $im, 0, 0, 0, 0, $width, $height);

                        imagejpeg($im2, $thumbname, 92);
                        imagedestroy($im);
                        imagedestroy($or);
                        imagedestroy($im2);
                    }

					//Agregar Marca de Agua

				if($watermark == 'S') Multimedia::AddWaterMark($thumbname);

	              //Agrego firma del fotografo en la foto
					if ($credito == "S") {
						//$rs[0]["id_fotografo"] = 1;
						//Selecciono la firma del fotografo
						$q_firma = "SELECT f.id_imagen, a.advLink FROM fotografos f LEFT JOIN advf a ON a.advID = f.id_imagen WHERE f.activo = 'S' AND f.estado = 'A' AND f.id = '" . $rs[0]["id_fotografo"] . "' ";
						$rs_firma = $conn->getRecordset($q_firma);

						if (!$rs->eof) {
							$image_origen = $thumbname;

							switch (exif_imagetype($image_origen)) {
								case IMAGETYPE_GIF:
									$im = imagecreatefromgif($image_origen);
									break;
								case IMAGETYPE_JPEG:
									$im = imagecreatefromjpeg($image_origen);
									break;
								case IMAGETYPE_PNG:
									$im = imagecreatefrompng($image_origen);
									break;
							}

							$firma = imagecreatefrompng($app_path . $rs_firma[0]["advLink"]);

							$margen_dcho = 10;
							$margen_inf = 10;
							$sx = imagesx($firma);
							$sy = imagesy($firma);

							imagecopymerge($im, $firma, imagesx($im) - $sx - $margen_dcho, imagesy($im) - $sy - $margen_inf, 0, 0, imagesx($firma), imagesy($firma), 50);

							// Guardar la imagen en el mismo archivo y libero memoria
							switch (exif_imagetype($image_origen)) {
								case IMAGETYPE_GIF:
									imagegif($im, $image_origen);
									break;
								case IMAGETYPE_JPEG:
									imagejpeg($im, $image_origen);
									break;
								case IMAGETYPE_PNG:
									imagepng($im, $image_origen);
									break;
							}

							imagedestroy($im);
						}
					}


                }

                return str_replace($app_path, "", $thumbname);
            } // cierre else no existe el archivo fisicamente
        } // cierre if no existe ID advf
    }

    //se puede acceder sin tener que instanciar el objeto
    //si es desde instancia llamar $obj->GetSize();
    public function GetSize($size = "", $MB = "") {

        $MB = strtoupper($MB);

        if (!$size) {
            $size = $this->size;
        }

        if (!$MB) {
            if (round($size / 1024 / 1024, 1) < 1) {
                return round($size / 1024, 1) . " KB";
            } else {
                return round($size / 1024 / 1024, 1) . "MB";
            }
        } else {

            switch ($MB) {
                case "K": return round($size / 1024, 1) . " KB";
                    break;
                case "M": return round($size / 1024 / 1024, 1) . "MB";
                    break;
            }
        }
    }

    public function GetExt($link = "") {

        if ($link) {
            return substr($link, -3);
        } else {
            $this->ext;
        }
    }

    public function GetVideoById($advID, $width, $height, $image="") {
        global $app_path;
        global $var_url;
        global $conn;

        $rs = $conn->getRecordset("SELECT * FROM advf WHERE advID = '" . $advID . "' AND advTipo = 'V'");

        if ($rs[0]["advID"] > 0) {
            if ($rs[0]["youtube_code"] <> "") {
                $str = '<iframe id="video-'.$advID.'" width="' . $width . '" height="' . $height . '" src="http://www.youtube.com/embed/' . $rs[0]["youtube_code"] . '?showinfo=0&autohide=1&rel=0" frameborder="0" allowfullscreen></iframe>';
            }
			elseif ($rs[0]["vimeo_code"] <> "") {
                $str = '<iframe id="video-'.$advID.'" src="//player.vimeo.com/video/' . $rs[0]["vimeo_code"] . '?portrait=0&title=0&byline=0" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> ';
            }
			else {
                $str = "<div id='videoplayer_" . $rs[0]["advID"] . "'>&nbsp;</div>
					<script type='text/javascript'>
							  jwplayer('videoplayer_" . $rs[0]["advID"] . "').setup({
								'id': 'player_" . $rs[0]["advID"] . "',
								'width': '" . $width . "',
								'height': '" . $height . "',
								'file': '/" . $rs[0]["advLink"] . "',
								'image': '" . $image . "',
								'controlbar': 'bottom',
								'modes': [
									{type: 'html5'},
									{type: 'flash', src: '/includes/jwplayer/player.swf'},
									{type: 'download'}
								]
							  });
					</script>";
            }

            return $str;
        }
        else
            return;
    }

    public function GetAudioById($advID, $width, $height) {
        global $app_path;
        global $var_url;
        global $conn;

        $rs = $conn->getRecordset("SELECT * FROM advf WHERE advID = '" . $advID . "' AND advTipo = 'A'");

        if ($rs[0]["advID"] > 0) {

            $str = "<div id='audioplayer_" . $rs[0]["advID"] . "'>&nbsp;</div>
					<script type='text/javascript'>
							  jwplayer('audioplayer_" . $rs[0]["advID"] . "').setup({
								'id': 'player_" . $rs[0]["advID"] . "',
								'width': '" . $width . "',
								'height': '" . $height . "',
								'file': '/" . $rs[0]["advLink"] . "',
								'controlbar': 'bottom',
								'modes': [
									{type: 'html5'},
									{type: 'flash', src: '/includes/jwplayer/player.swf'},
									{type: 'download'}
								]
							  });
					</script>";

            return $str;
        }
        else
            return;
    }

    /**
     * Función copiada de http://php.net/manual/en/function.imagecopyresampled.php (nombre original "image_resize")
     * Toma una url de una imagen externa y la redimensiona al igual que lo hace GetImagenStaticFitoBoxCenter
     * @see Multimedia::GetImagenStaticFitoBoxCenter
     * @global type $app_path
     * @global type $var_url
     * @param type $src link de la imagen externa
     * @param type $width ancho requerido
     * @param type $height altura requerida
     * @param type $crop opcional para 'cropear' la imagen, por defecto no lo hace.
     * @return string link absoluto de la imagen
     */
    public function GetExternalImageResized($src, $picName, $width, $height, $crop = 0) {
        global $app_path, $var_url;
        if (!list($w, $h) = @getimagesize($src))
            return ''; //"Unsupported picture type!"

        $type = strtolower(substr(strrchr($src, "."), 1));
        //@todo verificar si la uri de destino es correcta. La idea es vaciar esta carpeta cada x tiempo
        $relativPath = '/img/tmpRedesSociales/' . $picName . '.' . $type;
        $dst = $app_path . $relativPath;
        if(file_exists($dst)) {
            return $relativPath;
        }

        if ($type == 'jpeg')
            $type = 'jpg';

        switch ($type) {
            case 'bmp': $img = imagecreatefromwbmp($src);
                break;
            case 'gif': $img = imagecreatefromgif($src);
                break;
            case 'jpg': $img = imagecreatefromjpeg($src);
                break;
            case 'png': $img = imagecreatefrompng($src);
                break;
            default :
                return ''; //Unsupported picture type!
        }

        // resize
        if ($crop > 0) {
            if ($w < $width or $h < $height)
                return ''; //Picture is too small!
            $ratio = max($width / $w, $height / $h);
            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        }
        else {
            /* Lógica original
             * if ($w < $width and $h < $height)
              return "Picture is too small!";
              $ratio = min($width / $w, $height / $h);
              echo "en crop w: " . $w . " h: " . $h;
              echo "<br>";
              echo "ratio: " . $ratio;
              echo "<br>";
              $width = $w * $ratio;
              $height = $h * $ratio;
              $x = 0; */

            if ($w > $h) {
                $finalH = (int) $height;
                $finalW = ceil($w * ($height / $h));

                if ($finalW < $width) {
                    $finalW = (int) $width;
                    $finalH = ceil($h * ($width / $w));
                }
            } else {
                $finalW = (int) $width;
                $finalH = ceil($h * ($width / $w));
            }
            $x = 0;
        }
        if($finalW <= 0 || $finalH <= 0) {
            return ''; // Invalid image dimensions
        }
        $new = imagecreatetruecolor($finalW, $finalH);

        // preserve transparency
        if ($type == "gif" or $type == "png") {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $finalW, $finalH, $w, $h);

        switch ($type) {
            case 'bmp': imagewbmp($new, $dst);
                break;
            case 'gif': imagegif($new, $dst);
                break;
            case 'jpg': imagejpeg($new, $dst);
                break;
            case 'png': imagepng($new, $dst);
                break;
            default:
                break;
        }
        return $relativPath;
    }

	public function AddWaterMark($image)
	{
		global $app_path;

		switch (TRUE) {
		   case stristr($image,'jpg'):
			  $photoImage = ImageCreateFromJpeg("$image");
			  break;
		   case stristr($image,'gif'):
			  $photoImage = ImageCreateFromGIF("$image");
			  break;
		   case stristr($image,'png'):
			  $photoImage = ImageCreateFromPNG("$image");
			  break;
		}

		ImageAlphaBlending($photoImage, true);

		// Añadimos aquí el fichero de marca de agua.
		$logoImage = ImageCreateFromPNG($app_path."advf/marca_agua_telam.png");
		$logoW = ImageSX($logoImage);
		$logoH = ImageSY($logoImage);

		$tamanox = imagesx($photoImage) - 100;

		ImageCopy($photoImage, $logoImage, $tamanox, 20, 0, 0, $logoW, $logoH);

		imagejpeg($photoImage, $image);

		ImageDestroy($photoImage);
		ImageDestroy($logoImage);

	}
	
	
	public function CargarVideoHdSd($videoid, $linksd, $linkhd, $width, $height, $imageprev)
	{
		$str = "<div id='videoplayer_" . $videoid . "'>&nbsp;</div>
				<script type='text/javascript'>
					jwplayer('videoplayer_" . $videoid . "').setup({
						sources: [{
						file: '".$linkhd."',
						label: '720p HD'	
					  },{
						file: '".$linksd."',
						label: '360p SD',
						'default': 'true'
					  }],
					  image: '".$imageprev."',
					  width: " . $width . ",
					  height: " . $height . "	
					});
				</script>";
			
		return $str;	
	}
	

}

?>