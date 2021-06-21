<?php
class mwmod_mw_db_sql_where_textcomp extends mwmod_mw_db_sql_where_wherevalpair{
	public $textCompareMode="contains";
	function __construct($field,$val,$cod=false,$querypart=false){
		$this->set_query_part($querypart);
		$this->field=$field;
		$this->crit=$val;
		$this->set_cod($cod);
	}
	function setCompareMode($mode){
		$this->textCompareMode=$mode;
	}
	function getCompareMode(){
		$clause=$this->textCompareMode;
		switch ($clause) {
			case "startswith":
			case "endswith":
			case "contains":
			case "notcontains": {
				return $clause;	
			}
		}
		return "contains";
	
	}
	
	
	 
	function get_sql_in(){
		$r=$this->field;
		$val=$this->crit;
		//$val = addcslashes($val, "%_");
		$val=$this->real_escape_string($val);
		//
		$mode=$this->getCompareMode();
		if($mode=="notcontains"){
			$r.=" NOT";	
		}
		$r.=" LIKE ";
		if($mode=="startswith"){
			$r.="'$val%'";	
		}
		if($mode=="endswith"){
			$r.="'%$val'";	
		}
		if($mode=="contains"){
			$r.="'%$val%'";	
		}
		if($mode=="notcontains"){
			$r.="'%$val%'";	
		}
		return $r;
	}
	
}
?>