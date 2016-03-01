<?
/**
 * funcciones de fechas
 * @author Gustavo Fiasche
 * 
 * 2011
 */
class FuncionesFechas {
	
	/**
	 * retorna un int de la diferencia (timestamp)
	 * @param datetime $primera
	 * @param datetime $segunda
	 */
	private function getDiferenciaTimestamp($primera, $segunda) {
		
		$fecha1 = self::separarfecha ( $primera );
		$fecha2 = self::separarfecha ( $segunda );
		
		$inicio = mktime ( $fecha1 ['hora'], $fecha1 ['minutos'], $fecha1 ['segundos'], $fecha1 ['mes'], $fecha1 ['dia'], $fecha1 ['ano'] );
		$fin = mktime ( $fecha2 ['hora'], $fecha2 ['minutos'], $fecha2 ['segundos'], $fecha2 ['mes'], $fecha2 ['dia'], $fecha2 ['ano'] );
		$resultado = $inicio - $fin;
		
		return $resultado;
	}
	
	/**
	 * retorna la diferencia de dias. (int)
	 * @param datetime $primera
	 * @param date $segunda
	 */
	public function getDiferencia_dias($primera, $segunda) {
		$resultado = self::getDiferenciaTimestamp ( $primera, $segunda );
		return floor ( abs ( $resultado / 86400 ) );
	}
	
	/**
	 * retorna la diferencia de horas. (int)
	 * @param datetime $primera
	 * @param datetime $segunda
	 */
	public function getDiferencia_horas($primera, $segunda) {
		$resultado = self::getDiferenciaTimestamp ( $primera, $segunda );
		return abs ( $resultado / 3600 );
	}
	
	/**
	 * retorna el último dia del mes (int)
	 * @param date $fecha
	 */
	public function getUltimodiadelmes($fecha) {
		
		$vFecha = self::separarfecha ( $fecha );
		$timestamp = mktime ( 0, 0, 0, $vFecha ['mes'], 1, $vFecha ['ano'] );
		return date ( "t", $timestamp );
	}
	
	/**
	 * retorna una fecha con formato
	 * @param date $fecha
	 * @param string $idioma
	 * @param string $estilo
	 */
	public function getFormatoUnaFecha($fecha, $idioma = "", $estilo = "completa") {
		
		$ano=0;
		
		if ($fecha != "") {
			
			if (strstr("/",$fecha)){
				list ($dia,$mes,$ano ) = explode("/",$fecha );
			}
			
			if (!$ano) {
				list ( $ano, $mes, $dia ) = explode("-",$fecha );
			}
			
			$timestamp = mktime ( 0, 0, 0, $mes, $dia, $ano );
			$diaSemana = date ( "w", $timestamp );
			
			if ($idioma == "") {
				
				$nombreDia = self::GetNombreDia ( $diaSemana, $idioma );
				$nombreMes = self::GetNombreMes ( $mes, $idioma );
				switch ($estilo) {
					case "completa" :
						return $dia . " de " . $nombreMes . " de " . $ano;
						break;
					case "simple" :
						return $dia . "." . $mes . "." . $ano;
						break;
				}
			
			} else if ($idioma == "_pt") {
				$nombreDia = self::GetNombreDia ( $diaSemana, $idioma );
				$nombreMes = self::GetNombreMes ( $mes, $idioma );
				switch ($estilo) {
					case "completa" :
						return $dia . " de " . $nombreMes . " de " . $ano;
						break;
					case "simple" :
						return $dia . "." . $mes . "." . $ano;
						break;
				}
			
			} else {
				$nombreDia = date ( "l", $timestamp );
				$nombreMes = date ( "F", $timestamp );
				switch ($estilo) {
					case "completa" :
						return $nombreMes . " " . $dia . ", " . $ano;
						break;
					case "simple" :
						if ($idioma == "_en") {
							return $ano . "." . $mes . "." . $dia;
						} else {
							return $mes . "." . $dia . "." . $ano;
						}
						break;
				}
			}
		}
	
	} //end MostrarFecha
	

	/**
	 * retorna con formato dos fechas
	 * @param date $fecha1
	 * @param date $fecha2
	 * @param string $lang
	 */
	function getformatoDosFecha($fecha1, $fecha2 = "", $lang = "") {
		
		$mes =0; $dia=0; $ano=0;
		$mes1 =0; $dia1=0; $ano1=0;
		
		list($anio,$mes,$dia) = explode("-",$fecha1);
		$timestamp = mktime(0,0,0,$mes,$dia,$ano);
		$diaSemana = date ( "w", $timestamp );
		
		list ( $anio1, $mes1, $dia1 ) = explode("-",$fecha2 );
		$timestamp1 = mktime (0,0,0,$mes1,$dia1,$ano1 );
		$diaSemana1 = date ( "w", $timestamp1 );
		
		if ($lang == "" || $lang == "_pt") {
			
			$nombreDia = self::getNombreDia ( $diaSemana, $lang );
			$nombreMes = strtolower (self::GetNombreMes ( $mes, $lang ) );
			
			$nombreDia1 = self::getNombreDia ( $diaSemana1, $lang );
			$nombreMes1 = self::getNombreMes ( $mes1, $lang );
			
			if ($fecha2 != "") {
				
				if ($mes == $mes1) {
					
					if ($anio == $anio1) {
						
						if (date ( "Y" ) == $anio) {
							
							if ($dia == $dia1) {
								//2008-05-01 2008-05-01
								$sfecha = $dia . " de " . $nombreMes;
							} else {
								//2008-05-01 2008-05-03
								$sfecha = $dia . " al " . $dia1 . " de " . $nombreMes;
							}
						
						} else {
							if ($dia == $dia1) {
								//2008-05-01 2008-05-01
								$sfecha = $dia . " de " . $nombreMes . " del " . $anio;
							} else {
								//2008-05-01 2008-05-03
								$sfecha = $dia . " al " . $dia1 . " de " . $nombreMes . " del " . $anio;
							}
						
						}
					
					} else {
						//2008-05-01 2009-05-01
						$sfecha = $dia . " de " . $nombreMes . " del " . $anio . " al " . $dia1 . " de " . $nombreMes1 . " del " . $anio1;
					}
				
				} else {
					
					if ($anio == $anio1) {
						if (date ( "Y" ) == $anio) {
							//2008-05-01 2008-06-01
							$sfecha = $dia . " de " . $nombreMes . " al " . $dia1 . " de " . $nombreMes1;
						} else {
							//2009-05-01 2009-06-01
							$sfecha = $dia . " de " . $nombreMes . " al " . $dia1 . " de " . $nombreMes1 . " del " . $anio;
						}
					
					} else {
						//2008-05-01 2009-06-01
						$sfecha = $dia . " de " . $nombreMes . " del " . $anio . " al " . $dia1 . " de " . $nombreMes1 . " del " . $anio1;
					}
				
				}
			
			} else {
				
				if (date ( "Y" ) == $anio) {
					$sfecha = $dia . " de " . $nombreMes;
				} else {
					$sfecha = $dia . " de " . $nombreMes . " del " . $anio;
				}
			
			}
		
		} else {
			
			$nombreDia = date ( "l", $timestamp );
			$nombreMes = date ( "F", $timestamp );
			
			$nombreDia1 = date ( "l", $timestamp1 );
			$nombreMes1 = date ( "F", $timestamp1 );
			
			if ($fecha2 != "") {
				
				if ($mes == $mes1) {
					
					if ($anio == $anio1) {
						
						if (date ( "Y" ) == $anio) {
							
							if ($dia == $dia1) {
								//2008-05-01 2008-05-01
								$sfecha = $nombreMes . " " . $dia;
							} else {
								//2008-05-01 2008-05-03
								$sfecha = $dia . " to " . $dia1 . " of " . $nombreMes;
							}
						
						} else {
							if ($dia == $dia1) {
								//2008-05-01 2008-05-01
								$sfecha = $nombreMes . " " . $dia . "," . $anio;
							} else {
								//2008-05-01 2008-05-03
								$sfecha = $dia . " to " . $dia1 . " of " . $nombreMes . ", " . $anio;
							}
						
						}
					
					} else {
						//2008-05-01 2009-05-01
						$sfecha = $nombreMes . " " . $dia . ", " . $anio . " to " . $nombreMes1 . " " . $dia1 . ", " . $anio1;
					}
				
				} else {
					
					if ($anio == $anio1) {
						if (date ( "Y" ) == $anio) {
							//2008-05-01 2008-06-01
							$sfecha = $nombreMes . " " . $dia . " to " . $nombreMes1 . " " . $dia1;
						} else {
							//2009-05-01 2009-06-01
							$sfecha = $nombreMes . " " . $dia . " to " . $nombreMes1 . " " . $dia1 . ", " . $anio;
						}
					
					} else {
						//2008-05-01 2009-06-01
						$sfecha = $nombreMes . " " . $dia . ", " . $anio . " to " . $nombreMes1 . " " . $dia1 . ", " . $anio1;
					}
				
				}
			
			} else {
				
				if (date ( "Y" ) == $anio) {
					$sfecha = $nombreMes . " " . $dia;
				} else {
					$sfecha = $nombreMes . " " . $dia . ", " . $anio;
				}
			
			}
		}
		
		return $sfecha;
	}
	
	/**
	 * retorna nombre del dia
	 * @param int $dia
	 * @param string $lang
	 */
	function getNombreDia($dia, $lang = "_es", $corto = "") {

		if ($lang == "_es" || $lang == "") {
			
			switch ($dia) {
				case "0" :
					return ($corto == "" ? "Domingo" : "Dom");
					break;
				case "1" :
					return ($corto == "" ? "Lunes" : "Lun");
					break;
				case "2" :
					return ($corto == "" ? "Martes" : "Mar");
					break;
				case "3" :
					return ($corto == "" ? "Mi&#233;rcoles" : "Mie");
					break;
				case "4" :
					return ($corto == "" ? "Jueves" : "Jue");
					break;
				case "5" :
					return ($corto == "" ? "Viernes" : "Vie");
					break;
				case "6" :
					return ($corto == "" ? "S&#225;bado" : "Sab");
					break;
			}
		}
		
		if ($lang == "_pt") {
			
			switch ($dia) {
				
				case "0" :
					return "Domingo";
					break;
				case "1" :
					return "Segunda-feira";
					break;
				case "2" :
					return "Terça-feira";
					break;
				case "3" :
					return "Quarta-feira";
					break;
				case "4" :
					return "Quinta-feira";
					break;
				case "5" :
					return "Sexta-feira";
					break;
				case "6" :
					return "S&#225;bado";
					break;
			}
		}
	
	}
	
	/**
	 * retorna el string del mes
	 * @param int $mes
	 * @param string $lang
	 * @param $corto
	 */
	function getNombreMes($mes, $lang = "_es", $corto = "") {
		if ($lang == "_es" || $lang == "") {
			
			switch ($mes) {
				case "1" :
					return ($corto == "" ? "enero" : "ene");
					break;
				case "2" :
					return ($corto == "" ? "febrero" : "feb");
					break;
				case "3" :
					return ($corto == "" ? "marzo" : "marz");
					break;
				case "4" :
					return ($corto == "" ? "abril" : "abr");
					break;
				case "5" :
					return ($corto == "" ? "mayo" : "may");
					break;
				case "6" :
					return ($corto == "" ? "junio" : "jun");
					break;
				case "7" :
					return ($corto == "" ? "julio" : "jul");
					break;
				case "8" :
					return ($corto == "" ? "agosto" : "ago");
					break;
				case "9" :
					return ($corto == "" ? "septiembre" : "sep");
					break;
				case "10" :
					return ($corto == "" ? "octubre" : "oct");
					break;
				case "11" :
					return ($corto == "" ? "noviembre" : "nov");
					break;
				case "12" :
					return ($corto == "" ? "diciembre" : "dic");
					break;
			}
		
		}
		
		if ($lang == "_pt") {
			
			switch ($mes) {
				case "1" :
					return ($corto == "" ? "janeiro" : "jan");
					break;
				case "2" :
					return ($corto == "" ? "fevereiro" : "feb");
					break;
				case "3" :
					return ($corto == "" ? "março" : "mar");
					break;
				case "4" :
					return ($corto == "" ? "abril" : "abr");
					break;
				case "5" :
					return ($corto == "" ? "maio" : "mai");
					break;
				case "6" :
					return ($corto == "" ? "junho" : "jun");
					break;
				case "7" :
					return ($corto == "" ? "julho" : "jul");
					break;
				case "8" :
					return ($corto == "" ? "agosto" : "ago");
					break;
				case "9" :
					return ($corto == "" ? "setembro" : "set");
					break;
				case "10" :
					return ($corto == "" ? "outubro" : "out");
					break;
				case "11" :
					return ($corto == "" ? "novembro" : "nov");
					break;
				case "12" :
					return ($corto == "" ? "dezembro" : "dez");
					break;
			}
		
		}
		
		if ($lang == "_en") {
			
			switch ($mes) {
				case "1" :
					return ($corto == "" ? "January" : "Jan");
					break;
				case "2" :
					return ($corto == "" ? "February" : "Feb");
					break;
				case "3" :
					return ($corto == "" ? "March" : "Mar");
					break;
				case "4" :
					return ($corto == "" ? "April" : "Apr");
					break;
				case "5" :
					return ($corto == "" ? "May" : "May");
					break;
				case "6" :
					return ($corto == "" ? "June" : "Jun");
					break;
				case "7" :
					return ($corto == "" ? "July" : "Jul");
					break;
				case "8" :
					return ($corto == "" ? "August" : "Aug");
					break;
				case "9" :
					return ($corto == "" ? "September" : "Sep");
					break;
				case "10" :
					return ($corto == "" ? "October" : "Oct");
					break;
				case "11" :
					return ($corto == "" ? "November" : "Nov");
					break;
				case "12" :
					return ($corto == "" ? "December" : "Dec");
					break;
			}
		
		}
	
	}
	
	/**
	 * adapta fecha por medio de zona horaria (Y-m-d H:i:s)
	 * @param datetime $fecha_hora
	 * @param $zona_horaria
	 * 
	 */
	function getFechaZonaHoraria($fecha_hora, $zona_horaria, $zona_horaria_server) {
		
		if ($fecha_hora and $fecha_hora != "0000-00-00 00:00:00") {
			
			######################################################
			#Wed Dec 21 11:35:56 GMT-03:00 2005
			#	ejemplo $zona_horaria seria la del usuario
			#	y $_SESSION['zona_horaria'] la del coach
			#	Este ej adapta la hora del usuario ala del coach
			#	ya que estan en distintos lugares-.
			######################################################
			

			$dif = (- 1 * $zona_horaria) + $zona_horaria_server;
			
			$vector = self::separarfecha ($fecha_hora);
			$fechaReal = date ( "Y-m-d H:i:s", mktime ( $vector ["hora"] + $dif, $vector ["minutos"], $vector ["segundos"], $vector ["mes"], $vector ["dia"], $vector ["ano"] ) );
		
		}
		
		return $fechaReal;
	
	}
	
	/**
	 * separa la fechas con hora
	 * pueder ser: 10/10/2010 o 2010-10-10
	 * para pasarle hora
	 * 10/10/2010 15:00 o 2010-10-10 12:00
	 * @param date $date
	 */
	public function separarfecha($date) {
		
		list ($Vdatebits,$Vhora) = explode (" ",$date);
		
		if (count ( $datebits = explode ( '/', $Vdatebits ) ) == 3) {
			$dia = intval ( $datebits [0] );
			$mes = intval ( $datebits [1] );
			$ano = intval ( $datebits [2] );
		} elseif (count ( $datebits = explode ( '-', $Vdatebits ) ) == 3) {
			$dia = intval ( $datebits [2] );
			$mes = intval ( $datebits [1] );
			$ano = intval ( $datebits [0] );
		}
		
		if (trim ( $Vhora )) {
			list ( $hora, $minuto, $segundo ) = explode (":",trim($Vhora));
		} else {
			$hora = 0;
			$minuto = 0;
			$segundo = 0;
		}
		return array ('dia' => (strlen ( $dia ) == 1 ? "0" . $dia : $dia), 'mes' => (strlen ( $mes ) == 1 ? "0" . $mes : $mes), 'ano' => $ano, 'hora' => $hora, 'minutos' => $minuto, 'segundos' => $segundo );
	}

}