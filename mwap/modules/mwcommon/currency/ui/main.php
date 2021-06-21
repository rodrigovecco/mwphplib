<?php
class mwcommon_common_currency_ui_main extends mwmod_mw_ui_itemsman_main{
	function __construct($cod,$parent){
		$this->init_as_main_or_sub($cod,$parent);
		$this->set_def_title($this->get_msg("Monedas"));
		$this->set_items_man_cod("currency");
		
	}
	
	function add_mnu_items($mnu){
		$this->add_2_mnu($mnu);
		$this->add_sub_interface_to_mnu_by_code($mnu,"new");
	}
	
	
	function load_all_subinterfases(){
		
		$si=$this->add_new_subinterface(new mwcommon_common_currency_ui_new("new",$this));
		//$si=$this->add_new_subinterface(new mwerp_erp_man_currency_ui_cfg("cfg",$this));
		$si=$this->add_new_subinterface(new mwcommon_common_currency_ui_edit("edit",$this));
		
	}
	
	function do_exec_page_in_items_list(){
		if(!$items=$this->get_items()){
			return $this->do_exec_page_in_no_items();	
		}
		$tbl=$this->new_tbl_template();
		$tits=array(
			"id"=>$this->get_msg("ID"),
			"name"=>$this->get_msg("Nombre"),
			"code"=>$this->get_msg("Código"),
			//"valueinfo"=>$this->get_msg("Cambio"),
			"_mnu"=>""
		);
		echo $tbl->get_tbl_open_header_and_set_cols_cods($tits);
		
		foreach($items as $id=>$item){
			$data=$item->get_data();
			$url=$this->get_url_subinterface("edit",array("iditem"=>$id));
			//$data["valueinfo"]=$item->currency->get_exchange_rate_formated();
			$data["_mnu"]="<a href='$url'>".$this->get_msg("EDITAR")."</a>";
			echo $tbl->get_row_ordered($data);	
		}
		echo $tbl->get_tbl_close();

	}

}
?>