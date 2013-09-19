<?
class mySQL{
	var $cnx;
	var $selectDB;
	var $result;
	var $isConect;
	var $cadconsulta;
	
	function mySQL($uname, $pname, $bname){
		$this->conectar($uname, $pname, $bname);
	}
	
	function conectar($uname, $pname, $bname){
		$this->cnx = mysql_connect(DB_HOST,$uname,$pname) or die('Error conectando con el servidor de la Base de Datos');
		$this->selectDB = mysql_select_db($bname,$this->cnx);
		mysql_query('SET NAMES \'utf8\'');
		return $this->cnx;
	}
	
	function simpleQuery(){
		if($this->cadconsulta!=''){
			$this->result = mysql_query($this->cadconsulta,$this->cnx);
			return $this->result;
		}else{
			$this->result = '';
			echo 'No hay nada en la para buscar';
		}
	}
	
	function resultset($f,$cam){
		if($this->result){
			return mysql_result($this->result,$f,$cam);
		}
	}
	
	function query($sql){
		$this->cadconsulta = $sql; $this->simpleQuery();
		if($this->result){return $this->result;}
		return FALSE;
	}
	
	function fetchObject($sql){
		$this->cadconsulta = $sql; $this->simpleQuery();
		if($this->result){
			return mysql_fetch_object($this->result);
		}
		return FALSE;
	}
	
	function fetchArray(){
		$this->cadconsulta = $sql; $this->simpleQuery();
		if($this->result){
			return mysql_fetch_array($this->result);
		}
		return FALSE;
	}
	
	function numRows(){
		$this->cadconsulta = $sql; $this->simpleQuery();
		if($this->result){
			return mysql_num_rows($this->result);
		}
		return FALSE;
	}
	
	function desconectar(){
		$cerrar = mysql_close($this->cnx);
		return $cerrar;
	}
}
?>