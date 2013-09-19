<?
  include("../../config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $rss = $db->query("SELECT id,departamento_id,nombre FROM provincia ORDER BY departamento_id");
  $i = 0;
  while($rs = mysql_fetch_object($rss)){
	$arr[$i]["i"] = $rs->id;
	$arr[$i]["d"] = $rs->departamento_id;
	$arr[$i]["n"] = $rs->nombre;
	$i++;
  }
  $dat = json_encode($arr);
  $fp = fopen(DIR_PATH."assets/js/data/json_pro.json","w+") or die("Problemas en la creacion");
  fputs($fp, $dat);
  fclose($fp);
?>