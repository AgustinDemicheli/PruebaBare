<?php

/*
GDF 2007

*/
class XmliseRss extends Xmlise
{

	var $v;


	function XmliseRss($xml){
		
		$this->v=$xml;
		
	}
	

	function GetRss(){

		
		$xml = Xmlise::xmlize($this->v); # where $data is the xml in the above section.

				
		$items = $xml["rss"]["#"]["channel"][0]["#"];

		//print '<pre>'; print_r($items); print '</pre>';


		foreach ($items as $key=>$valor){

			switch ($key){

				case "title":
					$itemsNot["rss"]["title"]=$valor[0]["#"];

					break;
				case "link":
					$itemsNot["rss"]["link"]=ansihtml($valor[0]["#"]);
					break;
				case "description":
					$itemsNot["rss"]["description"]=ansihtml($valor[0]["#"]);
					break;
				case "image":
					$itemsNot["rss"]["image"]["url"]=$valor[0]["#"]["url"][0]["#"];
					$itemsNot["rss"]["image"]["link"]=$valor[0]["#"]["link"][0]["#"];

					break;

				case "item":

					for ($i=0; $i<count($valor);$i++){

						$itemsNot["item"][$i]["title"]=ansihtml($valor[$i]["#"]["title"][0]["#"]);
						$itemsNot["item"][$i]["link"]=ansihtml($valor[$i]["#"]["link"][0]["#"]);
						$itemsNot["item"][$i]["description"]=ansihtml($valor[$i]["#"]["description"][0]["#"]);
						$itemsNot["item"][$i]["author"]=ansihtml($valor[$i]["#"]["author"][0]["#"]);
						$itemsNot["item"][$i]["pubdate"]=ansihtml($valor[$i]["#"]["pubDate"][0]["#"]);


					}

			}

		}
		
		//print '<pre>'; print_r($itemsNot); print '</pre>';
		return $itemsNot;
	}
}
?>
