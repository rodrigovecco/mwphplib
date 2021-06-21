<?php
class mwmod_mw_placeholder_src_itemjsonparams extends mwmod_mw_placeholder_src_item{
	function __construct($cod,$value){
		$this->init($cod);
		$this->set_value($value);
	}
	function get_text($strparams=false){
		
		@$params=json_decode($strparams,true);
		if(!is_array($params)){
			$params=array();	
		}
		if(!$this->text){
			if($params["ifemtpy"]){
				return 	$params["ifemtpy"];
			}
			return "";
		}
		$b=$params["before"]."";
		$a=$params["after"]."";
		return $b.$this->text.$a;
	}
	
}
?>