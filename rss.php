<?
  include("config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  header("Content-Type: text/xml; charset=utf-8");
  print "<?xml version='1.0' encoding='UTF-8'?>\n";
  print "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>\n";
  print "<channel>\n";
  print "<title>Midepa.pe</title>\n";
  print "<link>http://www.midepa.pe/</link>\n";
  print "<description>Inmobiliaria</description>\n";
  print "<language>es</language>\n";
  print "<copyright>Midepa.pe</copyright>\n";

  $rs = $db->query("SELECT id,titulo,urltitulo,sumilla,fechacreacion FROM n_noticia WHERE estado=1 ORDER BY fechacreacion DESC LIMIT 0,20");
  while($row = mysql_fetch_object($rs)){
	$fecha  = $row->fechacreacion;
	echo "<item>
	<title><![CDATA[".testsho($row->titulo)."]]></title>
	<description><![CDATA[".testsho($row->sumilla)."]]></description>
	<pubDate>".date('D, d M Y H:i:s', strtotime($fecha))." -0500</pubDate>
	<link>http://www.generaccion.com/noticia/".$row->id."/".$row->urltitulo."</link>
	<guid>http://www.generaccion.com/noticia/".$row->id."/".$row->urltitulo."</guid>
	</item>\n"; 
  }
  //<pubDate>".date('D, d M Y H:i:s O', strtotime($fecha))."</pubDate>
  print "</channel>\n";
  print "</rss>";
?>