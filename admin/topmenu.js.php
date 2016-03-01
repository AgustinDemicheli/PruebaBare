<?php
include_once("../includes/DB_Conectar.php");
// definimos esta variable para que no corte la ejecucion de este script
include_once("../includes/lib/class.usuario.php");
include_once("../includes/lib/class.lenguaje.php");

$usr = new usuario();
//session_start();
$usr->login($_SESSION["sessUsuario"],$_SESSION["sessPassword"],"admin");

if($usr->_logged==true)
{

	$sql = "select * from admin_secciones where admin_secciones.activo = 'S' order by orden";
	$rsmenusuperior = $conn->execute($sql);

?>
<ul id="navigation" class="sf-js-enabled">
<? 	
	while (!$rsmenusuperior->eof)
	{
		$M_secc=$lang->t($rsmenusuperior->field("nombre"));
	
		$sql = "select * from admin_menu where activo='S' and visible = 'S' and seccion_id = ".$rsmenusuperior->field("id")." and id in (".$usr->listapaginas(true).") order by orden";
		$rsMenu = $conn->execute($sql);
		
		$strRecP = "|";
	
		if($rsMenu->numrows > 0)
		{
			?>
            <li>
                <a href="javascript:void(0)" title="<?=$strRec?>">
                    <?=$M_secc?>
                </a>
                <ul>
            <? 
		}
		
		
		while(!$rsMenu->eof)
		{
			$M_id	= $rsMenu->field("id");
			$M_menu    = $lang->t($rsMenu->field("nombre"));
			$M_link    = $rsMenu->field("link");
			$M_param   = $rsMenu->field("param");
			$M_paramJS = $rsMenu->field("paramJS");
			$M_target  = $rsMenu->field("target");
			$M_icono   = $rsMenu->field("icono");
	
			if($M_paramJS!="")
			{ 
				$M_el_link="javascript:var a = window.open('$link?menu=$M_id','$M_param','$M_paramJS');";
			}
			else
			{
				$M_el_link=$M_link. "?menu=" . $M_id .$M_param;
			}
		
			if ($M_icono!="")
			{
				$M_icon="icons/$icono";
			} 
			else 
			{
				$M_icon="";
			}
			?>
                <li>
                	<a href="<?= $M_el_link?>" target="<? echo $M_target;?>" title="<? echo $M_menu;?>">
                    	<? echo $M_menu;?>
                    </a>
                </li>
            <? 
	
			$rsMenu->movenext();
		}
		if($rsMenu->numrows > 0)
		{
			?>
                </ul>
            </li>
            <? 
		}
		
		$rsmenusuperior->movenext();
	}
?>
</ul>
<? 
}
?>
