<?
  /*include("../../config.php");
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);*/
  
  $dom = new domDocument("1.0");
  
  $empresas = $dom->createElement("empresas");
  $dom->appendChild($empresas);
  
  //CONSTRUCTORAS
  $emps = $db->query("SELECT id,nombrecomercial FROM empresa WHERE estado='1' AND empresatipo='1' LIMIT 0,6");
  $constructoras = $dom->createElement("constructoras"); $empresas->appendChild($constructoras);
  while($emp = mysql_fetch_object($emps)){
	$tim = str_replace('"','\'',$emp->nombrecomercial);
	$img = $db->fetchObject("SELECT imagen FROM usuario WHERE id=".$emp->id." LIMIT 0,1");
	$img='<a href="'.URL_PATH.'busqueda-inmuebles/?usrid='.$emp->id.'"><img src="'.URW_PATH.'imagenes/usuarios/'.$img->imagen.'" width="176" height="62" alt="'.$tim.'" title="'.$tim.'" /></a>';
	$itm = $dom->createElement("itm"); $constructoras->appendChild($itm); $itm->appendChild($dom->createCDATASection($img));
  }
  //AGENTES
  $emps = $db->query("SELECT id,nombrecomercial FROM empresa WHERE estado='1' AND empresatipo='2' LIMIT 0,6");
  $agentes = $dom->createElement("agentes"); $empresas->appendChild($agentes);
  while($emp = mysql_fetch_object($emps)){
	$tim = str_replace('"','\'',$emp->nombrecomercial);
	$img = $db->fetchObject("SELECT imagen FROM usuario WHERE id=".$emp->id." LIMIT 0,1");
	$img='<a href="'.URL_PATH.'busqueda-inmuebles/?usrid='.$emp->id.'"><img src="'.URW_PATH.'imagenes/usuarios/'.$img->imagen.'" width="176" height="62" alt="'.$tim.'" title="'.$tim.'" /></a>';
	$itm = $dom->createElement("itm"); $agentes->appendChild($itm); $itm->appendChild($dom->createCDATASection($img));
  }
  $dom->save(DIR_PATH."assets/xml/xml_empresas_home.xml");
?>