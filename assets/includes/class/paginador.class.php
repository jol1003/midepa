<?
/**
 ** Nombre de la Clase: Magic Paginator 1.1
 ** Autor: Oscar Pasache Elias
 ** URL Autor: http://www.elmagito.com
 ** ElMaGiTo (c) 2009 - Update:04/nov/2009 04:01 pm;
 **/

class mp_paginador{
	// Metodo 'normal' - 'ajax'
	var $mp_modo = 'normal';
	
	// Inicio del Ajax
	var $mp_ajax_ini;
	
	// Fin del Ajax
	var $mp_ajax_fin;
	
	// Texto Adelante - Atras  'normal' - 'flechas' - 'imagen' - 'none'
	var $mp_prev_next = 'normal';
	
	// Si escogiste 'imagen' en el metodo de arriba pon la url de la flecha Izq
	var $mp_img_prev;
	
	// Si escogiste 'imagen' en el metodo de arriba pon la url de la flecha Der
	var $mp_img_next;
	
	// Nombre por defecto es 'pagination'
	var $mp_nombre = 'pagination';
	
	// Variable Url, por defecto es 'pag'
	var $mp_variable = 'pag';
	
	// Cantidad de resultados por pagina
	var $mp_por_pag = 10;
	
	// Cantidad de numeros antes de recortar
	var $mp_num_pag = 15;
	
	// Cantidad de numeros recorte inicial
	var $mp_rec_ini = 10;
	
	// Cantidad de numeros recorte centro
	var $mp_rec_cen = 5;
	
	// Cantidad de numeros recorte final
	var $mp_rec_fin = 9;
	
	/** NO MODIFICAR **/
	var $mp_inicio;		// Inicio de la paginacion
	var $mp_total;		// Total de paginas
	var $mp_pag;		// Pagina actual
	var $mp_url;		// Url Pagina
	
	function install($con){
		$registros = mysql_num_rows($con);
		$this->mp_total = ceil($registros / $this->mp_por_pag);
		//$this->mp_total = ceil($con / $this->mp_por_pag);
		$this->metodo();
		$this->pagina();
	}
	
	function metodo(){
		$met = $this->mp_modo;
		if($met == 'normal'){
			$uri = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$uri = explode('?'.$this->mp_variable.'=',$uri);
			$uri = explode('&'.$this->mp_variable.'=',$uri[0]);
			$url = $uri[0];
			
			$com = explode('?',$url);
			if(count($com) > 1){
				$url .= '&';
			}else{
				$url .= '?';
			}
			$url .= $this->mp_variable.'=';
		}
		return $this->mp_url = $url;
	}
	
	function pagina(){
		$this->mp_pag = $_GET[$this->mp_variable];
		if($this->mp_pag == ''){
			$this->mp_pag = 1;
			$this->mp_inicio = 0;
		}else{
			$this->mp_inicio = ($this->mp_pag - 1) * $this->mp_por_pag;
		}
	}
	
	function anterior(){
		$ant = $this->mp_pag - 1;
		if($this->mp_pag == 1){
			$ant = '';
		}
		
		if($this->mp_modo == 'normal'){
			$a_url = $this->mp_url . $ant;
		}elseif($this->mp_modo == 'ajax'){
			$a_url  = "Javascript:";
			$a_url .= $this->mp_ajax_ini;
			$a_url .= $ant;
			$a_url .= $this->mp_ajax_fin;
			$a_url .= ";";
		}
		
		if($ant == ''){
			$anterior = '<span class="disabled">';
			if($this->mp_prev_next == 'normal'){
				$anterior .= '&#171; Anterior';
			}elseif($this->mp_prev_next == 'flechas'){
				$anterior .= '&#171;';
			}elseif($this->mp_prev_next == 'imagen'){
				$anterior .= '<img src="'. $this->mp_img_prev .'" border="0" align="absmiddle" />';
			}
			$anterior .= '</span>';
		}else{
			$anterior = '<a href="'. $a_url .'" class="prev">';
			if($this->mp_prev_next == 'normal'){
				$anterior .= '&#171; Anterior';
			}elseif($this->mp_prev_next == 'flechas'){
				$anterior .= '&#171;';
			}elseif($this->mp_prev_next == 'imagen'){
				$anterior .= '<img src="'. $this->mp_img_prev .'" border="0" align="absmiddle" />';
			}
			$anterior .= '</a>';
		}
		return $anterior;
	}
	
	function siguiente(){
		$nex = $this->mp_pag + 1;
		if($this->mp_pag >= $this->mp_total){
			$nex = '';
		}
		
		if($this->mp_modo == 'normal'){
			$n_url = $this->mp_url . $nex;
		}elseif($this->mp_modo == 'ajax'){
			$n_url  = "Javascript:";
			$n_url .= $this->mp_ajax_ini;
			$n_url .= $nex;
			$n_url .= $this->mp_ajax_fin;
			$n_url .= ";";
		}
		
		if($nex == ''){
			$siguiente  = '<span class="disabled">';
			if($this->mp_prev_next == 'normal'){
				$siguiente .= 'Siguiente &#187;';
			}elseif($this->mp_prev_next == 'flechas'){
				$siguiente .= '&#187;';
			}elseif($this->mp_prev_next == 'imagen'){
				$siguiente .= '<img src="'. $this->mp_img_next .'" border="0" align="absmiddle" />';
			}
			$siguiente .= '</span>';
		}else{
			$siguiente = '<a href="'. $n_url .'" class="next">';
			if($this->mp_prev_next == 'normal'){
				$siguiente .= 'Siguiente &#187;';
			}elseif($this->mp_prev_next == 'flechas'){
				$siguiente .= '&#187;';
			}elseif($this->mp_prev_next == 'imagen'){
				$siguiente .= '<img src="'. $this->mp_img_next .'" border="0" align="absmiddle" />';
			}
			$siguiente .= '</a>';
		}
		return $siguiente;
	}
	
	function enlaces(){
		// Enlace normal <a href="?pag=1">pagina</a>
		// Enlace activo <span class="current">pagina</span>
		$enlace;
		if($this->mp_total > 1){
		  if($this->mp_total > $this->mp_num_pag){
	  		if($this->mp_pag >= $this->mp_total-$this->mp_rec_fin){
			  /* URL METODO 'nomarl' - 'ajax' */
			  if($this->mp_modo == 'normal'){
			 	$p_url = $this->mp_url . '1';
				$s_url = $this->mp_url . '2';
			  }elseif($this->mp_modo == 'ajax'){
				$p_url = "Javascript:".$this->mp_ajax_ini.'1'.$this->mp_ajax_fin.";";
				$s_url = "Javascript:".$this->mp_ajax_ini.'2'.$this->mp_ajax_fin.";";
			  }
			  /* FIN URL METODO 'nomarl' - 'ajax' */
			  $enlace .= '<a href="'. $p_url .'">1</a>';
			  $enlace .= '<a href="'. $s_url .'">2</a>';
			  $enlace .= "...";
			  for($i = $this->mp_total-$this->mp_rec_fin; $i<=$this->mp_total; $i++){
			  	/* URL METODO 'nomarl' - 'ajax' */
				if($this->mp_modo == 'normal'){
			 	  $i_url = $this->mp_url . $i;
				}elseif($this->mp_modo == 'ajax'){
				  $i_url  = "Javascript:";
				  $i_url .= $this->mp_ajax_ini;
				  $i_url .= $i;
				  $i_url .= $this->mp_ajax_fin;
				  $i_url .= ";";
			    }
				/* FIN URL METODO 'nomarl' - 'ajax' */
				
				if($i == $this->mp_pag){
				  $enlace .= '<span class="current">'.$this->mp_pag."</span>";
				}else{
				  $enlace .= '<a href="'. $i_url .'">'.$i.'</a>';
				}
			  }
	  		}elseif($this->mp_pag <= $this->mp_rec_ini){
			  for($i=1; $i<=($this->mp_rec_ini+1); $i++){
			    /* URL METODO 'nomarl' - 'ajax' */
				if($this->mp_modo == 'normal'){
			 	  $i_url = $this->mp_url . $i;
				}elseif($this->mp_modo == 'ajax'){
				  $i_url  = "Javascript:";
				  $i_url .= $this->mp_ajax_ini;
				  $i_url .= $i;
				  $i_url .= $this->mp_ajax_fin;
				  $i_url .= ";";
			    }
				/* FIN URL METODO 'nomarl' - 'ajax' */
				
				if($i == $this->mp_pag){
				  $enlace .= '<span class="current">'.$this->mp_pag."</span>";
				}else{
				  $enlace .= '<a href="'. $i_url .'">'.$i.'</a>';
				}
			  }
			  /* URL METODO 'nomarl' - 'ajax' */
			  if($this->mp_modo == 'normal'){
			 	$p_url = $this->mp_url . ($this->mp_total-1);
				$s_url = $this->mp_url . $this->mp_total;
			  }elseif($this->mp_modo == 'ajax'){
				$p_url = "Javascript:".$this->mp_ajax_ini.($this->mp_total-1).$this->mp_ajax_fin.";";
				$s_url = "Javascript:".$this->mp_ajax_ini.$this->mp_total.$this->mp_ajax_fin.";";
			  }
			  /* FIN URL METODO 'nomarl' - 'ajax' */
			  $enlace .= "...";
			  $enlace .= '<a href="'. $p_url .'">'.($this->mp_total-1).'</a>';
			  $enlace .= '<a href="'. $s_url .'">'.$this->mp_total.'</a>';
	  	  	}else{
			  /* URL METODO 'nomarl' - 'ajax' */
			  if($this->mp_modo == 'normal'){
			  	$p_url = $this->mp_url . '1';
				$s_url = $this->mp_url . '2';
			 	$r_url = $this->mp_url . ($this->mp_total-1);
				$f_url = $this->mp_url . $this->mp_total;
			  }elseif($this->mp_modo == 'ajax'){
				$p_url = "Javascript:".$this->mp_ajax_ini.'1'.$this->mp_ajax_fin.";";
				$s_url = "Javascript:".$this->mp_ajax_ini.'2'.$this->mp_ajax_fin.";";
				$r_url = "Javascript:".$this->mp_ajax_ini.($this->mp_total-1).$this->mp_ajax_fin.";";
				$f_url = "Javascript:".$this->mp_ajax_ini.$this->mp_total.$this->mp_ajax_fin.";";
			  }
			  /* FIN URL METODO 'nomarl' - 'ajax' */
			  $enlace .= '<a href="'. $p_url .'">1</a>';
			  $enlace .= '<a href="'. $s_url .'">2</a>';
			  $enlace .= "...";
			  
			  for($i=$this->mp_pag-$this->mp_rec_cen; $i<$this->mp_pag+$this->mp_rec_cen; $i++){
			  	/* URL METODO 'nomarl' - 'ajax' */
				if($this->mp_modo == 'normal'){
			 	  $i_url = $this->mp_url . $i;
				}elseif($this->mp_modo == 'ajax'){
				  $i_url  = "Javascript:";
				  $i_url .= $this->mp_ajax_ini;
				  $i_url .= $i;
				  $i_url .= $this->mp_ajax_fin;
				  $i_url .= ";";
			    }
				/* FIN URL METODO 'nomarl' - 'ajax' */
				if($i == $this->mp_pag){
				  $enlace .= '<span class="current">'.$this->mp_pag."</span>";
				}else{
				  $enlace .= '<a href="'. $i_url .'">'.$i.'</a>';
				}
			  }
			  
			  $enlace .= "...";
			  $enlace .= '<a href="'. $r_url .'">'.($this->mp_total-1).'</a>';
			  $enlace .= '<a href="'. $f_url .'">'.$this->mp_total.'</a>';
	  		}
		  
		  }else{
		  	for($i=1; $i<=$this->mp_total; $i++){
			  if($this->mp_pag == $i){
			  	$enlace .= '<span class="current">'.$this->mp_pag.'</span>';
			  }else{
			  	/* URL METODO 'nomarl' - 'ajax' */
				if($this->mp_modo == 'normal'){
			 	  $i_url = $this->mp_url . $i;
				}elseif($this->mp_modo == 'ajax'){
				  $i_url  = "Javascript:";
				  $i_url .= $this->mp_ajax_ini;
				  $i_url .= $i;
				  $i_url .= $this->mp_ajax_fin;
				  $i_url .= ";";
			    }
				/* FIN URL METODO 'nomarl' - 'ajax' */
				
				$enlace .= '<a href="'. $i_url .'">'.$i.'</a>';
			  }
	  	  	}
		  }
		}else{
			$enlace = '<span class="current">1</span>';
		}

		return $enlace;
	}
	
	function show(){
		$mostrar  = '<div class="'. $this->mp_nombre .'">';
		if($this->mp_prev_next != 'none'){
			$mostrar .= $this->anterior();
		}
		$mostrar .= $this->enlaces();
		if($this->mp_prev_next != 'none'){
			$mostrar .= $this->siguiente();
		}
		$mostrar .= '</div>';
		echo $mostrar;
	}
}
?>