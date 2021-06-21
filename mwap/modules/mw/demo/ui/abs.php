<?php
abstract class mwmod_mw_demo_ui_abs extends mwmod_mw_ui_sub_uiabs{
	function is_allowed(){
		return $this->allow("debug");	
	}
	
}
?>