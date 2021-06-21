<?php
class mwcommon_common_currency_currencyman extends mwmod_mw_manager_man{
	//private $def_currency;
	function __construct($code,$ap,$tblname=false){
		$this->init($code,$ap,$tblname);
		$this->enable_treedata();
		
	}
	function create_item($tblitem){
		
		$item=new mwcommon_common_currency_currencyitem($tblitem,$this);
		return $item;
	}
	/*

	final function get_def_currency(){
		if(!$this->def_currency){
			$this->def_currency=new mwerp_erp_man_currency_currencydef($this);
		}
		return $this->def_currency; 	
	}
	final function __get_priv_def_currency(){
		return $this->get_def_currency(); 	
	}
	*/

	function get_new_item_datafield_creator(){
		$cr=new mwmod_mw_datafield_creator();
		$gr=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_group("data"));
		$input=$gr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_input("symbol",$this->get_msg("Símbolo")));
		$input->set_required(true);
		$input=$gr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_input("name",$this->get_msg("Nombre")));
		$input->set_required(true);
		$input=$gr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_input("code",$this->get_msg("Código")));
		$input->set_required(true);
		$input=$gr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_decimal("value",$this->get_msg("Cambio")));
		$input->set_required(true);
		$input=$gr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_checkbox("active",$this->get_msg("Activa")));

		return $cr;
	}

	
	

}
?>