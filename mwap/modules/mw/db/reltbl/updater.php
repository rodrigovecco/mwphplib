<?php
//maneja una tabla que vincula dos tablas
//se usa al salvar y crear relaciones con mwmod_mw_db_reltbl
class mwmod_mw_db_reltbl_updater extends mw_apsubbaseobj{
	public $cod;
	public $rel;
	public $isSecondary=false;
	public $editable=true;
	
	function __construct($cod,$rel,$isSecondary=false){
		$this->cod=$cod;
		$this->rel=$rel;
		$this->isSecondary=$isSecondary;
	}
	function add2Query($query){
		$this->rel->add2Query($this->cod,$query,$this->isSecondary);
	}
	function getMainField(){
		if($this->isSecondary){
			return $this->rel->elem2field;	
		}else{
			return $this->rel->elem1field;		
		}
	}
	function getSecondaryField(){
		if($this->isSecondary){
			return $this->rel->elem1field;		
		}else{
			return $this->rel->elem2field;	
		}
	}
	
	function doUpdateList($mainItemID,$relatedItsmsIds){
		if(!$this->editable){
			return;	
		}
		if($this->isSecondary){
			return $this->rel->doUpdateListForElem2($mainItemID,$relatedItsmsIds);	
		}else{
			return $this->rel->doUpdateListForElem1($mainItemID,$relatedItsmsIds);	
		}
		
	}
	function get_cod(){
		return $this->cod;	
	}
}


?>