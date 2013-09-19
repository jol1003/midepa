<?
  include("config.php");

  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $ntip = testins($_GET["tip"]); if($ntip == "constructoras"){$tip = 1;}elseif($ntip == "agentes-inmobiliarios"){$tip = 2;}else{header("location:".URL_PATH);}
  
  //PAGINANDO
  $pag = testins($_GET["pag"]);
  if(!$pag){$ini = 0; $pag = 1;}else{$ini = ($pag - 1) * 21;}
  $pager = $db->fetchObject("SELECT COUNT(id) AS can FROM empresa WHERE estado='1' AND empresatipo='".$tip."'");
  $totreg = $pager->can;
  $totpag = 20;
  $total = ceil($totreg / $totpag);
  $prev = $pag - 1; if($prev==0){$prev = $total;}
  $next = $pag + 1; if($next > $total){$next = 1;}
  $upag = URL_PATH.$ntip."/page/";
  
  $emps = $db->query("SELECT id,nombrecomercial FROM empresa WHERE estado='1' AND empresatipo='".$tip."' ORDER BY id DESC LIMIT ".$ini.",".$totpag);
  while($emp = mysql_fetch_object($emps)){
	$tim = str_replace('"','\'',$emp->nombrecomercial);
	$img = $db->fetchObject("SELECT imagen FROM usuario WHERE id=".$emp->id." LIMIT 0,1");
	$img='<a href="'.URL_PATH.'busqueda-inmuebles/?usrid='.$emp->id.'"><img src="'.URW_PATH.'imagenes/usuarios/'.$img->imagen.'" width="176" height="62" alt="'.$tim.'" title="'.$tim.'" /></a>';
	$aemp[] = $img;
  }
  $canemp = count($aemp);
  
  $page = $ntip;
  $styles = array("css/paginador.class.css");
  
  include(DIR_PATH."assets/includes/header.php");?>
<div class="izq">
  <div class="icd"><? if($tip==1){echo "Constructoras";}else{echo "Agentes inmobiliarios";}?></div>
<? if($canemp>0){foreach($aemp as $emp){?><div class="hco"><?=$emp;?></div><? }?><div class="flot"></div>
  <div class="pagination"><? $str='<a href="'.$upag.$prev.'/" class="prev">&#171; Anterior</a>';if($total>1){if($total>15){if($pag>=$total-6){$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$total-9;$i<=$total;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}elseif($pag<=9){for($i=1;$i<=10;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}else{$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$pag-5;$i<$pag+5;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}}else{for($i=1;$i<=$total;$i++){if($pag==$i){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}}else{$str='<span class="current">1</span>';};echo $str.'<a href="'.$upag.$next.'/" class="next">Siguiente &#187;</a>';?></div><? }?>
</div>
<div class="der">
  <div class="mod"><img src="<?=URL_PATH;?>assets/img/img1.jpg" /></div>
  <!--<div class="mod">
    <div class="bkm">
      <div class="bkmi">
      	<div class="ici">B&uacute;squeda avanzada</div>
        
      </div>
    </div>
  </div>
  <div class="mod">
  	[Zona del tabber]
  </div>-->
  <div class="mod"><div id="pasos"></div><script type="text/javascript">swfobject.embedSWF("<?=URL_PATH;?>assets/swf/3pasos.swf","pasos","310","300","9.0.0","<?=URL_PATH;?>assets/js/swfobject/expressInstall.swf",{},{wmode:"transparent"},{});</script></div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>