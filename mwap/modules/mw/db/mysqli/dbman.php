<?php
class mwmod_mw_db_mysqli_dbman extends mwmod_mw_db_dbman{
	function __construct($ap){
		$this->init($ap);	
	}
	//db methods
	function do_connect($cfg){
		if($cfg["port"]){
			@$mysqli=new mysqli($cfg["host"],$cfg["user"],$cfg["pass"],$cfg["db"],$cfg["port"] );
		}else{
			@$mysqli=new mysqli($cfg["host"],$cfg["user"],$cfg["pass"],$cfg["db"]);
		}
		if ($mysqli->connect_error) {
			return false;	
		}
		if($cfg["charset"]){
			$mysqli->set_charset($cfg["charset"]);
		}
		return $mysqli;
		
		/*
		if ($dblink=mysql_connect ($cfg["host"],$cfg["user"],$cfg["pass"],true)){
			
			if (mysql_selectdb($cfg["db"],$dblink)){
				mysql_set_charset($cfg["charset"],$dblink); 
				return $dblink;
						
			}
			
		}
		*/
			
	}
	function query($sql){
		if(!$l=$this->get_link()){
			return false;	
		}
		return $l->query($sql);
		/*
	
		if($query=mysql_query($sql,$l)){
			return 	$query;
		}
		*/
	}
	function fetch_array($query){
		if(!$query){
			return false;	
		}
		if(!is_object($query)){
			return false;	
		}
		return $query->fetch_array(MYSQLI_BOTH);
		//fetch_array 
		
		
		//return mysql_fetch_array($query);	
	}
	
	function fetch_assoc($query){
		if(!$query){
			return false;	
		}
		if(!is_object($query)){
			return false;	
		}
		return $query->fetch_assoc();
		
		
		//return mysql_fetch_assoc($query);	
	}
	function real_escape_string($txt){
		if(!$l=$this->get_link()){
			return false;	
		}
		return $l->real_escape_string($txt);
		
		//return mysql_real_escape_string($txt);	
	}
	function insert($sql){
		if(!$l=$this->get_link()){
			return false;	
		}
		if($l->real_query($sql)){
			return $l->insert_id;
		}
		/*
		if($query=mysql_query($sql,$l)){
			return 	mysql_insert_id($l);
		}
		*/
	}
	function query_get_affected_rows($sql){
		if(!$l=$this->get_link()){
			return false;	
		}
		$this->query($sql);
		return $l->affected_rows;
		/*
	
		if($query=mysql_query($sql,$l)){
			return 	mysql_affected_rows($l);
		}
		*/
	}
	function get_error(){
		if(!$l=$this->get_link()){
			
			return false;	
		}
		$l->error;
		//return mysql_error($l);
			
	}
	function get_errorno(){
		if(!$l=$this->get_link()){
			
			return false;	
		}
		$l->errorno;
		//return mysql_errno($l);
			
	}
	function affected_rows(){
		if(!$l=$this->get_link()){
			
			return false;	
		}
		return $l->affected_rows;
		//return mysql_affected_rows($l);
			
	}
	

	
	
	
}
?>