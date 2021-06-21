<?php
class  mwmod_mw_manager_related_relation_tbl extends mwmod_mw_manager_related_relation_abs{
	var $tbl_name;
	var $tbl_field_name;
	var $rel_items_tbl_id_key="id";
	
	private $tblman;
	function __construct($tbl_name,$tbl_field_name){
		$this->tbl_name=$tbl_name;
		$this->tbl_field_name=$tbl_field_name;
		
	}
	
	function load_related_items_data(){
		if(!$key=$this->rel_items_tbl_id_key){
			return false;
		}
		if(!$f=$this->tbl_field_name){
			return false;
		}
		if(!$tblman=$this->get_tblman()){
			return false;	
		}
		if(!$query=$tblman->new_query()){
			return false;	
		}
		if(!$id=$this->get_mainitem_id()){
			return false;	
		}
		$query->select->add_count($key,"num",true);
		$query->select->add_group_concat($key,"ids",true);
		$query->where->add_where_crit($f,$id);
		//echo $query->get_sql()."<br>";
		return $query->get_one_row_result();
		
		
	}
	function get_tblman(){
		return $this->__get_priv_tblman();	
	}
	function load_tblman(){
		if(!$this->tbl_name){
			return false;	
		}
		if(!$db=$this->mainap->get_submanager("db")){
			return false;	
		}
		if($tblman=$db->get_tbl_manager($this->tbl_name)){
			return $tblman;
		}

	}
	final function __get_priv_tblman(){
		if(!isset($this->tblman)){
			if(!$this->tblman=$this->load_tblman()){
				$this->tblman=false;	
			}
		}
		return $this->tblman;
	}


}
?>