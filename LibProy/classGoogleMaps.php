<?php
    
    class mapaGoogle{


        private $modo = "list"; // se modifica desde el __construct. puede ser "edit" o "list"

        private $marcadores = array();

        private $divNombreAsociado = "map_canvas";
        private $divIdBuscador = "direccionABuscar";

        private $latInicial     = 0;
        private $lngInicial     = 0;
        private $zoomInicial    = 4;
        private $disableDoubleClickZoom = "false";
        private $tipoMapa       = "ROADMAP";    /*  ROADMAP
                                                HYBRID
                                                SATELLITE
                                                TERRAIN    */
        private $buscador           = false;
        private $ItemInicial        = "";
        private $controlTipoMapa    =   array( "posicion"=>"DEFAULT",   "style"=>"DEFAULT");
        private $controlNavegacion  =   array( "posicion"=>"DEFAULT",   "style"=>"DEFAULT");
        private $controlEscala      =   array( "posicion"=>"no"                      );  /*  posicines
                                                                                            TOP
                                                                                            TOP_LEFT
                                                                                            TOP_RIGHT
                                                                                            BOTTOM
                                                                                            BOTTOM_LEFT
                                                                                            BOTTOM_RIGHT
                                                                                            LEFT
                                                                                            RIGHT
                                                                                            DEFAULT
                                                                                        style de $controlTipoMapa
                                                                                            HORIZONTAL_BAR
                                                                                            DROPDOWN_MENU
                                                                                            DEFAULT
                                                                                        style de $controlNavegacion
                                                                                            SMALL
                                                                                            ZOOM_PAN
                                                                                            ANDROID
                                                                                            DEFAULT            */


            // Constructor
        public function __construct($modo="list"){
            $this->modo = $modo;
            if($modo == "edit"){
                $this->setBuscador();
            }
        }



        /**
        *    quita espacios inrrelevantes de un texto html; y los reemplaza por $reemplazar
        *    $cadena = "<h1>hola</h1>       <p>que tal?</p>               hola";
        *    devuelve =  "<h1>hola</h1><p>que tal?</p>               hola"
        */
        private function sacarEspaciosEntreTags($cadena,$reemplazar=""){
            $cadena = trim($cadena);
            return  preg_replace('/>\s+</m', ">".$reemplazar."<", $cadena);
        }



        // Funciones para los marcadores
        public function infoVentana($html){
            $cant = count($this->marcadores)-1;
            $this->marcadores[$cant]["content"] = $html;
        }

        public function addmarker($lat,$lng,$titulo,$draggable=false, $icono="",$id=""){

            array_push($this->marcadores,array( "titulo"=>$titulo,
                                                "lat"=>$lat,
                                                "lng"=>$lng,
                                                "draggable"=>$draggable,
                                                "icono"=>$icono,
                                                "id"=>($id!=""?$id:count($this->marcadores))));
        }
        // Fin Funciones para los marcadores

    	public function setItemInitial($value){
            return $this->ItemInicial = $value;
        }
        
        public function setLatInicial($lat){
            return $this->latInicial = $lat;
        }

        public function getLatInicial(){
            return $this->latInicial;
        }

        public function setLngInicial($lng){
            return $this->lngInicial = $lng;
        }

        public function getLngtInicial(){
            return $this->lngInicial;
        }

        public function setPuntoInicial($lat,$lng){
            $this->setLatInicial($lat);
            $this->setLngInicial($lng);
        }

        public function setZoomInicial($zoom){
            return $this->zoomInicial = $zoom;
        }

        public function getZoomInicial(){
            return $this->zoomInicial;
        }

        public function setdisableDoubleClickZoom(){
            return $this->disableDoubleClickZoom = "true";
        }

        public function getdisableDoubleClickZoom(){
            return $this->disableDoubleClickZoom;
        }

        /**
         * 
         * @param STRING $pos
         * @param STRING $tipo
         */
        public function setControlTipoMapa($pos="no", $tipo="DEFAULT"){
			$this->controlTipoMapa["style"]=$tipo;
            $this->controlTipoMapa["posicion"]=$pos;
        }
    	public function setTipoMapa($tipo){
			
    		switch ($tipo){
				case "G_HYBRID_MAP": 	$this->tipoMapa="TERRAIN";
					break;
				case "G_SATELLITE_MAP":	$this->tipoMapa="SATELLITE";
					break;
				case "G_NORMAL_MAP":	$this->tipoMapa="ROADMAP";
					break;
			}
    	   
        }
        
        public function setControlNavegacion($pos="no", $tipo="DEFAULT"){
            $this->controlNavegacion["style"]=$tipo;
            $this->controlNavegacion["posicion"]=$pos;
        }
        public function setControlEscala($pos="no"){
            $this->controlEscala["posicion"]=$pos;
        }

        public function setBuscador($x=true){
            $this->buscador = $x;
            $this->disableDoubleClickZoom = "true";
        }






        public function printEncambezados($ajax = false){
            
        	if (!$ajax){ 
        		$salida_jx ="<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=true&language=en\"></script>";
        	}
        	
            $salida = $salida_jx."
<script type=\"text/javascript\">
window.onload = function(){inicializarGoogleMaps();}
var marcadores = new Array();
var map;
var infoWindow = new google.maps.InfoWindow;
";

            if($this->buscador) $salida .= "var geocoder; var markerBuscado;\n\n";


            if($this->buscador){
                $salida .= "function crearBuscador(controlDiv, map) {
                                controlDiv.style.padding = '5px';
                                var controlUI = document.createElement('DIV');
                                controlUI.style.backgroundColor = 'white';
                                controlUI.style.borderStyle = 'solid';
                                controlUI.style.borderWidth = '2px';
                                controlUI.style.cursor = 'pointer';
                                controlUI.style.textAlign = 'center';
                                controlUI.title = 'Busque un lugar';
                                controlDiv.appendChild(controlUI);
                                var controlText = document.createElement('DIV');
                                controlText.style.fontFamily = 'Arial,sans-serif';
                                controlText.style.fontSize = '12px';
                                controlText.style.paddingLeft = '1px';
                                controlText.style.paddingRight = \"1px\";
                                controlText.innerHTML = '<input type=\"text\" id=\"".$this->divIdBuscador."\" value=\"Buscador\" onclick=\"\" /><input type=\"button\" value=\"Buscar\" onclick=\"codeAddress()\" />';
                                controlUI.appendChild(controlText);
                                }
                                \n";
            }

             $salida .= "
                function openWindow(pos){
                    infoWindow.close();
                    bindInfoWindow(marcadores[pos][0], map, infoWindow, marcadores[pos][1]);
                   	//alert (marker);
                    //map.setCenter(map.center);
                }
                
                function bindInfoWindow(marker, map, infoWindow, html) {
                   infoWindow.setContent(html);
                   infoWindow.open(map, marker);
                   
                }
				
                ";

            $salida .= "function inicializarGoogleMaps() {";
            
            if($this->buscador) {
            	$salida .= "geocoder = new google.maps.Geocoder();";
            }
            
            $salida .= "markerBuscado = new google.maps.Marker();
            var latlng = new google.maps.LatLng(".$this->latInicial.",".$this->lngInicial.");
            var myOptions = {
                     zoom: ".$this->zoomInicial.",
                     center: latlng,
                     disableDoubleClickZoom: ".$this->disableDoubleClickZoom.",
                     mapTypeId: google.maps.MapTypeId.".$this->tipoMapa.",
                     disableDefaultUI: true,

                     mapTypeControl: ".($this->controlTipoMapa["posicion"]=="no"?"false":"true").",
                     mapTypeControlOptions: {
                                style: google.maps.MapTypeControlStyle.".$this->controlTipoMapa["style"].",
                                position: google.maps.ControlPosition.".$this->controlTipoMapa["posicion"]."
                     },
                     navigationControl: ".($this->controlNavegacion["posicion"]=="no"?"false":"true").",
                     navigationControlOptions: {
                                style: google.maps.NavigationControlStyle.".$this->controlNavegacion["style"].",
                                position: google.maps.ControlPosition.".$this->controlNavegacion["posicion"]."
                     },
                     scaleControl:  ".($this->controlEscala["posicion"]=="no"?"false":"true").",
                     scaleControlOptions: {
                                position: google.maps.ControlPosition.".$this->controlEscala["posicion"]."
                     }";

            $salida .= "
            }
            map = new google.maps.Map(document.getElementById(\"".$this->divNombreAsociado."\"), myOptions);
            ";

            if($this->buscador){
                $salida .= "var buscadorDiv = document.createElement('DIV');
                            crearBuscador(buscadorDiv, map);
                            buscadorDiv.index = 1;
                            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(buscadorDiv);";
            }

            foreach($this->marcadores as $marca){
                $salida .= "var Latlng = new google.maps.LatLng(".$marca['lat'].",".$marca['lng'].");
                            var marker = new google.maps.Marker({
                                position: Latlng,
                                title: \"". $marca['titulo'] ."\",
                                draggable: ".($marca['draggable']==true?"true":"false").",
                                map: map
                                ".($marca['icono']!=""?",icon: '".$marca['icono']."'":"")."
                            });
                            ";

                    $salida .= " marcadores[".$marca['id']."] = new Array(marker,\"".$this->sacarEspaciosEntreTags($marca['content'],"\"+\"")."\");
                                    google.maps.event.addListener(marker, \"click\", function() {
                                        openWindow(".$marca['id'].");
                                    });
                    
                    			             
                    ";
            }
            
            if($this->modo == "edit"){
                $salida .= "
                            google.maps.event.addListener(map, 'dblclick', function(locationDblClick){
                                map.setCenter(locationDblClick.latLng);
                                markerBuscado.setOptions({
                                    map: map,
                                    position: locationDblClick.latLng,
                                    draggable: true
                                });
                            });
                            ";
            }

            $salida .= "


            google.maps.event.addListener(markerBuscado, 'position_changed', function(){
                    formLat = document.getElementById('mapaGoogleMarker[lat]');
                    formLng = document.getElementById('mapaGoogleMarker[lng]');
                    formLat.value = markerBuscado.getPosition().lat();
                    formLng.value = markerBuscado.getPosition().lng();
                });";
            
	        	if ($this->ItemInicial>0){
	            	$salida.="openWindow(".$this->ItemInicial.");";
	            }
	            
        	$salida.="}";

            if($this->buscador){
                $salida .= "function codeAddress() {
                                        var address = document.getElementById('".$this->divIdBuscador."').value;
                                        if (geocoder) {
                                            geocoder.geocode( { 'address': address}, function(results, status) {
                                                if (status == google.maps.GeocoderStatus.OK) {
                                                    map.setCenter(results[0].geometry.location);
                                                    markerBuscado.setOptions({
                                                        map: map,
                                                        position: results[0].geometry.location,
                                                        title: document.getElementById('".$this->divIdBuscador."').value,
                                                        draggable: true
                                                    });

                                                    formLat = document.getElementById('mapaGoogleMarker[lat]');
                                                    formLng = document.getElementById('mapaGoogleMarker[lng]');

                                                    formLat.value = results[0].geometry.location.lat();
                                                    formLng.value = results[0].geometry.location.lng();



                                                } else {
                                                    alert(\"Geocode no fue satisfactiorio por: \" + status);
                                                }
                                            });
                                        }
                            }";
            }




            $salida .= "</script>";


            echo $salida;

        }

        /**
        *  el parametro es el style CSS del div donde estara en mapa
        *
        * @param string $style
        */
        public function dibujarMapa($style="width:100px; height:100px;"){
            $salida = "<div id=\"".$this->divNombreAsociado."\" style=\"".$style."\"></div>
            ";

            if($this->buscador){
                $salida .= "
                    <input type=\"hidden\" name=\"mapaGoogleMarker[lat]\" id=\"mapaGoogleMarker[lat]\" value=\"latitud\" />
                    <input type=\"hidden\" name=\"mapaGoogleMarker[lng]\" id=\"mapaGoogleMarker[lng]\" value=\"longitud\" />
                ";
            }

            echo $salida;
        }
    }

?>