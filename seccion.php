<?php 
include "_doctype.php";
$id_seccion = intval($_GET["id"]);
$seccion = getSeccion($id_seccion);

$orden_actual = $seccion[0]["orden_seccion"];
$anterior = getAnterior($orden_actual);
$siguiente = getSiguiente($orden_actual);

$url_share = $var_url."/s".$seccion[0]["id"]."/".htmlentities_dir($seccion[0]["nombre_seccion_menu"]);

//echo "<pre>";print_r($seccion);echo "</pre>";
?>
	<body id="interior">
		<?php include "_header.php"; ?>
		<div id="container">
			<?if(count($anterior)==1){?>
			<div class="back-content-arr arrow-content">
				<a href="/s<?=$anterior[0]["id"]?>/<?=htmlentities_dir($anterior[0]["nombre_seccion_menu"])?>">
					<div class="ico"><img src="/img/big_arr_back.png"></div>
					<div class="text"><?=$anterior[0]["nombre_seccion_menu"]?></div>
				</a>
			</div>
			<?}?>
			<?if(count($siguiente)==1){?>
			<div class="next-content-arr arrow-content">
				<a href="/s<?=$siguiente[0]["id"]?>/<?=htmlentities_dir($siguiente[0]["nombre_seccion_menu"])?>">
					<div class="ico"><img src="/img/big_arr_next.png"></div>
					<div class="text"><?=$siguiente[0]["nombre_seccion_menu"]?></div>
				</a>
			</div>
			<?}?>
			<div class="wrapper-breadcrumb">
				<div class="main-box">
					<ul class="breadcrumb">
						<li><?=$seccion[0]["titulo_menu"]?></li>
						<?if($seccion[0]["id_seccion_padre"]==0){?>
						<li><?=$seccion[0]["nombre_seccion_menu"]?></li>
						<?}else{?>
						<li><?=$seccion[0]["padre"]?></li>
						<li><?=$seccion[0]["nombre_seccion_menu"]?></li>
						<?}?>
					</ul>
				</div>
			</div>
			<div class="wrapper-content-head">
				<div class="main-box">
					<div class="content-head">
						<div class="titles">
							<h2><?=$seccion[0]["nombre_seccion"]?></h2>
							<h5><?=$seccion[0]["subtitulo"]?></h5>
						</div>
						<? if($seccion[0]["copete"]<>""){?>
						<p class="copete">
							<?=$seccion[0]["copete"]?>
						</p>
						<?}?>
						<div class="share-content">
							<a href="javascript:facebookDialog('<?=$url_share?>','Guia del Poder Judicial - <?=$seccion[0]["nombre_seccion_menu"]?>');" class="fb">Facebook</a>
							<a href="javascript:twitterDialog('Guia del Poder Judicial - <?=$seccion[0]["nombre_seccion_menu"]?>','<?=$url_share?>');" class="tw">Twitter</a>
						</div>
					</div>					
				</div>
			</div>
			<div class="editable-content clearfix">
				<div class="main-box">
				<?=$seccion[0]["cuerpo"]?>
				</div>
			</div>
			<div class="main-box">
				<div class="links-bottom clearfix">
					<? if(count($seccion[0]["ENLACES_RELACIONADOS"]) > 0 ){ ?>
					<div class="link">
						<div class="icon">
							<img src="/img/ico_related.png">
						</div>
						<div class="title">Enlaces<br />relacionados</div>
						<ul>
							<?for($i=0;$i< count($seccion[0]["ENLACES_RELACIONADOS"]); $i++){?>
							<li><a href="<?=$seccion[0]["ENLACES_RELACIONADOS"][$i]["ENLACE"]?>" target="<?=$seccion[0]["ENLACES_RELACIONADOS"][$i]["TARGET"]?>" ><?=$seccion[0]["ENLACES_RELACIONADOS"][$i]["TITULO"]?></a></li>
							<?}?>
						</ul>
					</div>
					<?}?>
					<? if(count($seccion[0]["CONTENIDO_RELACIONADO"]) > 0 ){ ?>
					<div class="link">
						<div class="icon">
							<img src="/img/ico_more_guide.png">
						</div>
						<div class="title">M&aacute;s en esta gu&iacute;a</div>
						<ul>
							<?for($i=0;$i< count($seccion[0]["CONTENIDO_RELACIONADO"]); $i++){?>
							<li><a href="<?=$seccion[0]["CONTENIDO_RELACIONADO"][$i]["LINK"]?>"><?=$seccion[0]["CONTENIDO_RELACIONADO"][$i]["TITULO"]?></a></li>
							<?}?>
						</ul>
					</div>
					<?}?>
					<? if(count($seccion[0]["ARCHIVOS"]) > 0 ){ ?>
					<div class="link">
						<div class="icon">
							<img src="/img/ico_download.png">
						</div>
						<div class="title">Descargar</div>
						<ul>
							<?for($i=0;$i< count($seccion[0]["ARCHIVOS"]); $i++){?>
							<li><a href="<?=$seccion[0]["ARCHIVOS"][$i]["LINK_ABSOLUTE"]?>" target="_blank"><?=$seccion[0]["ARCHIVOS"][$i]["TITULO"]?></a></li>
							<?}?>
						</ul>
					</div>
					<?}?>
				</div>
			</div>
		</div>
<?php include "_footer.php"; ?>