<?php
class  mwmod_mw_util_itemsbycod extends mw_apsubbaseobj{
	var $items=array();
	public $addItemsAssocMode=false;
	public $isEnabledMethod;
	public $defaultItem;
	var $getItemCodMethod="get_id,get_cod";
	function __construct(){
		
	}
	function getDefaultItem(){
		if(isset($this->defaultItem)){
			return $this->defaultItem;	
		}
		if($item=$this->loadDefaultItem()){
			return $this->setDefaultItem($item);
		}
	}
	function loadDefaultItem(){
		return false;	
	}
	function setDefaultItem($item){
		$this->defaultItem=$item;
		return $item;
	}
	function getItem($cod){
		return $this->get_item($cod);	
	}
	function getItems(){
		return $this->get_items();
	}
	function getItemsEnabled($opossite=false){
		return $this->getItemsByMethod($this->isEnabledMethod,$opossite);
	}
	function getItemsByMethod($method,$opossite=false){
		if(!$method){
			return false;	
		}
		$r=array();
		if($items=$this->get_items()){
			foreach($items as $id=>$item){
				$ok=-1;
				if($item->$method()){
					$ok=1;
				}
				if($opossite){
					$ok=$ok*-1;
				}
				if($ok==1){
					$r[$id]=$item;
				}
			}
		}
		
		return $r;
	}
	
	
	function addItemsAssoc($items){
		$n=0;
		if(is_array($items)){
			foreach($items as $id=>$item){
				if($this->add_itemByCod($id,$item)){
					$n++;	
				}
			}
		}
		return $n;
	}
	function addItemsUnssoc($items){
		$n=0;
		if(is_array($items)){
			foreach($items as $id=>$item){
				if($this->add_item($item)){
					$n++;	
				}
			}
		}
		return $n;
	}
	function addItems($items){
		if($this->addItemsAssocMode){
			return $this->addItemsAssoc($items);
				
		}
		return $this->addItemsUnssoc($items);
	}
	
	function get_items(){
		return $this->items;
	}
	
	function get_item($cod){
		if(!$cod){
			return false;	
		}
		return $this->items[$cod];	
	}
	function add_item($item){
		$cod=$this->get_item_cod($item);
		return $this->add_itemByCod($cod,$item);
	}
	function get_item_cod($item){
		if($this->getItemCodMethod){
			$methods=explode(",",$this->getItemCodMethod);
			foreach($methods as $m){
				if($m=trim($m)){
					if(method_exists($item,$m)){
						if($c=$item->$m()){
							return $c;	
						}
					}
				}
				
			}
		}
	}
	function add_itemByCod($cod,$item){
		if(!$cod){
			return false;	
		}
		$this->items[$cod]=$item;
		return $item;	
	}
	
}
?>