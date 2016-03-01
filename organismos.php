<?php
include_once("includes/DB_Conectar.php");

if( intval($_REQUEST["tipos"]) > 0) $_SESSION["tipos"] = intval($_REQUEST["tipos"]); else $params["tipo"] = $_SESSION["tipos"];

if( $_SESSION["tipos"] == 9999 ) $params["tipo"] = 0; else $params["tipo"] = $_SESSION["tipos"];

if( intval($_REQUEST["orgpadre"]) > 0)  $_SESSION["orgpadre"] = intval($_REQUEST["orgpadre"]); else $params["orgpadre"] = $_SESSION["orgpadre"];

if( $_SESSION["orgpadre"] == 9999 ) $params["orgpadre"] = 0; else $params["orgpadre"] = $_SESSION["orgpadre"];

if( intval($_REQUEST["comunas"]) > 0)  $_SESSION["comuna"] = intval($_REQUEST["comunas"]); else $params["comuna"] = $_SESSION["comuna"];

if( $_SESSION["comuna"] == 9999 )  $params["comuna"] = 0;  else $params["comuna"] = $_SESSION["comuna"];


if(isset($_GET['p']) && $_GET['p']>0 && intval($_GET['p']))
{
	 $page = intval($_GET["p"]);
}else
	$page = 1;

$cant = 8;
$current = ( $page - 1 ) * $cant;

$element_count = cantOrganismos($params);

// Paginacion
if ($current + $cant <= $element_count)
    $next_page = $page + 1;
else
    $next_page = $page;

if ($current - $cant >= 0)
    $prev_page = $page - 1;
else
    $prev_page = $page;

$mostrar = 6;
$primera = $page - abs($mostrar / 2);
$ultima = $page + abs($mostrar / 2);
$numpages = ceil($element_count / $cant);
if ($numpages < 1)
    $numpages = 1;

if ($primera < 1) {
    $primera = 1;
    if ($numpages > $mostrar)
        $ultima = $mostrar;
    else
        $ultima = $numpages;
}

if ($ultima > $numpages) {
    $ultima = $numpages;
    $primera = $ultima - $mostrar;
    if ($primera < 1)
        $primera = 1;
}

$paginado = "";

$organismos = getOrganismos($current,$cant,$params);

$tipos = getTiposOrganismos();
$padres = getOrgPadres();
$comunas = getComunas();

include "_doctype.php"; 
?>
	<body id="mapa-buscador" class="organismos">
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
					<div class="head-form">
						<form action="/organismos" method="POST" name="filters" id="filters">
							<fieldset>
								<div class="selector">
									<label for="tipos">Tipo</label>
									<select name="tipos" id="tipos" onchange="document.getElementById('filters').submit();">
										<option value="9999">Todos</option>
										<? for($i=0; $i < count($tipos) ; $i++){?>
										<option value="<?=$tipos[$i]["id"]?>" <?=($tipos[$i]["id"]==$_SESSION["tipos"]?" selected='selected'":"")?> ><?=$tipos[$i]["tipo"]?></option>
										<?}?>
									</select>													
								</div>
								<div class="selector">
									<label for="orgpadre">Dependencia</label>
									<select name="orgpadre" id="orgpadre" onchange="document.getElementById('filters').submit();">
										<option value="9999">Todos</option>
										<? for($i=0; $i < count($padres) ; $i++){?>
										<option value="<?=$padres[$i]["id"]?>"  <?=($padres[$i]["id"]==$_SESSION["orgpadre"]?" selected='selected'":"")?> ><?=$padres[$i]["nombre"]?></option>
										<?}?>
									</select>	
								</div>
								<div class="selector">
									<label for="comunas">Comuna</label>
									<select name="comunas" id="comunas" onchange="document.getElementById('filters').submit();">
										<option value="9999">Todas</option>
										<? for($i=0; $i < count($comunas) ; $i++){?>
										<option value="<?=$comunas[$i]["id"]?>" <?=($comunas[$i]["id"]==$_SESSION["comuna"]?" selected='selected'":"")?> ><?=$comunas[$i]["numero"]?></option>
										<?}?>
									</select>						
								</div>
							</fieldset>
						</form>
					</div>
					<div class="results">
						<? for($i=0; $i < count($organismos) ; $i++){?>
							<? if($i % 2 == 0){?><div class="row clearfix"><?}?>
							<div class="result">
								<span><?=$organismos[$i]["tipo_organismo"]?></span>
								<strong><?=$organismos[$i]["nombre"]?></strong>
								<p><?=$organismos[$i]["direccion"]?><br /><?=$organismos[$i]["telefonos"]?></p>
								<a href="/organismo/<?=$organismos[$i]["id"]?>/<?=htmlentities_dir($organismos[$i]["nombre"])?>" class="more">ver m&aacute;s</a>
							</div>
							<? if($i % 2 == 1 OR $i==(count($organismos)-1)){?></div><?}?>
						<?}?>
					</div>
					<? if($numpages > 1){?>
					<div class="paginator">
						<? for($i=1; $i <= $numpages ; $i++){?>
							<a href="/organismos/p<?=$i?>" <?=($i==$page?' class="active"':'')?>><?=$i?></a>
						<?}?>
					</div>
					<?}?>
				</div>
			</div>
		</div>
<?php include "_footer.php"; ?>