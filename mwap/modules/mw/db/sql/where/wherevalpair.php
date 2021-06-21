<?php
class mwmod_mw_db_sql_where_wherevalpair extends mwmod_mw_db_sql_where_abs{
	function __construct($field,$val,$cod=false,$querypart=false){
		$this->set_query_part($querypart);
		$this->field=$field;
		//$this->crit=mysql_real_escape_string($val);
		$this->crit=$val;
		$this->set_cod($cod);
	}
	
	 
	function get_sql_in(){
		return $this->field.$this->cond."'".$this->real_escape_string($this->crit)."'";
		
		//return $this->field.$this->cond."'".$this->crit."'";
	}
	/*
	function get_sql(){
		$sql=$this->get_sql_in();
		return "(".$sql.")";
	}
	*/
	
}
?>