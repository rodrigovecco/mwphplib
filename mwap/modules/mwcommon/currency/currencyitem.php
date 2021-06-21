<?php
class mwcommon_common_currency_currencyitem extends mwmod_mw_manager_item{
	//private $currency;
	function __construct($tblitem,$man){
		$this->init($tblitem,$man);
		//$this->set_currency(new mwerp_erp_man_currency_currencyadd($man,$this));	
	}
	/*
	final function __get_priv_currency(){
		return $this->currency; 	
	}

	
	final function set_currency($c){
		$this->currency=$c;
	}
	*/
	

}
?>