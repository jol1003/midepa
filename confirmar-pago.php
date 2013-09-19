<?
  include("config.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  $idPago = $_POST['idPago'];
  $nombre_pago = $_POST['nombre_pago'];
  
  $_SESSION['idPago'] = $_POST['idPago'];
  
  $sqlPago = "UPDATE pago_inmueble_temp SET id_tipo_pago = ".$idPago."  where id_pago_inmueble = '".$_SESSION['idcar']."' AND usuario_id = ".$log." ";
  $queryPago = $db->query($sqlPago);
  //$nro = mysql_num_rows($queryL);
  
  $sqlL = "SELECT p.*, pd.* FROM pago_inmueble_temp p,pago_inmueble_detalle_temp pd WHERE p.id_pago_inmueble = pd.id_pago_inmueble AND p.id_pago_inmueble = '".$_SESSION['idcar']."' AND usuario_id = ".$log." ";
  $queryL = $db->query($sqlL);
  $nro = mysql_num_rows($queryL);

  $page = "micuenta-mispublicaciones";
  $styles = array("css/paginador.class.css");
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi">Mi cuenta - Mis publicaciones (Inmuebles)</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
  <div class="sub">Confirmar Pago</div>
  <div class="frc frn">
  <div class="aviso">Detalle de la renovaci&oacute;n de su aviso :</div>
   
  <div class="aviso">M&eacute;todo de pago : <strong><?=$nombre_pago?></strong></div>
  
  <div class="item-cesta"><strong>Item</strong></div>
  
  <div class="itm-cesta-detalle"><strong>Descripcion</strong></div>
  
  <div class="cantidad-cesta"><strong>Dias</strong></div>
  
  <div class="cantidad-cesta"><strong>Precio</strong></div>
 
  <div class="cantidad-cesta"><strong>Subtotal</strong></div>
  <div class="clear"></div>
<?
  if($nro > 0){
    $j=1;
	while($row = mysql_fetch_object($queryL)){ 
		$inms = $db->query("SELECT id,nombre,urlnombre,imagen,antiguedad,direccion,areatotalmin,areatotalmax,areaconstruidamin,areaconstruidamax,preciomin,preciomax,preciodolaresmin,preciodolaresmax,adicionales,fechacreacion,fechainicio, fechafin FROM inmueble WHERE usuario_id=".$row->usuario_id." and id = ".$row->id_inmueble." ORDER BY fechacreacion DESC ");
		
		  	$inm = mysql_fetch_object($inms);
			$nlnk = URL_PATH.'inmueble-detalle/'.$inm->id.'/'.$inm->urlnombre;
			$ainm[0]["nom"] = testsho($inm->nombre);
			$ainm[0]["lnk"] = $nlnk;
			$ainm[0]["lne"] = URL_PATH."micuenta-editarpublicacion/?id=".$inm->id;
			$ainm[0]["img"] = '<a href="'.$nlnk.'"><img src="'.URW_PATH.'imagenes/inmuebles/thumbs/'.$inm->imagen.'" width="130" height="94" alt="'.str_replace('"','\'',$inm->nombre).'" /></a>';
			if($inm->antiguedad == 1){$ainm[0]["ant"] = "PROYECTO EN CONSTRUCCION";}elseif($inm->antiguedad == 2){$ainm[0]["ant"] = "USADO";}else{$ainm[0]["ant"] = "NUEVO";}
			$ainm[0]["dir"] = testsho($inm->testsho);
			if($inm->areatotalmax == ""){$ainm[0]["ato"] = "AT ".$inm->areatotalmin." m²";}else{$ainm[0]["ato"] = "AT de ".$inm->areatotalmin." m² a ".$inm->areatotalmax." m²";}
			if($inm->areaconstruidamin != ""){if($inm->areaconstruidamax == ""){$ainm[0]["aco"] = "AC ".$inm->areaconstruidamin." m²";}else{$ainm[0]["aco"] = "AC de ".$inm->areaconstruidamin." m² a ".$inm->areaconstruidamax." m²";}}
			if($inm->preciomin != ""){
			  if($inm->preciomax == ""){$ainm[0]["pso"] = "S/. ".$inm->preciomin;}else{$ainm[0]["pso"] = "de S/. ".$inm->preciomin." a S/. ".$inm->preciomax;}
			  if($inm->preciodolaresmax == ""){$ainm[0]["pdo"] = "$. ".$inm->preciodolaresmin;}else{$ainm[0]["pdo"] = "de $. ".$inm->preciodolaresmin." a $. ".$inm->preciodolaresmax;}
			}else{
			  $ainm[0]["pso"] = "Por consultar"; $ainm[$i]["pdo"] = "Por consultar";
			}
			$ainm[0]["adi"] = testsho($inm->adicionales);
			$ainm[0]["fec"] = fecha($inm->fechacreacion, true);
			$ainm[0]["fecI"] = fecha($inm->fechainicio, true);
			$ainm[0]["fecF"] = fecha($inm->fechafin, true);
			$ainm[0]["id"]   = $inm->id;
			
			$i++;
		 	$caninm = count($ainm);
     $subtotal = $row->cantidad_pago_detalle*$row->precio_pago_detalle;
	 $total+= $subtotal; 
	//for($i=0;$i<$caninm;$i++){?>
	<div class="item-cesta"><?=$j?> <input type="hidden" name="idInm" id="idInm<?=$j?>" value="<?=$row->id_inmueble?>" /><input type="hidden" name="idDet" id="idDet<?=$j?>" value="<?=$row->id_pago_detalle?>" /></div>
	<div class="itm-cesta-detalle">
	<div class="img-cesta"><?=$ainm[0]["img"];?></div>
		<div class="det-cesta">
		<!--<div class="chi"><?=$ainm[0]["fec"];?> </div>-->
		<div class="tit"><h3><?=$ainm[0]["nom"];?></h3></div>
		<div class="bld"><?=$ainm[0]["dir"];?></div>
		<div><?=$ainm[0]["ant"];?> </div>
		<div class="bld"><?=$ainm[0]["ato"];?> <?=$ainm[0]["aco"];?> </div>
			<? if($ainm[0]["adi"]!=""){?>
					<div class="chi"><?=$ainm[0]["adi"];?> </div>
			<? 
			   }
			?>
		<div class="chi"><?=$ainm[0]["fecI"];?> - <?=$ainm[0]["fecF"];?></div>
		</div>
	<div class="flot"></div>
	<div class="dpr">
	
	</div>
	</div>
	
	<div class="cantidad-cesta"><?=$row->cantidad_pago_detalle?></div>
	<div class="cantidad-cesta">S/. <?=$row->precio_pago_detalle?></div>
	<div class="cantidad-cesta">S/. <?=$subtotal?></div>
	
	<div class="cantidad-cesta">&nbsp;</div>
	
	
	<div class="separacion"></div>
	
	
	<? 
	$j++;
	//}
	$detail.= $ainm[0]["nom"]." - ".$row->cantidad_pago_detalle." dias.";
	}
	?>
  	
<?
  }?>
  <div class="total-cesta"><strong>Total</strong> S/. <?=$total?></div>
  <div id="recalcularcantidad"></div>
  </div>
  <div>
  
  <?php
  $class = $nombre_pago;
  $amount = $total;
  $monedalocal = 'S./';
  $monedaconvertir = '';
  if($class=='Paypal'){
  	$sqlMonedaLocal = "select * from tipo_cambio order by fecha_tipo_cambio DESC";
	$queryMoneda    = $db->query($sqlMonedaLocal);
	$rowMoneda 		= mysql_fetch_object($queryMoneda);
	$monedaconvertir = $rowMoneda->monto_tipo_cambio;
  }
  
  //tipodepago

	$sqlTP = "SELECT * FROM tipo_pagos where estado_tipo_pago = 1 and id_tipo_pago = ".$_SESSION['idPago'];
	$queryTP = $db->query($sqlTP); 
	$rowTP = mysql_fetch_object($queryTP);
	$texto = trim($rowTP->texto_tipo_pago);

  echo classFormaPago($class, $amount, $monedalocal, $monedaconvertir,$detail, $texto)?>
  	<?php
  	if($class=='Paypal'){
	?>
	<div class="nota"><strong>Nota :</strong> Para culminar el pago correctamente debe apretar el link <a href="javascript:;">volver a numerosycuentas@hotmail.com</a> despues de haber realizado el pago en <strong>PAYPAL</strong></div>
	<?php
	}
	?>
  </div>
 
</div>
<div class="flot"></div>

<?
  include(DIR_PATH."assets/includes/footer.php");?>