<?php
abstract class mwmod_mw_ui_base_basesubui extends mwmod_mw_ui_sub_uiabs{
	
	function get_lngmsgsmancod(){
		return $this->mainAp->get_lngmsgsmancod();	
	}
	
	/*Todos los permisos configurados solo para admin*/
	function is_allowed(){
		return $this->allow_admin();	
	}
	function allow_admin(){
		return $this->allow("admin");	
	}
	function allow_edit(){
		return $this->allow("admin");	
	}
	function allow_view(){
		if($this->allow("admin")){
			return true;	
		}
		return false;
			
	}
	
}
?>