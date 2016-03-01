<?
/**
 * funcciones de fechas
 * @author Gustavo Fiasche
 * 2011
 */
class FuncionesForm {
	
	private $string_form_name="";
	private $instanciado = false;
	private $array_objetos = array();
	
	public function __construct($formName){
		$string_form_name  = $formName;
		$this->instanciado = true;
	}
	
	/**
	 * elemento select de formulario
	 * @param string $id
	 * @param string $name
	 * @param array $arrayParam
	 * @param array $arrayValues
	 * @param string $op0Text
	 * @param string $op0Value
	 */
	public function select($id,$name, $arrayParam, $arrayValues, $op0Text="", $op0Value="",$validar=false){
		
		if ($validar){
			if ($this->instanciado){
				array_push($this->array_objetos,$id);
			}
		}
		
		
		$obj  ="<select name='".$name."' id='".$id."' ";
		foreach ($arrayParam as $key=>$value){
			$obj .=$key."='".$value."'";
		}
		$obj .=">";
		$obj .="<option value='".($op0Value!=""?0:$op0Value)."'>".(($op0Text!=""?"Seleccionar":$op0Text))."</option>";
		for ($i=0; $i<count($arrayValues); $i++){
			$obj .="<option ='".$arrayValues[$i]['id']."'>".$arrayValues[$i]['text']."</option>";
		}
		$obj .="</select>";
		
		return $obj;
	}
	
	/**
	 * 
	 * @param string $id
	 * @param string $name
	 * @param string $type
	 * @param array $arrayParam
	 */
	public function input($id,$name,$type,$arrayParam,$validar=false){
		
		if ($validar){
			if ($this->instanciado){
				array_push($this->array_objetos,$id);
			}
		}
		
		$obj = "<input type='".$type."' name='".$name."' id='".$id."' ";
		foreach ($arrayParam as $key=>$value){
			$obj .=$key."='".$value."'";
		}
		$obj .=">"; 	
	}
	
	/**
	 * 
	 */
	public function getValidacionJs(){
	
	}
	
	/**
	 * 
	 */
	public function getValidacionPhp(){
		
	}
	
	
}