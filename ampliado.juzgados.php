<?php
include_once("includes/DB_Conectar.php");

$id = intval($_GET["id"]);

$org = getJuzgadoById($id);

if(count($org)==0)
{
	header("Location: /");
	die();
}

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
							Juzgados
						</li>
					</ul>
				</div>
			</div>
			<div class="wrapper-search-mapa">
				<div class="main-box clearfix">
					<div class="ampliado-result clearfix">
						<div class="info-result">
							<span><?=$org[0]["fuero"]?></span>
							<span><?=$org[0]["tipo_juzgado"]?></span>

							<h3><?=$org[0]["numero_juzgado"]?></h3>

							<? if($org[0]["nombre_juez"]<>""){?>
							<p><strong><?=$org[0]["nombre_juez"]?></strong></p>
							<?=nl2br($org[0]["domicilio"])?><br />
							<?=$org[0]["telefonos"]?><br />
							<?=$org[0]["emails"]?><br/>
							<?}?>

							<? if($org[0]["nombre_juez2"]<>""){?>
							<p><strong><?=$org[0]["nombre_juez2"]?></strong></p>
							<?=nl2br($org[0]["domicilio2"])?><br />
							<?=$org[0]["telefonos2"]?><br />
							<?=$org[0]["emails2"]?><br/>
							<?}?>

							<? if($org[0]["nombre_juez3"]<>""){?>
							<p><strong><?=$org[0]["nombre_juez3"]?></strong></p>
							<?=nl2br($org[0]["domicilio3"])?><br />
							<?=$org[0]["telefonos3"]?><br />
							<?=$org[0]["emails3"]?><br/>
							<?}?>

							<? if($org[0]["nombre_secretario"]<>""){?>
							<p><strong>Secretario/a: <?=$org[0]["nombre_secretario"]?></strong></p>
							<?=nl2br($org[0]["secretarias"])?><br />
							<?}?>

							<? if($org[0]["nombre_secretario2"]<>""){?>
							<p><strong>Secretario/a: <?=$org[0]["nombre_secretario2"]?></strong></p>
							<?=nl2br($org[0]["secretarias2"])?><br />
							<?}?>
							
							<? if($org[0]["mesa_entradas"]<>""){?>
							<p><strong>Mesa de entradas</strong></p>
							<?=nl2br($org[0]["mesa_entradas"])?><br />
							<?}?>
							
							<br />
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