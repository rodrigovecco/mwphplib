<?php
/*
Puedan todos los seres lograr la felicidad y sus causas.
Puedan todos los seres estar libres del sufrimiento y de sus causas.
Puedan todos los seres no estar separados del gran gozo, libre de todo sufrimiento.
Puedan todos los seres morar en la gran ecuanimidad, libre de los extremos del apego y la aversión sin considerar a unos cercanos y a otros distantes.

Dedico todos los méritos de la realización de este trabajo al beneficio de todos los seres.

Que la preciosa mente del despertar nazca donde no ha nacido.
Y donde ya existe que se incremente más y más.

OM MANI PADME HUM

TAYATA OM GAT GATE PARAGATA PARASAMGATE BODHI SOHA

TAYATA OM MINI MUNI MAHA MUNIYE SOHA

OM BENZA SATO SAMAYA MANU PALAYA BENZA SATO TENOPA TIDRA DRIHO MEKHABA SUTO KAYO MEKHABA
SUPO KAYO MEKHABA ANU RAKTO MEKHABA SRWA SIDDHI ME PRA YATSA KARMA TU TSAME
SITAM SIDDHI YAM KURU HUN HAHAHAHAHO BAGAVAN SARWA TATAGATA BENZA MAME MUNTZA BENSA BAWA MAHA 
SAMAYA SATO AH

TAYATA OM BEKANZHE BEKANZHE MAHA BEKANZHE RAZA SAMUGATE SOHA

*/
abstract class  mw_baseobj{
	function __accepts_exec_cmd_by_url(){
		return false;	
	}
	function check_str_key_alnum_underscore($cod){
		if(!$cod=$this->check_str_key($cod)){
			return false;	
		}
		if(ctype_alnum($cod)){
			return $cod;	
		}
		$list=explode("_",$cod);
		foreach($list as $c){
			if(!ctype_alnum($c)){
				return false;	
			}
		}
		return implode("_",$list);
	}
	function check_str_key($cod){
		if(!$cod){
			return false;	
		}
		if(!is_string($cod)){
			return false;	
		}
		return $cod;
			
	}
	function __get($name){
		if(!$name){
			return false;	
		}
		if(!is_string($name)){
			return false;	
		}
		$method="__get_priv_".$name;
		if(method_exists($this,$method)){
			return $this->$method();	
		}
	}
	
	
	
	
}
abstract class  mw_apsubbaseobj extends mw_baseobj{
	private $mainap;
	private $____lng_order;
	private $__ap_submanager_cod;
	
	//private $lngmsgsmancod="def";
	private $lngmsgsmancod;
	
	final function set_lngmsgsmancod($cod){
		if($cod){
			$this->lngmsgsmancod=$cod;
			return true;
		}
	}
	function get_lngmsgsmancod(){
		return "def";	
	}
	final function __get_priv_lngmsgsmancod(){
		if(!isset($this->lngmsgsmancod)){
			
			$this->lngmsgsmancod=$this->get_lngmsgsmancod();	
		}
		
		return $this->lngmsgsmancod;
	}
	function lng_get_msg_txt($cod,$def=false,$params=false){
		if($man=$this->get_msgs_man_specific()){
			return $man->get_msg_txt($cod,$def,$params);
		}
		return $this->lng_common_get_msg_txt($cod,$def,$params);
	}
	function lng_common_get_msg_txt($cod,$def=false,$params=false){
		if($man=$this->get_msgs_man_common()){
			return $man->get_msg_txt($cod,$def,$params);
		}
		return $def;
	}
	function get_msgs_man_specific(){
		
		if($ap=$this->__get_priv_mainap()){
			//return $ap->get_msgs_man($this->lngmsgsmancod);
			return $ap->get_msgs_man($this->__get_priv_lngmsgsmancod());
		}
		
	}
	function get_msgs_man_common(){
		if($ap=$this->__get_priv_mainap()){
			return $ap->get_msgs_man_common();
		}
	}
	final function set_lngmsgsmancod_by_obj($obj){
		return $this->set_lngmsgsmancod($obj->lngmsgsmancod);
	}
	final function __get_lngmsgsmancod(){
		return $this->__lngmsgsmancod;	
	}
	
	final function __get_ap_submanager_cod(){
		return $this->__ap_submanager_cod;	
	}
	function get_exec_cmd_url($cmd,$params=array(),$filename=false){
		$this->__get_priv_mainap();
		if(!$url=$this->mainap->get_submanagerexeccmdurl()){
			return false;	
		}
		if(!$cod=$this->__get_ap_submanager_cod()){
			return false;
		}
		
		$url.="/".$cod."/".$cmd;
		if(is_array($params)){
			foreach($params as $c=>$v){
				$url.="/$c/$v";	
			}
		}
		if($filename){
			$url.="/$filename";		
		}
		return $url;
	}

	final function __set_ap_submanager_cod($cod){
		return $this->__ap_submanager_cod=$cod;	
	}
	private function ___init_main_ap(){
		if(!isset($this->mainap)){
			$this->mainap=mw_get_main_ap();	
		}
	}
	final function set_mainap($ap=false){
		if(!$ap){
			$ap=mw_get_main_ap();
		}
		$this->mainap=$ap;	
	}
	final function __get_priv_mainap(){
		$this->___init_main_ap();
		return $this->mainap; 	
	}
	private function ___set_lng_indexes_for_codes(){
		if(isset($this->____lng_order)){
			return;	
		}
		$this->____lng_order=false;
		if($a=$this->get_lng_order()){
			$this->____lng_order=array();
			foreach($a as $index=>$code){
				$this->____lng_order[$code]=$index;	
			}
		}
		return;
	}
	final function _get_lng_index_for_codes(){
		$this->___set_lng_indexes_for_codes();
		return $this->____lng_order;
	}
	
	final function _get_lng_index_for_code($code){
		$this->___set_lng_indexes_for_codes();
		if($this->____lng_order){
			return $this->____lng_order[$code];	
		}
		return false;
	}
	
	function get_lng_order(){
		return false;	
	}
	function get_msg(){
		$this->__get_priv_mainap();
		if(!$man=$this->mainap->get_submanager("lng")){
			return false;
		}
		$msgslist=func_get_args();
		return $man->get_msg_by_list($msgslist,$this);
	}
	
	function get_msg_by_code(){
		$this->__get_priv_mainap();
		if(!$man=$this->mainap->get_submanager("lng")){
			return false;
		}
		$msgslist=func_get_args();
		return $man->get_msg_by_list_and_code($msgslist,$this);
	}


	
	
}
?>