<?
include("../includes/DB_Conectar.php");


if(isset($_GET["w"])) $width  = $_GET["w"]; 
if(isset($_GET["h"])) $height = $_GET["h"]; 

if(!isset($width)&&!isset($height)) die();

if(isset($_GET["id"])) $id    = $_GET["id"]; else die("Error en la imagen");

$sql = "select * from advf where advID = ".$_GET["id"];
$rs  = $conn->execute($sql);

$filename = $rs->field("advLink");
$bufFilename = explode(".",$filename);
$filename =  "../".$rs->field("advLink");
// Antes que nada nos fijamos si el thumb ya existe

list($widthor, $heightor, $type, $attr) = getimagesize($filename);

if(isset($width)&&!isset($height))
{
        $height = (int)($heightor * ($width/$widthor));
}
elseif(isset($height)&&!isset($width))
{
        $width = (int)($widthor * ($height/$heightor));
}
elseif(isset($height)&&isset($width))
{
        if($widthor >= $heightor)
        {
                $height = (int)($heightor * ($width/$widthor));
        }else
        {
                $width = (int)($widthor * ($height/$heightor));
        }
}

$thumbname ="../".$bufFilename[0]."_".$width."x".$height.".".(preg_match("/(png|gif|jpg)/i",$bufFilename[1])?"jpg":$bufFilename[1]);

if(!is_file("$thumbname"))
{
	if(preg_match("/png/i",$filename))
	{
		$im = imagecreatetruecolor ($width,$height);
		$or = imagecreatefrompng($filename);
		imagecopyresampled ( $im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
	}
	if(preg_match("/jpg/i",$filename)) 
	{
		$im = imagecreatetruecolor ($width,$height);
		$or = imagecreatefromjpeg($filename);
		imagecopyresampled ( $im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
	}
	if(preg_match("/gif/i",$filename)) 
	{
		$im = imagecreate ($width,$height);
		$or = imagecreatefromgif($filename);
		imagecopyresized ( $im, $or, 0, 0, 0, 0, $width, $height, $widthor, $heightor);
	}

	if(preg_match("/(png|gif|jpg)/i",$filename)) 
	{
		$im2 = imagecreatetruecolor ($width,$height);
		imagecopy($im2,$im,0,0,0,0,$width,$height);

		imagejpeg ($im2,$thumbname,100);
		imagedestroy($im);
		imagedestroy($or);
		imagedestroy($im2);
	}
/*	if(ereg("jpg",$filename))
	{
		imagejpeg ($im,$thumbname);
		imagedestroy($im);
		imagedestroy($or);
	}
//	if(ereg("gif",$filename)) 
//	{
//		imagegif ($im,$thumbname);
//		imagedestroy($im);
//		imagedestroy($or);
//	}*/
}
else {

}

//if(ereg("(png|gif)",$filename)) header ("Content-type: image/png");
if(preg_match("/(png|gif|jpg)/i",$filename)) header ("Content-type: image/jpeg");
//if(ereg("gif",$filename)) header ("Content-type: image/gif");

readfile("$thumbname");
die();
?>