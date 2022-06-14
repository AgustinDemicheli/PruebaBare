<?php 
include "_doctype.php"; 
$slider = getSlider();
$sextuple = getSextuple();
$accesos = getAccesos();
?>
	<body id="home">
		<?php include "_header.php"; ?>
		<div id="container">
			<div class="main-box">			
				<div class="slider clearfix">
					<ul class="bxslider">
						<?for($i=0;$i<count($slider);$i++){?>
						<li>
							<div class="info">
								<h2><?=$slider[$i]["valor2_modulo"]?></h2>
								<p><?=$slider[$i]["valor3_modulo"]?></p>
								<a href="<?=$slider[$i]["valor4_modulo"]?>">+ Info</a>
							</div>
							<div class="image">
								<img src="/<?=Multimedia::GetImagenStaticById(610,0,$slider[$i]["id_portal"])?>">
							</div>
						</li>
						<?}?>
					</ul>
				</div>
			</div>
			<div class="wrapper-highlight-links">
				<div class="main-box clearfix">
					<?for($i=0;$i<count($sextuple);$i++){?>
					<div class="button">
						<a href="<?=$sextuple[$i]["valor4_modulo"]?>">
							<div class="number"><?=($i==5?"*":$i+1)?></div>
							<p><?=$sextuple[$i]["valor2_modulo"]?></p>
							<p class="hover"><?=$sextuple[$i]["valor3_modulo"]?></p>
						</a>
					</div>
					<?}?>
				</div>
			</div>
			<div class="wrapper-bottom-links">
				<div class="main-box clearfix">
					<?for($i=0;$i<count($accesos);$i++){?>
					<div class="link clearfix">
						<a href="<?=$accesos[$i]["valor4_modulo"]?>">
							<div class="ico">
								<img src="/<?=Multimedia::GetImagenStaticById(100,0,$accesos[$i]["id_portal"])?>">
							</div>
							<div class="info">
								<p class="one-line"><?=$accesos[$i]["valor2_modulo"]?></p>
							</div>
						</a>
					</div>
					<?}?>
				</div>
			</div>
		</div>
<?php include "_footer.php"; ?>