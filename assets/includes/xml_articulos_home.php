<?
  include("../../config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $dom = new domDocument("1.0");
  
  $articulos = $dom->createElement("articulos");
  $dom->appendChild($articulos);
  
  //ARTICULOS
  $arts = $db->query("SELECT id,titulo,urltitulo,sumilla,imagen FROM articulo WHERE estado='1' LIMIT 0,3");
  while($art = mysql_fetch_object($arts)){
	$ntim = str_replace('"','\'',testsho($art->titulo));
	$nsml = substr(testsho($art->sumilla),0,106);
	$nlnk = URL_PATH.'articulo/'.$art->id.'/'.$art->urltitulo;
	$ntit = '<a href="'.$nlnk.'">'.testsho($art->titulo).'</a>';
	$nimg = '<a href="'.$nlnk.'"><img src="'.URW_PATH.'imagenes/articulos/thumbs/'.$art->imagen.'" width="128" alt="'.$ntim.'" title="'.$ntim.'" /></a>';
	$itm = $dom->createElement("itm"); $articulos->appendChild($itm);
	$tit = $dom->createElement("tit"); $itm->appendChild($tit); $tit->appendChild($dom->createCDATASection($ntit));
	$sml = $dom->createElement("sml"); $itm->appendChild($sml); $sml->appendChild($dom->createTextNode($nsml));
	$img = $dom->createElement("img"); $itm->appendChild($img); $img->appendChild($dom->createCDATASection($nimg));
  }
  $dom->save(DIR_PATH."assets/xml/xml_articulos_home.xml");
?>