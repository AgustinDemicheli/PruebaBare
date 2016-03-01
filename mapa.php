<?php 
include_once("includes/DB_Conectar.php");

$tipo = $_REQUEST["tipo"];

$marcas = getMarkers($tipo);

include "_doctype.php"; 
?>
	<body id="mapa">
		<?php include "_header.php"; ?>
		<div id="container">
			<div class="wrapper-breadcrumb">
				<div class="main-box">
					<ul class="breadcrumb">
						<li>
							Consejo de la Magistratura
						</li>
						<li>
							Mapa de Juzgados y Organismos
						</li>
					</ul>
				</div>
			</div>
			<div class="mapa-large-wrapper">
				<div class="main-box">
					<div class="row solapas clearfix">
						<a href="/mapa" <?=($tipo==""?"class='active'":"")?> />Todos</a>
						<a href="/mapa/juzgados" <?=($tipo=="juzgados"?"class='active'":"")?> />Juzgados</a>
						<a href="/mapa/poder-judicial" <?=($tipo=="poder"?"class='active'":"")?> />Poder judicial</a>
						<a href="/mapa/servicios-juridicos-gratuitos" <?=($tipo=="gratuitos"?"class='active'":"")?> />Servicios juridicos gratuitos</a>
					</div>
					<div class="mapa">
						<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
						<script type="text/javascript" src="/js/maps/googlemaps.js"></script>
						<div id="divGoogleMaps" style="display:block;position:relative; width: 100% !important; height:600px; "></div>
						<script language="javascript">
							var ObjMapa;
							$(document).ready(function() {
								ObjMapa = $("#divGoogleMaps").mapaTelam({
									'zoom': 12,
									'lat': -34.61,
									'long': -58.45,
									'tipo': google.maps.MapTypeId.ROADMAP,
									'MultipleMarkers': true
								}
								);
								ObjMapa.Inicializate();
								
								<? 
									$lat = $marcas[0]["latitud"];
									for($i=0; $i < count($marcas) ; $i++){

										$link = $marcas[$i]["link"].$marcas[$i]["id"]."/".htmlentities_dir($marcas[$i]["nombre"]);
										$texto .= "<a href='".$link."'>".$marcas[$i]["tipo"]." - ".$marcas[$i]["nombre"]."</a><br />";
								
										if( $lat <> $marcas[$i+1]["latitud"])
										{?>
											ObjMapa.AddMarkerWithText(<?=$marcas[$i]["latitud"]?>,<?=$marcas[$i]["longitud"]?>,"<?=$texto?>");
										<?
											$link = ""; $texto = "";
											$lat = $marcas[$i]["latitud"];
										}
								}?>		
							});
						</script>
					</div>
				</div>
			</div>
		</div>
<?php include "_footer.php"; ?>