<?
  include("config.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);

  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi">Mi cuenta - Mis publicaciones (Inmuebles)</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
  <div class="sub">Mensaje del Pago</div>
  <form name="frmpago" id="frmpago" method="post">
  <div class="frc frn">
  <div class="clear"></div>
	<?php
	$rep = procesarPago($db, $log, $_SESSION['idcar'],$_SESSION['idPago']);
	
	if($rep){
	?>
		<div class="mensaje_de_compra">Gracias por su compra.</div>
	<?php
	}else{
	?>
		<div class="mensaje_de_compra">Ha ocurrido un problema al realizar la compra, comuniquese con el administrador.</div>
	<?php
	}
	?>
  </div>
  
  </form>
</div>
<div class="flot"></div>

<?
  include(DIR_PATH."assets/includes/footer.php");?>