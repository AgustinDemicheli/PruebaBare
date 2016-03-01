<?php
#########################################################
#	GiveMeEnergy Projects				#
#	Engine by []==ThoR®				#
#							#
#	contacts: kender@fastwebnet.it			#
#							#
#							#
#########################################################

/*
	$Id: class.makeImg.php,v 1.11 2005/03/09 16:26:06 thor Exp $
*/

/*
EXAMPLE

$mkImg->imgSource("img/tolkien5.png");
$mkImg->Message("Hi All!");
$mkImg->Font(PATH/TO/FONT/verdana.ttf");
$mkImg->FontSize(12);
$mkImg->Coordinate(5,5);
$mkImg->Angle(0);
$mkImg->Colors(0 0 0);
$mkImg->Shadow(255 255 255);
$mkImg->ImgDest("img/tolkien5_saved.png");
$finalImg	= $mkImg->WriteTXT(25,$centerTxt);

*/

define("_AVERAGE_WIDTHFONT",0.8);		//	

class putTxtOnImg	{

	Function imgSource($img)	{
		$img	= (!$img)	? trigger_error("NoImage specified") : $img;
		$this->imgSource	= $img;
	}

	##	This is the new name of the img with Msg	##
	Function ImgDest($img)	{
		$img	= (!$img)	? "new.jpg" : $img;
		$this->newImg	= $img;
	}
	##	The Message	##
	Function Message($msg)	{
		$msg	= (!$msg)	? "" : $msg;
		$this->message	= $msg;
	}

	##	Font Size	##
	Function FontSize($fsize)	{
		$fsize	= (!$fsize)	? 5 : $fsize;
		$this->fontSize	= $fsize;
	}

	##	If you want to use a font TTF	##
	Function Font($font)	{
		If (!file_exists($font))	{
			die("Font doesn't exists: ".$font);
		}
		##	We can try to use DOCUMENT_ROOT, but don't know if it works	##
		//$font	= $DOCUMENT_ROOT."path/to/".$font;
		$this->font	= $font;
	}

	##	Where to put text on the image	##
	Function Coordinate($x,$y)	{
		$x	= (!$x)	? 0 : $x;
		$y	= (!$y)	? 0 : $y;
		$this->x	= $x;
		$this->y	= $y;
	}

	##	If you want to rotate text		##
	Function Angle($angle)	{
		$angle	= (!$angle)	? 0 : $angle;
		$this->angle	= $angle;
	}

	##	Color of the text				##
	Function Colors($colors)	{
		$colors	= (count($colors) <= 0)	? "0 0 0" : $colors;
		$colors	= explode(" ",$colors);
		$this->colorR	= $colors[0];
		$this->colorG	= $colors[1];
		$this->colorB	= $colors[2];
	}

	##	Shadow Color of the Text		##
	Function Shadow($cols)	{
		$colors	= (count($cols) <= 0)	? "0 0 0" : $cols;
		$colors	= explode(" ",$colors);
		$this->colorSR	= $colors[0];
		$this->colorSG	= $colors[1];
		$this->colorSB	= $colors[2];
		$this->colorShadow	= 1;
	}

	//	This function try to put the sentence on 2 or more lines if it's longer than the width of the image
	//	This is not accurate, and maybe it doesn't work well while using "centered" mode
	//	@private
	function _preventHidden($img,$padding)	{
		$infoImg	= GetImageSize($img);
		$imgWidth	= $infoImg[0];
		//$maxChars	= $imgWidth - $padding;			$this->x
		$maxChars	= $imgWidth - $this->x;
		$Message	= Array();
		//echo "".$this->font."<br>";
		//ImageLoadFont($this->font);
		//echo $this->fontSize." => ".ImageFontWidth($this->fontSize)." | ";
		if ($maxChars > 0) { 
			If (is_array($this->message))	{
				foreach ($this->message as $kMessage=>$vMessage)	{
					$chars		= StrLen($vMessage);
					//$str_width	= ImageFontWidth($this->fontSize) * $chars;
					$str_width	= ($this->fontSize * _AVERAGE_WIDTHFONT) * $chars;
					//echo $str_width."<br>";
					$newMessage  = Array();
					if ($str_width > $maxChars) { 
						//echo "OK: ".$phrases[$a]." ".$chars."<hr>";
						$newSentence	= "";
						$newLen			= 0;
						$singleWord		= Explode(" ",$vMessage);
						foreach ($singleWord as $k=>$v) {
							//echo "SingleWord: ".$v."<br>";
							//$charLenWord = StrLen($v);
							if ($newLen <= $maxChars) {
								$newSentence	.= $v." ";
								//$newLen			= ImageFontWidth($this->fontSize) * StrLen($newSentence);
								$newLen			= ($this->fontSize * _AVERAGE_WIDTHFONT) * StrLen($newSentence);
								//echo "NewSentenceAdd: ".$newSentence." (".$newLen.")<hr>";
							} else {
								//echo "NewSentenceInArray: ".$newSentence." ".$newLen." ".$maxChars."<hr>";
								array_push($Message,$newSentence);
								$newSentence	= $v." ";
								$newLen			= 0;
								//echo "START NewSentenceAdd: ".$newSentence." ".$newLen." ".$maxChars."<hr>";
							}
						}
						array_push($Message,$newSentence);
					} else {
						//echo "< ".$maxChars.": ".$phrases[$a]."<hr>";
						array_push($Message,$vMessage);
					}
				}		##	=>	end foreach message
			} else {
				$chars		= StrLen($this->message);
				//$str_width	= ImageFontWidth($this->fontSize) * $chars;
				$str_width	= ($this->fontSize * _AVERAGE_WIDTHFONT) * $chars;
				$newMessage  = Array();
				if ($str_width > $maxChars) { 
					//echo "OK: ".$phrases[$a]." ".$chars."<hr>";
					$newSentence	= "";
					$newLen			= 0;
					$singleWord		= Explode(" ",$this->message);
					foreach ($singleWord as $k=>$v) {
						//echo "SingleWord: ".$v."<br>";
						//$charLenWord = StrLen($v);
						if ($newLen <= $maxChars) {
							$newSentence	.= $v." ";
							//$newLen			= ImageFontWidth($this->fontSize) * StrLen($newSentence);
							$newLen			= ($this->fontSize * _AVERAGE_WIDTHFONT) * StrLen($newSentence);
							//echo "NewSentenceAdd: ".$newSentence." (".$newLen.")<hr>";
						} else {
							//echo "NewSentenceInArray: ".$newSentence." ".$newLen." ".$maxChars."<hr>";
							array_push($Message,$newSentence);
							$newSentence	= $v." ";
							$newLen			= 0;
							//echo "START NewSentenceAdd: ".$newSentence." ".$newLen." ".$maxChars."<hr>";
						}
					}
					array_push($Message,$newSentence);
				} else {
					//echo "< ".$maxChars.": ".$phrases[$a]."<hr>";
					array_push($Message,$this->message);
				}
				//array_push($Message,$this->message);
			}			##	=>	end if array
		} else {
			//$Message	= $this->message;
			If (is_array($this->message))	{
				foreach ($this->message as $kMessage=>$vMessage)	{
					array_push($Message,$vMessage);
				}
			} else {
				array_push($Message,$this->message);
			}
		}				##	=>	end if maxchars > 0
		return $Message;
	}

	//	This function try to center the Text in the Image (x & y)
	//	@private
	function _centerText($x)	{
		//$font_height	= ImageFontHeight($this->fontSize);
		//$font_width	= ImageFontWidth($this->fontSize);
		$img			= $this->imgSource;
		$infoImg		= GetImageSize($img);
		$tempX			= "";
		$imgWidth		= "";
		$chars			= "";
		$imgWidth		= $infoImg[0];
		$imgHeight		= $infoImg[1];
		$chars			= StrLen($this->message[$x]);
		$chars1			= 0;
		//$tempX			= $tempX_c;
		//$tempY			= $tempY_c;
		if ($chars1 < $chars)	{
			$chars1			= $chars;
			$str_width		= ImageFontWidth($this->fontSize) * $chars;
			$tempX_c		= ($imgWidth / 2) - ($str_width / 2);
			$tempX			= $tempX_c;
		}
		$str_height		= ImageFontHeight($this->fontSize) * count($this->message);
		$tempY_c		= ($imgHeight / 2) - ($str_height / 4) - 7;
		$tempY			= $tempY_c;

		$this->tempX	= $tempX;
		$this->tempY	= $tempY;

	}

	function WriteTXT($perLine=0,$center=0,$padding=100)	{
		$img		= $this->imgSource;
		$fileType	= SubStr($img,-3);
		//Header("Content-type: image/png");
		$incrementLine	= 0;
		if (!isset($img) || $img == "")	die("no image selected");
		$infoImg	= GetImageSize($img);
		$alt		= "";

		//	Try to put sentence on 2 or more rows if longer than the image
		$this->message	= $this->_preventHidden($img,$padding);

		switch ($fileType)	{
			case "png":
	/*	PNG	*/
				$im			= ImageCreateFromPNG($img);
				$text_color	= ImageColorAllocate($im, $this->colorR, $this->colorG, $this->colorB);
				If (isset($this->colorShadow) && $this->colorShadow == 1)	{
					$shadow	= ImageColorAllocate($im, $this->colorSR, $this->colorSG, $this->colorSB);
				}
				If (is_array($this->message))	{
					//	patch for 2 rows	//
					for($addLine=0;$addLine<count($this->message);$addLine++)	{
						$this->y	= $this->y-7;
					}
					$this->y	= ($this->y < 0)	? 0 : $this->y;
					for($x = 0; $x < count($this->message); $x++)	{

						//	try to center the message	//
						//	font 14 is ~14px			//
						If ($center == 1)	{
							$this->_centerText($x);
							$tempX	= $this->tempX;
							$tempY	= $this->tempY;
						} else {
							$tempX	= $this->x;
							$tempY	= $this->y;
						}

						If ($this->font != "")	{
							If (isset($this->colorShadow) && $this->colorShadow == 1)	{
								imagettftext($im, $this->fontSize, $this->angle, ($tempX+2), (($tempY+$incrementLine)+2), $shadow, $this->font,$this->message[$x]);
							}
							imagettftext($im, $this->fontSize, $this->angle, $tempX, ($tempY+$incrementLine), $text_color, $this->font,$this->message[$x]);
						} else {
							If (isset($this->colorShadow) && $this->colorShadow == 1)	{
								ImageString($im,$this->fontSize,($tempX+2), (($tempY+$incrementLine)+2),$this->message[$x],$text_color);
							}
							ImageString($im,$this->fontSize,$tempX, ($tempY+$incrementLine),$this->message[$x],$text_color);
						}
						$incrementLine	= $incrementLine + $perLine;
					}
				} else {

					If ($center == 1)	{
						$this->_centerText($x);
						$tempX	= $this->tempX;
						$tempY	= $this->tempY;
					} else {
						$tempX	= $this->x;
						$tempY	= $this->y;
					}

					If ($this->font != "")	{
						If (isset($this->colorShadow) && $this->colorShadow == 1)	{
							imagettftext($im, $this->fontSize, $this->angle, ($tempX+2), (($tempY+$incrementLine)+2), $shadow, $this->font,$this->message);
						}
						imagettftext($im, $this->fontSize, $this->angle, $tempX, $tempY, $text_color, $this->font,$this->message);
					} else {
						If (isset($this->colorShadow) && $this->colorShadow == 1)	{
							ImageString($im,$this->fontSize,($tempX+2), (($tempY+$incrementLine)+2),$this->message,$text_color);
						}
						ImageString($im,$this->fontSize,$tempX,$tempY,$this->message,$text_color);
					}
				}
				If (isset($this->newImg))	{
					imagePNG($im,$this->newImg);
				} else {
					imagePNG($im);
				}
			break;
			case "jpg":
	/*	JPG	*/
				$im			= ImageCreateFromJPEG($img);
				$text_color	= ImageColorAllocate($im, $this->colorR, $this->colorG, $this->colorB);
				If ($this->colorShadow == 1)	{
					$shadow	= ImageColorAllocate($im, $this->colorSR, $this->colorSG, $this->colorSB);
				}
				If (is_array($this->message))	{
					//	patch for 2 rows	//
					for($addLine=0;$addLine<count($this->message);$addLine++)	{
						$this->y	= $this->y-5;
					}
					$this->y	= ($this->y < 0)	? 0 : $this->y;
					for($x = 0; $x < count($this->message); $x++)	{

						//	try to center the message	//
						//	font 14 is ~14px			//
						If ($center == 1)	{
							$this->_centerText($x);
							$tempX	= $this->tempX;
							$tempY	= $this->tempY;
						} else {
							$tempX	= $this->x;
							$tempY	= $this->y;
						}

						If ($this->font != "")	{
							If (isset($this->colorShadow) && $this->colorShadow == 1)	{
								imagettftext($im, $this->fontSize, $this->angle, ($tempX+2), (($this->y+$incrementLine)+2), $shadow, $this->font,$this->message[$x]);
							}
							imagettftext($im, $this->fontSize, $this->angle, $tempX, ($this->y+$incrementLine), $text_color, $this->font,$this->message[$x]);
						} else {
							If (isset($this->colorShadow) && $this->colorShadow == 1)	{
								ImageString($im,$this->fontSize,($tempX+2), (($this->y+$incrementLine)+2),$this->message[$x],$text_color);
							}
							ImageString($im,$this->fontSize,$tempX, ($this->y+$incrementLine),$this->message[$x],$text_color);
						}
						$incrementLine	= $incrementLine + $perLine;
					}
				} else {

					If ($center == 1)	{
						$this->_centerText($x);
						$tempX	= $this->tempX;
						$tempY	= $this->tempY;
					} else {
						$tempX	= $this->x;
						$tempY	= $this->y;
					}

					If ($this->font != "")	{
						If (isset($this->colorShadow) && $this->colorShadow == 1)	{
							imagettftext($im, $this->fontSize, $this->angle, ($tempX+2), (($this->y+$incrementLine)+2), $shadow, $this->font,$this->message);
						}
						imagettftext($im, $this->fontSize, $this->angle, $tempX, $this->y, $text_color, $this->font,$this->message);
					} else {
						If (isset($this->colorShadow) && $this->colorShadow == 1)	{
							ImageString($im,$this->fontSize,($tempX+2), (($this->y+$incrementLine)+2),$this->message,$text_color);
						}
						ImageString($im,$this->fontSize,$tempX,$this->y,$this->message,$text_color);
					}
				}
				If (isset($this->newImg))	{
					imageJPEG($im,$this->newImg);
				} else {
					imageJPEG($im);
				}
			break;
		}
		If (isset($this->newImg))	{
			$alt	= "immagine ritoccata";
			//echo "<img src=\"".$this->newImg."\" alt=\"".$alt."\">";
			$finalImg	= "<img src=\"".$this->newImg."\" alt=\"".$alt."\">";
			return $finalImg;
		} else {
			Header("Content-type: image/png");
		}
		ImageDestroy($im);
	}
}
?>