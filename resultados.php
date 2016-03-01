<?php 
include_once("includes/DB_Conectar.php");

$busq = mysql_real_escape_string($_REQUEST["buscar"]);
$res = buscar($busq);

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
							Resultados de búsqueda
						</li>
					</ul>
				</div>
			</div>
			<div class="wrapper-search-mapa">
				<div class="main-box clearfix">
					<h4 class="results-title">Resultados para <span><?=htmlspecialchars($_REQUEST["buscar"])?></span></h4>
					<ul class="results-list">
					<?for($i=0;$i<count($res);$i++){
						switch($res[$i]["tipo"])
						{
							case "organismo":
								$url = "/organismo/" . $res[$i]["id"] . "/" . htmlentities_dir($res[$i]["nombre"]);
								break;
							case "juzgado":
								$url = "/juzgado/" . $res[$i]["id"] . "/" . htmlentities_dir($res[$i]["nombre"]);
								break;
							case "seccion":
								$url = "/s" . $res[$i]["id"] . "/" . htmlentities_dir($res[$i]["nombre"]);
								break;
						}
					?>
						<li><a href="<?=$url?>" class="more"><?=$res[$i]["nombre"]?></a></li>
					<?}?>
					</ul>
				</div>
			</div>
		</div>
<?php include "_footer.php"; ?>