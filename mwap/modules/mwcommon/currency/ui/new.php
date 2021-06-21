<?php
class mwcommon_common_currency_ui_new extends mwmod_mw_ui_itemsman_new{
	function __construct($cod,$parent){
		$this->init_as_subinterface($cod,$parent);
		$this->set_def_title($this->get_msg("Crear"));
		$this->set_items_man_cod("currency");
		
	}

}
?>