<?
/**
 * GDF 2009
 */
class MyException extends Exception {
  	
	private $guardar = true;
	
	public function getError() {
		global $desarrollo;
		$vErrores = $this->getTrace();
		
		$text_error = "Error: Verifique ".$vErrores[0]['file']." linea ".$vErrores[0]['line']."' [".$vErrores[0]['class']."|".$vErrores[0]['function']."]. Mensaje Local:".$this->getMessage()."' ";
	    if ($this->guardar){
			$fp2 = @fopen($app_path."error_.log","a+"); 
			fwrite($fp2,date("Y-m-d H:i")." ".$text_error."\n"); 
			fclose($fp2);
		}
		if ($desarrollo){
			print '<pre>'; print_r($text_error); print '</pre>';
		}
	}

}

?>