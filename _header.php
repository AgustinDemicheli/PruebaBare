<div class="header-wrapper">
	<header>
		<div class="top-head">
			<div class="main-box clearfix">
				<h1 class="logo">
					<a href="/"><img src="/img/logo_main.png" ></a>
				</h1>
				<div class="sec-logo">
					<img src="/img/poder_judicial_head.png">
				</div>		
			</div>
		</div>
		<div class="nav-wrapper">
			<div class="main-box clearfix">
				<nav>
					<button id="menu-button">Menu</button>
					<ul class="nav">
						<?for($i=0;$i<count($menu);$i++){?>
						<li>
							<? if(trim($menu[$i]["LINK_MENU"])==""){?>
								<a href="javascript:void(0);"><?=$menu[$i]["TITULO"]?></a>
							<?}else{?>
								<a href="<?=$menu[$i]["LINK_MENU"]?>"><?=$menu[$i]["TITULO"]?></a>
							<?}?>
							<ul class="sub-menu">
								<?for($j=0;$j<count($menu[$i]["HIJOS"]);$j++){?>
									<?if(count($menu[$i]["HIJOS"][$j]["NIETOS"])>0){?>
										<li><a href="javascript:void(0);"><?=$menu[$i]["HIJOS"][$j]["TITULO"]?></a>
									<?}else{?>
										<? if(trim($menu[$i]["HIJOS"][$j]["LINK_MENU"])==""){?>
											<li><a href="/s<?=$menu[$i]["HIJOS"][$j]["ID"]?>/<?=htmlentities_dir($menu[$i]["HIJOS"][$j]["TITULO"])?>"><?=$menu[$i]["HIJOS"][$j]["TITULO"]?></a>
										<?}else{?>
											<li><a href="<?=$menu[$i]["HIJOS"][$j]["LINK_MENU"]?>"><?=$menu[$i]["HIJOS"][$j]["TITULO"]?></a>
										<?}?>
									<?}?>
									<?if(count($menu[$i]["HIJOS"][$j]["NIETOS"])>0){?>
										<ul class="sub-menu">
											<?for($h=0;$h<count($menu[$i]["HIJOS"][$j]["NIETOS"]);$h++){?>	
												<li>
													<? if(trim($menu[$i]["HIJOS"][$j]["NIETOS"][$h]["link_menu"])==""){?>
													<a href="/s<?=$menu[$i]["HIJOS"][$j]["NIETOS"][$h]["id"]?>/<?=htmlentities_dir($menu[$i]["HIJOS"][$j]["NIETOS"][$h]["nombre_seccion_menu"])?>"><?=$menu[$i]["HIJOS"][$j]["NIETOS"][$h]["nombre_seccion_menu"]?></a>
													<?}else{?>
													<a href="<?=$menu[$i]["HIJOS"][$j]["NIETOS"][$h]["link_menu"]?>"><?=$menu[$i]["HIJOS"][$j]["NIETOS"][$h]["nombre_seccion_menu"]?></a>
													<?}?>
												</li>
											<?}?>
										</ul>
									<?}?>
									</li>
								<?}?>
							</ul>
						</li>
						<?}?>
					</ul>
				</nav>
				<div class="search-wrapper">
					<form action="/resultados" method="GET">
						<fieldset>
							<input type="search" placeholder="B&uacute;squeda" name="buscar">
							<a id="button-search" href="#ABACAD">Buscar</a>
						</fieldset>
					</form>					
				</div>
				<div class="social clearfix">
					<a class="fb" href="javascript:facebookDialog('http://<?=$_SERVER["HTTP_HOST"]?>','Guía Poder Judicial de la Ciudad Autónoma de Buenos Aires');">Facebook</a>
					<a class="tw" href="javascript:twitterDialog('Guía Poder Judicial de la Ciudad Autónoma de Buenos Aires ','http://<?=$_SERVER["HTTP_HOST"]?>');">Twitter</a>
					<a class="mail" href="mailto:scpj@jusbaires.gov.ar">Correo</a>
				</div>
			</div>
		</div>			
	</header>
</div>