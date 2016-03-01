<?php
@set_time_limit(7200); //2 horas
include_once("../includes/DB_Conectar.php");

$tipo = !empty($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : "F";
//mensajes de error
if ($tipo == "F") {
    $msj_error[0] = "La imagen <u><i>%s</i></u> subi&oacute; con &eacute;xito.";
    $msj_error[1] = "Hubo un error desconocido y la imagen <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";
    $msj_error[2] = "El taman&ntilde;o de la imagen <u><i> %s </i></u> supera lo permitido.";
    $msj_error[3] = "El archivo <u><i> %s </i></u> que intenta subir no es una imagen jpg, ni gif ni png.";
    $msj_error[4] = "Hubo un error desconocido y la imagen <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";

    $ext_permitidas = array('jpg', 'png');

    $tam_max = "4 mb";
}

if ($tipo == "V") {
    $msj_error[0] = "El video <u><i>%s</i></u> subi&oacute; con &eacute;xito.";
    $msj_error[1] = "Hubo un error desconocido y el video <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";
    $msj_error[2] = "El taman&ntilde;o de el video <u><i> %s </i></u> supera lo permitido.";
    $msj_error[3] = "El archivo <u><i> %s </i></u> que intenta subir no es una imagen jpg, ni gif ni png.";
    $msj_error[4] = "Hubo un error desconocido y el video <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";

    $ext_permitidas = array('flv', 'mpg', 'avi', 'wmv', 'f4v', 'webm', 'mp4','ogg','ogv');

    $tam_max = "150 mb";
}
if ($tipo == "D") {
    $msj_error[0] = "El documento <u><i>%s</i></u> subi&oacute; con &eacute;xito.";
    $msj_error[1] = "Hubo un error desconocido y el documento <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";
    $msj_error[2] = "El taman&ntilde;o de el documento <u><i> %s </i></u> supera lo permitido.";
    $msj_error[3] = "El archivo <u><i> %s </i></u> que intenta subir no es un documento válido.";
    $msj_error[4] = "Hubo un error desconocido y el documento <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";

    $ext_permitidas = array('xls', 'pdf', 'ppt', 'pps', 'doc', 'zip', 'rar', 'odt', 'txt', 'rtf');

    $tam_max = "10 mb";
}

if ($tipo == "A") {
    $msj_error[0] = "El audio <u><i>%s</i></u> subi&oacute; con &eacute;xito.";
    $msj_error[1] = "Hubo un error desconocido y el audio <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";
    $msj_error[2] = "El taman&ntilde;o de el audio <u><i> %s </i></u> supera lo permitido.";
    $msj_error[3] = "El archivo <u><i> %s </i></u> que intenta subir no es un audio válido.";
    $msj_error[4] = "Hubo un error desconocido y el documento <u><i> %s </i></u> no pudo ser subida. Int&eacute;ntelo m&aacute;s tarde.";

    $ext_permitidas = array('wav', 'mp3');
    $tam_max = "80 mb";
}

if (!empty($_FILES)) {
    //las rtas son generales a todos. rtas[nombre] = mensaje
    $rtas = array();

    if ($tipo == "F") {
        for ($i = 0; $i < count($_FILES["file_foto"]["name"]); $i++) {
            if (!empty($_FILES["file_foto"]["name"][$i])) {


                $files["name"] = $_FILES["file_foto"]["name"][$i];
                $files["type"] = $_FILES["file_foto"]["type"][$i];
                $files["tmp_name"] = $_FILES["file_foto"]["tmp_name"][$i];
                $files["error"] = $_FILES["file_foto"]["error"][$i];
                $files["size"] = $_FILES["file_foto"]["size"][$i];

                $post["titulo_foto"] = $_POST["titulo_foto"][$i];
                $post["descripcion_foto"] = $_POST["descripcion_foto"][$i];
                $post["id_categoria_foto"] = $_POST["id_categoria_foto"][$i];
				$post["id_fotografo"] 		= $_POST["id_fotografo"][$i];


                //guardo una posicion antes $rtas[][name], por las dudas que subas 2 imagenes con el mismo nombre ej. images.jpg.
                $rtas[$i]["rta"] = UploadFoto($files, $post);
                $rtas[$i]["imagen"] = htmlentities($files["name"]);
            }
        }
        echo "<script>parent.toggleMuestraCargaContenidos();</script>;";
        echo "<script>parent.RefreshContent();</script>;";
        //header("Location: ".$_SERVER["PHP_SELF"]."?tipo=".$tipo."&rtas=".base64_encode(json_encode($rtas)));
        die();
    }

    if (!empty($_FILES["file_video"])) {
        if ($tipo == "V") {

            for ($i = 0; $i < count($_FILES["file_video"]["name"]); $i++) {
                if (!empty($_FILES["file_video"]["name"][$i]) || !empty($_POST["codigo_YouTube"][$i])) {

                    $files["name"] = $_FILES["file_video"]["name"][$i];
                    $files["type"] = $_FILES["file_video"]["type"][$i];
                    $files["tmp_name"] = $_FILES["file_video"]["tmp_name"][$i];
                    $files["error"] = $_FILES["file_video"]["error"][$i];
                    $files["size"] = $_FILES["file_video"]["size"][$i];

                    $post["titulo_video"] = $_POST["titulo_video"][$i];
                    $post["descripcion_video"] = $_POST["descripcion_video"][$i];
                    $post["id_categoria_video"] = $_POST["id_categoria_video"][$i];
                    $post["codigo_YouTube"] = $_POST["codigo_YouTube"][$i];
                    $post["tipo_video"] = $_POST["tipo_video"][$i];

                    if ($_FILES["file_preview_video"]["name"][$i] != "") {
                        $preview["name"] = $_FILES["file_preview_video"]["name"][$i];
                        $preview["type"] = $_FILES["file_preview_video"]["type"][$i];
                        $preview["tmp_name"] = $_FILES["file_preview_video"]["tmp_name"][$i];
                        $preview["error"] = $_FILES["file_preview_video"]["error"][$i];
                        $preview["size"] = $_FILES["file_preview_video"]["size"][$i];

                        $post["advLink_preview"] = UploadPreview($preview);

                        if ($post["advLink_preview"] === false) {
                            $post["advLink_preview"] = "images/previewVideoGenerico.jpg";
                        }
                    }

                    //guardo una posicion antes $rtas[][name], por las dudas que subas 2 videos con el mismo nombre ej. video.flv.
                    $rtas[$i]["rta"] = UploadVideo($files, $post);
                    $rtas[$i]["imagen"] = htmlentities($files["name"]);
                }
            }
            echo "<script>parent.toggleMuestraCargaContenidos();</script>;";
            echo "<script>parent.RefreshContent();</script>;";
            //header("Location: ".$_SERVER["PHP_SELF"]."?tipo=".$tipo."&rtas=".base64_encode(json_encode($rtas)));
            die();
        }
    }

    if ($tipo == "D") {
        for ($i = 0; $i < count($_FILES["file_doc"]["name"]); $i++) {
            if (!empty($_FILES["file_doc"]["name"][$i])) {


                $files["name"] = $_FILES["file_doc"]["name"][$i];
                $files["type"] = $_FILES["file_doc"]["type"][$i];
                $files["tmp_name"] = $_FILES["file_doc"]["tmp_name"][$i];
                $files["error"] = $_FILES["file_doc"]["error"][$i];
                $files["size"] = $_FILES["file_doc"]["size"][$i];

                $post["titulo_doc"] = $_POST["titulo_doc"][$i];
                $post["descripcion_doc"] = $_POST["descripcion_doc"][$i];
                $post["id_categoria_doc"] = $_POST["id_categoria_doc"][$i];


                //guardo una posicion antes $rtas[][name], por las dudas que subas 2 imagenes con el mismo nombre ej. images.jpg.
                $rtas[$i]["rta"] = UploadDocumento($files, $post);
                $rtas[$i]["imagen"] = htmlentities($files["name"]);
            }
        }
        echo "<script>parent.toggleMuestraCargaContenidos();</script>;";
        echo "<script>parent.RefreshContent();</script>;";
        //header("Location: ".$_SERVER["PHP_SELF"]."?tipo=".$tipo."&rtas=".base64_encode(json_encode($rtas)));
        die();
    }

    if ($tipo == "A") {
        for ($i = 0; $i < count($_FILES["file_audio"]["name"]); $i++) {
            if (!empty($_FILES["file_audio"]["name"][$i])) {

                $files["name"] = $_FILES["file_audio"]["name"][$i];
                $files["type"] = $_FILES["file_audio"]["type"][$i];
                $files["tmp_name"] = $_FILES["file_audio"]["tmp_name"][$i];
                $files["error"] = $_FILES["file_audio"]["error"][$i];
                $files["size"] = $_FILES["file_audio"]["size"][$i];

                $post["titulo_audio"] = $_POST["titulo_audio"][$i];
                $post["descripcion_audio"] = $_POST["descripcion_audio"][$i];
                $post["id_categoria_audio"] = $_POST["id_categoria_audio"][$i];


                //guardo una posicion antes $rtas[][name], por las dudas que subas 2 imagenes con el mismo nombre ej. images.jpg.
                $rtas[$i]["rta"] = UploadAudio($files, $post);
                $rtas[$i]["imagen"] = htmlentities($files["name"]);
            }
        }
        echo "<script>parent.toggleMuestraCargaContenidos();</script>;";
        echo "<script>parent.RefreshContent();</script>;";
        //header("Location: ".$_SERVER["PHP_SELF"]."?tipo=".$tipo."&rtas=".base64_encode(json_encode($rtas)));
        die();
    }
}//not empty files
?> 
<html>
    <head>
        <title><?= $TITULO_SITE ?></title>
        <link rel="stylesheet" href="css/stylo.css" type="text/css">
        <script type="text/javascript" src="../includes/lib/jQuery/jquery.js"></script>
        <style>
            .dispatcher_toggle{
                cursor:pointer;

            }
            .solapas{
                width:100%;
                height:23px;
                padding:0;
                margin:auto;
                text-align:left;
            }

            .solapas ul{
                margin:10px 0 0 10px;
                padding:0;
            }

            .solapas ul li{
                height:23px;
                width:100px;
                margin-right:5px;
                background-color:#333;
                font-family:Arial, Helvetica, sans-serif;
                font-size:14;
                font-weight:bold;
                color:#FFF;
                float:left;
                list-style:none;
                text-align:center;
                vertical-align:middle;
                -webkit-border-top-left-radius: 7px;
                -moz-border-top-left-radius: 7px;
                border-top-left-radius: 7px;
                -webkit-border-top-right-radius: 7px;
                -moz-border-top-right-radius: 7px;
                border-top-right-radius: 7px;
                padding:3px 10px 0 10px;
                display:block;	
            }
            .solapas ul li a{
                font-family:Arial, Helvetica, sans-serif;
                font-size:14;
                font-weight:bold;
                color:#FFF;	
            }
            .solapas ul li.selected{
                background-color:#fff;
                color:#333;
            }

            .solapas ul li.selected a{
                color:#333;
            }

            .solapas ul li a span{
                font-weight:normal;
                color:#C00;
            }
            .titulo_row{
                font-family:Arial, Helvetica, sans-serif;
                font-size:14px;
                font-weight:bold;
                color:cc0000;
            }

            input[type=file] {
                max-width: 220px;
            }
            
            *+html .floatFix{display:inline-block;}
            *+html .floatFix{display:block;}
            * html .floatFix {height:1%}

            .body_layer{
                border: thin dotted;
                height: 400px;
                overflow: auto;
                width: 98%;
            }
            }
        </style>
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body_layer" scroll=no leftmargin="2" topmargin="2" marginwidth="0" marginheight="0">
        <DIV class="div_contenedor" id="outerDiv">

            <form id="frmUpload" name="frmFile" action="<?php echo $_SERVER["PHP_SELF"] ?>?tipo=<?php echo $tipo ?>" enctype="multipart/form-data" method="POST">
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
                    <tr>
                        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
                        <td width="480" align="center" bgcolor="#e5e5e5"></td>
                        <td width="94" align="center" bgcolor="#e5e5e5"></td>
                        <td width="196" align="center" bgcolor="#e5e5e5"></td>
                        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                        <td align="center" bgcolor="#ffffff" colspan="3">
                            <div class="solapas floatFix">
                                <ul>
                                    <li class="dispatcher_toggle <?php if ($tipo == "F") echo "selected" ?>" ><a href="<?php echo $_SERVER["PHP_SELF"] ?>?tipo=F"><span>1.</span> Fotos</a></li>
                                    <li class="dispatcher_toggle <?php if ($tipo == "V") echo "selected" ?>" ><a href="<?php echo $_SERVER["PHP_SELF"] ?>?tipo=V"><span>2.</span> Videos</a></li> 
                                    <li class="dispatcher_toggle <?php if ($tipo == "A") echo "selected" ?>" ><a href="<?php echo $_SERVER["PHP_SELF"] ?>?tipo=A"><span>3.</span> Audios</a></li>
                                    <li class="dispatcher_toggle <?php if ($tipo == "D") echo "selected" ?>" ><a href="<?php echo $_SERVER["PHP_SELF"] ?>?tipo=D"><span>4.</span> Docs</a></li>         
                                </ul>
                            </div>

                            <!-- RESPUESTAS -->
                            <table width="100%" cellspacing="0" cellpadding="3">
                                <?php
                                if (!empty($_GET["rtas"])) {
                                    $msjs = json_decode(base64_decode($_GET["rtas"]));
                                    foreach ($msjs as $i => $msj) {
                                        if ($msj->rta == 0) {
                                            ?>
                                            <tr class="tablaOscuro"> 
                                                <td colspan="6" align="center" valign="top">
                                                    <table width="60%" align="center" border=0>
                                                        <tr>
                                                            <td width="5%"><img src="images/exito.png" width="25" height="25" border="0"></td>
                                                            <td><span class="titulooferta" style="color: green"><b><? printf($msj_error[$msj->rta], $msj->imagen) ?></b></span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr class="tablaOscuro"> 
                                                <td colspan="6" align="center" valign="top">
                                                    <table width="60%" align="center" border=0>
                                                        <tr><td width="5%"><img src="images/error.png" width="25" height="25" border="0"></td>
                                                            <td><span class="titulooferta" style="color: red"><b><? printf($msj_error[$msj->rta], $msj->imagen) ?></b></span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>	  
                                </table>
                                <!-- /RESPUESTAS -->
                            <?php } ?>
                            <br/>
                            <!-- acá van las tablas según corrsponda -->
                            <?php
                            if ($tipo == "F") {
                                require_once '_tabla_fotos_elementos_up.php';
                            }
                            if ($tipo == "V") {
                                require_once '_tabla_videos_elementos_up_layer.php';
                            }
                            if ($tipo == "D") {
                                require_once '_tabla_docs_elementos_up.php';
                            }
                            if ($tipo == "A") {
                                require_once '_tabla_audios_elementos_up.php';
                            }
                            ?>			



                            <table style="display:block;" width="100%" cellspacing="0" cellpadding="3">
                                <tr>
                                    <td width="3%">&nbsp;</td>
                                    <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">&nbsp;</td>
                                    <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;">&nbsp;</td>
                                    <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;"></td>
                                    <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;" width="3%">Agregar L&iacute;nea &nbsp;</td>
                                    <td class="titulooferta" style="background-image:url(images/separador_h1.gif); background-repeat:repeat-x; background-position:bottom; padding:7px;" width="3%">
                                        <a href="javascript:void(0);" onClick="AgregarTr()"><img src="images/btn_mas.gif" alt="Agregar Linea" title="Agregar Linea" /></a></td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" valign="bottom" colspan="20" bgcolor="#e5e5e5" class="titulooferta" >
                            Extensiones permitidas: <?php echo implode(", ", $ext_permitidas) ?>
                            <br />Tama&ntilde;o m&aacute;ximo: <?php echo $tam_max; ?>
                        </td>

                    </tr>
                    <tr>
                        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
                    </tr>
                </table>
                <br />

                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
                    <tr>
                        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
                        <td width="480" align="center" bgcolor="#e5e5e5"></td>
                        <td width="94" align="center" bgcolor="#e5e5e5"></td>
                        <td width="196" align="center" bgcolor="#e5e5e5"></td>
                        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
                    </tr>
                    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                    <td align="center" bgcolor="#e5e5e5" colspan="3">
                    <!-- <img onclick='document.location="index.php?=menu=<?= $usr->_menu; ?>";' src="images/btn_volver.gif" border="0" style="cursor:pointer;">&nbsp;&nbsp;&nbsp; -->
                        <input name="subir" type="image" value="subir" src="images/btn_guardar.gif"></td>
                    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="center" bgcolor="#e5e5e5"></td>
                        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
                    </tr>
                </table>
            </form>
            <br />
        </div>

        <script type="text/javascript"> 
            function AgregarTr(){
                $("#tabla_upload").append($("#tr_clone").clone().removeAttr("id").show()); 	
            }

            function EliminarTr(el){
                $(el).parent("td").parent("tr").remove();
            }
            function Enviar(){
	
                $("#tr_clone").remove();
                $("#frmUpload").submit();
            }
        </script>
    </body>
</html>
