<?php
class mwmod_mw_ui_debug_tests_timezones extends mwmod_mw_ui_sub_uiabs{
	function __construct($cod,$parent){
		$this->init_as_subinterface($cod,$parent);
		
		$this->set_def_title("Timezones");
		
		
	}
	
	function do_exec_no_sub_interface(){
	}
	function do_exec_page_in(){
		$man=$this->mainap->get_submanager("dateman");
		
		$data=array();
		if($items=$man->timezones->get_all_items()){
			foreach($items as $id=>$item){
				$data[$id]=$item->get_debug_data();
			}
		}
		
		mw_array2list_echo($data);

		
	}
	function is_allowed(){
		return $this->allow("debug");	
	}
	
}
?>