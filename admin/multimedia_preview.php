<?php

include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");

?>
<script type="text/javascript" src="<?=$var_url?>/includes/flvplayer/ufo.js"></script>
<script type="text/javascript" src="<?=$var_url?>/includes/mp3player/ufo.js"></script>
<link rel="stylesheet" href="css/stylo.css" type="text/css">
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="300" height="250" border="1" bordercolor="#666699" cellpadding="0" cellspacing="0" align="center">
	<tr height="220" class="tablaOscuro">
		<td align="center" class="tituloOFerta">
<?
		if(isset($_REQUEST['media']) && isset($_REQUEST['link'])) {
			if($_REQUEST['media'] == "JPG" || $_REQUEST['media'] == "BMP" || $_REQUEST['media'] == "GIF") {
				print("<img src=\"../" . $_REQUEST['link'] . "\" width=\"260\" height=\"200\">");
			}else if($_REQUEST['media'] == "FLV") {
?>
				<div id="VideoPreview"></div>

				<script language="javascript">
					var VP = { movie: "<?=$var_url;?>/includes/flvplayer/flvplayer.swf", width: "260", height: "200", majorversion: "7", build: "0", bgcolor: "#FFFFFF", flashvars: "file=../../<?=$_REQUEST['link'];?>&showdigits=true&showicons=true&volume=100" }
					UFO.create(VP, "VideoPreview");
				</script>
<?
			}else if($_REQUEST['media'] == "WAV" || $_REQUEST['media'] == "MP3") {
?>
				<div id="AudioPreview"></div>

				<script language="javascript">
					var AP = { movie: "<?=$var_url;?>/includes/mp3player/mp3player.swf", width: "260", height: "20", majorversion: "7", build: "0", bgcolor: "#FFFFFF", flashvars: "file=../<?=$_REQUEST['link'];?>&showdigits=true&showicons=true&volume=100" }
					UFO.create(AP, "AudioPreview");
				</script>
<?
			}else{
				print("No hay preview disponible");
			}
		}
?>
		</td>
	</tr>
	<tr height="30" class="tablaOscuro">
		<td align="center"colspan="3"><input type="button" class="boton" value="Cerrar preview" onclick="window.parent.restoreFormUpLoad();"></td>
	</tr>
</table>
</body>