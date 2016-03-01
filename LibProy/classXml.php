<?
/**
 * Clase para generar archivos xml
 * GDF 2007
 * 
 * Ejemplo de CDATA 
 * $aXml['images']['pic']['autor_en|CDATA'] = $aux->field("advAutor_archivo_en");
 * indicador "|CDATA"
 * 
 * Ejemplo de ATRIBUTO
 * $aXml['images']['pic']['image?width="150px"?height="84px"']= $aux->field("imagen");
 * separador de atributos "?"
 * 
 * creación del objeto
 * new CreateXml("prueba.xml",$aXml,"utf-8",true,true);
 * 				  1_ archivo (destino donde se va a guardar el xml armado)
 * 				  2_ array	 (estructura del xml. expresada en un array)
 * 				  3_ charset (charset de la cabecea del xml)
 * 				  4_ encode  (true:encode todos los valores y los atributos en utf-8,false: no encodea
 * 				  5_ display (true:mostrar el xml armado,false:no muestra  
 */
class CreateXml {
	
	
	private $datos;
	private $file;
	private $xml;
	private $encode = true;
	
	/**
	 * Metodo constructor
	 * 
	 * @param string_type $file
	 * @param array() $arrayData
	 * @param charset $encoding
	 * @param bool $encode
	 */
	public function CreateXml($file,&$arrayData,$encoding = "utf-8",$encode = true,$display = false){
		
		$this->encode = $encode;
		$this->datos = $arrayData;
		$this->file = $file;
		$this->xml = "<?xml version=\"1.0\" encoding=\"".$encoding."\" standalone=\"yes\"?>\n";
		$this->GetElement($this->datos);
		$this->SaveXml();
		
		if ($display == true){
			echo $this->xml;
		}
	}
	
	/**
	 * Metodo privado, trae el elementos
	 * @param array $xx
	 */
	private function GetElement($xx){
		
		foreach ($xx as $key=>$value){
			
			$x="";
			$apert="";
			$close="";
			
			$att = $this->GetAttribute($key);
			$apert = $att[0];
			$close=$att[0];
			
			for ($i=1; $i<count($att); $i++){
				
				if ($this->encode==true){
					$att[$i]=utf8_encode($att[$i]);
				}
				$x .=" ".$att[$i];
			}
			$apert .= $x;
			
			if (is_array($value)){
				
				if (!is_numeric($apert)){
					$this->xml .="<".$apert.">\n";
				}
				
				$this->GetElement($value);
				if (!is_numeric($apert)){
					$this->xml .="</".$close.">\n";
				}
				
			}else {
				
				if ($this->encode == true){
					$value = utf8_encode($value);
				}
				
				if (strpos($key,"|CDATA")){
					$att = $this->GetCDATA($key);
					$apert = $att[0];
					$close=$att[0];
					$value="<![CDATA[".$value."]]>";
				}
				
				
				$this->xml .="<".$apert.">";
				$this->xml .=$value;
				$this->xml .="</".$close.">\n";
			}	
		}
	}		
	
	/**
	 * Metodo privado donde se separan los 
	 * atributos
	 *
	 * @param string $att
	 * @return array
	 */
	private function GetAttribute($att){
	
		$element=array();
		$element = split("\?",$att);
		return $element;
	}
	
	/**
	 * Metodo privado para sacar el valor 
	 * del elemento, cuando el valor del elemento
	 * debe ser CDATA.
	 *
	 * @param string $att
	 * @return array
	 */
	private function GetCDATA($att){
	
		$element=array();
		$element = split("\|CDATA",$att);
		return $element;
	}
	
	/**
	 * Guardo en archivo con todo el xml 
	 * que fue procesado.
	 *
	 */
	private function SaveXml(){
		
		$fp = fopen($app_path.$this->file,"w");
		fputs($fp, $this->xml);
		fclose($fp);
	}
		
		
	
}

?>