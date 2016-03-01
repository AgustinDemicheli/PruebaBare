<?php 
include_once("includes/DB_Conectar.php");

$id = intval($_GET["id"]);

$org = getOrganismoById($id);

if(count($org)==0)
{
	header("Location: /");
	die();
}

$padre=getOrganismoPadre($org[0]["id_padre"]);

include "_doctype.php"; 
?>
	<body id="mapa-buscador">
		<?php include "_header.php"; ?>
		<div id="container">
			<div class="wrapper-breadcrumb">
				<div class="main-box">
					<ul class="breadcrumb">
						<li>
							Consejo de la Magistratura
						</li>
						<li>
							Organismos
						</li>
					</ul>
				</div>
			</div>
			<div class="wrapper-search-mapa">
				<div class="main-box clearfix">
					<div class="ampliado-result clearfix">
						<div class="info-result">
							<span><?=$org[0]["tipo_organismo"]?></span>
							<? if(count($padre)>0){?>
							<span><a href="/organismo/<?=$padre[0]["id"]?>/<?=htmlentities_dir($padre[0]["nombre"])?>"><?=$padre[0]["nombre"]?></a></span>
							<?}?>
							<h3><?=$org[0]["nombre"]?></h3>
							
							Comuna: <?=$org[0]["comuna_numero"]?><br />
							<?=nl2br($org[0]["direccion"])?><br />
							<?=$org[0]["telefonos"]?><br />
							<?=$org[0]["horarios"]?><br />
							<?=$org[0]["mails"]?><br/>
							<?=$org[0]["web"]?><br/>
							<a href="javascript:window.history.go(-1);" class="back-button">Volver al listado</a>
						</div>
						<? if($org[0]["latitud"] <> ""){ ?>
						<div class="mapa">
							<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
							<script type="text/javascript" src="/js/maps/googlemaps.js"></script>
							<div id="divGoogleMaps" style="display:block;position:relative;width:100% !important;height:567px !important;"></div>
							<script language="javascript">
								var ObjMapa;
								$(document).ready(function() {
									ObjMapa = $("#divGoogleMaps").mapaTelam({
										'zoom': <?= $org[0]["mapa_zoom"] ?>,
										'lat': <?= $org[0]["latitud"] ?>,
										'long': <?= $org[0]["longitud"] ?>,
										'tipo': google.maps.MapTypeId.ROADMAP,
										'MultipleMarkers': false
									}
									);
									ObjMapa.Inicializate();
									ObjMapa.AddMarker(<?= $org[0]["latitud"] ?>,<?= $org[0]["longitud"] ?>);
								});
							</script>
						</div>
						<?}?>
					</div>
				</div>
			</div>
		</div>
<?php include "_footer.php"; ?>