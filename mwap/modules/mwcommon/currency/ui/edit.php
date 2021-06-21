<?php
class mwcommon_common_currency_ui_edit extends mwmod_mw_ui_itemsman_edit{
	function __construct($cod,$parent){
		$this->init_as_subinterface($cod,$parent);
		$this->set_def_title($this->get_msg("Editar"));
		$this->set_items_man_cod("currency");
		
	}

}
?>