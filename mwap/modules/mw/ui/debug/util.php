﻿<?php
class mwmod_mw_ui_debug_util extends mwmod_mw_ui_sub_uiabs{
	function mwmod_mw_ui_debug_util($cod,$parent){
		$this->init_as_subinterface($cod,$parent);
		$this->set_def_title($this->get_msg("Util"));
		$this->subinterface_def_code="privprops";
		
	}
	function add_2_sub_interface_mnu($mnu){
		
		if($subs=$this->get_subinterfaces_by_code("privprops",true)){
			$item=new mwmod_mw_mnu_items_dropdown_single($this->get_cod_for_mnu(),$this->get_mnu_lbl(),$mnu,$this->get_url());
			$mnu->add_item_by_item($item);
			foreach($subs as $su){
				$sitem=new mwmod_mw_mnu_mnuitem($su->get_cod_for_mnu(),$su->get_mnu_lbl(),$item,$su->get_url());
				$item->add_item_by_item($sitem);
				if($su->is_current()){
					$sitem->active=true;	
				}
			}
		}

	}
	
	function load_all_subinterfases(){
		
		$si=$this->add_new_subinterface(new mwmod_mw_ui_debug_util_privprops("privprops",$this));
	}
	/*
	function add_mnu_items($mnu){
		$this->add_sub_interface_to_mnu_by_code($mnu,"panels");
		
		
	}
	*/
	
	function do_exec_no_sub_interface(){
	}
	function do_exec_page_in(){
		
		
	}
	function is_allowed(){
		if($this->parent_subinterface){
			return 	$this->parent_subinterface->is_allowed();
		}
	}
	
}
?>