<?
  include("config.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $i = 0;
  $id = $_POST['id'];
  $idTipoR = $_POST['idTipor'];
  //$_SESSION['idcar'] = session_id('session_id');
 
  
 		if(!isset($_SESSION['idcar'])){
             //$session_id = session_id();
  			 //$_SESSION['idcar'] = $session_id;
			$now = (string)microtime();
			$now = explode(' ', $now);
			$mm = explode('.', $now[0]);
			$mm = $mm[1];
			$now = $now[1];
			$segundos = $now % 60;
			$segundos = $segundos < 10 ? "$segundos" : $segundos;
			$date = strval(date("YmdHi",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))) . "$segundos$mm");
			
			$_SESSION['idcar'] = $date;
        }else{
            $_SESSION['idcar'] = $_SESSION['idcar'];
        }
		
		
  $cantidad = 30; 
  //calculamos el precio
  $sqlRP= "select * from tipo_renovacion  where id_tipo_renovacion = ".$idTipoR;
  $queryRP = $db->query($sqlRP);
  $rowRP= mysql_fetch_object($queryRP);
  
  $precio = $rowRP->precio_tipo_renovacion; 
  //insertando al temp
  $sql = "INSERT pago_inmueble_temp(id_pago_inmueble, inmueble_id, usuario_id, id_tipo_pago, id_tipo_moneda, id_tipo_cambio, fecha_pago_inmueble, comentario_pago_inmueble, transaccion_pago_inmueble, referencia_pago_inmueble) VALUES ( '".$_SESSION['idcar']."', ".$id.", ".$log.", '', '','','','','','')";
  $query = $db->query($sql);
  
  //solo una vez
  if($id!=''){
	  $sqlC = "select * from pago_inmueble_detalle_temp where id_pago_inmueble = '".$_SESSION['idcar']."' AND id_inmueble = ".$id." ";
	  $queryD = $db->query($sqlC);
	  $nroV = mysql_num_rows($queryD);
  }else{
  	  $nroV=0;
  }
  
  if($nroV==0){
	  $sqlD = "INSERT pago_inmueble_detalle_temp(id_pago_detalle, id_pago_inmueble, id_inmueble,  id_tipo_renovacion, cantidad_pago_detalle, precio_pago_detalle) VALUES ( '', '".$_SESSION['idcar']."', ".$id." , ".$idTipoR." ,'".$cantidad."','".$precio."')";
	  $queryD = $db->query($sqlD);
  }
  $sqlL = "SELECT p.*, pd.* FROM pago_inmueble_temp p,pago_inmueble_detalle_temp pd WHERE p.id_pago_inmueble = pd.id_pago_inmueble AND p.id_pago_inmueble = '".$_SESSION['idcar']."' AND usuario_id = ".$log." ";
  $queryL = $db->query($sqlL);
  $nro = mysql_num_rows($queryL);

  $page = "pago-publicaciones.php";
  $styles = array("css/paginador.class.css");
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi">Mi cuenta - Mis publicaciones (Inmuebles)</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
  <div class="sub">Renovar mis publicaciones</div>
  <div class="frc frn">
  
<?
  if($nro > 0){
  ?>
  <div class="aviso">Aviso de Inmueble agregado a carrito de compra</div>
  <div class="item-cesta"><strong>Item</strong></div>
  
  <div class="itm-cesta-detalle"><strong>Descripcion</strong></div>
  
  <div class="cantidad-cesta"><strong>Dias</strong></div>
  
  <div class="cantidad-cesta"><strong>Precio</strong></div>
 
  <div class="cantidad-cesta"><strong>Subtotal</strong></div>
  <div class="clear"></div>
  <?php
    $j=1;
	while($row = mysql_fetch_object($queryL)){ 
		$inms = $db->query("SELECT id,nombre,urlnombre,imagen,antiguedad,direccion,areatotalmin,areatotalmax,areaconstruidamin,areaconstruidamax,preciomin,preciomax,preciodolaresmin,preciodolaresmax,adicionales,fechacreacion,fechainicio, fechafin FROM inmueble WHERE usuario_id=".$row->usuario_id." and id = ".$row->id_inmueble." ORDER BY fechacreacion DESC ");
		
		  	$inm = mysql_fetch_object($inms);
			$nlnk = URL_PATH.'inmueble-detalle/'.$inm->id.'/'.$inm->urlnombre;
			$ainm[0]["nom"] = '<a href="'.$nlnk.'">'.testsho($inm->nombre).'</a>';
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
	
	<div class="cantidad-cesta"><input type="text" name="cantidad" id="cantidad<?=$j?>" value="<?=$row->cantidad_pago_detalle?>" class="texto" maxlength="3" onblur="recalcular('<?=URL_PATH?>');" /></div>
	<div class="cantidad-cesta">S/. <input type="text" name="precio" id="precio<?=$j?>" class="texto" value="<?=$row->precio_pago_detalle?>" readonly /></div>
	<div class="cantidad-cesta">S/. <input type="text" name="subtotal" readonly class="texto" id="subtotal<?=$j?>" value="<?=$subtotal?>" /></div>
	
	<div class="cantidad-cesta"><a href="<?=URL_PATH?>eliminar-pago-publicaciones.php?id=<?=$ainm[0]["id"];?>" title="Eliminar"><img src="assets/img/btn_eliminar.jpg" title="Eliminar Item" /></a></div>
	
	
	<div class="separacion"></div>
	
	
	<? 
	$j++;
	//}
	
	}
	?>
  	
<?
  }else{?>
  <div class="aviso-negativo"><strong>No tiene publicaciones por renovar en el carrito de compra.</strong></div>
  <?php
  }
  ?>
  <?php
  if($nro > 0){
  ?>
  <div class="total-cesta"><strong>Total</strong> S/. <input type="text" name="totalcesta" class="texto" id="totalcesta" value="<?=$total?>" readonly /></div>
  <?php
  }
  ?>
  <div id="recalcularcantidad"></div>
  </div>
  
  <div>
  <a href="<?=URL_PATH?>micuenta-mispublicaciones/"><img src="assets/img/btn_seguir_comp.jpg" title="Seguir Comprando" border="0" /></a>
  <input type="hidden" name="totI" value="<?=$j-1?>" id="totI" /><br />
  <?php
  if($nro > 0){
  ?>
  <a href="<?=URL_PATH?>pago-metodos.php"><img src="assets/img/btn_comprar.jpg" title="Comprar" border="0" /></a>
  <?php
  }
  ?>
  </div>
</div>
<div class="flot"></div>

<?
  include(DIR_PATH."assets/includes/footer.php");?>
