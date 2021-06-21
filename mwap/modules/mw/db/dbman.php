<?php
class mwmod_mw_db_dbman extends mw_apsubbaseobj{
	private $__dbcfg;
	private $__dblink;
	private $_connected;
	private $_tblmanagers;
	function __construct($ap){
		$this->init($ap);	
	}
	//db methods
	function do_connect($cfg){
		if ($dblink=mysql_connect ($cfg["host"],$cfg["user"],$cfg["pass"],true)){
			if (mysql_selectdb($cfg["db"],$dblink)){
				mysql_set_charset($cfg["charset"],$dblink); 
				return $dblink;
						
			}
		}
			
	}
	function query($sql){
		if(!$l=$this->get_link()){
			return false;	
		}
	
		if($query=mysql_query($sql,$l)){
			return 	$query;
		}
	}
	function fetch_array($query){
		
		
		return mysql_fetch_array($query);	
	}
	
	function fetch_assoc($query){
		
		
		return mysql_fetch_assoc($query);	
	}
	function real_escape_string($txt){
		return mysql_real_escape_string($txt);	
	}
	function insert($sql){
		if(!$l=$this->get_link()){
			return false;	
		}
		if($query=mysql_query($sql,$l)){
			return 	mysql_insert_id($l);
		}
	}
	function exec_delete($sql,$unsafe=false){
		if($unsafe){
			$this->query("SET SQL_SAFE_UPDATES = 0;");	
		}
		$r=$this->query_get_affected_rows($sql);
		if($unsafe){
			$this->query("SET SQL_SAFE_UPDATES = 1;");	
		}
		return $r;
			
	}
	function query_get_affected_rows($sql){
		if(!$l=$this->get_link()){
			return false;	
		}
	
		if($query=mysql_query($sql,$l)){
			return 	mysql_affected_rows($l);
		}
	}
	function get_error(){
		if(!$l=$this->get_link()){
			
			return false;	
		}
		return mysql_error($l);
			
	}
	function get_errorno(){
		if(!$l=$this->get_link()){
			
			return false;	
		}
		return mysql_errno($l);
			
	}
	function affected_rows(){
		if(!$l=$this->get_link()){
			
			return false;	
		}
		return mysql_affected_rows($l);
			
	}
	
	///////////////////
	function get_value_as_date($val){
		if(!$val){
			return "";	
		}
		
		if($val=="0000-00-00"){
			return "";	
		}
		if($val=="0000-00-00 00:00:00"){
			return "";	
		}
		return $val;
		
	}

	function fix_data_values(&$data,$date_keys){
		if(!is_array($date_keys)){
			$date_keys=explode(",",$date_keys);	
		}
		foreach($date_keys as $cod){
			$data[$cod]=$this->get_value_as_date($data[$cod]);	
		}
		return $data;
	}
	function format_time($time=true){
		if($time===true){
			$time=time();	
		}elseif(is_string($time)){
			$time=strtotime($time);	
		}
		if(!$time){
			return false;	
		}
		if(!is_numeric($time)){
			return false;	
		}
		return date("Y-m-d H:i:s",$time);
	}
	
	
	
	function get_array_data_from_sql($sql,$idfield="id"){
		//modificado 2013-10-05
		if(!$query=$this->query($sql)){
			return false;
		}
		$num=0;
		while ($data=$this->fetch_assoc($query)){
			$num++;
			if($idfield){
				$id=$data[$idfield];
			}else{
				$id=$num;
			}
			$r[$id]=$data;	
		}
		return $r;
	}

	function get_array_from_sql($sql){
		if(!$q=$this->query($sql)){
			return false;	
		}
		return $this->fetch_assoc($q);
	}
	function get_field_from_sql($sql,$field){
		if(!$data=$this->get_array_from_sql($sql)){
			return false;	
		}
		return $data[$field];
	}

	function get_tbl_manager($cod){
		if(!$cod=$this->check_str_key($cod)){
			return false;	
		}
		if(!$this->_init_tbl_managers()){
			return false;	
		}
		return $this->_tblmanagers[$cod];
		
	}

	final function get_tbl_managers(){
		if(!$this->_init_tbl_managers()){
			return false;	
		}
		return $this->_tblmanagers;
	}
	function create_tbl_man_def($tbl){
		if(!$tbl=$this->check_str_key($tbl)){
			return false;	
		}
		$man=new mwmod_mw_db_tbl($this,$tbl);
		return $man;
			
	}
	function create_tbl_man($tbl){
		if(!$tbl=$this->check_str_key($tbl)){
			return false;	
		}
		$method="create_tbl_man_tbl_".$tbl;
		if(method_exists($this,$method)){
			return $this->$method($tbl);	
		}
		return $this->create_tbl_man_def($tbl);	
	
			
	}
	function create_tbl_managers(){
		$sql = "SHOW TABLES";
		
		if(!$query=$this->query($sql)){
			return false;	
		}
		if ($array=$this->fetch_array($query)){
			do{
				if($tbl=$array[0]){
					if($man=$this->create_tbl_man($tbl)){
						$r[$tbl]=$man;	
					}
				}
			}while ($array=$this->fetch_array($query));
		}
		if($r){
			return $r;	
		}
			
	}
	private function _init_tbl_managers(){
		if(isset($this->_tblmanagers)){
			return true;	
		}
		if(!$mans=$this->create_tbl_managers()){
			return false;
		}
		if(is_array($mans)){
			$this->_tblmanagers=$mans;
			return true;	
		}
	}
	
	function query_debug($sql){
		$r=array();
		$r["sql"]=$sql;
		$r["ok"]=false;
		if(!$l=$this->get_link()){
			$r["error"]="DB not connected";
			return $r;	
		}
	
		//if($query=mysql_query($sql,$l)){
		if($query=$this->query($sql)){
			$r["result"]=$query;
			$r["ok"]=true;
			$r["affected_rows"]=$this->affected_rows();
			
			//return 	$query;
		}else{
			$r["error"]=$this->get_error();	
			$r["errornum"]=$this->get_errorno();	
		}
		return $r;	
	}
	
	final function get_link(){
		if(!$this->_connected){
			return false;	
		}
		
		return $this->__dblink;	
	}
	final function connect(){
		if(	$this->_connected){
			return true;	
		}
		if(!$cfg=$this->__dbcfg){
			return false;	
		}
		unset($this->__dblink);	
		
		if ($dblink=$this->do_connect($cfg)){
			$this->__dblink=$dblink;
			$this->_connected=true;
			return true;
			
		}
		/*
		if ($dblink=mysql_connect ($cfg["host"],$cfg["user"],$cfg["pass"],true)){
			if (mysql_selectdb($cfg["db"],$dblink)){
				$this->__dblink=$dblink;
				
				mysql_set_charset($cfg["charset"],$this->__dblink); 
				$this->_connected=true;
				return true;
			}
		}
		*/

	}
	final function unconnect(){
		unset($this->_connected);	
		unset($this->__dblink);	
	}
	final function set_db_cfg($cfg){
		$this->unconnect();
		unset($this->__dbcfg);	
		if(!is_array($cfg)){
			return false;	
		}
		if(!$cfg["host"]){
			return false;	
		}
		if(!$cfg["user"]){
			return false;	
		}
		if(!$cfg["db"]){
			return false;	
		}
		if(!$cfg["pass"]){
			return false;	
		}
		if(!$cfg["charset"]){
			$cfg["charset"]="utf8";	
		}
		$this->__dbcfg=$cfg;
		return true;
	}
	final function init($ap){
		$this->set_mainap($ap);	
	}

	
	
	
}
?>