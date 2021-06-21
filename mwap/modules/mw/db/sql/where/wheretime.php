<?php
class mwmod_mw_db_sql_where_wheretime extends mwmod_mw_db_sql_where_abs{
	var $time;
	var $include_hour=false;
	
	function __construct($field,$timeOrDate,$cod=false,$querypart=false){
		$this->set_query_part($querypart);
		$this->field=$field;
		//$this->crit=mysql_real_escape_string($val);
		//$this->crit=$val;
		$this->set_cod($cod);
		$this->set_time($timeOrDate);
	}
	function set_time($timeOrDate){
		if(!$timeOrDate){
			return false;	
		}
		
		if($time=$this->helper->dateman->checkTimeOrDate($timeOrDate)){
			$this->time=$time;
			return $this->time;	
		}
		
	}
	
	function is_ok(){
		if($this->time){
			return true;	
		}
		return false;
	}
	
	function get_sql_formated_left(){
		if(!$this->include_hour){
			return "DATE_FORMAT(".$this->field.",'%Y-%m-%d')";	
		}
		return $this->field;
		
	}
	function get_sql_formated_right(){
		if(!$this->time){
			return "";	
		}
		return $this->helper->dateman->get_sys_date($this->time,$this->include_hour);
	}
	function get_sql_in(){
		if(!$this->is_ok()){
			return "";	
		}
		return $this->get_sql_formated_left().$this->cond."'".$this->real_escape_string($this->get_sql_formated_right())."'";
		
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