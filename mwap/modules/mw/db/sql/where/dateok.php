<?php
class mwmod_mw_db_sql_where_dateok extends mwmod_mw_db_sql_where_abs{
	function __construct($field,$cod=false,$querypart=false){
		$this->set_query_part($querypart);
		$this->field=$field;
		$this->set_cod($cod);
		$this->set_operator(">");
	}
	function get_sql_formated_left(){
		return $this->field;
		
	}
	function get_sql_formated_right(){
		return "0000-00-00";	
	}
	function get_sql_in(){
		if(!$this->is_ok()){
			return "";	
		}
		return $this->get_sql_formated_left().$this->cond."'".$this->real_escape_string($this->get_sql_formated_right())."'";
		
	}
	
}
?>