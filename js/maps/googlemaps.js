/*
Tagger - Converts any text input box into an easy-to-edit multiple-tag interface.
*/

(function($){
    var trim = function(str)
    {
        return str.replace(/^\s+|\s+$/g, '');
    };
    
    var emptyFunction = function(){};
    
    var MapaTelam = function(element, options)
    {
        var obj = this;
        var map;
		var geocoder;
        var element = $(element);
        var InputBuscador = $('<input type="text"/>');
        var ResultadoBusqueda = $('<div/>');
		var gmarkers = [];
		var contadorMarker;
		var gicons = [];
		
		
        var defaults = {
			IdBuscador: null,
			MultipleMarkers: true,
			RemoveMarkerClick: false,
			lat: -34.651285198954135 ,
			long: -58.77685546875 ,
			zoom: 10 ,
			tipo: google.maps.MapTypeId.ROADMAP,
			beforeSelected:      emptyFunction,
			onChangeZoom:      emptyFunction,
			onChangeMapType:      emptyFunction
        };
        
        var config = $.extend(defaults, options || {});
		if (config['IdBuscador']!=null) 
		{	
			InputBuscador = $('#'+config['IdBuscador'].idBuscador);
			ResultadoBusqueda = $('#'+config['IdBuscador'].idBuscadorListado);
		}

		this.getConfig = function()
        {
            return config;
        };
		
        this.Inicializate = function()
        {
			var myLatlng = new google.maps.LatLng(obj.getConfig().lat,obj.getConfig().long);
			contadorMarker =0;
			var myOptions = {
			  zoom: obj.getConfig().zoom,
			  center: myLatlng,
			  mapTypeId: obj.getConfig().tipo
			}        
			if( map == undefined) {		
				 map = new google.maps.Map($(element)[0],myOptions);
			}
			geocoder = new google.maps.Geocoder();
			geocoder.firstItem = {};
			
			
		   google.maps.event.addListener(map, 'zoom_changed', function() {
				if (false === obj.getConfig().onChangeZoom(map.getZoom())) return;
		   });
			
		   google.maps.event.addListener(map, 'maptypeid_changed', function() {
				if (false === obj.getConfig().onChangeMapType(returnMapType(map.getMapTypeId()))) return;
		   });
			
			
			return true;
        };

		InputBuscador.keypress(function(event){timeoutHnd = setTimeout(obj.Geocode,500) });


        this.Geocode = function()
		{
			var query = InputBuscador.val();
			if(query && query.trim) query = query.trim();
			// trim space if browser supports
			
			if(query != geocoder.resultAddress && query.length > 1) { // no useless request
				clearTimeout(geocoder.waitingDelay);
				geocoder.waitingDelay = setTimeout(function(){
				geocoder.geocode({address: query}, geocodeResult);
			}, 300);
			}else{
				$(ResultadoBusqueda).html("");
				geocoder.resultAddress = "";
				geocoder.resultBounds = null;
			}
			  // callback function
			function geocodeResult(response, status) 
			{
				if (status == google.maps.GeocoderStatus.OK && response[0]) {
					geocoder.firstItem = response[0];
					$(ResultadoBusqueda).html("");
					var len = response.length;
					for(var i=0; i<len; i++){
						addListItem(response[i]);
					}
				} else if(status == google.maps.GeocoderStatus.ZERO_RESULTS) {
					ResultadoBusqueda.html("");
					geocoder.resultAddress = "";
					geocoder.resultBounds = null;
				} else {
					ResultadoBusqueda.html(status);
					geocoder.resultAddress = "";
					geocoder.resultBounds = null;
				}
			}
			
			function addListItem(resp)
			{
				var loc = resp || {};
				var row = document.createElement("li");
				row.innerHTML = loc.formatted_address;
				row.className = "list_item";
				row.onclick = function(){
					updateMap(loc);
				}
				$(ResultadoBusqueda).append(row);
			}
			
			function updateMap(respons){
				function doIt(respons){
					var val= new Array();
					console.log(respons.geometry.viewport);
					respons.geometry && respons.geometry.viewport && map.fitBounds(respons.geometry.viewport);
					var val= new Array();
					obj.AddMarker(respons.geometry.location.lat(),respons.geometry.location.lng());
					ResultadoBusqueda.html("");
				}
				setTimeout(function(){doIt(respons)},500);
			}
			
	
			
			
		}// Fin Geocode
		

        this.AcceptAddMarkerButton = function(button)
		{
			google.maps.event.addListener(map, button, function (e) { 
			
				obj.AddMarker(e.latLng.lat().toFixed(6),e.latLng.lng().toFixed(6));
				if (false === obj.getConfig().beforeSelected(obj, e.latLng)) return;
				
			});
		}


		this.AddMarker = function(lat,lng)
		{		
			var myLatLng = new google.maps.LatLng(lat,lng);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: 'Marcador '+ contadorMarker,
				indexVal: contadorMarker,
				zIndex: Math.round(myLatLng.lat()*-100000)<<5//,
			});
			
			map.setCenter(myLatLng);

			if (obj.getConfig().MultipleMarkers==false)
				obj.RemoveAllMarkers();
				
				
			if (obj.getConfig().RemoveMarkerClick==true)
			{
				google.maps.event.addListener(marker, 'click', function() {
					if (confirm("Desea eliminar el marker?"))
						  obj.RemoveMarker(marker);
				});
			}
			gmarkers.push(marker);	
			contadorMarker++;	
			if (false === obj.getConfig().beforeSelected(map, myLatLng)) return;
		}

		this.RemoveMarker = function(marker)
		{		
			marker.setMap(null);
			if (gmarkers.length>0)
			{
				for (var i=0;i<gmarkers.length;i++) {
					if (marker.indexVal==gmarkers[i].indexVal)
					{
						gmarkers[i].setMap(null);
						gmarkers.splice(i,1); 
					}
				}
			}
		}


		this.obtenerZoom = function()
		{		
			map.getZoom();
		}

		this.RemoveAllMarkers = function()
		{		
			if (gmarkers.length>0) {
				for (i=0;i<gmarkers.length;i++) {
					gmarkers[i].setMap(null);
				}
				gmarkers.length = 0;
			}
			gmarkers = [];
			contadorMarker=0;
		}
		
		
		this.getMarkers = function()
		{		
			return gmarkers;				
		}
		

		this.AddMarkerWithText = function(lat,lng, texto)
		{		
			var myLatLng = new google.maps.LatLng(lat,lng);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: 'Marcador '+ contadorMarker,
				indexVal: contadorMarker,
				zIndex: Math.round(myLatLng.lat()*-100000)<<5//,
			});
			

			if (obj.getConfig().MultipleMarkers==false)
				obj.RemoveAllMarkers();
				

			var infowindow = new google.maps.InfoWindow({content: texto});
			google.maps.event.addListener(marker, 'click', function() { infowindow.open(map,marker); }); 

			gmarkers.push(marker);	
			contadorMarker++;	
			if (false === obj.getConfig().beforeSelected(map, myLatLng)) return;
		}

		
		function returnMapType(type){
			switch(type)
			{
				case "hybrid":
					return "google.maps.MapTypeId.HYBRID";	
				case "satellite":
					return "google.maps.MapTypeId.SATELLITE";	
				case "roadmap":
					return "google.maps.MapTypeId.ROADMAP";	
								
			}
			return "google.maps.MapTypeId.ROADMAP";	
		}
		
    };
    
    $.fn.extend({
        mapaTelam: function(options)
        {
			return mapaTelam = new MapaTelam(this, options);
        }
    });
})(jQuery);

