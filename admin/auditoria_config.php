<?
	include_once("../includes/DB_Conectar.php");
	include_once("../includes/lib/auth.php");

	if(isset($_POST)&&(count($_POST)>0)) {
		$conn->execute("delete from admin_auditoria_config");
		$conn->execute("insert into admin_auditoria_config (crear, modificar, eliminar, activar, aprobar) values ('" . $_POST['crear'] . "', '" . $_POST['modificar'] . "', '" . $_POST['eliminar'] . "', '" . $_POST['activar'] . "', '" . $_POST['aprobar'] . "')");
	}

	$rs_color	= $conn->execute("select admin_auditoria_config.crear, admin_auditoria_config.modificar, admin_auditoria_config.eliminar, admin_auditoria_config.activar, admin_auditoria_config.aprobar from admin_auditoria_config limit 1");
?>
<html>
	<head>
		<title>Configuracion de Auditoria</title>
		<link rel="stylesheet" href="css/stylo.css" type="text/css">
	</head>
	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body">
<?
	<?include_once("_barra.php");?>
?>
	<script language="javascript">
		function pickColor(color) {
			for(index = 0; index < document.getElementsByName('colorFor').length; index++) {
				if(document.getElementsByName('colorFor')[index].checked) {
					colorElement	= document.getElementsByName('colorFor')[index].value;

					document.getElementById(colorElement).value				= color;
					document.getElementById(colorElement + '_preview').style.background	= color;
				}
			}
		}
	</script>
	<br>
	<form id="config" name="config" action="auditoria_config.php" method="post">
		<table class="tablaGrande" align="center" cellpadding="0" cellspacing="0" width="500">
			<tr class="Title">
				<td align="center" colspan="2">Configuracion de Auditoria</td>
			</tr>
			<tr>
				<td class="tituloOferta" style="padding-left: 120px"><br><input type="radio" name="colorFor" value="crear" checked>Creación<br></td>
				<td class="tituloOferta" style="padding-right: 100px"><br><input type="text" class="comun" style="width: 100px;" id="crear" name="crear" value="<?=$rs_color->field('crear');?>" readonly>&nbsp;<input id="crear_preview" name="crear_preview" type="text" class="comun" style="width: 16px; background: <?=$rs_color->field('crear');?>;" readonly><br></td>
			</tr>
			<tr>
				<td class="tituloOferta" style="padding-left: 120px"><br><input type="radio" name="colorFor" value="modificar">Modificación<br></td>
				<td class="tituloOferta" style="padding-right: 100px"><br><input type="text" class="comun" style="width: 100px;" id="modificar" name="modificar" value="<?=$rs_color->field('modificar');?>" readonly>&nbsp;<input id="modificar_preview" name="modificar_preview" type="text" class="comun" style="width: 16px; background: <?=$rs_color->field('modificar');?>;" readonly><br></td>
			</tr>
			<tr>
				<td class="tituloOferta" style="padding-left: 120px"><br><input type="radio" name="colorFor" value="eliminar">Eliminación<br></td>
				<td class="tituloOferta" style="padding-right: 100px"><br><input type="text" class="comun" style="width: 100px;" id="eliminar" name="eliminar" value="<?=$rs_color->field('eliminar');?>" readonly>&nbsp;<input id="eliminar_preview" name="eliminar_preview" type="text" class="comun" style="width: 16px; background: <?=$rs_color->field('eliminar');?>;" readonly><br></td>
			</tr>
			<tr>
				<td class="tituloOferta" style="padding-left: 120px"><br><input type="radio" name="colorFor" value="activar">Activación<br></td>
				<td class="tituloOferta" style="padding-right: 100px"><br><input type="text" class="comun" style="width: 100px;" id="activar" name="activar" value="<?=$rs_color->field('activar');?>" readonly>&nbsp;<input id="activar_preview" name="activar_preview" type="text" class="comun" style="width: 16px; background: <?=$rs_color->field('activar');?>;" readonly><br></td>
			</tr>
			<tr>
				<td class="tituloOferta" style="padding-left: 120px"><br><input type="radio" name="colorFor" value="aprobar">Aprobación<br></td>
				<td class="tituloOferta" style="padding-right: 100px"><br><input type="text" class="comun" style="width: 100px;" id="aprobar" name="aprobar" value="<?=$rs_color->field('aprobar');?>" readonly>&nbsp;<input id="aprobar_preview" name="aprobar_preview" type="text" class="comun" style="width: 16px; background: <?=$rs_color->field('aprobar');?>;" readonly><br></td>
			</tr>
			<tr>
				<td align="center" colspan="2"> 
					<br>
					<table border="1" cellspacing="1" cellpadding="0" style="margin-top: 6px; margin-bottom: 0px; background-color: #444444; cursor: pointer;">
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 0, 0);" onclick="pickColor('#330000')" title="#330000"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 51, 0);" onclick="pickColor('#333300')" title="#333300"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 102, 0);" onclick="pickColor('#336600')" title="#336600"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 153, 0);" onclick="pickColor('#339900')" title="#339900"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 204, 0);" onclick="pickColor('#33CC00')" title="#33CC00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 255, 0);" onclick="pickColor('#33FF00')" title="#33FF00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 255, 0);" onclick="pickColor('#66FF00')" title="#66FF00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 204, 0);" onclick="pickColor('#66CC00')" title="#66CC00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 153, 0);" onclick="pickColor('#669900')" title="#669900"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 102, 0);" onclick="pickColor('#666600')" title="#666600"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 51, 0);" onclick="pickColor('#663300')" title="#663300"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 0, 0);" onclick="pickColor('#660000')" title="#660000"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 0, 0);" onclick="pickColor('#FF0000')" title="#FF0000"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 51, 0);" onclick="pickColor('#FF3300')" title="#FF3300"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 102, 0);" onclick="pickColor('#FF6600')" title="#FF6600"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 153, 0);" onclick="pickColor('#FF9900')" title="#FF9900"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 204, 0);" onclick="pickColor('#FFCC00')" title="#FFCC00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 255, 0);" onclick="pickColor('#FFFF00')" title="#FFFF00"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 0, 51);" onclick="pickColor('#330033')" title="#330033"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 51, 51);" onclick="pickColor('#333333')" title="#333333"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 102, 51);" onclick="pickColor('#336633')" title="#336633"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 153, 51);" onclick="pickColor('#339933')" title="#339933"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 204, 51);" onclick="pickColor('#33CC33')" title="#33CC33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 255, 51);" onclick="pickColor('#33FF33')" title="#33FF33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 255, 51);" onclick="pickColor('#66FF33')" title="#66FF33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 204, 51);" onclick="pickColor('#66CC33')" title="#66CC33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 153, 51);" onclick="pickColor('#669933')" title="#669933"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 102, 51);" onclick="pickColor('#666633')" title="#666633"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 51, 51);" onclick="pickColor('#663333')" title="#663333"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 0, 51);" onclick="pickColor('#660033')" title="#660033"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 0, 51);" onclick="pickColor('#FF0033')" title="#FF0033"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 51, 51);" onclick="pickColor('#FF3333')" title="#FF3333"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 102, 51);" onclick="pickColor('#FF6633')" title="#FF6633"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 153, 51);" onclick="pickColor('#FF9933')" title="#FF9933"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 204, 51);" onclick="pickColor('#FFCC33')" title="#FFCC33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 255, 51);" onclick="pickColor('#FFFF33')" title="#FFFF33"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 0, 102);" onclick="pickColor('#330066')" title="#330066"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 51, 102);" onclick="pickColor('#333366')" title="#333366"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 102, 102);" onclick="pickColor('#336666')" title="#336666"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 153, 102);" onclick="pickColor('#339966')" title="#339966"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 204, 102);" onclick="pickColor('#33CC66')" title="#33CC66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 255, 102);" onclick="pickColor('#33FF66')" title="#33FF66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 255, 102);" onclick="pickColor('#66FF66')" title="#66FF66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 204, 102);" onclick="pickColor('#66CC66')" title="#66CC66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 153, 102);" onclick="pickColor('#669966')" title="#669966"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 102, 102);" onclick="pickColor('#666666')" title="#666666"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 51, 102);" onclick="pickColor('#663366')" title="#663366"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 0, 102);" onclick="pickColor('#660066')" title="#660066"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 0, 102);" onclick="pickColor('#FF0066')" title="#FF0066"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 51, 102);" onclick="pickColor('#FF3366')" title="#FF3366"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 102, 102);" onclick="pickColor('#FF6666')" title="#FF6666"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 153, 102);" onclick="pickColor('#FF9966')" title="#FF9966"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 204, 102);" onclick="pickColor('#FFCC66')" title="#FFCC66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 255, 102);" onclick="pickColor('#FFFF66')" title="#FFFF66"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 0, 153);" onclick="pickColor('#330099')" title="#330099"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 51, 153);" onclick="pickColor('#333399')" title="#333399"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 102, 153);" onclick="pickColor('#336699')" title="#336699"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 153, 153);" onclick="pickColor('#339999')" title="#339999"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 204, 153);" onclick="pickColor('#33CC99')" title="#33CC99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 255, 153);" onclick="pickColor('#33FF99')" title="#33FF99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 255, 153);" onclick="pickColor('#66FF99')" title="#66FF99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 204, 153);" onclick="pickColor('#66CC99')" title="#66CC99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 153, 153);" onclick="pickColor('#669999')" title="#669999"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 102, 153);" onclick="pickColor('#666699')" title="#666699"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 51, 153);" onclick="pickColor('#663399')" title="#663399"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 0, 153);" onclick="pickColor('#660099')" title="#660099"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 0, 153);" onclick="pickColor('#FF0099')" title="#FF0099"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 51, 153);" onclick="pickColor('#FF3399')" title="#FF3399"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 102, 153);" onclick="pickColor('#FF6699')" title="#FF6699"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 153, 153);" onclick="pickColor('#FF9999')" title="#FF9999"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 204, 153);" onclick="pickColor('#FFCC99')" title="#FFCC99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 255, 153);" onclick="pickColor('#FFFF99')" title="#FFFF99"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 0, 204);" onclick="pickColor('#3300CC')" title="#3300CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 51, 204);" onclick="pickColor('#3333CC')" title="#3333CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 102, 204);" onclick="pickColor('#3366CC')" title="#3366CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 153, 204);" onclick="pickColor('#3399CC')" title="#3399CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 204, 204);" onclick="pickColor('#33CCCC')" title="#33CCCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 255, 204);" onclick="pickColor('#33FFCC')" title="#33FFCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 255, 204);" onclick="pickColor('#66FFCC')" title="#66FFCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 204, 204);" onclick="pickColor('#66CCCC')" title="#66CCCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 153, 204);" onclick="pickColor('#6699CC')" title="#6699CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 102, 204);" onclick="pickColor('#6666CC')" title="#6666CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 51, 204);" onclick="pickColor('#6633CC')" title="#6633CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 0, 204);" onclick="pickColor('#6600CC')" title="#6600CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 0, 204);" onclick="pickColor('#FF00CC')" title="#FF00CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 51, 204);" onclick="pickColor('#FF33CC')" title="#FF33CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 102, 204);" onclick="pickColor('#FF66CC')" title="#FF66CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 153, 204);" onclick="pickColor('#FF99CC')" title="#FF99CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 204, 204);" onclick="pickColor('#FFCCCC')" title="#FFCCCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 255, 204);" onclick="pickColor('#FFFFCC')" title="#FFFFCC"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 0, 255);" onclick="pickColor('#3300FF')" title="#3300FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 51, 255);" onclick="pickColor('#3333FF')" title="#3333FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 102, 255);" onclick="pickColor('#3366FF')" title="#3366FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 153, 255);" onclick="pickColor('#3399FF')" title="#3399FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 204, 255);" onclick="pickColor('#33CCFF')" title="#33CCFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 255, 255);" onclick="pickColor('#33FFFF')" title="#33FFFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 255, 255);" onclick="pickColor('#66FFFF')" title="#66FFFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 204, 255);" onclick="pickColor('#66CCFF')" title="#66CCFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 153, 255);" onclick="pickColor('#6699FF')" title="#6699FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 102, 255);" onclick="pickColor('#6666FF')" title="#6666FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 51, 255);" onclick="pickColor('#6633FF')" title="#6633FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 0, 255);" onclick="pickColor('#6600FF')" title="#6600FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 0, 255);" onclick="pickColor('#FF00FF')" title="#FF00FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 51, 255);" onclick="pickColor('#FF33FF')" title="#FF33FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 102, 255);" onclick="pickColor('#FF66FF')" title="#FF66FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 153, 255);" onclick="pickColor('#FF99FF')" title="#FF99FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 204, 255);" onclick="pickColor('#FFCCFF')" title="#FFCCFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 255, 255);" onclick="pickColor('#FFFFFF')" title="#FFFFFF"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 0, 255);" onclick="pickColor('#0000FF')" title="#0000FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 51, 255);" onclick="pickColor('#0033FF')" title="#0033FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 102, 255);" onclick="pickColor('#0066FF')" title="#0066FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 153, 255);" onclick="pickColor('#0099FF')" title="#0099FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 204, 255);" onclick="pickColor('#00CCFF')" title="#00CCFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 255, 255);" onclick="pickColor('#00FFFF')" title="#00FFFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 255, 255);" onclick="pickColor('#99FFFF')" title="#99FFFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 204, 255);" onclick="pickColor('#99CCFF')" title="#99CCFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 153, 255);" onclick="pickColor('#9999FF')" title="#9999FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 102, 255);" onclick="pickColor('#9966FF')" title="#9966FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 51, 255);" onclick="pickColor('#9933FF')" title="#9933FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 0, 255);" onclick="pickColor('#9900FF')" title="#9900FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 0, 255);" onclick="pickColor('#CC00FF')" title="#CC00FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 51, 255);" onclick="pickColor('#CC33FF')" title="#CC33FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 102, 255);" onclick="pickColor('#CC66FF')" title="#CC66FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 153, 255);" onclick="pickColor('#CC99FF')" title="#CC99FF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 204, 255);" onclick="pickColor('#CCCCFF')" title="#CCCCFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 255, 255);" onclick="pickColor('#CCFFFF')" title="#CCFFFF"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 0, 204);" onclick="pickColor('#0000CC')" title="#0000CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 51, 204);" onclick="pickColor('#0033CC')" title="#0033CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 102, 204);" onclick="pickColor('#0066CC')" title="#0066CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 153, 204);" onclick="pickColor('#0099CC')" title="#0099CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 204, 204);" onclick="pickColor('#00CCCC')" title="#00CCCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 255, 204);" onclick="pickColor('#00FFCC')" title="#00FFCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 255, 204);" onclick="pickColor('#99FFCC')" title="#99FFCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 204, 204);" onclick="pickColor('#99CCCC')" title="#99CCCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 153, 204);" onclick="pickColor('#9999CC')" title="#9999CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 102, 204);" onclick="pickColor('#9966CC')" title="#9966CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 51, 204);" onclick="pickColor('#9933CC')" title="#9933CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 0, 204);" onclick="pickColor('#9900CC')" title="#9900CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 0, 204);" onclick="pickColor('#CC00CC')" title="#CC00CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 51, 204);" onclick="pickColor('#CC33CC')" title="#CC33CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 102, 204);" onclick="pickColor('#CC66CC')" title="#CC66CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 153, 204);" onclick="pickColor('#CC99CC')" title="#CC99CC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 204, 204);" onclick="pickColor('#CCCCCC')" title="#CCCCCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 255, 204);" onclick="pickColor('#CCFFCC')" title="#CCFFCC"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 0, 153);" onclick="pickColor('#000099')" title="#000099"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 51, 153);" onclick="pickColor('#003399')" title="#003399"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 102, 153);" onclick="pickColor('#006699')" title="#006699"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 153, 153);" onclick="pickColor('#009999')" title="#009999"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 204, 153);" onclick="pickColor('#00CC99')" title="#00CC99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 255, 153);" onclick="pickColor('#00FF99')" title="#00FF99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 255, 153);" onclick="pickColor('#99FF99')" title="#99FF99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 204, 153);" onclick="pickColor('#99CC99')" title="#99CC99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 153, 153);" onclick="pickColor('#999999')" title="#999999"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 102, 153);" onclick="pickColor('#996699')" title="#996699"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 51, 153);" onclick="pickColor('#993399')" title="#993399"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 0, 153);" onclick="pickColor('#990099')" title="#990099"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 0, 153);" onclick="pickColor('#CC0099')" title="#CC0099"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 51, 153);" onclick="pickColor('#CC3399')" title="#CC3399"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 102, 153);" onclick="pickColor('#CC6699')" title="#CC6699"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 153, 153);" onclick="pickColor('#CC9999')" title="#CC9999"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 204, 153);" onclick="pickColor('#CCCC99')" title="#CCCC99"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 255, 153);" onclick="pickColor('#CCFF99')" title="#CCFF99"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 0, 102);" onclick="pickColor('#000066')" title="#000066"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 51, 102);" onclick="pickColor('#003366')" title="#003366"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 102, 102);" onclick="pickColor('#006666')" title="#006666"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 153, 102);" onclick="pickColor('#009966')" title="#009966"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 204, 102);" onclick="pickColor('#00CC66')" title="#00CC66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 255, 102);" onclick="pickColor('#00FF66')" title="#00FF66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 255, 102);" onclick="pickColor('#99FF66')" title="#99FF66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 204, 102);" onclick="pickColor('#99CC66')" title="#99CC66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 153, 102);" onclick="pickColor('#999966')" title="#999966"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 102, 102);" onclick="pickColor('#996666')" title="#996666"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 51, 102);" onclick="pickColor('#993366')" title="#993366"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 0, 102);" onclick="pickColor('#990066')" title="#990066"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 0, 102);" onclick="pickColor('#CC0066')" title="#CC0066"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 51, 102);" onclick="pickColor('#CC3366')" title="#CC3366"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 102, 102);" onclick="pickColor('#CC6666')" title="#CC6666"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 153, 102);" onclick="pickColor('#CC9966')" title="#CC9966"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 204, 102);" onclick="pickColor('#CCCC66')" title="#CCCC66"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 255, 102);" onclick="pickColor('#CCFF66')" title="#CCFF66"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 0, 51);" onclick="pickColor('#000033')" title="#000033"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 51, 51);" onclick="pickColor('#003333')" title="#003333"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 102, 51);" onclick="pickColor('#006633')" title="#006633"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 153, 51);" onclick="pickColor('#009933')" title="#009933"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 204, 51);" onclick="pickColor('#00CC33')" title="#00CC33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 255, 51);" onclick="pickColor('#00FF33')" title="#00FF33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 255, 51);" onclick="pickColor('#99FF33')" title="#99FF33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 204, 51);" onclick="pickColor('#99CC33')" title="#99CC33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 153, 51);" onclick="pickColor('#999933')" title="#999933"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 102, 51);" onclick="pickColor('#996633')" title="#996633"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 51, 51);" onclick="pickColor('#993333')" title="#993333"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 0, 51);" onclick="pickColor('#990033')" title="#990033"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 0, 51);" onclick="pickColor('#CC0033')" title="#CC0033"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 51, 51);" onclick="pickColor('#CC3333')" title="#CC3333"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 102, 51);" onclick="pickColor('#CC6633')" title="#CC6633"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 153, 51);" onclick="pickColor('#CC9933')" title="#CC9933"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 204, 51);" onclick="pickColor('#CCCC33')" title="#CCCC33"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 255, 51);" onclick="pickColor('#CCFF33')" title="#CCFF33"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 0, 0);" onclick="pickColor('#000000')" title="#000000"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 51, 0);" onclick="pickColor('#003300')" title="#003300"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 102, 0);" onclick="pickColor('#006600')" title="#006600"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 153, 0);" onclick="pickColor('#009900')" title="#009900"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 204, 0);" onclick="pickColor('#00CC00')" title="#00CC00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 255, 0);" onclick="pickColor('#00FF00')" title="#00FF00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 255, 0);" onclick="pickColor('#99FF00')" title="#99FF00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 204, 0);" onclick="pickColor('#99CC00')" title="#99CC00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 153, 0);" onclick="pickColor('#999900')" title="#999900"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 102, 0);" onclick="pickColor('#996600')" title="#996600"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 51, 0);" onclick="pickColor('#993300')" title="#993300"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 0, 0);" onclick="pickColor('#990000')" title="#990000"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 0, 0);" onclick="pickColor('#CC0000')" title="#CC0000"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 51, 0);" onclick="pickColor('#CC3300')" title="#CC3300"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 102, 0);" onclick="pickColor('#CC6600')" title="#CC6600"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 153, 0);" onclick="pickColor('#CC9900')" title="#CC9900"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 204, 0);" onclick="pickColor('#CCCC00')" title="#CCCC00"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 255, 0);" onclick="pickColor('#CCFF00')" title="#CCFF00"></td>
						</tr>
						<tr>
							<td style="width: 16px; height: 12px; background-color: rgb(0, 0, 0);" onclick="pickColor('#000000')" title="#000000"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(17, 17, 17);" onclick="pickColor('#111111')" title="#111111"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(34, 34, 34);" onclick="pickColor('#222222')" title="#222222"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(51, 51, 51);" onclick="pickColor('#333333')" title="#333333"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(68, 68, 68);" onclick="pickColor('#444444')" title="#444444"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(85, 85, 85);" onclick="pickColor('#555555')" title="#555555"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(102, 102, 102);" onclick="pickColor('#666666')" title="#666666"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(119, 119, 119);" onclick="pickColor('#777777')" title="#777777"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(136, 136, 136);" onclick="pickColor('#888888')" title="#888888"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(153, 153, 153);" onclick="pickColor('#999999')" title="#999999"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(170, 170, 170);" onclick="pickColor('#AAAAAA')" title="#AAAAAA"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(187, 187, 187);" onclick="pickColor('#BBBBBB')" title="#BBBBBB"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(204, 204, 204);" onclick="pickColor('#CCCCCC')" title="#CCCCCC"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(221, 221, 221);" onclick="pickColor('#DDDDDD')" title="#DDDDDD"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(238, 238, 238);" onclick="pickColor('#EEEEEE')" title="#EEEEEE"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(255, 255, 255);" onclick="pickColor('#FFFFFF')" title="#FFFFFF"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(68, 68, 68);"></td>
							<td style="width: 16px; height: 12px; background-color: rgb(68, 68, 68);"></td>
						</tr>
					</table>
					<br>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input type="submit" class="boton" value="Guardar Configuracion"><br><br>
				</td>
			</tr>
		</table>
	</form>
</html>
