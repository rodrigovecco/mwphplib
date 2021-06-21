<?php
//rvh 2015-01-10 v 1
class mwmod_mw_html_manager_js_jquery extends mwmod_mw_html_manager_item_js{
	
	function __construct($cod="jquery"){
		$this->init_item($cod);
		
	}
	function get_src(){
		if($this->extenal_src){
			return $this->extenal_src;
		}
		return "/res/jquery/jquery-3.5.1.min.js";
	}
	
}
?>