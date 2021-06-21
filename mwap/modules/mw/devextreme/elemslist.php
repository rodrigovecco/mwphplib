<?php
class mwmod_mw_devextreme_elemslist extends mw_apsubbaseobj{
	private $_items=array();	
	
	function get_items(){
		$r=array();
		if($items=$this->get_all_items()){
			foreach($items as $cod=>$item){
				if($item->is_active()){
					$r[$cod]=$item;	
				}
			}
		}
		return $r;
	}
	final function get_item($cod){
		if(!$cod){
			return false;	
		}
		return $this->_items[$cod];	
	}
	final function get_all_items(){
		return $this->_items;	
	}
	final function add_item($item){
		if(!$cod=$item->get_cod()){
			return false;	
		}
		$this->_items[$cod]=$item;
		return $item;
	}
}
?>