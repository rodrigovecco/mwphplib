<?php
abstract class mwmod_mw_templates_basetemplate extends mw_apsubbaseobj{
	var $htmlclasspref="sys_inteface_main";
	function get_html_class($code){
		if(!$code){
			return false;	
		}
		if(!is_string($code)){
			return false;	
		}
		$method="get_html_class_".$code;
		if(method_exists($this,$method)){
			return $this->$method;	
		}
		return $this->htmlclasspref."_".$code;
	}
	function get_html_tag_open($classcode=false,$tagname="div"){
		if($class=$this->get_html_class($classcode)){
			return "<".$tagname." class='".$class."'>";	
		}
		return "<".$tagname.">";	
	}
	
	
	
	
	function __call($a,$b){
		return false;	
	}
	
}
?>