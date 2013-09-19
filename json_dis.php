<?
  //include("../../config.php");
  include("config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $rss = $db->query("SELECT id,provincia_id,nombre FROM distrito ORDER BY provincia_id");
  $i = 0;
  while($rs = mysql_fetch_object($rss)){
	$arr[$i]["i"] = $rs->id;
	$arr[$i]["p"] = $rs->provincia_id;
	$arr[$i]["n"] = $rs->nombre;
	$i++;
  }
  $dat = json_encode($arr);
  $fp = fopen(DIR_PATH."assets/js/data/json_dis.json","w+") or die("Problemas en la creacion");
  fputs($fp, $dat);
  fclose($fp);
?>