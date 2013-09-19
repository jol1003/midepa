<?
  /*include("../../config.php");
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);*/
  
  $dom = new domDocument("1.0");
  
  $inmuebles = $dom->createElement("inmuebles");
  $dom->appendChild($inmuebles);
  
  //INMUEBLES
  $inmx = $db->query("SELECT id,nombre,urlnombre,imagen FROM inmueble WHERE estado='1' AND categoria='1' ORDER BY fechacreacion DESC LIMIT 0,6");
  while($inx = mysql_fetch_object($inmx)){
	$tim = str_replace('"','\'',$inx->nombre);
	$lnk = URL_PATH.'inmueble-detalle/'.$inx->id.'/'.$inx->urlnombre;
	$ite = '<div><a href="'.$lnk.'"><img src="'.URW_PATH.'imagenes/inmuebles/'.$inx->imagen.'" width="182" height="132" /></a></div><h3><a href="'.$lnk.'">'.testsho($inx->nombre).'</a></h3>';
	$itm = $dom->createElement("itm"); $inmuebles->appendChild($itm); $itm->appendChild($dom->createCDATASection($ite));
  }
  $dom->save(DIR_PATH."assets/xml/xml_inmuebles_home.xml");
?>