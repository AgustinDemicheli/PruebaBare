<?php

class Video extends Constructor {

  private $str_tabla 	="videos";
  static  $static_tabla = "videos";
  
  public function __construct()
  {
 	global $lang;
 		
  	parent::__construct($this->str_tabla,array($this->str_tabla.".id"=>"id",
												$this->str_tabla.".id_categoria"=>"id_categoria",
												$this->str_tabla.".id_tema"=>"id_tema",
												$this->str_tabla.".id_serie"=>"id_serie",
  												$this->str_tabla.".titulo"=>"titulo",
												$this->str_tabla.".copete"=>"copete",
												$this->str_tabla.".id_video"=>"id_video",
												$this->str_tabla.".id_preview"=>"id_preview",
												$this->str_tabla.".fecha"=>"fecha",
												$this->str_tabla.".link_hd"=>"link_hd",
												$this->str_tabla.".link_sd"=>"link_sd",
  												"a.advLink"=>"video",
												"aa.advLink"=>"imagen",
												"c.descripcion"=>"categoria",
												"t.nombre"=>"tema",
												"s.serie"=>"serie",
  												));

  	parent::SetJoin("LEFT JOIN advf a ON ".$this->str_tabla.".id_video = a.advID
					 LEFT JOIN advf aa ON ".$this->str_tabla.".id_preview = aa.advID
					 LEFT JOIN categorias_contenidos c ON ".$this->str_tabla.".id_categoria = c.id
					 LEFT JOIN temas t ON ".$this->str_tabla.".id_tema = t.id
					 LEFT JOIN series s ON ".$this->str_tabla.".id_serie = s.id");
				
	//parent::SetCondicion("a.advLink != '' ");
											
   }
   
   public function GetCategorias($id,$actual){
  	
  	$sql = "SELECT DISTINCT c.id, c.descripcion
			FROM categorias_contenidos c 
			INNER JOIN videos v ON v.id_categoria = c.id 
			WHERE c.activo='S' and c.estado='A' AND v.activo='S' AND v.estado='A'
			ORDER BY c.descripcion";
  	
  	return $this->ExecuteSql($sql);
  }
  
  public function GetTemas($id,$actual){
  	
  	$sql = "SELECT DISTINCT t.id, t.nombre 
			FROM temas t 
			INNER JOIN videos v ON v.id_tema = t.id 
			WHERE t.activo='S' and t.estado='A' AND v.activo='S' AND v.estado='A'  
			ORDER BY nombre";
  	
  	return $this->ExecuteSql($sql);
  }
  
  public function GetSeries($id,$actual){
  	
  	$sql = "SELECT DISTINCT s.id, s.serie
			FROM series s 
			INNER JOIN videos v ON v.id_serie = s.id 
			WHERE s.activo='S' AND s.estado='A' AND v.activo='S' AND v.estado='A'
			ORDER BY s.serie";
  	
  	return $this->ExecuteSql($sql);
  }
  
  
  public function GetVideosHome($tabla){

	$sql = "SELECT v.id, v.id_preview as id_foto, a.advLink AS foto, v.id_video, a2.advLink AS video, v.titulo, v.copete, v.fecha, s.serie
			FROM ".$tabla." h
			INNER JOIN videos v ON v.id = h.var
			INNER JOIN advf a ON v.id_preview = a.advID
			INNER JOIN advf a2 ON v.id_video = a2.advID
			LEFT JOIN series s ON v.id_serie= s.id
			WHERE h.varName='videos' 
			ORDER BY h.orden LIMIT 3";
	
	$rs = $this->ExecuteSql($sql);
	return $rs;
  }
  
  public function GetVideosDestacadasHome($tabla){

	$sql = "SELECT v.id, v.id_preview as id_foto, a.advLink AS foto, v.id_video, a2.advLink AS video, v.titulo, v.fecha, 'videos' as tipo
			FROM ".$tabla." h
			INNER JOIN videos v ON v.id = h.var
			INNER JOIN advf a ON v.id_preview = a.advID 
			INNER JOIN advf a2 ON v.id_video = a2.advID
			WHERE h.varName='destacadosvideos' 
			ORDER BY h.orden";

	$rs = $this->ExecuteSql($sql);
	return $rs;
  }
  
	public function GetUltimas($limit, $id){
		return parent::Lista($limit," videos.fecha DESC, videos.id DESC",false,0,0,1, " videos.id NOT IN ('".$id."') ");
	}
  
}
?>