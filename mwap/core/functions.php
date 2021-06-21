<?php
function mw_get_autoload_manager(){
	if($GLOBALS["__mw_autoload_manager"]){
		if(is_object($GLOBALS["__mw_autoload_manager"])){
			if(is_a($GLOBALS["__mw_autoload_manager"],"mw_autoload_manager")){
				return $GLOBALS["__mw_autoload_manager"];	
			}
			
		}
	}
	return false;

}
function mw_get_main_ap(){
	if($GLOBALS["__mw_main_ap"]){
		if(is_object($GLOBALS["__mw_main_ap"])){
			if(is_a($GLOBALS["__mw_main_ap"],"mwmod_mw_ap_apabs")){
				return $GLOBALS["__mw_main_ap"];	
			}
			
		}
	}
	return false;

}

?>