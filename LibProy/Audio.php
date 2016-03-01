<?php

class Audio extends Constructor {

    private $str_tabla = "audios";
    static $static_tabla = "audios";

    public function __construct() {
        global $lang;

        parent::__construct($this->str_tabla, array($this->str_tabla . ".id" => "id",
            $this->str_tabla . ".id_categoria" => "id_categoria",
            $this->str_tabla . ".id_tema" => "id_tema",
            $this->str_tabla . ".id_serie" => "id_serie",
            $this->str_tabla . ".titulo" => "titulo",
            $this->str_tabla . ".copete" => "copete",
            $this->str_tabla . ".id_audio" => "id_audio",
            $this->str_tabla . ".id_preview" => "id_preview",
            $this->str_tabla . ".fecha" => "fecha",
            $this->str_tabla . ".hora" => "hora",
            "a.advLink" => "audio",
            "aa.advLink" => "imagen",
            "c.descripcion" => "categoria",
            "t.nombre" => "tema",
            "s.serie" => "serie",
            "p.programa" => "programa"
        ));

        parent::SetJoin("LEFT JOIN advf a ON " . $this->str_tabla . ".id_audio = a.advID
					 LEFT JOIN advf aa ON " . $this->str_tabla . ".id_preview = aa.advID
					 LEFT JOIN categorias_contenidos c ON " . $this->str_tabla . ".id_categoria = c.id
					 LEFT JOIN temas t ON " . $this->str_tabla . ".id_tema = t.id
					 LEFT JOIN series s ON " . $this->str_tabla . ".id_serie = s.id
					 LEFT JOIN programas p ON " . $this->str_tabla . ".id_programa = p.id");

        parent::SetCondicion("a.advLink != ''");
    }

    public function GetCategorias($id = 0) {

        $sql = "SELECT DISTINCT c.id, c.descripcion
			FROM categorias_contenidos c 
			INNER JOIN audios a ON a.id_categoria = c.id 
			WHERE c.activo='S' and c.estado='A' AND a.activo='S' AND a.estado='A'
			ORDER BY c.descripcion";

        return $this->ExecuteSql($sql);
    }

    public function GetTemas($id = 0) {

        $sql = "SELECT DISTINCT t.id, t.nombre 
			FROM temas t 
			INNER JOIN audios a ON a.id_tema = t.id 
			WHERE t.activo='S' and t.estado='A' AND a.activo='S' AND a.estado='A'
			ORDER BY nombre";

        return $this->ExecuteSql($sql);
    }

    public function GetSeries($id = 0) {

        $sql = "SELECT DISTINCT s.id, s.serie
			FROM series s 
			INNER JOIN audios a ON a.id_serie = s.id 
			WHERE s.activo='S' AND s.estado='A' AND a.activo='S' AND a.estado='A'
			ORDER BY s.serie";

        return $this->ExecuteSql($sql);
    }

    public function GetProgramas() {

        $sql = "SELECT DISTINCT p.id, p.programa
			FROM programas p
			INNER JOIN audios a ON a.id_programa= p.id 
			WHERE p.activo='S' AND p.estado='A' AND a.activo='S' AND a.estado='A' 
			ORDER BY p.orden";

        return $this->ExecuteSql($sql);
    }

    public function GetAudiosHome($tabla) {

        $audios = array();

		$sql = "
		SELECT p.programa, au.id, au.titulo, au.copete, au.fecha, au.id_audio, a1.advLink AS audio, '' AS foto, p.orden, a2.advLink AS icono
			FROM programas p
			INNER JOIN audios au ON  p.id = au.id_programa
			INNER JOIN home_multimedia h ON au.id = h.var
			INNER JOIN advf a1 ON au.id_audio = a1.advID
			LEFT  JOIN advf a2 ON p.id_imagen = a2.advID
			WHERE h.varName='audios' 
			ORDER BY orden
			LIMIT 4
		";

		/*
        $sql = "SELECT *
			FROM 
			( ( SELECT '&uacute;ltimas<br />noticias' AS programa, au.id, au.titulo, au.copete, au.fecha, au.id_audio, a1.advLink AS audio, '' AS foto, 0 AS orden, 'advf/imagenes/2013/06/51aca4fc94f38.png' AS icono
			FROM home_multimedia h
			INNER JOIN audios au ON au.id = h.var
			INNER JOIN advf a1 ON au.id_audio = a1.advID
			WHERE h.varName='audios' AND au.id_programa = 0
			LIMIT 1 )
			
			UNION
			
			(SELECT p.programa, au.id, au.titulo, au.copete, au.fecha, au.id_audio, a1.advLink AS audio, '' AS foto, p.orden, a2.advLink AS icono
			FROM programas p
			INNER JOIN audios au ON  p.id = au.id_programa
			INNER JOIN home_multimedia h ON au.id = h.var
			INNER JOIN advf a1 ON au.id_audio = a1.advID
			LEFT  JOIN advf a2 ON p.id_imagen = a2.advID
			WHERE h.varName='audios' 
			ORDER BY orden
			LIMIT 3 )
			) AS audios
			ORDER BY orden ASC ";
			*/

        $audios = $this->ExecuteSql($sql);

        return $audios;
    }

}

?>