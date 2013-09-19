<?
  //include("../../config.php");
  include("config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $rss = $db->query("SELECT id,nombre FROM provincia ORDER BY departamento_id");
  $i = 0;
  while($rs = mysql_fetch_object($rss)){
	$dis = $db->query("SELECT id,nombre FROM distrito WHERE provincia_id=".$rs->id);
	while($di = mysql_fetch_object($dis)){
	  $arr[$i]["n"] = testsho($rs->nombre."-".$di->nombre);
	  $arr[$i]["i"] = $di->id;
	  $i++;
	}
  }
  $dat = json_encode($arr);
  $fp = fopen(DIR_PATH."assets/js/data/json_prodis.json","w+") or die("Problemas en la creacion");
  fputs($fp, $dat);
  fclose($fp);
?>