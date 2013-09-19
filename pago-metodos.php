<?
  include("config.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);

  $page = "micuenta-mispublicaciones";
  $styles = array("css/paginador.class.css");
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi">Mi cuenta - Mis publicaciones (Inmuebles)</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
  <div class="sub">M&eacute;todos de Pago</div>
  
  <form name="frmpago" id="frmpago" method="post">
  <div class="frc frn">
  <div class="aviso">Escoja el m&eacute;todo de pago que desea utilizar :</div>
  <div class="clear"></div>
	<div class="logos-align">
	<?php
	$inms = $db->query("SELECT  * from tipo_pagos WHERE estado_tipo_pago = 1");
  while($inm = mysql_fetch_object($inms)){
  	?>
		<div class="log-pago">
			<div class="logpag"><a href="javascript:;"><img src="<?=URL_PATH?>assets/img/<?=$inm->img_tipo_pago?>" /></a> </div>
			<div class="radio"><input type="radio" name="tp" onclick="ver_nombre_pago('<?=$inm->nombre_tipo_pago?>',<?=$inm->id_tipo_pago?>)" /></div>
		</div> 
		<div class="clear"></div>
		<div class="separacion-pagos"><img src="assets/img/separacion_pago.jpg" title="" /></div>
		<div class="clear"></div>
	<?php
	}
	?>
	
	<div id="nombrepag"> <input type="text"  name="nombre_pago" id="nombre_pago" class="class-tipo-pago" readonly /> <input type="hidden" name="idPago" id="idPago"  /></div>
	</div>
  </div>
  <div class="derecha">
  <div class="btn btx"><span><input type="hidden" name="totI" value="<?=$j-1?>" id="totI" /><a href="javascript:;" onclick="escoger_pago('<?=URL_PATH?>')">Continuar</a></span></div>
  </div>
  </form>
</div>
<div class="flot"></div>

<?
  include(DIR_PATH."assets/includes/footer.php");?>