<?php
/*
	Author: Jonás Delgado Mesa             14 - 04 - 2006
	Version: 1.0
	E-Mail: yohnah@gmail.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

	For most information see LICENSE.txt

	This library is to create the RSS 2.0 code. To create a new object
	to make $object= new RSS ('title','link','description'). This three parameters
	are the minimum to make run the RSS generator. Next, you have to create a items
	information. It's simple, $object -> item("title","link","description") where this
	parameters are the minimum to show a item RSS information. Every $object -> item is
	a new item RSS information.

	Also, it's posible to use other RSS tags, like language, simplely to write $object -> nametag();. Example
	$object -> language('en-en');
	It's posible with Channel options and Items options. Look RSS 2.0 specification to look some posibilities
	http://feedvalidator.org/docs/rss2.html

	A example:

	$RSS = new RSS ("Webpage name","http://www.example.com/","This is a RSS test");
	$RSS -> language(en-en);
	$RSS -> webmaster("guy@example.com");
	$RSS -> item("Document name 1","http://www.example.com/text1.htm","This is a document test 1");
	$RSS -> author("guy");
	$RSS -> item("Document name 2","http://www.example.com/text2.htm","This is a document test 2");
	$RSS -> author("guy2");
	$RSS -> item("Document name 3","http://www.example.com/text3.htm","This is a document test 3");
	$RSS -> author("guy3");
	.
	.
	.
	dispose($RSS) //This option is optional but its the best.

	Esta librería es para crear un código RSS 2.0. Para crear un nuevo objeto hacer $objeto = new RSS ('título','enlace','descripción'). Estos tres parámetros son lo minimos para hacer funcionar el generador RSS.
	A continuación, para crear bloques de items simplemente ejecutar $objeto -> item ('título','enlace','descripción')
	por cada item requerido.

	También es posible usar otros tags de RSS, como language, simplemente escribir $objeto -> nombredeltag(); Pôr ejemplo
	$objeto -> language('es-es');
	Es posible usar tags de las opciones de Channel y de Items. Para ello mirar las especificaciones del RSS 2.0 en la web
	http://feedvalidator.org/docs/rss2.html

	Ejemplo de uso:

	$RSS = new RSS ("Nombre de la web","http://www.example.com/","Esto es una prueba de RSS");
	$RSS -> language(es-es);
	$RSS -> webmaster("chico@example.com");
	$RSS -> item("Nombre del documento 1","http://www.example.com/text1.htm","Esto es una prueba de documento 1");
	$RSS -> author("chico 1");
	$RSS -> item("Nombre del documento 2","http://www.example.com/text2.htm","Esto es una prueba de documento 2");
	$RSS -> author("chico 2");
	$RSS -> item("Nombre del documento 3","http://www.example.com/text3.htm","Esto es una prueba de documento 3");
	$RSS -> author("chico 3");
	.
	.
	.
	dispose($RSS) //ésto es opcional, pero es mejor hacerlo
*/
/* Class to generate the channel info*/
/* Clase para generar la información del channel */
Class RSSChannel {
	private $channel_=array();
	private $version_;
	private $encoding_;
	protected function __construct ($title,$link,$description,$version="2.0",$encoding){
		$this->channel_['title']=$title;
		$this->channel_['link']=$link;
		$this->channel_['description']=$description;
		$this->version_=$version;
		$this->encoding_=$encoding;
	}
	protected function __destruct(){
		/*
		?></channel><?
		?></rss><?*/
	}
	protected function runChannel(){
		
		//$dato = "header('Content-Type: text/xml')\n";
		
		$dato = "<?xml version=\"1.0\" encoding=\"".$this->encoding_."\"?>\n";
		$dato .="<rss version=\"".$this->version_."\">\n";
		$dato .="<channel>\n";
		
		foreach ($this->channel_ as $key => $value){
			
			$dato .="<".$key.">".$value."</".$key.">\n";
		}
		
		return $dato;
	}
	public function language($language="es-ar"){
		$this->channel_['language']=$language;
	}
	public function copyright($copyright){
		$this->channel_['copyright']=$copyright;
	}
	public function managingEditor($managingEditor){
		$this->channel_['managingEditor']=$managingEditor;
	}
	public function webMaster($webMaster){
		$this->channel_['webMaster']=$webMaster;
	}
	public function pubDate($pubDate){
		$this->channel_['pubDate']=$pubDate;
	}
	public function lastBuildDate($lastBuildDate){
		$this->channel_['lastBuildDate']=$lastBuildDate;
	}
	public function category($category){
		$this->channel_['category']=$category;
	}
	public function generator($generator){
		$this->channel_['generator']=$generator;
	}
	public function docs($docs){
		$this->channel_['docs']=$docs;
	}
	public function cloud($cloud){
		$this->channel_['cloud']=$cloud;
	}
	public function ttl($ttl){
		$this->channel_['ttl']=$ttl;
	}
	public function image($title="",$url="",$link=""){
		
			
		$this->channel_['image']="<title>".$title."</title><url>".$url."</url><link>".$link."</link>";
	
	
	}
	public function rating($rating){
		$this->channel_['rating']=$rating;
	}
	public function textinput($textinput){
		$this->channel_['textinput']=$textinput;
	}
	public function skipHours($skipHours){
		$this->channel_['skipHours']=$skipHours;
	}
	public function skipDays($skipDays){
		$this->channel_['skipDays']=$skipDays;
	}
}

/* Class to generate the items info*/
/* Clase para generar la información de los items */
Class RSSitem extends RSSChannel {
	private $item_ = array();
	private $count_ = 0;
	protected function __construct ($title,$link,$description,$version="2.0",$encoding){
		parent::__construct($title,$link,$description,$version,$encoding);
	}
	protected function __destruct(){
		parent::__destruct();
	}
	protected function runItem(){
		$dato =parent::runChannel();
		foreach ($this->item_ as $keys => $items){
			$dato .="<item>\n";
			foreach ($items as $key => $value){
				$dato .="<".$key.">".$value."</".$key.">\n";
			}
			$dato .="</item>\n";
		}
		return $dato;
	}
	public function item($title,$link,$description){
		$this->count_++;
		$this->item_[$this->count_]=array("title"=>str_replace("&","&amp;",$title),"link"=>$link,"description"=>str_replace("&","&amp;",$description));
	}
	public function author ($author){
		$this->item_[$this->count_]['author'] = $author;
	}
	public function category ($category){
		$this->item_[$this->count_]['category'] = $category;
	}
	public function comments ($comments){
		$this->item_[$this->count_]['comments'] = $comments;
	}
	public function enclosure ($enclosure){
		$this->item_[$this->count_]['enclosure'] = $enclosure;
	}
	public function guid ($guid){
		$this->item_[$this->count_]['guid'] = $guid;
	}
	public function pubDate ($pubDate){
		$this->item_[$this->count_]['pubDate'] = $pubDate;
	}
	public function source ($source){
		$this->item_[$this->count_]['source'] = $source;
	}
}

/* Class to generate the RSS */
/* Clase para generar el RSS */
Class RSS extends RSSitem{
	public function __construct ($title,$link,$description,$encoding="UTF-8",$version="2.0"){
		parent::__construct($title,$link,$description,$version,$encoding);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function runRSS(){
		return $this->runItem();
	}
}

?>
