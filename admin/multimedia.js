var filename = "includes/ajaxResponseMultimedia.php";

function BuscarCategoria(source,  target, parentElem){
	//borro lo que hay en el buscador para que busque por categorias
	$("#input_buscar").val('');
	var cat_id = $(source).val();
	if(target!=''){
		$.ajax({
			type:"POST",
			url: filename,
			data:"ajax=1&BuscarCategoria=1&parent=" + cat_id,
			success:function(html){
					$("#" + target).html(html);
			}
		});
	}
	if(cat_id == 0 && parentElem != ''){
		cat_id = $("#" + parentElem).val();
	}
	GetContenido(cat_id,1);
	
}

function GetContenido(cat_id, pag){
	//borro lo que hay en el buscador para que busque por categorias
	$("#input_buscar").val('');
	//var tipo_contenido = $("#div_thumbs").attr("data-tipo");
	var layout_url = "_layout_fotos.php?tipo=" + $("#div_thumbs").attr("data-tipo");
		layout_url += "&object=" + $("#div_thumbs").attr("data-target_object");
		layout_url += "&relation=" + $("#div_thumbs").attr("data-relation");
		layout_url +="&buscar=" + $("#input_buscar").val();
		
	$.ajax({
		type:"GET",
		url: layout_url,
		data:"cat_id=" + cat_id + "&p=" + pag,
		success:function(html){
			$("#div_contenedor_fotos").html(html);
		}
	});
}

function CambiarPagina(elem){

		var cat_id = $("#div_thumbs").attr("data-cat_id");
		var pag_actual = parseInt($(".ul_paginador").children("li.selected").attr("nro"));
		var nro_pag_sel = $(elem).children("li").attr("nro");
		var ul_parent = $(elem).parent(".ul_paginador");
		
		
		if(nro_pag_sel < pag_actual){
			pagina_ir = pag_actual - (pag_actual - nro_pag_sel);
		}
		if(nro_pag_sel > pag_actual){
			pagina_ir = pag_actual + (nro_pag_sel - pag_actual);
		}
		
	
		//- siguiente
		if($(elem).attr("data-id") == "pag_siguiente"){
			pagina_ir = pag_actual + 1;
			if(pagina_ir > parseInt(ul_parent.attr("data-fin"))){
				ul_parent.hide();
			}
		}
		
		// - anterior 
		if($(elem).attr("data-id") == "pag_anterior"){
			pagina_ir = pag_actual - 1;
		}
		
		if($.trim($("#input_buscar").val()) != ''){
			BuscarContenido(pagina_ir)
		}else{
			GetContenido(cat_id, pagina_ir);
		}
		
}	

function UtilizarGaleria(){
	//agarro los li's q estan en el ul de galeria ahi voy a tener en los atributos data- todo los datos q necesito de la foto
	var object = $("#div_thumbs").attr("data-target_object");
	var tipo = $("#div_thumbs").attr("data-tipo");
	$(".li_galeria").each(function(){
		var id = $(this).attr("data-advID");
		var url = $(this).attr("data-advLink");
		var advTexto = $(this).attr("data-advTexto");
		parent.insertMediaRelation(id, url , tipo, object, advTexto);
		parent.cerrarOpenMedia();
	});
	
}

function UtilizarContenido(elem){
	
		var id 		= $(elem).attr("data-advID");
		var media 	= $(elem).attr("data-advTipo");
		var url 	= $(elem).attr("data-advLink");
		
		var object = $("#div_thumbs").attr("data-target_object");
		parent.insertMedia(id,url,media,object);
		parent.cerrarOpenMedia();
}
function editarTituloGaleria(elem){
	var id 	= $(elem).attr("data-advID");
	var url = $(elem).attr("data-advLink");
	var tipo = $(elem).attr("data-advTipo");
	var catID = $(elem).attr("data-catID");
	var scr_img = "";
	switch(tipo){
		case "F":
		src_img = 'thumbs.php?id=' + id + '&w=82&h=55'
			break;
		case "D":
		src_img = 'images/iconoDocumento.png';
			break;
		case "A":
		src_img =  'images/iconoAudio.jpg';
			break;
		case "V":
		src_img =  'images/iconoVideo.png';
			break;
	}
	
    if(id === null) return false;
	   $.openDOMWindow({ 
	        height:220, 
	        width:700, 
	        positionType:'centered', 
	        windowSource:'iframe', 
	        windowPadding:0, 
	        loader:1, 
	        loaderImagePath:'animationProcessing.gif', 
	        loaderHeight:16, 
	        windowSourceURL: '_iframe_edit_titulo_galeria.php?id=' + id + '&catID=' + catID,
	        loaderWidth:17
	    });
	   
}

function AgregarContenidoGaleria(elem){
	
	var id 	= $(elem).attr("data-advID");
	var url = $(elem).attr("data-advLink");
	var tipo = $(elem).attr("data-advTipo");
	var advTexto = $(elem).attr("data-advTexto");
	var preview = $(elem).attr("data-preview");
	var scr_img = "";
	//alert(preview)
	switch(tipo){
		case "F":
		src_img = 'thumbs.php?id=' + id + '&w=82&h=55'
			break;
		case "D":
		src_img = 'images/iconoDocumento.png';
			break;
		case "A":
		src_img =  'images/iconoAudio.jpg';
			break;
		case "V":
		src_img =  preview;
			break;
	}
	
	//si ya esta no lo agrego
	if($("#li-galeria-"+ id).length == 0){
		var li = '<li class="li_galeria" id="li-galeria-' + id+ '" data-advID="'+ id +'" data-advLink="'+ url +'"  data-advTexto="">'
	        	li+= '<div class="container_foto floatFix">'
	                li+='<div class="foto"><img src="'+ src_img +'" width="82"/></div>'
	                li+='<div class="tools floatFix">'
	                    li+='<div class="icons">'
	                        li+='<ul>'
	                            li+='<li><a href="#"><img src="images/btn_lightbox_zoom.gif" /></a></li>'
	                            li+='<li><a href="javascript:void(0);" onclick="QuitarContenidoGaleria('+ id +')"><img src="images/btn_lightbox_eliminar.gif" alt="" /></a></li>'    
	                        li+='</ul>'
	                    li+='</div>'
	                li+='</div>'
	            li+='</div>'
	        li+='</li>';
		$("#ul_galeria_imagenes").append(li);
		$(".btn_galeria-" + id).toggle();
	}
}

function QuitarContenidoGaleria(id){
	$("#li-galeria-" + id).remove();
	$(".btn_galeria-" + id).toggle();
}
(function($){

	$.confirm = function(params){

		if($('#confirmOverlay').length){
			// A confirm is already shown on the page:
			return false;
		}

		var buttonHTML = '';
		$.each(params.buttons,function(name,obj){

			// Generating the markup for the buttons:

			buttonHTML += '<a href="#" class="button '+obj['class']+'">'+name+'<span></span></a>';

			if(!obj.action){
				obj.action = function(){};
			}
		});

		var markup = [
			'<div id="confirmOverlay">',
			'<div id="confirmBox">',
			'<h1>',params.title,'</h1>',
			'<p>',params.message,'</p>',
			'<div id="confirmButtons">',
			buttonHTML,
			'</div></div></div>'
		].join('');

		$(markup).hide().appendTo('body').fadeIn();

		var buttons = $('#confirmBox .button'),
			i = 0;

		$.each(params.buttons,function(name,obj){
			buttons.eq(i++).click(function(){

				// Calling the action attribute when a
				// click occurs, and hiding the confirm.

				obj.action();
				$.confirm.hide();
				return false;
			});
		});
	}

	$.confirm.hide = function(){
		$('#confirmOverlay').fadeOut(function(){
			$(this).remove();
		});
	}

})(jQuery);



function EliminarContenidoGaleria(id){
	//$("#li-galeria-" + id).remove();
	//$("#" + id).toggle("slow");
	$(document).ready(function(){

		$("#" + id).click(function(){
			var filename = "includes/ajaxRemovePhoto.php";
			var elem = $(this).closest("#" + id);

			$.confirm({
				'title'		: 'Confirmar eliminaci&oacute;n',
				'message'	: '&iquest;Est&aacute;s seguro de que deseas elminar esta im&aacute;gen? <br />',
				'buttons'	: {
					'S&iacute;'	: {
						'class'	: 'blue',
						'action': function(){
							$.ajax({
								type:"POST",
								url: filename,
								data:"advID=" + id,
								success:function(data){
										if(data == true) elem.toggle("slow");
								}
							});
							
						}
					},
					'No'	: {
						'class'	: 'gray',
						'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
					}
				}
			});

		});

	});
}

function RefreshContent(){
	var cat_id = 0;
	var pag = parseInt($('.paginado .ul_paginador li.selected').html());
	if($("#categoria_hija").val() == ""){
		cat_id == $("#categoria_padre").val();
	}else{
		cat_id == $("#categoria_hija").val();
	}
	GetContenido(cat_id, pag);
}
$(document).ready(function(){
	if($("#categoria_padre").val() > 0){
		BuscarCategoria('#categoria_padre','categoria_hija','')
	}
});

function BuscarContenido(pag){
	$.ajax({
		type:"GET",
		url: "_layout_fotos.php?",
		data:"buscar=" + $("#input_buscar").val() + "&p=" +pag + "&object=" + $("#div_thumbs").attr("data-target_object") + "&tipo=" + $("#div_thumbs").attr("data-tipo")+ "&relation=" + $("#div_thumbs").attr("data-relation") ,
		success:function(html){
			$("#div_contenedor_fotos").html(html);
		}
	});
}

function toggleMuestraCargaContenidos(){
	var cat_id = $("#div_thumbs").attr("data-cat_id");
	if(!$("#div_thumbs").is(":visible")){
		GetContenido(cat_id,0);
	}
	$('.toggle_muestra_upload').toggle();
	
}

