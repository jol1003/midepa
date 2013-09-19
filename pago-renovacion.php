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
  <div class="sub">Tipos de Renovaci&oacute;n</div>
  
  <form name="frmpago" id="frmpago" method="post">
  <div class="frc frn">
  <div class="aviso">Escoja el tipo de renovaci&oacute;n que desea utilizar :</div>
  <div class="clear"></div>
	<div class="logos-align">
	<?php
	$inms = $db->query("SELECT  * from tipo_renovacion WHERE estado_tipo_renovacion = 1");
  while($inm = mysql_fetch_object($inms)){
  	?>
		<div class="log-pago">
			<div class="logpag"><a href="javascript:;"><img src="<?=URL_PATH?>assets/img/<?=$inm->img_tipo_renovacion?>" /></a> </div>
			<div class="radio"><input type="radio" name="tp" onclick="ver_nombre_renovacion('<?=$inm->nombre_tipo_renovacion?>',<?=$inm->id_tipo_renovacion?>)" /></div>
		</div> 
		<div class="clear"></div>
		<div class="separacion-pagos"><img src="assets/img/separacion_pago.jpg" title="" /></div>
		<div class="clear"></div>
	<?php
	}
	?>
	
	<div id="nombrepag"> <input type="text"  name="nombre_trenovacion" id="nombre_trenovacion" class="class-tipo-renova" readonly /> <input type="hidden" name="idTipor" id="idTipor"  /></div>
	</div>
  </div>
  <div class="derecha">
  <div class="btn btx">
  <span><input type="hidden" name="totI" value="<?=$j-1?>" id="totI" /><a href="javascript:;" onclick="escoger_renovacion('<?=URL_PATH?>')">Continuar</a></span>
  </div>
  </div>
  <input type="hidden" value="<?=$_GET['id']?>"  name="id" id="id" />
  </form>
</div>
<div class="flot"></div>

<?
  include(DIR_PATH."assets/includes/footer.php");?>