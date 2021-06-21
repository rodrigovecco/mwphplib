<?php
class mwmod_mw_jsobj_inputs_gr extends mwmod_mw_jsobj_inputs_def{
	function __construct($cod,$type=false,$def_js_class_pref=false){
		$this->def_js_class="mw_datainput_item_group";
		$this->def_js_class_type="group";
		if($def_js_class_pref){
			$this->setJSClassPref($def_js_class_pref);
		}
		$this->init_js_input_type_mode($cod,$type);
	}
	function setTitleMode($lbl=false,$type="groupwithtitle"){
		if(!$type){
			$type="groupwithtitle";	
		}
		$this->set_js_type($type);
		if($lbl){
			$this->set_prop("lbl",$lbl);	
		}
		
	}
}
?>