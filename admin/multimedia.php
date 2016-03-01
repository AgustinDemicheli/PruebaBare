<?php
include_once("../includes/DB_Conectar.php");
include_once("../includes/lib/auth.php");
/*
?menu=4&id=0&media=F&object=id_plano
?menu=4&id=&media=F&object=imagenes_dst_select&relation=true
*/
if(isset($_GET['media'])) { $extended_version = 1; $media = $_GET['media']; }else{ $extended_version = 0; $media = "F"; }
if($_GET['relation'] == true) { $relation_version = 1; } else { $relation_version = 0; }

if(count($_REQUEST)>0)
{
	// Agregar categorias
	if($_POST['create_cat']=='yes') {
		if(trim($_POST['create_cat'])==''){
			$error .= 'Ingrese el nombre de la categoria<br/>';
			$mostrar = 0;
		} else {

			$conn->execute("INSERT INTO advf_categorias(nombre, padre,activo,estado) VALUES('".$_POST['cat_nombre']."','".$_POST['cat_padre']."','S','A')");
			
			//echo "<script>window.location.href='galeria.php?tabID=1&posImg=".$_REQUEST["posImg"]."';</script>";
			header("Location:".$_SERVER['PHP_SELF']."?menu=".$_GET["menu"]."&id=".$_GET["id"]."&media=".$_GET["media"]."&object=".$_GET["object"]."&relation=".$_GET["relation"]."&saved=true");
			die();
		}
	}
	// -----------------

}

// AJAX - Load Content
if(isset($_POST['ajax']) && isset($_POST['type']) && isset($_REQUEST['category']) && isset($_POST['search'])) {
	
	$media_type	= $_POST['type'];
	$media_category	= $_REQUEST['category'];
	$media_search	= $_POST['search'];

	if($media_search != '') {
		$rs_content	= $conn->execute("select advf.advID, advf.advTitulo, advf.advLink 
										from advf 
										where advf.advTipo = '" . $media_type . "' and advf.advTitulo like '%" . str_replace(' ', '%', $media_search) . "%' and advf.advActivos ='S' order by advf.advID desc");
	}else{
		$rs_content	= $conn->execute("select advf.advID, advf.advTitulo, advf.advLink 
										from advf 
										where advf.advTipo = '" . $media_type . "' and advf.catID = '" . $media_category . "' and advf.advActivos ='S' 
										UNION 
										select advf.advID, advf.advTitulo, advf.advLink 
										from advf INNER JOIN advf_vinculante ON advf.advID = rel_advID and rel_catAdv='".$media_category."'
										where advf.advTipo = '" . $media_type . "' and advf.advActivos ='S'
										order by advID desc 
										");
	}

	// Generate XML
	$xmlMedia	= "<content>";

	while(!$rs_content->eof) {
		$media_id		= $rs_content->field('advID');

		$tit = str_replace("'","",$rs_content->field('advTitulo'));
		$tit = str_replace('"','',$tit);

		if(strlen($tit) < 20) { 
			$media_title	= utf8_encode($tit);
		}else{
			$media_title	= utf8_encode(substr($tit, 0, 18) . "...");
		}

		$media_link		= $rs_content->field('advLink');
		$media_extension 	= preg_replace("/[A-z0-9\/]+\./i", "", $media_link);

		$xmlMedia	.= "<media id=\"" . $media_id . "\" title=\"" . $media_title . "\" link=\"" . $media_link . "\" extension=\"" . strtoupper($media_extension) . "\" />";

		$rs_content->movenext();
	}

	$xmlMedia	.= "</content>";

	print($xmlMedia);

	exit(0);
}

// AJAX - Change category content
if(isset($_POST['ajax']) && isset($_POST['action']) && ($_POST['action'] == "CHG") && isset($_REQUEST['category']) && isset($_POST['content'])) {
	for($index = 0; $index < count($_POST['content']); $index++) {
		$contentIDs .= $_POST['content'][$index];

		// Inserto en tabla de auditoria
		$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $_POST['content'][$index] . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '4')");

		if(($index + 1) < count($_POST['content'])) { $contentIDs .= ","; }
	}

	$conn->execute("update advf set catID = " . $_REQUEST['category'] . " where advID in (" . $contentIDs . ")");

	exit(0);
}

// AJAX - Delete Content
if(isset($_POST['ajax']) && isset($_POST['action']) && ($_POST['action'] == "DEL") && isset($_POST['content'])) {
	for($index = 0; $index < count($_POST['content']); $index++) {
		$contentIDs .= $_POST['content'][$index];

		if(($index + 1) < count($_POST['content'])) { $contentIDs .= ","; }
	}

	$record_physical_delete	= $conn->execute("select advID, advLink from advf where advID in (" . $contentIDs . ")");

	while(!$record_physical_delete->eof) {
		@unlink("../" . $record_physical_delete->field('advLink'));

		// Insert en tabla de auditoria
		$conn->execute("insert into admin_auditoria (menu_id, usuario_id, contenido_id, ip, fecha, accion) values ('" . $usr->_menu . "', '" . $usr->_id . "', '" . $record_physical_delete->field('advID') . "', '" . $_SERVER['REMOTE_ADDR'] . "', now(), '8')");

		$record_physical_delete->movenext();
	}

	$conn->execute("delete from advf where advID in (" . $contentIDs . ")");

	exit(0);
}

// Build tree for <select>
function buildTree($parent_id,$selected_id = "") {
global $conn, $exclude_array, $depth_tree;

$rs_childnodes = $conn->execute("select id, nombre, padre from advf_categorias where activo = 'S' and estado = 'A' and padre = '" . $parent_id . "' order by nombre");

while (!$rs_childnodes->eof) {
	if($rs_childnodes->field('id') != $rs_childnodes->field('padre')) {
		
		if ($selected_id==$rs_childnodes->field('id')){
			$selected="SELECTED";
		}else {
			$selected="";
		}
		
		
		@$temp_tree	.= "<option value=\"" . $rs_childnodes->field('id') . "\" ".$selected.">" . str_repeat("--", ($depth_tree + 1)) . " " . $rs_childnodes->field('nombre') . "</option>"; 

		$depth_tree++;
		@$temp_tree	.= buildTree($rs_childnodes->field('id'),$selected_id);
		$depth_tree--;
		array_push($exclude_array, $rs_childnodes->field('id'));
	}

		$rs_childnodes->movenext();
	}

return $temp_tree;
}

?>
<title><?=$TITULO_SITE?> - Archivos Multimedia</title>
<link rel="stylesheet" href="css/stylo.css" type="text/css">
<script language='Javascript' src='../includes/lib/jQuery/jquery.js'></script>
<script type="text/javascript" src="../includes/lib/DOMWindow/jquery.DOMWindow.js"></script>
		
<script language="javascript">
// Global Variables
mediaXML		= null;
mediaType		= null;
mediaCategory		= null;
mediaContent		= null;
mediaPaginate		= null;

mediaOffset		= 0;

mediaID			= null;
mediaTitle		= null;
mediaLink		= null;
mediaExtension		= null;

mediaActionwall		= null;
mediaActionUpload	= null;
mediaActionChange	= null;
mediaActionPreview	= null;

mediaActionFrame	= null;
mediaActionpreviewFrame	= null;

function getMediaType() {
	for(index = 0; index < document.getElementsByName('type').length; index++) {
		if(document.getElementsByName('type')[index].checked == true) {
			return document.getElementsByName('type')[index].value;
		}
	}
}

function getCheckedParameters() {
	arrayParameters		= new Array();
	arrayParameters[0]	= new Array();
	arrayParameters[1]	= new Array();
	arrayParameters[2]	= new Array();
	arrayParameters[3]	= new Array();

	// Get checked count
	checkedCount	= 0;

	for(index = 0; index < document.getElementsByName('mediaItem').length; index++) { if(document.getElementsByName('mediaItem')[index].checked == true) { checkedCount++; } }

	arrayParameters[0].push(checkedCount);

	// Get checked category
	arrayParameters[1].push(mediaType);

	// Get checked values
	for(index = 0; index < document.getElementsByName('mediaItem').length; index++) { if(document.getElementsByName('mediaItem')[index].checked == true) { arrayParameters[2].push(document.getElementsByName('mediaItem')[index].value); }	}
	
	// Get checked values link
	for(index = 0; index < arrayParameters[2].length; index++) { arrayParameters[3].push(document.getElementById(arrayParameters[1][0] + arrayParameters[2][index]).value); }

	// Return array with parameters
	return arrayParameters;
}

function defineObjects() {
	mediaType		= getMediaType();
	mediaCategory		= document.getElementById('category');
	mediaContent		= document.getElementById('content');
	mediaPaginate		= document.getElementById('paginate');

	mediaActionwall		= document.getElementById('actionWall');
	mediaActionUpload	= document.getElementById('actionUpload');
	mediaActionChange	= document.getElementById('actionChange');
	mediaActionPreview	= document.getElementById('actionPreview');

	mediaActionFrame	= document.getElementById('actionFrame');
	mediaActionPreviewFrame	= document.getElementById('actionPreviewFrame');
	
		
	if (mediaCategory.value	> 0){
		loadContent('');
	}
	// Restore values
	mediaContent.innerHTML	= "<table><tr><td height=\"170\" align=\"center\" valign=\"center\" class=\"tituloOferta\">Seleccione una categoria</td></tr></table>";
	mediaPaginate.innerHTML	= "";
	
}

function restoreForm() {
	
	mediaActionFrame.src			= "";
	mediaActionPreviewFrame.src		= "";

	mediaCategory.disabled			= "";

	mediaActionwall.style.display		= "none";
	mediaActionUpload.style.display		= "none";
	mediaActionChange.style.display		= "none";
	mediaActionPreview.style.display	= "none";

	// After all, load new content
	loadContent('');
}

function restoreFormUpLoad() {
	
	//alert("laverga");
	mediaActionFrame.src			= "";
	mediaActionPreviewFrame.src		= "";
	mediaCategory.disabled			= "";
	mediaActionwall.style.display		= "none";
	mediaActionUpload.style.display		= "none";
	mediaActionChange.style.display		= "none";
	mediaActionPreview.style.display	= "none";
	// After all, load new content
	loadContent('');
}

function actionContent(action, args) {
	
	arrayParameters	= getCheckedParameters();

	switch(action) {
		case "NEW":
			mediaActionFrame.src		= "multimedia_upload.php?media=" + arrayParameters[1][0] + "&categoria=" + mediaCategory.value;
			mediaCategory.disabled		= "true";
			mediaActionwall.style.display	= "inline";
			mediaActionUpload.style.display	= "inline";
		break;
		
		case "MUL":
			mediaActionFrame.src		= "elementos_up.php?tipo=" + arrayParameters[1][0] + "&categoria=" + mediaCategory.value;
			mediaCategory.disabled		= "true";
			mediaActionwall.style.display	= "inline";
			mediaActionUpload.style.display	= "inline";
		break;

		case "CHG":
			// Check Parameters for CHG
			if(arrayParameters[0] == 0) { alert("Debe seleccionar un item del listado para ser utilizado"); return false; }

			
			changeString	= new String("");

			for(offset in arrayParameters[2]) {
				contentID	= arrayParameters[2][offset];
				//changeString	+= "&content[]=" + contentID;
				changeString	+= "," + contentID;
			}
			
			document.getElementById("actionFrameCat").src = "multimedia_cat.php?media=" + arrayParameters[1][0] + "&categoria="+mediaCategory.value + "&ids="+changeString+"&id=" + arrayParameters[2][0];
			

			mediaCategory.disabled		= "true";
			mediaActionwall.style.display	= "inline";
			mediaActionChange.style.display	= "inline";
		break;

		case "MOD":
			// Check Parameters for MOD
			if(arrayParameters[0] == 0) { alert("Debe seleccionar un item del listado para ser utilizado"); return false; }
			if(arrayParameters[0] >= 2) { alert("Debe seleccionar solo un item del listado para ser utilizado"); return false; }

			mediaActionFrame.src		= "multimedia_upload.php?media=" + arrayParameters[1][0] + "&id=" + arrayParameters[2][0]+ "&categoria="+mediaCategory.value;

			mediaCategory.disabled		= "true";
			mediaActionwall.style.display	= "inline";
			mediaActionUpload.style.display	= "inline";
		break;

		case "DEL":
			// Check Parameters for DEL
			if(arrayParameters[0] == 0) { alert("Debe seleccionar un item del listado para ser utilizado"); return false; }

			deleteString	= new String("");

			for(offset in arrayParameters[2]) {
				contentID	= arrayParameters[2][offset];
				deleteString	+= "&content[]=" + contentID;
			}

			if(confirm("¿ Esta seguro que desea eliminar el contenido seleccionado ?")) {
				mediaContent.innerHTML = "<table><tr><td height=\"170\" align=\"center\" valign=\"center\" class=\"tituloOferta\"><img src=\"images/loading.gif\"><br>Eliminando archivos ...</td></tr></table>";

				if(window.XMLHttpRequest) { DEL = new XMLHttpRequest(); } else if(window.ActiveXObject) { DEL = new ActiveXObject('Microsoft.XMLHTTP'); }

				DEL.onreadystatechange	= function() { if((DEL.readyState == 4) && (DEL.status == 200)) { loadContent(''); } };
				DEL.open("POST", "<?=$_SERVER['PHP_SELF'];?>", true);
				DEL.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				DEL.send("ajax=true&action=DEL" + deleteString);
			}
		break;

		case "USE":
			// Check Parameters for USE
			if(arrayParameters[0] == 0) { alert("Debe seleccionar un item del listado para ser utilizado"); return false; }
			if(arrayParameters[0] >= 2) { alert("Debe seleccionar solo un item del listado para ser utilizado"); return false; }

			parent.insertMedia(arrayParameters[2][0], arrayParameters[3][0], arrayParameters[1][0], '<?=$_REQUEST['object'];?>');

			parent.cerrarOpenMedia();
		break;

		case "REL":
			// Check Parameters for REL
			if(arrayParameters[0] == 0) { alert("Debe seleccionar un item del listado para ser utilizado"); return false; }
			//if(arrayParameters[0] >= 2) { alert("Debe seleccionar solo un item del listado para ser utilizado"); return false; }
			
			for(i = 0 ; i < arrayParameters[0] ; i++)
				parent.insertMediaRelation(arrayParameters[2][i], arrayParameters[3][i], arrayParameters[1][i], '<?=$_REQUEST['object'];?>');
				parent.cerrarOpenMedia();
				
		break;

		case "PRW":
			mediaActionPreviewFrame.src		= "multimedia_preview.php?media=" + args.extension + "&link=" + args.link;

			mediaCategory.disabled			= "true";
			mediaActionwall.style.display		= "inline";
			mediaActionPreview.style.display	= "inline";
		break;
	}
}

function loadContent(search) {
	
	
	/*if (mediaCategory == null){
	var mediaCategory = document.getElementById('category');
	var mediaContent = document.getElementById('content');
	}*/
	
	/*if (mediaContent == null){
	
	}*/
	//alert (mediaCategory.value);
	if(search) {
		mediaCategory.value	= 0;
		mediaContent.innerHTML	= "<table><tr><td height=\"170\" align=\"center\" valign=\"center\" class=\"tituloOferta\"><img src=\"images/loading.gif\"><br>Buscando \"" + search + "\"...</td></tr></table>";
	}else{
		
		if(mediaCategory.value == 0) { return defineObjects(); }else{ mediaContent.innerHTML = "<table><tr><td height=\"170\" align=\"center\" valign=\"center\" class=\"tituloOferta\"><img src=\"images/loading.gif\"><br>Cargando ...</td></tr></table>"; }
	}

	if(window.XMLHttpRequest) { AJAX = new XMLHttpRequest(); } else if(window.ActiveXObject) { AJAX = new ActiveXObject('Microsoft.XMLHTTP'); }
	
	AJAX.onreadystatechange	= createMXML;
	AJAX.open("POST", "<?=$_SERVER['PHP_SELF'];?>", true);
	AJAX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	AJAX.send("ajax=true&type=" + mediaType + "&category=" + mediaCategory.value + "&search=" + search);
}

function createMXML() {
	
	if((AJAX.readyState == 4) && (AJAX.status == 200)) {
		if(window.ActiveXObject) {
			mediaXML	= new ActiveXObject("Microsoft.XMLDOM");
			mediaXML.async	= false;

			mediaXML.loadXML(AJAX.responseText);

			scrollXML(0);
		}else{
			mediaXML	= new DOMParser();
			mediaXML	= mediaXML.parseFromString(AJAX.responseText, "text/xml");
			mediaXML.onLoad	= scrollXML(0);
		}
	}
}

function scrollXML(offset) {
	
	if(mediaXML) {
		if(mediaXML.childNodes.item(0).childNodes.length == 0) {
			mediaContent.innerHTML	= "<table><tr><td height=\"170\" align=\"center\" valign=\"center\" class=\"tituloOferta\">No hay resultados disponibles</td></tr></table>";
			mediaPaginate.innerHTML	= "";
		}else if(offset < mediaXML.childNodes.item(0).childNodes.length) {
			mediaHTML	= "<table width=\"100%\" height=\"170\" cellpadding=\"0\" cellspacing=\"2\" align=\"center\" valign=\"top\">";

			if(mediaType == 'F') {
				for(index = 0; index < 12; index++) {
					mediaOffset	= (offset + index);

					if((mediaOffset % 4) == 0) { mediaHTML += "<tr>"; }

					if(mediaOffset < mediaXML.childNodes.item(0).childNodes.length) {
						mediaID		= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(0).value;
						mediaTitle	= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(1).value;
						mediaLink	= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(2).value;
						mediaExtension	= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(3).value;

						mediaHTML += "<td width=\"165\"><table width=\"165\" align=\"center\" border=\"0\"><tr class=\"tablaOscuro\"><td width=\"20\" class=\"tituloOferta\" align=\"center\" valign=\"center\" bgcolor=\"#ffffff\"><input type=\"hidden\" id=\"" + mediaType + mediaID + "\" name=\"" + mediaType + mediaID + "\" value=\"" + mediaLink + "\"><input type=\"checkbox\" name=\"mediaItem\" value=\"" + mediaID + "\"></td>";
						mediaHTML += "<td class=\"tituloOferta\" align=\"center\" valign=\"center\" width=\"105\"><a href=\"javascript:actionContent('PRW', { 'extension' : '" + mediaExtension + "', 'link' : '" + mediaLink + "' });\"><img src=\"thumbs.php?id=" + mediaID + "&w=125\" alt=\"" + mediaTitle + "\" title=\"" + mediaTitle + "\" border=\"0\"></a></td>";
						mediaHTML += "<td width=\"20\"></td></tr></table></td>";
					}else{
						mediaHTML += "<td width=\"215\"><table width=\"145\" align=\"center\"><tr ><td width=\"20\"  align=\"center\" valign=\"center\">&nbsp;</td><td  align=\"center\" valign=\"center\" width=\"105\"></td><td width=\"20\">&nbsp;</td></tr></table></td>";
					}

					if((mediaOffset % 4) == 3) { mediaHTML += "</tr>"; }
				}
			}else{
				for(index = 0; index < 12; index++) {
					mediaOffset	= (offset + index);

					if((mediaOffset % 3) == 0) { mediaHTML += "<tr>"; }

					if(mediaOffset < mediaXML.childNodes.item(0).childNodes.length) {
						mediaID		= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(0).value;
						mediaTitle	= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(1).value;
						mediaLink	= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(2).value;
						mediaExtension	= mediaXML.childNodes.item(0).childNodes.item(mediaOffset).attributes.item(3).value;

						mediaHTML += "<td width=\"190\"><table width=\"190\" align=\"center\"><tr class=\"tablaOscuro\"><td width=\"20\" class=\"tituloOferta\" align=\"center\" valign=\"center\"><input type=\"hidden\" id=\"" + mediaType + mediaID + "\" name=\"" + mediaType + mediaID + "\" value=\"" + mediaLink + "\"><input type=\"checkbox\" name=\"mediaItem\" value=\"" + mediaID + "\"></td>";
						mediaHTML += "<td class=\"tituloOferta\" align=\"left\" valign=\"center\" width=\"150\">" + mediaID + " . " + mediaTitle + "</td>";
						mediaHTML += "<td width=\"20\"><a href=\"javascript:actionContent('PRW', { 'extension' : '" + mediaExtension + "', 'link' : '" + mediaLink + "' });\"><img src=\"images/icon_preview.gif\" border=\"0\"></a></td></tr></table></td>";
					}else{
						mediaHTML += "<td width=\"190\"><table width=\"190\" align=\"center\"><tr class=\"tablaOscuro\"><td width=\"20\" class=\"tituloOferta\" align=\"center\" valign=\"center\">&nbsp;</td><td class=\"tituloOferta\" align=\"left\" valign=\"center\" width=\"150\">&nbsp;</td><td width=\"20\">&nbsp;</td></tr></table></td>";
					}

					if((mediaOffset % 3) == 2) { mediaHTML += "</tr>"; }
				}
			}

			mediaHTML	+= "</table>";

			mediaContent.innerHTML	= mediaHTML;

			// Paginator
		 	if(offset == 0) { mediaPaginate.innerHTML = "<img src=\"images/prev.gif\" border=\"0\">&nbsp;&nbsp;"; }else{ mediaPaginate.innerHTML = "<a href=\"javascript:scrollXML(" + (offset - 12) + ");\"><img src=\"images/prev.gif\" border=\"0\"></a>&nbsp;&nbsp;"; }

			for(pageIndex = (Math.ceil(offset / 12) - 5); (pageIndex <= Math.ceil(offset / 12)); pageIndex++) {
				if(pageIndex > 0) {
					mediaPaginate.innerHTML += "<a href=\"javascript:scrollXML(" + ((pageIndex * 12) - 12) + ");\" class=\"tituloOferta\" style=\"text-decoration: none;\">" + pageIndex;
	
					if(pageIndex >= (Math.ceil(offset / 12) - 5) && (pageIndex >= 1)) { mediaPaginate.innerHTML += " • "; }
				}
			}

			for(pageIndex = (Math.ceil(offset / 12) + 1); (pageIndex <= (Math.ceil(offset / 12) + 5) && pageIndex <= Math.ceil(mediaXML.childNodes.item(0).childNodes.length / 12)); pageIndex++) {
				if(pageIndex > (Math.ceil(offset / 12) + 1)) { mediaPaginate.innerHTML += " • "; }

				mediaPaginate.innerHTML += "<a href=\"javascript:scrollXML(" + ((pageIndex * 12) - 12) + ");\" class=\"tituloOferta\" style=\"text-decoration: none;\">" + (pageIndex==(Math.ceil(offset / 12) + 1)? "<font color='orange'>"+ pageIndex +"</font>":pageIndex);
			}

		 	if((offset + 12) < mediaXML.childNodes.item(0).childNodes.length) { mediaPaginate.innerHTML += "&nbsp;&nbsp;<a href=\"javascript:scrollXML(" + (offset + 12) + ");\"><img src=\"images/next.gif\" border=\"0\"></a>"; }else{ mediaPaginate.innerHTML += "&nbsp;&nbsp;<img src=\"images/next.gif\" border=\"0\">"; }
		}
	}
}
</script>
<script language="Javascript">
function mostrar_cat()
{
	if(document.getElementById("categ_div").style.display == "none")
	{
		document.getElementById("categ_div").style.display = "block";
		document.getElementById("open_cat").innerHTML = "-";
	} else {
		document.getElementById("categ_div").style.display = "none";
		document.getElementById("open_cat").innerHTML = "+";
	}
}
function reload()
{
	document.location.href = "<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>&cargar=1&category=<?=$_REQUEST['category']?>";
}

</script>
<body class="body" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  onload="defineObjects();">
<? if(!$extended_version) { ?>
<?include_once("_barra.php");?>
<?} ?>
<!-- Divs for hidden actions -->
	<div id="actionWall" style="position: absolute; width: 100%; height: 100%; background-color: gray; filter:alpha(opacity=50); -moz-opacity: .50; opacity: .50; z-index: 10000; display: none;"></div>
	<div id="actionUpload" style="position: absolute; width: 100%; height: 100%; z-index: 20000; display: none;">
		<table align="center" valign="center" width="100%" height="100%">
			<tr>
				<td align="center">
					<iframe   id="actionFrame" name="actionFrame" frameborder="0"  width="97%" height="100%" src=""  >El navegador no soporta el uso de iframes</iframe>
				</td>
			</tr>
		</table>
	</div>
	<div id="actionChange" style="position: absolute; width: 100%; height: 100%; z-index: 20000; display: none;">
		<table align="center" valign="center" width="100%" height="90%">
			<tr>
				<td align="center">
					<iframe id="actionFrameCat" name="actionFrame" frameborder="0"  width="500" height="490" src="" >El navegador no soporta el uso de iframes</iframe>
				</td>
			</tr>
		</table>
	</div>
	<div id="actionPreview" style="position: absolute; width: 100%; height: 100%; z-index: 20000; display: none;">
		<table align="center" valign="center" width="100%" height="90%">
			<tr>
				<td align="center">
					<iframe id="actionPreviewFrame" name="actionPreviewFrame" frameborder="0" scrolling="no" width="300" height="250" src="" >El navegador no soporta el uso de iframes</iframe>
				</td>
			</tr>
		</table>
	</div>
<!--- End of divs for hidden actions -->

<div id="outerDiv" align="center" class="why">
<?=(!$extended_version)? "<br>":"";?>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="Title" style="padding-bottom:10px;"><?=$TITULO_SITE?> - Archivos Multimedia</td>
      </tr>
    </table>
	<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
      <tr>
        <td width="5" align="left" valign="top"><img src="images/corner_si.gif" width="5" height="5"></td>
        <td width="480" align="center" bgcolor="#e5e5e5"></td>
        <td width="94" align="center" bgcolor="#e5e5e5"></td>
        <td width="196" align="center" bgcolor="#e5e5e5"></td>
        <td width="5" align="right" valign="top"><img src="images/corner_sd.gif" width="5" height="5"></td>
      </tr>
      <?if (!$extended_version){?>
      <tr>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5" colspan="3" class="titulooferta">
			<strong>Tipo de archivos a utilizar:</strong>&nbsp;&nbsp;
			<label class="pointer">
			<input name="type" type="radio" value="A" <?=($media == "A")? "checked":"";?> onclick="defineObjects();">Audio</label>
			<label class="pointer"><input name="type" type="radio" value="D" <?=($media == "D")? "checked":"";?> onclick="defineObjects();">
			Documentos
			</label>
			<label class="pointer"><input name="type" type="radio" value="V" <?=($media == "V")? "checked":"";?> onclick="defineObjects();">Videos</label>
			<label class="pointer"><input name="type" type="radio" value="F" <?=($media == "F")? "checked":"";?> onclick="defineObjects();">Imagenes</label>
		</td>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
      <?}else {?>
      	<tr>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
        <td align="center" bgcolor="#e5e5e5" colspan="3" class="titulooferta">
        	<div style="display:none">
			<input name="type" id="type" type="radio" value="<?=$media?>" checked />
			</div>
		</td>
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
      </tr>
      <?}?>
	  <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
    </table>
	<br />
  <table width="780" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
	<td width="5" align="left" valign="top"><img src="images/corner_si.gif"></td>
	<td width="480" align="center" bgcolor="#e5e5e5"></td>
	<td width="94" align="center" bgcolor="#e5e5e5"></td>
	<td width="196" align="center" bgcolor="#e5e5e5"></td>
	<td width="5" align="right" valign="top"><img src="images/corner_sd.gif"></td>
  </tr>
  <tr class="tablaOscuro">
		<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		<td align="center" colspan="3">	
		<?if($_GET["saved"]==true){?>
			<table width="740" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(images/separador_h8.gif); background-repeat:repeat-x; background-position:bottom;">
			  <tr>
				<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
				<td align="center" bgcolor="#e5e5e5" class='arial12'><img src="images/status_A.gif" border="0">&nbsp;&nbsp;<font color="#C50000"><b> Se ha creado la categoria correctamente</b></font></td>
				<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
			  </tr>
			</table>
		<?}?>
		   <table cellpadding="0" cellspacing="0" border="0" width="100%">
			<?if ($_SESSION['sessTipo_id']==1){?> 
		   <tr class="titulooferta">
				<td>&nbsp;Crear Categoria 
					<a href="javascript:mostrar_cat();" class="titulooferta"><div class="texto" id="open_cat" style="display:inline;text-decoration:none;font-weight:bold;">+</div></a></td>
			</tr>
			<?}?>
			<tr bgcolor="#FFFFFF">
				<td>
					<div id="categ_div" style="position:inherit;display:none;">
					<table cellpadding="1" cellspacing="2" border="0" >
						<tr>
							<form method="post" action="<?=$_SERVER['PHP_SELF']."?menu=".$_GET["menu"]."&id=".$_GET["id"]."&media=".$_GET["media"]."&object=".$_GET["object"]."&relation=".$_GET["relation"]?>">
							<input type="hidden" name="posImg" value="<?=$_REQUEST["posImg"]?>">
							<input type="hidden" name="advID" value="<?=$_REQUEST['advID']?>">
							<input type="hidden" name="tabID" value="<?=$_REQUEST['tabID']?>">
							<input type="hidden" name="create_cat" value="yes" />
							<td class="titulooferta" width="100">&nbsp;</td>	
							<td class="textomediano">&nbsp;Nombre:&nbsp;<input type="text" name="cat_nombre" style="width:100px;" class="comun">
							</td>							
							<td class="textomediano">Padre:
								<select class="comun" name="cat_padre" style="background-color:#F5F5F5;">
								<option value="0">--- Seleccione ---</option>
								<?
									$exclude_status	= 0;
									$exclude_array	= array();

									$deapth_tree	= 1;

									if (isset($_GET) && $_GET['id']>0){
									$rs_categoria_selected = $conn->execute("select catID 
																			from advf 
																			where advID='".$_GET['id']."' 
																			");
									
									$categoria_selected = $rs_categoria_selected->field('catID');
									}
									
									$rs_categoria = $conn->execute("select id, nombre, padre from advf_categorias where activo = 'S' and padre=0 and estado = 'A' order by nombre");
									
									while(!$rs_categoria->eof) {
										$exclude_status		= 1;

										for($index = 0; $index < count($exclude_array); $index++) {
											if($exclude_array[$index] == $rs_categoria->field('id')) {
												$exclude_status	= 0;
												break;
											}
										}

										if($exclude_status == 1) {
											
											if ($categoria_selected == $rs_categoria->field('id')){

												$selected="selected";
											}else {
												$selected="";
											}
											
											print("<option value=\"" . $rs_categoria->field('id') . "\" ".$selected.">" . $rs_categoria->field('nombre') . "</option>");

											array_push($exclude_array, $rs_categoria->field('id'));

											print(buildTree($rs_categoria->field('id'),$categoria_selected));
										}

										$rs_categoria->movenext();
									}
								?>
								</select>
							</td>					
							<td><input type="submit" class="boton" value="Crear"></td>	
							</form>	
						</tr>					
					</table>
					</div>
				</td>				
			</tr>
			
			<tr>
				<td height="4"></td>
			</tr>												
			</table>
			<!--Categorias-->
		</td>
		<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
	</tr>
	<tr class="tablaOscuro" >
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		<td class="textooferta" style="padding:5px" valign="top" colspan="3">
		<?if ($_SESSION['sessTipo_id']==1){?>
			<span class="titulooferta">Categorías de multimedia</span>
	        <select id="category" name="category" class="comun" style="width: 200px;" onchange="loadContent('');">
			<option value="0">--- Seleccione ---</option>
<?
							$exclude_status	= 0;
							$exclude_array	= array();

							$deapth_tree	= 1;

							if (isset($_GET) && $_GET['id']>0){
							$rs_categoria_selected = $conn->execute("select catID 
																	from advf 
																	where advID='".$_GET['id']."' 
																	");
							
							$categoria_selected = $rs_categoria_selected->field('catID');
							}
							
							$rs_categoria = $conn->execute("select id, nombre, padre from advf_categorias where activo = 'S' and padre=0 and estado = 'A' order by nombre");
							
							while(!$rs_categoria->eof) {
								$exclude_status		= 1;

								for($index = 0; $index < count($exclude_array); $index++) {
									if($exclude_array[$index] == $rs_categoria->field('id')) {
										$exclude_status	= 0;
										break;
									}
								}

								if($exclude_status == 1) {
									
									if ($categoria_selected == $rs_categoria->field('id')){

										$selected="selected";
									}else {
										$selected="";
									}
									
									print("<option value=\"" . $rs_categoria->field('id') . "\" ".$selected.">" . $rs_categoria->field('nombre') . "</option>");

									array_push($exclude_array, $rs_categoria->field('id'));

									print(buildTree($rs_categoria->field('id'),$categoria_selected));
								}

								$rs_categoria->movenext();
							}
?>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?}else{
						$rs = $conn->Execute("select id from advf_categorias where nombre='_cat_soc_".$_SESSION['sessID']."' LIMIT 1");	
						?>
						<input type="hidden" id="category" name="category" value="<?=$rs->Field("id")?>" />
						<?}?>
						<?if ($_SESSION['sessTipo_id']==1){?>
						<span class="titulooferta">Búsqueda</span>&nbsp;
						<input id="searchString" name="searchString" type="textbox" class="comun" style="width: 178px;" maxlength="25">&nbsp;
						<img src="images/examinar.gif" align="center" style="cursor: pointer;" onclick="if(document.getElementById('searchString').value != '') { loadContent(document.getElementById('searchString').value); }else{ alert('Debe ingresar las palabras a buscar'); }">
						<?}?>
		</td>
		<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
	</tr>
	<tr class="tablaOscuro" >
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		<td align="center" valign="top" class="tituloOferta" style="padding-top:5px" bgcolor="#ffffff" colspan="3">
		<div id="content" name="content"><table align="center"><tr><td  align="center" valign="center" class="tituloOferta">Seleccione una categoria</td></tr></table></div>
		</td>
		<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
	</tr>
	<tr class="tablaOscuro" height="30" >
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		<td align="center" valign="center" class="tituloOferta" colspan="3">
			<div id="paginate" name="paginate"></div>
			<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		</td>
	</tr>
	<tr class="tablaOscuro" style="border-top: 1px #666699 solid;" height="30">
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		<td align="center" valign="center" class="tituloOferta" colspan="3">
			<?=($usr->checkUserRights(2) != 'disabled')? "<input type=\"image\" src=\"images/btn_uploadmultiple.gif\" value=\"Upload multiple\" onclick=\"actionContent('MUL');\">":"";?>	
			<!--<?=($usr->checkUserRights(2) != 'disabled')? "<input type=\"image\" src=\"images/btn_upload.gif\" value=\"Nuevo archivo\"  onclick=\"actionContent('NEW');\">":"";?>-->
			<?if ($_SESSION['sessTipo_id']==1){?> 
			<?=($usr->checkUserRights(4) != 'disabled')? "<input type=\"image\" src=\"images/btn_cambiarcategoria.gif\" value=\"Cambiar categoria\"  onclick=\"actionContent('CHG');\">":"";?>
			<?}?>
			<?=($usr->checkUserRights(4) != 'disabled')? "<input type=\"image\" src=\"images/btn_modificararchivo.gif\" value=\"Modificar archivo\"  onclick=\"actionContent('MOD');\">":"";?>
			<?=($usr->checkUserRights(8) != 'disabled')? "<input type=\"image\" src=\"images/btn_eliminar2.gif\" value=\"Eliminar archivo/s\" onclick=\"actionContent('DEL');\">":"";?>	
			
		</td>
		<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
	</tr>
<? if($extended_version) { ?>
	<? if($relation_version) { ?>
	<tr class="tablaOscuro" height="30">
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		<td align="center" valign="center" class="tituloOferta" colspan="3">
			<img src="images/btn_utilizararchivo.gif" style="cursor:pointer;" onclick="actionContent('REL');">
		</td>
		<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
	</tr>
	<? }else{ ?>
	<tr class="tablaOscuro" height="30">
	    <td align="center" bgcolor="#e5e5e5">&nbsp;</td>
		<td align="center" valign="center" class="tituloOferta" colspan="3">
			<img src="images/btn_utilizararchivo.gif" style="cursor:pointer;"  onclick="actionContent('USE');">
		</td>
		<td align="center" bgcolor="#e5e5e5">&nbsp;</td>
	</tr>
	<? } ?>
<? } ?>
   <tr>
        <td align="left" valign="bottom"><img src="images/corner_ii.gif" width="5" height="5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="center" bgcolor="#e5e5e5"></td>
        <td align="right" valign="bottom"><img src="images/corner_id.gif" width="5" height="5"></td>
      </tr>
</table>
</div>
<script type="text/javascript" language="javascript">
defineObjects();	
loadContent('');
</script>
</body>