<?php
##############################################################################################################################################
##			information																														##
##############################################################################################################################################
##																																			##
##	description:		class combobox is for create three comboboxes for input date in a form												##
##	version:			1.0.0																												##
##	filename:			combobox.class.php																									##
##	author:				jürgen kaucher																										##
## 	author Email: 		juergen@kaucher.org																									##
##	created:			2002-09-11																											##
##	last modified:		2002-09-25																											##
##																																			##
##  thanks to richard sharp for the idea to create a blanco for non specific date (00.00.-)												##
##																																			##
##############################################################################################################################################
##			class combobox																													##
##############################################################################################################################################
##																																			##
class combobox {																															##
	function combobox ($input, $value) {																									##
		print("<select  class='campos' name=".$input."_d>\n");																								##
		if ($value=="today") {																												##
			$seldat = date("d");																											##
		} elseif ($value=="") {																												##
			$seldat = "-";																													##
		} else {																															##
			$seldat = substr($value,8,2);																									##
		}																																##
		for ($n=0; $n<=31; $n++) {																			if ($n==1) {
				$entry = "-";
			}
			if ($n<=9) {																													##
				$entry = "0".$n;																											##
			} else {																														##
				$entry = $n;																												##
			}																							
			
			if ($n==0) {
				$entry = "-";
			}
			if ($entry == $seldat) {																										##
				print("<option value=".$entry." selected>".$entry."\n");																	##
			} else {																														##
				print("<option value=".$entry.">".$entry."\n");																				##
			}																																##
		}																																	##
		print("</select> ");																												##
		print("<select class='campos' name=".$input."_m>\n");																								##
		if ($value=="today") {																												##
			$seldat = date("m");																											##
		} elseif ($value=="") {																												##
			$seldat = "-";																													##
		} else {																															##
			$seldat = substr($value,5,2);																									##
		}																																	##
		for ($n=0; $n<=12; $n++) {																											##
			if ($n<=9) {																													##
				$entry = "0".$n;																											##
			} else {																														##
				$entry = $n;																												##
			}																							
			
			if ($n==0) {
				$entry = "-";
			}

			if ($entry == $seldat) {																										##
				print("<option value=".$entry." selected>".$entry."\n");																	##
			} else {																														##
				print("<option value=".$entry.">".$entry."\n");																				##
			}																																##
		}																																	##
		print("</select> ");																												##
		print("<select class='campos' name=".$input."_y>\n");																								##
		if ($value=="today") {																												##
			$seldat = date("Y");																											##
		} elseif ($value=="") {																												##
			$seldat = "0000";																												##
		} else {																															##
			$seldat = substr($value,0,4);																									##
		}																																	##
		$entry = "----";																													##
		if ($entry == $seldat) {																											##
			print("<option value=".$entry." selected>".$entry."\n");																		##
		} else {																															##
			print("<option value=".$entry.">".$entry."\n");																					##
		}																																	##		
		for ($n=1930; $n<=2006; $n++) {																										##
			$entry = $n;																													##
			if ($entry == $seldat) {																										##
				print("<option value=".$entry." selected>".$entry."\n");																	##
			} else {																														##
				print("<option value=".$entry.">".$entry."\n");																				##
			}																																##
		}																																	##
		print("</select>\n");																												##
	}																																		##
}																																			##
##																																			##
##############################################################################################################################################
?>