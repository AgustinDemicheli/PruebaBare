var contador_previews = 0;
function EditRelationVideos(elem){
	var valores = $("#" + elem + "_dst_select").val()[0].split('||');
	var desc  = valores[0];
	var cod   = valores[1];
	var medio = valores[2];
	console.log(medio);
	if(medio == "" || medio == "PROPIO") return false;
	
	$("#" + elem + "_src_input_namevideo").val(desc);
	$("#" + elem + "_src_input").val(cod);
	$("#" + elem + "_medio_video").val(medio);
	
	$("#btn_agregar_video").hide();
	$("#btn_guardar_video").show();
}

function GuardarEditVideo(elem){

	var desc = $("#" + elem + "_src_input_namevideo").val();
	var cod = $("#" + elem + "_src_input").val();
	var medio = $("#" + elem + "_medio_video").val();
	
	if(desc == ''){
		alert('Complete el campo descripcion');
		return false;
	}
	
	if(cod == ''){
		alert('Complete el campo código');
		return false;
	}
	
	if(medio == 'null'){
		alert('Seleccione el medio');
		return false;
	}
	
	$("#" + elem + "_dst_select option:selected").val(desc + '||' + cod + '||' + medio);
	$("#" + elem + "_dst_select option:selected").text(desc + ' (' + cod + ') ');
	
	$("#" + elem + "_src_input_namevideo").val('');
	$("#" + elem + "_src_input").val('');
	$("#" + elem + "_medio_video").val('null');
	
	$("#btn_agregar_video").show();
	$("#btn_guardar_video").hide();
}

function openMedia(id,media,object,relation)
{
	var url;
	$("body").css("overflow","auto");
	if(relation == 0) {
		url = 'multimedia2.php?menu=4&id=' + id + '&tipo=' + media + '&object=' + object;
	}else{
		url = 'multimedia2.php?menu=4&id=' + id + '&tipo=' + media + '&object=' + object + '&relation=1';
	}

	$.openDOMWindow({ 
		height:700, 
		width:900, 
		positionType:'centered', 
		windowSource:'iframe', 
		windowPadding:0, 
		loader:1, 
		loaderImagePath:'animationProcessing.gif', 
		loaderHeight:16, 
		windowSourceURL: url,
		loaderWidth:17
	});
}

function openDuplicarContenido(id)
{
	var url;
	$("body").css("overflow","auto");
	url = 'contenidos_duplicar.php?id=' + id  ;
	
	$.openDOMWindow({ 
		height:200, 
		width:400, 
		positionType:'centered', 
		windowSource:'iframe', 
		windowPadding:0, 
		loader:1, 
		loaderImagePath:'animationProcessing.gif', 
		loaderHeight:16, 
		windowSourceURL: url,
		loaderWidth:17
	});
}

function insertMedia(id,url,media,object)
{
	switch(media) {
		case 'A':
			eval('document.formedit.' + object + '_audio.value = url');
			eval('document.formedit.' + object + '.value = id');
		break;

		case 'D':
			eval('document.formedit.' + object + '_document.value = url');
			eval('document.formedit.' + object + '.value = id');
		break;

		case 'V':
			eval('document.formedit.' + object + '_video.value = url');
			eval('document.formedit.' + object + '.value = id');
		break;

		case 'F':
			eval('document.formedit.' + object + '_image.value = url');
			eval('document.formedit.' + object + '.value = id');
		break;
	}
}

function obtenerTipoObjeto(object) {
    if(object.match(/fotos/)) {
        return "FOTO";
    }
    if(object.match(/videos/)) {
        return "VIDEO";
    }
    
    if(object.match(/audios/)) {
        return "AUDIO";
    }
    
    if(object.match(/archivos/)) {
        return "ARCHIVO";
    }
}
function insertMediaRelation(id, text, media, object, advTexto){
	
	objectDst	= document.getElementById(object);
    var objetosConPreview = ["FOTO", "VIDEO", "AUDIO", "ARCHIVO"];
    var crearTablaPreview = $.inArray(obtenerTipoObjeto(object), objetosConPreview) >= 0 ? true : false;
	if(crearTablaPreview) {
		crearTablaPreviewsFotos(object);
	}
	crearTablaEpigrafesFotos(object);
	if(id != 0) {
		objectDst[objectDst.length] = new Option(text + " (" + id + ")", id);
		if(crearTablaPreview){
            AgregarPreview(id,object);
		}
		
		AgregarEpigrafes(id,object,advTexto);
		return true;
	}

	alert("No se pudo agregar el archivo multimedia a la lista");
}
/*
function SacarFoto(id_foto, elem){
	$("#" + elem + "_fotos_" + id_foto ).remove();
	$("#ep" + elem  + "_" + id_foto ).remove();
	removeSelected(elem ,id_foto);
}

function SacarAudio(id_foto, elem){
	$("#ep" + elem  + "_" + id_foto ).remove();
	removeSelected(elem ,id_foto);
}

function SacarVideo(id_video, elem) {
    $("#" + elem + "_videos_" + id_video ).remove();
    $("#ep" + elem  + "_" + id_video ).remove();
	removeSelected(elem ,id_video);
}

function SacarEpigrafe(elem){
	//$("#eparchivos_dst_select_" + valor ).remove();
	//removeSelected('archivos_dst_select', valor)
	
	$("#ep" + elem.id + "_" + elem.value ).remove();
	removeSelected(elem.id, elem.value)
}
*/
function SacarElemento(id_obj, elem) {
    $("#preview_img_" + elem + "_" + id_obj ).remove();
    $("#ep_" + elem  + "_" + id_obj ).remove();
	removeSelected(elem ,id_obj);
}


function EstaCreadoElTRDePreviews(elem){
	if($("#"+ elem + "_tr_previews").length > 0){
		return true;
	}
	return false;
}

function crearTablaPreviewsFotos(elem){
	if(!EstaCreadoElTRDePreviews(elem)){
		var tr = '<tr ><td colspan="3"><table><tr id="' + elem + '_tr_previews"></tr></table></td></tr>';
		$("#"+ elem + "_tabla").append(tr);
	}
}

function AgregarPreview(id_obj, elem){
var tipoObjeto = obtenerTipoObjeto(elem);
var imgSrc,
    tipo,
    nombreFuncionEliminar;
    
if(tipoObjeto == "FOTO") {
    imgSrc = 'thumbs.php?w=50&h=50&id=' + id_obj;
    tipo = 'fotos';
}
if(tipoObjeto == 'VIDEO') {
    imgSrc = 'images/iconoVideo.png';
    tipo = 'videos';
}

if(tipoObjeto == 'ARCHIVO') {
    imgSrc = 'images/iconoDocumento.png';
    tipo = 'archivos';
}

if(tipoObjeto == 'AUDIO') {
    imgSrc = 'images/iconoAudio.jpg';
}

var td = '';
	td+='<td style="padding-top:5px;">';
		td+='<div id="preview_img_'+ elem +'_'+ id_obj +'">';
			td+='<img src="' + imgSrc + '" width="50" height="50" style="border:1px solid #AEAEAE"  />';
			td+='<br />';
			td+='<span class="arial11">(' + id_obj + ')</span>';
			td+='<img src="images/eliminar.gif" border="0" style="cursor:pointer;padding-top:7px;"';
			td+=' onclick="SacarElemento('+ id_obj +', \'' + elem + '\');" ';
			td+=' valign="top" />&nbsp;&nbsp; ';
		td+=' </div>';
	td+='</td>';
	contador_previews++;
$("#"+ elem + "_tr_previews").append(td);	
}

function EstaCreadoElTRDeEpigrafes(elem){
	if($("#"+ elem + "_tr_epigrafes").length > 0){
		return true;
	}
	return false;
}

function crearTablaEpigrafesFotos(elem){
	if(!EstaCreadoElTRDeEpigrafes(elem)){
		var tr = '<tr ><td colspan="3" id="' + elem + '_tr_epigrafes"></td></tr>';
		$("#"+ elem + "_tabla").append(tr);
	}
}

function AgregarEpigrafes(id_foto, elem,advTexto){
	var div = '';



	div+='<div id="ep_' + elem + '_' + id_foto + '">'
		div+='<span class="arial11">Epigrafe ' + id_foto + ':</span>&nbsp;'
		div+='<input class="comun" type="text" value="'+  advTexto +'" name="ep_' + elem  +'_'+ id_foto +'">'
	div+='</div>';
	$("#" + elem  +"_tr_epigrafes").append(div);
}

function clearMedia(media, object)
{
	switch(media) {
		case 'A':
			eval('document.formedit.' + object + '_audio.value = ""');
			eval('document.formedit.' + object + '.value = ""'); 
		break;

		case 'D':
			eval('document.formedit.' + object + '_document.value = ""');
			eval('document.formedit.' + object + '.value = ""'); 
		break;

		case 'V':
			eval('document.formedit.' + object + '_video.value = ""');
			eval('document.formedit.' + object + '.value = ""'); 
		break;

		case 'F':
			eval('document.formedit.' + object + '_image.value = ""');
			eval('document.formedit.' + object + '.value = ""'); 
			$("#preview_image_" + object).remove();
		break;
	}
}

function addRelationFromInputEnlaces(inputSrc, selectDst ,inputSrcNameEnlace , inputSrcTargetEnlace) {
	var inputSrc	= document.getElementById(inputSrc);
	var selectDst	= document.getElementById(selectDst);
	var inputSrcName = document.getElementById(inputSrcNameEnlace);
	var inputSrcTarget = document.getElementById(inputSrcTargetEnlace);

	if(inputSrcName.value == ''){
		alert('Complete el campo descripción');
		return false;
	}
	if(inputSrc.value == ''){
		alert('Complete el campo URL');
		return false;
	}
	selectDst[selectDst.length] = new Option(inputSrcName.value + ' (' + inputSrc.value + ')',inputSrcName.value + '||' + inputSrc.value + '||' + inputSrcTarget.value);
	
	inputSrcName.value = '';
	inputSrc.value = '';
	inputSrcTarget.value = '_blank';
}

function addRelationFromInputVideo(inputSrc, selectDst ,inputSrcNameVideo, medio) {
	var inputSrc	= document.getElementById(inputSrc);
	var selectDst	= document.getElementById(selectDst);
	var inputSrcName = document.getElementById(inputSrcNameVideo);
	
	if(inputSrcName.value == ''){
		alert('Complete el campo descripcion');
		return false;
	}
	
	if(inputSrc.value == ''){
		alert('Complete el campo código');
		return false;
	}
	
	if($('#'+ medio).val() == 'null'){
		alert('Seleccione el medio');
		return false;
	}
	
	selectDst[selectDst.length] = new Option(inputSrcName.value + ' (' + inputSrc.value  + ') - ' + $('#'+ medio).val(),inputSrcName.value + '||' + inputSrc.value + '||' + $('#'+ medio).val());
	
	inputSrcName.value = '';
	inputSrc.value = '';

}

function addRelationFromInput(inputSrc, selectSrc, selectDst) {
	var inputSrc	= document.getElementById(inputSrc);
	var selectSrc	= document.getElementById(selectSrc);
	var selectDst	= document.getElementById(selectDst);

	
	for(index = 0; index < selectSrc.length; index++) {
		if(selectSrc[index].value == inputSrc.value && inputSrc.value != 0) {
			selectDst[selectDst.length] = new Option(selectSrc[index].text,selectSrc[index].value);

			return true;
		}
	}

	alert("El ID no pertenece a la lista de relaciones");
}

function addRelationFromSelect(selectSrc, selectDst) {
	var selectSrc	= document.getElementById(selectSrc);
	var selectDst	= document.getElementById(selectDst);

	if(selectSrc[selectSrc.selectedIndex].value != 0) {
		selectDst[selectDst.length] = new Option(selectSrc[selectSrc.selectedIndex].text,selectSrc[selectSrc.selectedIndex].value);

		return true;
	}

	alert("Debe seleccionar un contenido de la lista.");
}

function removeSelected(contenedor,valor)
{

	objeto = document.getElementById(contenedor);
	elementos = objeto.length;

	for(bor=0;bor<elementos;bor++){
		if (objeto.options[bor]){
			if (objeto.options[bor].value == valor){	
				objeto.options[bor]=null;
			}
		}
	}				
}

function selectRelationItems() {
	for(index = 0; index < document.all.length; index++) {
		regexp	= /^[A-z0-9]+(\_dst\_select)$/;

		if(regexp.test(document.all[index].id)) {
			for(subindex = 0; subindex < document.getElementById(document.all[index].id).length; subindex++) {
				document.getElementById(document.all[index].id)[subindex].selected = true;
			}
		}
	}
}

function EscalarElemento(elemento,modo){
	
	var o = new Array();
	var n = new Array();
	var obj = document.getElementById(elemento);
	var Op_selected=-1;
	var Op_total=0;
	
	Op_total = obj.options.length;
	
	if (Op_total > 0){

		for (var i=0; i< Op_total; i++){
			o[o.length] = new Option(obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected);

			if (obj.options[i].selected == true){
				
				Op_selected = i;
			}
		}
		
		if (Op_selected!=-1){
		
			if (modo == 'arriba'){
			obj.options[Op_selected-1].text = obj.options[Op_selected].text
			obj.options[Op_selected-1].value = obj.options[Op_selected].value
			obj.options[Op_selected-1].selected =true;
					
			obj.options[Op_selected].text = o[Op_selected-1].text;
			obj.options[Op_selected].value = o[Op_selected-1].value;
			obj.options[Op_selected].selected = false;
			
			}else{
			obj.options[Op_selected+1].text = obj.options[Op_selected].text
			obj.options[Op_selected+1].value = obj.options[Op_selected].value
			obj.options[Op_selected+1].selected =true;
					
			obj.options[Op_selected].text = o[Op_selected+1].text;
			obj.options[Op_selected].value = o[Op_selected+1].value;	
			obj.options[Op_selected].selected = false;	
				
			}
		
		}else{
			
			  obj.options[0].selected = true
		}
		
	}
}

function ValidarFormulario(){
	var error = '';
	if($("#id_categoria_raiz").val() == '0'){
		error += '-Seleccione una Categoria Raíz\n';
	}
	//if($("#id_categoria").val() == '0'){
	//	error += '-Seleccione una Categoria\n';
	//}
	if(error !=''){
		alert(error);
		return false;
	}
	return true;
}

function cargarCategorias(id_cat,def){
	if(id_cat == 0){
		return false;
	}
	$.ajax({
		   type: "POST",
		   url: "contenidos_edit.php",
		   data: "ajax=1&id_categoria_raiz=" + id_cat + "&id_seleccionado=" + def ,
		   success: function(rta){
		     $("#id_categoria").html(rta);
		     var id = $("body").attr("data-id");
		     GetTags(id,$('#id_categoria').val())
		   }
	});
}

function openSearch(tabla, destino){
	window.open('buscador.php?tabla='+tabla + '&destino='+ destino,'Buscador','width=750,height=550');

}

function openSearchTags(tabla, destino){
	var texto = $("#tags").val();
	window.open('buscador.php?for_tags=1&input_buscar='+ texto +'&tabla='+ tabla + '&destino='+ destino,'Buscador Contenidos Relacionados','width=750,height=450');

}

function EditRelationEnlaces(elem){
	var valores = $("#" + elem + "_dst_select").val()[0].split('||');
	var desc  = valores[0];
	var url   = valores[1];
	var tipo = valores[2];
	
	$("#" + elem + "_src_input_enlacetitulo").val(desc);
	$("#" + elem + "_src_input").val(url);
	$("#" + elem + "_src_input_enlacetarget").val(tipo);
	
	$("#btn_agregar_enlaces").hide();
	$("#btn_guardar_enlaces").show();
}

function GuardarEditEnlace(elem){

	var desc = $("#" + elem + "_src_input_enlacetitulo").val();
	var url = $("#" + elem + "_src_input").val();
	var tipo = $("#" + elem + "_src_input_enlacetarget").val();
	
	if(desc == ''){
		alert('Complete el campo titulo');
		return false;
	}
	
	if(url == ''){
		alert('Complete el campo url');
		return false;
	}
	

	
	$("#" + elem + "_dst_select option:selected").val(desc + '||' + url + '||' + tipo);
	$("#" + elem + "_dst_select option:selected").text(desc + ' (' + url + ') ');
	
	$("#" + elem + "_src_input_enlacetitulo").val('');
	$("#" + elem + "_src_input").val('');
	$("#" + elem + "_src_input_enlacetarget").val('_blank');
	
	$("#btn_agregar_enlaces").show();
	$("#btn_guardar_enlaces").hide();
}


function cerrarOpenMedia(){
	(function(){
		$.closeDOMWindow();
	}());
}
$(document).ready(function() {
	if(document.formedit.addEventListener) {
		document.formedit.addEventListener("submit",function() { selectRelationItems(); },false);
	}else{
		document.formedit.attachEvent("onsubmit",function() { selectRelationItems(); });
	}
	//switchear contenido extra
	$(".dispatcher_toggle").each(function(){
		$(this).click(function(){
			$(this).parent("tr").toggle();
			$("#" + $(this).attr("target")).toggle();
		});
	});
	
	$(".toggle_solapas").each(function(){
		$(this).click(function(){
			$(".toggle_solapas").removeClass("selected");
			$(this).addClass("selected");
			
			$(".tablas_solapas").hide();
			$("#" + $(this).attr("data-target")).toggle();
			
		});
	});
	
	$("#btn_agregar_img").click(function(){
		$("#iframe_subir_fotos").toggle();
	});
});
function GetTags(id, id_categoria){
	
	$.ajax({
		type: "GET",
		url: "ajax_tags_contenidos_edit.php",
		data: "id=" + id + "&id_categoria=" + id_categoria,
		success:function(rta){
			data = {items: rta.tags};
			var selected = rta.seleccionados;
			$("#tags_autosuggest").autoSuggest(data.items, 
				{
				selectedItemProp: "name",
				selectedValuesProp: "value",
				searchObjProps: "name",
				startText: "Ingrese un tag",
				//minChars: 2, 
				emptyText:"No se han encontrado resultados",
				neverSubmit:true,
				asHtmlID: "tags",
				preFill:selected
				});
		}
	})
}

