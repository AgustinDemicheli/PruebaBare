<?

// Modulo de traducciones, para soportar el multilenguaje duro, es decir, de contenidos estaticos.

class lenguaje
{
	var $_traducciones = array();	// Listado de traducciones

	function lenguaje ( $archivo = 'espanol.lang') 
	{
		global $conn;

		if(is_numeric($archivo)) // Si mando el id del lenguaje
		{
			$sql = "select * from admin_lenguajes where id = $archivo";
			$rs = $conn->execute($sql);

			if($rs->numrows==1) $archivo = $rs->field("traducciones");
		}
		// En archivo debe estar definido el array trad que contiene todas las traducciones necesarias del admin.
		include_once("lang/".$archivo);

		$this->_traducciones = $trad;
	}

	function t($field)
	{
		// Esta funcion nos da la traduccion al lenguaje que este cargado del idioma.
		if($this->_traducciones[strtolower($field)]!="")
			return $this->_traducciones[strtolower($field)];
		else
			return $field;
	}
}
?>