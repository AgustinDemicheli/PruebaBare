<?php
/**
 * Gustavo Fiasche
 * gdf 2011
 */
class Enlaces extends Constructor {

	private $str_tabla 	= "enlaces";
	static  $static_tabla = "enlaces";
	  
	public function __construct($tipo="N")
	{
		global $lang;
	 	parent::__construct($this->str_tabla,array($this->str_tabla.".id"=>"id",
							$this->str_tabla.".titulo".$lang=>"titulo",
							$this->str_tabla.".link".$lang=>"link",
							"a.advLink"=>"imagen",
							"c.nombre"=>"categoria"	
	 						));
	 						
	 	parent::SetJoin(" LEFT JOIN advf a ON ".$this->str_tabla.".id_imagen = a.advID
	 					  LEFT JOIN enlaces_categorias c ON ".$this->str_tabla.".id_categoria = c.id
	 						");	

		parent::SetCondicionInicial(" ".$this->str_tabla.".activo='S' AND ".$this->str_tabla.".estado='A' and ".$this->str_tabla.".es_banner='".$tipo."'");
	 	
	}
	
}
?>