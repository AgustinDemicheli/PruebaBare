<?php
include_once("includes/DB_Conectar.php");

if( intval($_REQUEST["tiposj"]) > 0) $_SESSION["tiposj"] = intval($_REQUEST["tiposj"]); else $params["tipoj"] = $_SESSION["tiposj"];

if( $_SESSION["tiposj"] == 9999 ) $params["tipoj"] = 0; else $params["tipoj"] = $_SESSION["tiposj"];

if( intval($_REQUEST["fueros"]) > 0)  $_SESSION["fueros"] = intval($_REQUEST["fueros"]); else $params["fuero"] = $_SESSION["fueros"];

if( $_SESSION["fueros"] == 9999 ) $params["fuero"] = 0; else $params["fuero"] = $_SESSION["fueros"];

if(isset($_GET['p']) && $_GET['p']>0 && intval($_GET['p']))
{
	 $page = intval($_GET["p"]);
}else
	$page = 1;

$cant = 8;
$current = ( $page - 1 ) * $cant;

$element_count = cantJuzgados($params);

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

$juzgados = getJuzgados($current,$cant,$params);

$fueros = getFueros();
$tipos = getTiposJuzgados();

include "_doctype.php"; ?>
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
					<div class="head-form">
						<form action="/juzgados" method="POST" name="filters" id="filters">
							<fieldset>
									<div class="selector">
										<label for="fueros">Fuero</label>
										<select name="fueros" id="fueros" onchange="document.getElementById('filters').submit();">
											<option value="9999">Todos</option>
											<? for($i=0; $i < count($fueros) ; $i++){?>
											<option value="<?=$fueros[$i]["id"]?>" <?=($fueros[$i]["id"]==$_SESSION["fueros"]?" selected='selected'":"")?> ><?=$fueros[$i]["fuero"]?></option>
											<?}?>
										</select>	
									</div>	
									<div class="selector">															
										<label for="tiposj">Instancia</label>
										<select name="tiposj" id="tiposj" onchange="document.getElementById('filters').submit();">
											<option value="9999">Todos</option>
											<? for($i=0; $i < count($tipos) ; $i++){?>
											<option value="<?=$tipos[$i]["id"]?>" <?=($tipos[$i]["id"]==$_SESSION["tiposj"]?" selected='selected'":"")?> ><?=$tipos[$i]["tipo"]?></option>
											<?}?>
										</select>
									</div>			
							</fieldset>
						</form>
					</div>
					<div class="results">
						<? for($i=0; $i < count($juzgados) ; $i++){?>
						<? if($i % 2 == 0){?><div class="row clearfix"><?}?>
						<div class="result">
							<span><?=$juzgados[$i]["fuero"]?></span>
							<span><?=$juzgados[$i]["tipo_juzgado"]?></span>
							<strong><?=$juzgados[$i]["numero_juzgado"]?></strong>
							<a href="/juzgado/<?=$juzgados[$i]["id"]?>/<?=htmlentities_dir($juzgados[$i]["numero_juzgado"])?>" class="more">ver m&aacute;s</a>
						</div>
						<? if($i % 2 == 1 OR $i==(count($juzgados)-1)){?></div><?}?>
						<?}?>
					</div>
					<? if($numpages > 1){?>
					<div class="paginator">
						<? for($i=1; $i <= $numpages ; $i++){?>
							<a href="/juzgados/p<?=$i?>" <?=($i==$page?' class="active"':'')?>><?=$i?></a>
						<?}?>
					</div>
					<?}?>
				</div>
			</div>
		</div>
<?php include "_footer.php"; ?>