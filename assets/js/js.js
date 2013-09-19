
function recalcular(url){
	//alert("recalc");
	var tot = document.getElementById("totI").value;
	var sum = 0;
	for(i=1;i<=tot;i++){
		cant = document.getElementById("cantidad"+i).value;
		if(cant>=10){
			preci = document.getElementById("precio"+i).value;
			det  = document.getElementById("idDet"+i).value;
			$("#recalcularcantidad").load(url+'update-pago-dias.php',{
				id:det,
				cant:cant
			} , function(){
				
			});
			document.getElementById("subtotal"+i).value = cant *preci;
			sum+=cant *preci;
		}else{
			alert("ERROR : La cantidad no debe ser menor a 10 dias.");
			return false;	
		}
	}
	document.getElementById("totalcesta").value = sum;
}

function ver_nombre_pago(nombre,id){
		document.getElementById("nombre_pago").value = nombre;
		document.getElementById("idPago").value = id;
}

function escoger_pago(url){
	if(document.getElementById("nombre_pago").value==''){
		alert("ERROR : Seleccione un Tipo de Pago");	 
		return false;
	}else{
		document.frmpago.action=url+'confirmar-pago.php';
		document.frmpago.submit();
	}
	
}

function ver_nombre_renovacion(nombre,id){
		document.getElementById("nombre_trenovacion").value = nombre;
		document.getElementById("idTipor").value = id;
}

function escoger_renovacion(url){
	if(document.getElementById("nombre_trenovacion").value==''){
		alert("ERROR : Seleccione un Tipo de Renovacion");	 
		return false;
	}else{
		document.frmpago.action=url+'pago-publicaciones.php';
		document.frmpago.submit();
	}
	
}
