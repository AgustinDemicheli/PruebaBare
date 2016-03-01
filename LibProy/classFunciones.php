<?
/**
 * factory
 * funciones
 * @author Gustavo Fiasche
 * 2011
 */
class funciones{
	
	/**
	 * functiones comunes
	 */
	function base(){
		return new FuncionesBase();
	}
	
	/**
	 * funciones de fechas 
	 */
	function fechas(){
		return new FuncionesFechas();
	}
	
	/**
	 * functiones form
	 */
	function form(){
		return new FuncionesForm();
	}
	
	
}



?>