<?
function testsho($str){$str = stripslashes(trim($str));return $str;}
function testins($str){$str = mysql_real_escape_string(strip_tags(trim($str)));return $str;}
/*function testurl($str){$str = mysql_real_escape_string(strip_tags(trim($str)));return $str;}
function testins($str){$str = mysql_real_escape_string(utf8_decode(strip_tags(trim($str))));return $str;}
function testsho($str, $jq=false){
  if($jq){
  	$str = stripslashes(nl2br(strip_tags(trim($str)))); //para el html desde jquery
  }else{
  	$str = stripslashes(utf8_encode(nl2br(strip_tags(trim($str))))); //para el html
  }
  return $str;
}*/

function mes($mes,$cor=false){
  $mes = $mes-1;
  if($cor){
	$meses = array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
  }else{
	$meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
  }
  return $meses[$mes];
}

function dia($dia){
  switch($dia){
  	case "0": $dia = "Domingo"; break;
    case "1": $dia = "Lunes"; break;
    case "2": $dia = "Martes"; break;
    case "3": $dia = "Mi&eacute;rcoles"; break;
    case "4": $dia = "Jueves"; break;
    case "5": $dia = "Viernes"; break;
    case "6": $dia = "S&aacute;bado"; break;
  }
  //return utf8_encode($dia);
  return $dia;
}

function fecha($fec,$lar=false){
  if($lar){
	$date = explode(" ", $fec); $fec = explode("-", $date[0]);
  }else{
	$fec = explode("-", $fec);
  }
  $fec = $fec[2]."-".mes($fec[1], true)."-".$fec[0];
  return $fec;
}

function titurl($txt){
  $url = utf8_encode(html_entity_decode(strtolower(htmlentities(utf8_decode($txt)))));
  $a = array("á","é","í","ó","ú","ñ","à","è","ì","ò","ù","ä","ë","ï","ö","ü","-");
  $b = array("a","e","i","o","u","n","a","e","i","o","u","a","e","i","o","u"," ");
  $w = array("la","en","de","las","les","los","a","i","o","u","del","para","es","el","y","un","una");
  $url = str_replace($a, $b, $url);
  $url = trim(preg_replace('/\s\s+/', ' ', $url));
  $url = preg_replace('/[^A-Za-z0-9- ]/', '', $url);
  $arrs = explode(" ", $url);
  if(count($arrs)){
  	foreach($arrs as $arr){
	  if(!in_array($arr, $w)){$str=$str.$arr." ";}
	}
  }
  $url = trim($str);
  $url = preg_replace('/ /','-',$url);
  return $url;
}

function classFormaPago($class, $amount, $monedalocal, $monedaconvertir,$detail, $texto=''){
		if(isset($class) && !empty($class)){
			switch($class){
				case 'Paypal':
					//require_once("model/paypal.cls.php");	
					$montoCalcular = number_format($amount/$monedaconvertir, 2);
					$montoFinal = 	$monedaconvertir!=''? $montoCalcular : $amount;			
					//$urlProcess="https://www.sandbox.paypal.com/cgi-bin/webscr";
					//$urlProcess ="https://secure.paypal.com/cgi-bin/webscr";
					$urlProcess ="https://www.paypal.com/cgi-bin/webscr";
					$cmd		="_xclick";
					//$business	="j_olov_1353598628_biz@hotmail.com";
					$business	= $texto;
					$item_name  = utf8_decode($detail);//detalle de la compra
					$amount 	= $montoFinal; //monto
					$shipping   = $shipping; //costo de envio
					$currency_code  = "USD";//moneda
					$retorno		= "http://www.midepa.pe/pago-transferencia.php";
					$cancel_return  = "http://www.midepa.pe/pago-metodos.php";
					
					$form='
					<form name="checkout_confirmation" action="'.$urlProcess.'" method="post">
						<input type="hidden" name="cmd" value="_xclick">
						<input type="hidden" name="bn"  value="'.$item_name.'">
						<input type="hidden" name="business" value="'.$business.'">
						<input type="hidden" name="item_name" value="'.$item_name.'">
						<input type="hidden" name="amount" value="'.number_format($amount,2).'">
						<input type="hidden" name="shipping" value="'.number_format($shipping,2).'">
						<input type="hidden" name="currency_code" value="'.$currency_code.'">
						<input type="hidden" name="return" value="'.$retorno.'">
						<input type="hidden" name="cancel_return" value="'.$cancel_return.'">
						<input type="submit" class="btn_confirmar" border="0" alt="Confirmar Pedido" title=" Confirmar Pedido " value="" />
					</form>';
					return $form;
				break;	
				
				case 'Deposito Bancario':				
					$form='
						<form name="checkout_confirmation" action="pago-transferencia.php" method="post">	
							<input type="hidden" name="amount" value="'.$amount.'">
							<input type="submit" class="btn_confirmar" border="0" alt="Confirmar Pedido" title=" Confirmar Pedido" value="" />
					</form>';
					return $form;
				break;
				
				case 'Safetypay':				
					$form='';
					return $form;
				break;
						
			}			
		}
}	

function procesarPago($db, $usu, $idcar, $idpago){
	$sql = "select * from pago_inmueble_temp where usuario_id = ".$usu." and id_pago_inmueble = '".$idcar."' ";
	$query = $db->query($sql);
	$row = mysql_fetch_object($query);
	
	$estadopago = $row->id_tipo_pago==3?'0':'1';
	$sqlI = "INSERT pago_inmueble(id_pago_inmueble, inmueble_id, usuario_id, id_tipo_pago, id_tipo_moneda, id_tipo_cambio, fecha_pago_inmueble, comentario_pago_inmueble, transaccion_pago_inmueble, referencia_pago_inmueble, estado_pago_inmueble) VALUES 
								( '', '', ".$row->usuario_id.", '".$row->id_tipo_pago."', '','',NOW(),'','','', '".$estadopago."')";
  	$queryI = $db->query($sqlI);
  	$idCP   = mysql_insert_id();
     $pago  = $row->id_tipo_pago;
	 
	$sqlL = "SELECT p.*, pd.* FROM pago_inmueble_temp p,pago_inmueble_detalle_temp pd WHERE p.id_pago_inmueble = pd.id_pago_inmueble AND p.id_pago_inmueble = '".$idcar."' AND usuario_id = ".$usu." ";
  	$queryL = $db->query($sqlL);
  	$nroD = mysql_num_rows($queryL);
	
	$idDet = 0;
	$it=1;
	while($rowD = mysql_fetch_object($queryL)){
		$sqlD = "INSERT pago_inmueble_detalle(id_pago_detalle, id_pago_inmueble, id_inmueble,  id_tipo_renovacion, cantidad_pago_detalle, precio_pago_detalle) VALUES ( '', ".$idCP."  ,   ".$rowD->id_inmueble.",  ".$rowD->id_tipo_renovacion." ,'".$rowD->cantidad_pago_detalle."','".$rowD->precio_pago_detalle."')";
	  	$queryD = $db->query($sqlD);
		$idDet++;
		
		//listar los inmuebles
		$sqlInmu = "select nombre from inmueble where id = ".$rowD->id_inmueble;
		$queryInmu = $db->query($sqlInmu);
		$rowInmu   = mysql_fetch_object($queryInmu);
		
		$messageP.="<tr><td>".$it."</td> <td>".$rowD->cantidad_pago_detalle."</td> <td>".$rowInmu->nombre."</td> <td>".$rowD->cantidad_pago_detalle*$rowD->precio_pago_detalle."</td></tr>";
		$totalP+= $rowD->cantidad_pago_detalle*$rowD->precio_pago_detalle;
		//fin
				
		if($pago!=3){
			
			//calculando la fecha final
			$sqlAS     = "select fechafin from inmueble where id=".$rowD->id_inmueble." ";
			$queryAS = $db->query($sqlAS); 
			$rowAS    = mysql_fetch_object($queryAS);
			$fecf		  = $rowAS->fechafin;
			$feca       = date('Y-m-d');
			 
			if($fecf >=$feca){
			 	$fecCD = $fecf;
			 }else{
			 	$fecCD = $feca;
			 }
			 
			$fecArray = explode("-", $fecCD);
			$year 		= $fecArray[0];
		  	$month 	= $fecArray[1];
		  	$day   		= $fecArray[2];
		     
			 
		  	$fechafin   = mktime(0,0,0,$month,$day+$rowD->cantidad_pago_detalle,$year);
		  	$fechafinal = date('Y-m-d' , $fechafin);  
			 
			//actualizar los anuncios
			$sqlU = "UPDATE  inmueble SET   fechainicio = '".$fecCD."', fechafin = '".$fechafinal."' , tipopago = '".$rowD->id_tipo_renovacion."' WHERE  id = ".$rowD->id_inmueble." ";
			$queryU = $db->query($sqlU);
			
			if($rowD->id_tipo_renovacion==2){
				//recalculando fechas en destacado
				//calculando la fecha final
				$sqlAS     = "select fecha_fin_inmueble_destacado from inmueble_destacado where id=".$rowD->id_inmueble."  order by id_inmueble_destacado desc";
				$queryAS = $db->query($sqlAS); 
				$rowAS    = mysql_fetch_object($queryAS);
				$fecf		  = $rowAS->fecha_fin_inmueble_destacado;
				$feca       = date('Y-m-d');
				 
				if($fecf >=$feca){
					$fecCD = $fecf;
				 }else{
					$fecCD = $feca;
				 }
				 
				$fecArray = explode("-", $fecCD);
				$year 		= $fecArray[0];
				$month 	= $fecArray[1];
				$day   		= $fecArray[2];
				 
				 
				$fechafin   = mktime(0,0,0,$month,$day+$rowD->cantidad_pago_detalle,$year);
				$fechafinal = date('Y-m-d' , $fechafin);  
			
				
					$sqlDes = "INSERT inmueble_destacado(id_inmueble_destacado, id_inmueble, dias_inmueble_destacado, fecha_inicio_inmueble_destacado, fecha_fin_inmueble_destacado) VALUES 
									( '', '', ".$rowD->id_inmueble.", '".$rowD->cantidad_pago_detalle."', '".$fecCD."' , '".$fechafinal."'  )";
					$queryDes = $db->query($sqlDes);
			}
				
		}
		
		/*if($rowD->id_tipo_renovacion==2){
			$sqlI = "INSERT inmueble_destacado(id_inmueble_destacado, id_inmueble, dias_inmueble_destacado, fecha_inicio_inmueble_destacado, fecha_fin_inmueble_destacado) VALUES 
								( '', '', ".$rowD->id_inmueble.", '".$rowD->cantidad_pago_detalle."', '".$estadopago."' , ".$estadopago."')";
  			$queryI = $db->query($sqlI);
		}*/
		
		$it++;
	}
	
		//sacar datos del cliente
		$sqlCliente = "select id, usuariotipo, email from usuario where id = ".$usu;
		$queryCliente = $db->query($sqlCliente);
		$rowCliente    = mysql_fetch_object($queryCliente);
		
		if($rowCliente->usuariotipo == 1){
			//persona
			$sqlCliente1 = "select nombres, apellidos from persona where id = ".$usu;
			$queryCliente1 = $db->query($sqlCliente1);
			$rowCliente1    = mysql_fetch_object($queryCliente1);
			
			$nombreCompleto = $rowCliente1->nombres." ".$rowCliente1->apellidos;
		}else{
			//empresa
			$sqlCliente1 = "select nombrecomercial from empresa where id = ".$usu;
			$queryCliente1 = $db->query($sqlCliente1);
			$rowCliente1    = mysql_fetch_object($queryCliente1);
			
			$nombreCompleto = $rowCliente1->nombrecomercial;
		}
		
		
		$strNomOrigen 	= "MIDEPA";
		//$strMensaje 	= str_replace("<br>","<br />",$_POST['mensaje']); 
		$message = "
			Estimado(a) ".$nombreCompleto." Gracias por realizar su compra con nosotros a continuaci&oacute;n le detallamos los datos de su orden:		
			";

		$message .= "<table border='1' align='center' width='100%' style='border:#CCCCCC'>
		<tr>
		<td> Item </td>  <td>Dias </td> <td>Producto </td> <td>Precio </td>
		</tr>";
		$message .= $messageP;
		$message .= " 		
		<tr>
		<td colspan='4'>
		Total en Productos " . number_format($totalP,2)."
		</td>
		</tr>
		</table>
		";	
	
		$strMensaje 	= $message; 
		$strMailOrigen 	= "info@midepa.pe";
		$strMailDestino = $rowCliente->email;
		$strAsunto 		= "Gracias por Comprar - MiDepa.pe";
		$strAsunto1 	= "Se ha realizado una compra en MiDepa.pe";
		$strMailDestino1 = "info@midepa.pe";
				
		fun_sendMailPedido($strNomOrigen, $strMailOrigen, $strMailDestino, $strMensaje, $strAsunto);
		fun_sendMailPedido($strNomOrigen, $strMailOrigen, $strMailDestino1, $strMensaje, $strAsunto1);
		
	//eliminando temporales
	$sqlA   = "DELETE from pago_inmueble_temp where usuario_id = ".$usu." and id_pago_inmueble = '".$idcar."' ";
	$queryA = $db->query($sqlA);
	
	$sqlLA   = "DELETE from pago_inmueble_detalle_temp WHERE id_pago_inmueble = ".$idcar." ";
  	$queryLA = $db->query($sqlLA);
	
	if($nroD==$idDet){
		$rep = true;
	}else{
		$rep = false;
	}
	
	return $rep;
}

function fun_sendMailPedido($strNomOrigen, $strMailOrigen, $strMailDestino, $strMensaje, $strAsunto){
		$responder=$strMailOrigen;
		$remite=$strMailOrigen;//correo
		$remitente=$strNomOrigen;//nombre
		$cabecera ="Date: ".date("l j F Y, G:i")."\n";
		$cabecera .="MIME-Version: 1.0\n";
		$cabecera .="From: ".$remitente."<".$remite.">\n";
		$cabecera .="Return-path: ". $remite."\n";	 	
		$cabecera .="Reply-To: ".$responder."\n";
		$cabecera .="X-Mailer: PHP/". phpversion()."\n";
		$cabecera .="Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		if (mail ($strMailDestino,$strAsunto,$strMensaje,$cabecera)){
			return true;
		}else{
			return false;
		}
}
	
?>