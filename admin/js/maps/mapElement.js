
function ModificarZoom(zoom) {
    $("#mapa_zoom").val(zoom);
}

function CargarTipoMapa(type) {
    $("#mapa_tipo").val(type);
}


function CargarIds(map, location) {
    $("#latitud").val(location.lat());
    $("#longitud").val(location.lng());
    console.log('zoom: ' + map.getZoom());
    console.log('tipo: ' + map.getMapTypeId());
    $("#mapa_zoom").val(map.getZoom());
    $("#mapa_tipo").val("google.maps.MapTypeId." + map.getMapTypeId());
}

function limpiarCoordenadasNota() {
    $("#latitud").val("");
    $("#longitud").val("");
    $("#mapa_zoom").val("");
    $("#mapa_tipo").val("");
}