<?
class Paginado {
	
	private $registros_x_pagina = 0;
	private $paginas_x_pagina = 0;
	private $pagina_actual = 0;
	private $registro_inicial = 0;
	
	private $pagina_inicial = 0;
	private $pagina_fin=0;
	
	private $sql;
	
	private $total_registros = 0;
	private $total_paginas = 0;
	
	private $pagina_anterior;
	private $pagina_siguiente;
	private $test = false;
	
	
	
	const BTN_SIG='images/btn_siguiente.gif'; //imagen siguiente
	const BTN_ANT='images/btn_anterior.gif';  //imagen anterior
	const CS_num_pag='nros';      //pagina, las que no estan selecionadas 
	const CS_num_pag_sel='fc_c5_11_bold';  //pagina seleccionada
	
	//$sql no tiene que incluir el limit (mysql)
	public function __construct($registros_x_pagina,$paginas_x_pagina,$sql,$test = false){
		global $conn;
		
		$this->test = $test;
		$this->registros_x_pagina = $registros_x_pagina;
		$this->paginas_x_pagina = $paginas_x_pagina;
		$this->sql = $sql;
		
		preg_match("@group by@",$sql,$encontrados);
		if (count($encontrados)>0){
			// el A es para que no cambie los que estan es subsonsulta
			//$sqlCount=preg_replace("@SELECT(.*?)FROM@siA","SELECT count(*) as cuantosPag,$1 FROM ",$sql);
			$sqlCount=preg_replace("@SELECT(.*?)FROM@si","SELECT count(*) as cuantosPag,$1 FROM ",$sql);		
		}else {
			// el A es para que no cambie los que estan es subsonsulta
			//$sqlCount=preg_replace("@SELECT(.*?|\*)FROM@siA","SELECT count(*) as cuantosPag FROM ",$sql);
			$sqlCount=preg_replace("@SELECT(.*?|\*)FROM@si","SELECT count(*) as cuantosPag FROM ",$sql);		
		}
		//si viene el order lo saco
		
		if (stripos($sqlCount,"order")>0){
			$sqlCount = substr($sqlCount,0,stripos($sqlCount,"order"));
		}
		if ($this->test){
			print '<pre>Consulta: total de registros '; print_r('"'.$sqlCount.'"'); print '</pre>'; 
		}
		
		$rs = $conn->Execute($sqlCount);
		
		if (!$rs->eof){
			
			if ($rs->numrows>0){
				
				while (!$rs->eof) {
					$this->total_registros = $this->total_registros + $rs->Field("cuantosPag");
					$rs->MoveNext();
				}
			}else {
				$this->total_registros = $rs->Field("cuantosPag");
			}
		}else {
			$this->total_registros = 0;
		}
		
		$this->total_paginas = ceil($this->total_registros / $this->registros_x_pagina);
		
		
		
	}
	
	/**
	 * trae los registro dado el limit calculado
	 */
	public function Listar($pagina_actual){
		global $conn;
		$datos = array();
		
		$this->pagina_actual = $pagina_actual;
		
		if ($this->total_paginas < $pagina_actual){
			$this->pagina_actual = $this->total_paginas;
		}else {
			$this->pagina_actual = $pagina_actual;
		}
		
		if ($pagina_actual<1){
			$this->pagina_actual = 1;
		}
		
		$this->Calcular();
		
		if ($this->total_registros<=0) { return "";}
		
		$sql = $this->sql." LIMIT ".$this->registro_inicial.",".$this->registros_x_pagina;
		
		$rs = $conn->execute($sql);
		
		if ($this->test){
			print '<pre>consulta '; print_r($sql); print '</pre>'; 
		}
		
		$i=0;
		while (!$rs->eof) {
			
			for ($k=0; $k<$rs->numfields; $k++){

			$datos[$i][$rs->fieldname[$k]] = $rs->field($rs->fieldname[$k]);	
			/*if($rs->fieldname[$k] == 'precio') {
		    		$datos[$i][$rs->fieldname[$k]] = '$ ' . $rs->field($rs->fieldname[$k]);
			}else{
		    		$datos[$i][$rs->fieldname[$k]] = $rs->field($rs->fieldname[$k]);
			}*/
	    	}
			$i++;
			
			$rs->MoveNext();
		}
	
		return $datos;
	}
	
	/**
	 * calcula los registros iniciales y paginas
	 */
	private function Calcular(){
	
		$this->registro_inicial = (($this->pagina_actual -1) * $this->registros_x_pagina);
		
		
		#Si la pagina no es la primera muestro la anterior
		if($this->pagina_actual > 1){
			$this->pagina_anterior = $this->pagina_actual-1;
		}else {
			$this->pagina_anterior = 1;
		}

		#######################################################################
		# Valido que paginas mostrar segun la posicion en la que me encuentre #
		#######################################################################


		$dif = ceil($this->paginas_x_pagina/2);
		$Varleft = $this->pagina_actual - $dif;
		$VarRight = $this->pagina_actual + $dif;

		if ($this->pagina_actual <= $this->paginas_x_pagina && $this->pagina_actual>=1){

			if ($this->pagina_actual > $dif && $this->total_paginas > $this->paginas_x_pagina){
				$cuenta = $this->pagina_actual + $dif;
				$i = ($cuenta - $this->paginas_x_pagina) +1;
			}else{
				$i = 1;
				$cuenta = $this->paginas_x_pagina;
					if ($this->total_paginas < $this->paginas_x_pagina){
						$cuenta = $this->total_paginas;
					}
			}

		}else{

			$i = $Varleft +1;
			$cuenta = $VarRight;
		}

		if ($cuenta > $this->total_paginas){
			$cuenta = $this->total_paginas;
			$i = ($cuenta - $this->paginas_x_pagina)+1;
		}
			
		$this->pagina_inicial = $i;
		$this->pagina_fin = $cuenta;	
		
		
		#Si la pagina no es la ultima muestro la siguiente
		if(($this->pagina_actual+1) > $this->total_paginas){
			$this->pagina_siguiente = 1;
		}else{
			$this->pagina_siguiente = $this->pagina_actual+1;
		}
			
	
	}
	
	public function GetPaginaInicial (){
		return $this->pagina_inicial;
	}
	
	public function GetPaginaFin(){
		return $this->pagina_fin;
	}
	
	public function GetTotalPaginas(){
		return $this->total_paginas;
	}
	
	public function GetTotalRegstros(){
		return $this->total_registros;
	}
	
	public function GetPaginaAnterior(){
		return $this->pagina_anterior;
	}
	
	public function GetPaginaSiguiente(){
		return $this->pagina_siguiente;
	}
	
	/**
	 * Retorna el paginado, directamente el html
	 */
	public function GetPaginado(){
		global $var_url;
		
		if ($this->total_registros<=0) { return "";}
		
		$datos=array_merge($_GET,$_POST);
		
		$paginado ="";
		if ($this->total_paginas>1){
			
			
			$paginado .="<center><div class='caja_paginador clearfix'>";
			$paginado .="<div class='paginador clearfix'>";
			
			if ($this->pagina_actual>1){
				$datos['p'] = $this->pagina_anterior;
				$paginado .="<div class='anterior'><a href='".$var_url.funciones::base()->getHref(PAGINA,$datos)."'> Anterior</a></div>";
			}else {
				$paginado .="<div class='anterior'><a href='#' onclick='return false'> Anterior</a></div>";
			}
			
			$paginado .="<div class='numeros clearfix'><center>";

			for ($i=$this->pagina_inicial; $i<=$this->pagina_fin; $i++){
				
				if ($i==$this->pagina_actual){
					$paginado .="<div class='nro'><span>".$i."</span></div>";
				}else {
					$datos['p'] = $i;
					$paginado .="<div class='nro'><a href='".$var_url.funciones::base()->getHref(PAGINA,$datos)."' class='".self::CS_num_pag."'>".$i."</a></div>";
				}
			}
			
			$paginado .="</center></div>";
	
			if ($this->total_paginas>$this->pagina_actual){
				$datos['p']=$this->pagina_siguiente;
				$paginado .="<div class='siguiente'><a href='".$var_url.funciones::base()->getHref(PAGINA,$datos)."'>Siguiente </a></div>";
			}else {
				$paginado .="<div class='siguiente'><a href='#' onclick='return false'>Siguiente </a></div>";
			}
				
			$paginado .="</div>";
			$paginado .="</div></center>";
				
		}
		
	return $paginado;
	
	}
	
	/**
	 * Retorna el paginado, directamente el html
	 */
	public function GetPaginadoAmigable(){
		
		
		if ($this->total_registros<=0) { return "";}
		
		
		$paginado ="";
		if ($this->total_paginas>1){
			
			$ruta_amigable = $_SERVER['REQUEST_URI'];
			if (strstr($ruta_amigable,"pagina/")){
				$ruta_amigable = substr($ruta_amigable,0,strrpos($ruta_amigable,"pagina/"));
				
			}
			
			
			$ruta_amigable = preg_replace("/\/ES|\/EN|\/PT/","",$ruta_amigable);
			
			$paginado .="<center><div class='caja_paginador clearfix'>";
			$paginado .="<div class='paginador clearfix'>";
			
			
			foreach ($datos as $key=>$val){
				if ($key!="p"){
					$param_bus .="&".$key."=".urlencode($val);
				}
			}
			$param_bus = substr($param_bus,1);
			if($param_bus){
			$param_bus ="/".$param_bus;
			}
			
			if ($this->pagina_actual>1){
				$datos['p'] = $this->pagina_anterior;
				
				$paginado .="<div class='anterior'><a href='".$ruta_amigable."pagina/".$datos['p'].$param_bus."/'> anterior</a></div>";
			}else {
				$paginado .="<div class='anterior'><a href='#' onclick='return false'> anterior</a></div>";
			}
			
			$paginado .="<div class='numeros clearfix'><center>";
			
			for ($i=$this->pagina_inicial; $i<=$this->pagina_fin; $i++){
				
				if ($i==$this->pagina_actual){
					$paginado .="<div class='nro'><span class='".self::CS_num_pag_sel."'>".$i."</span></div>";
				}else {
					$datos['p'] = $i;
					$paginado .="<div class='nro'><a href='".$ruta_amigable."pagina/".$datos['p'].$param_bus."/' class='".self::CS_num_pag."'>".$i."</a></div>";
				}
			}
			
			$paginado .="</center></div>";
	
			if ($this->total_paginas>$this->pagina_actual){
				$datos['p']=$this->pagina_siguiente;
				$paginado .="<div class='siguiente'><a href='".$ruta_amigable."pagina/".$datos['p'].$param_bus."/'>siguiente </a></div>";
			}else {
				$paginado .="<div class='siguiente'><a href='#' onclick='return false'>siguiente </a></div>";
			}
				
			$paginado .="</div>";
			$paginado .="<div class=\"cantidad\">(".$this->total_registros." articulos)</div>";
			$paginado .="</div></center>";
		
		}
		
	return $paginado; 
	
	}
	
	
	/**
	 * Retorna el mini paginado, directamente el html
	 */
	public function GetMultimediaPaginado()
	{	
		global $var_url;
		
		if ($this->total_registros<=0) { return "";}
		
		$datos=array_merge($_GET,$_POST);
		
		$paginado ="";
		if ($this->total_paginas>1){
			
			$paginado .="<div class='paginator clearfix'>";
			
			if ($this->pagina_actual>1){
				$datos['p'] = $this->pagina_anterior;
				$paginado .="<a href='".$var_url.funciones::base()->getHref(PAGINA,$datos)."' class='prev ir'> prev</a>";
			}else {
				$paginado .="<a href='#' onclick='return false' class='prev ir'> prev</a>";
			}
			
			$paginado .="<ul>";

			for ($i=$this->pagina_inicial; $i<=$this->pagina_fin; $i++){
				
				if ($i==$this->pagina_actual){
					$paginado .="<li><a class='active' href='#'>".$i."</a></li>";
				}else {
					$datos['p'] = $i;
					$paginado .="<li><a href='".$var_url.funciones::base()->getHref(PAGINA,$datos)."'>".$i."</a></li>";
				}
			}
			
			$paginado .="</ul>";
	
			if ($this->total_paginas>$this->pagina_actual){
				$datos['p']=$this->pagina_siguiente;
				$paginado .="<a href='".$var_url.funciones::base()->getHref(PAGINA,$datos)."' class='next ir'>next </a>";
			}else {
				$paginado .="<a href='#' onclick='return false' class='next ir'>next </a>";
			}
				
			$paginado .="</div>";
				
		}
		
		return $paginado; 

	}
	
	/**
	 * Retorna el paginado, directamente el html
	 */
	public function GetMultimediaPaginadoAmigable(){
		
		
		if ($this->total_registros<=0) { return "";}
		
		
		$paginado ="";
		if ($this->total_paginas>1){
			
			$ruta_amigable = $_SERVER['REQUEST_URI'];
			if (strstr($ruta_amigable,"/pagina/")){
				$ruta_amigable = substr($ruta_amigable,0,strrpos($ruta_amigable,"/pagina/"));
				
			}
			
			
			$ruta_amigable = preg_replace("/\/ES|\/EN|\/PT/","",$ruta_amigable);
			
			$paginado .="<div class='paginator clearfix'>";
			
			foreach ($datos as $key=>$val){
				if ($key!="p"){
					$param_bus .="&".$key."=".urlencode($val);
				}
			}
			$param_bus = substr($param_bus,1);
			if($param_bus){
			$param_bus ="/".$param_bus;
			}
			
			if ($this->pagina_actual>1){
				$datos['p'] = $this->pagina_anterior;
				$paginado .="<a href='".$ruta_amigable."/pagina/".$datos['p']."/' class='prev ir'> prev</a>";
			}else {
				$paginado .="<a href='#' class='prev ir' onclick='return false'> prev</a>";
			}
			
			$paginado .="<ul>";
			
			for ($i=$this->pagina_inicial; $i<=$this->pagina_fin; $i++){
				
				if ($i==$this->pagina_actual){
					$paginado .="<li><a class='active' href='#'>".$i."</a></li>";
				}else {
					$datos['p'] = $i;
					$paginado .="<li><a href='".$ruta_amigable."/pagina/".$datos['p']."/' >".$i."</a></li>";
				}
			}
			
			$paginado .="</ul>";
	
			if ($this->total_paginas>$this->pagina_actual){
				
				$datos['p']=$this->pagina_siguiente;
				$paginado .="<a href='".$ruta_amigable."/pagina/".$datos['p']."/' class='next ir'>next </a>";
			}else {
				$paginado .="<a href='#' class='next ir' onclick='return false'>next </a>";
			}
				
			$paginado .="</div>";
		
		}
		
	return $paginado; 
	
	}
}?>