<?php
class mwmod_mw_db_sql_limit extends mwmod_mw_db_sql_querypart{
	var $active=false;
	var $from;
	var $num;
	function __construct($query=false){
		if($query){
			$this->set_query($query);	
		}
	}
	function set_limit($num,$from=false){
		$this->num=intval(abs($num));
		if(!$from){
			$this->from=false;	
		}else{
			$this->from=intval(abs($from));
		}
		//$this->from=$from;
		
	}
	function get_sql(){
		$sql="";
		if(!$this->num){
			return "";	
		}
		
		if(!is_int($this->num)){
			return "";	
		}
		if(is_int($this->from)){
			return " limit $this->num OFFSET $this->from ";	
		}
		return " limit $this->num ";	
		
	}

	/*
	function add_from_join($tbl,$external_field,$as=false){
		if(is_string($tbl)){
			$item = new mwmod_mw_db_sql_from_tbl($tbl,$as,$this);
			$item->external_join_field=$external_field;
			return $this->add_item($item);
		}
	}
	function add_from($tbl,$as=false){
		if(is_string($tbl)){
			$item = new mwmod_mw_db_sql_from_tbl($tbl,$as,$this);
			return $this->add_item($item);
		}
	}
	*/
	function get_sql_start(){
		return " from ";	
	}

	
}
?>