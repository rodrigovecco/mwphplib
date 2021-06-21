<?php
class mwmod_mw_date_timezone_tzman extends mwmod_mw_manager_man{
	private $GMT;
	function __construct($code,$ap,$tblname=false){
		$this->init($code,$ap,$tblname);
	}
	final function __get_priv_GMT(){
		if(!isset($this->GMT)){
			$this->GMT=new DateTimeZone("GMT");
		}
		return $this->GMT;
	}
	
	
	function create_item($tblitem){
		
		$item=new mwmod_mw_date_timezone_tzitem($tblitem,$this);
		return $item;
	}
	
	function get_item_name($item){
		return $item->get_data("zone_name");	
		
	}

}
?>