<?php

class mwmod_mw_users_rols_rolsman extends mw_apsubbaseobj{
	private $_items=array();
	private $usersman;
	private $_tbl_fields_fixed=false;
	function __construct($usersman){
		$this->init($usersman);	
	}
	final function __get_priv_usersman(){
		return $this->usersman; 	
	}

	function fix_tbl_fields(){
		return $this->do_fix_tbl_fields();
	}
	final function do_fix_tbl_fields(){
		if($this->_tbl_fields_fixed){
			return;	
		}
		$this->_tbl_fields_fixed=true;
		if(!$tblman=$this->usersman->get_tblman()){
			return;
		}
		
		$tbl=$tblman->tbl;
		if($items=$this->get_assignable_items()){
			$num=0;
			foreach($items as $cod=>$item){
				if($fieldname=$this->usersman->get_rol_tbl_field($cod)){
					if(!$tblman->get_field_info($fieldname)){
						$sql="ALTER TABLE $tbl ADD COLUMN $fieldname tinyint(1) NOT NULL DEFAULT '0'";
						$tblman->dbman->query($sql);
						//echo $sql."<br>";
						$num++;
					}
				}
			}
			if($num){
				$tblman->reload_tbl_flields();	
			}
		}
		
	}
	function get_debug_data(){
		$r=array();
		if($items=$this->get_items()){
			foreach($items as $cod=>$item){
				$r[$cod]=$item->get_debug_data();	
			}
		}
		return $r;
	}
	function get_assignable_items(){
		$r=array();
		if($items=$this->get_items()){
			foreach($items as $cod=>$item){
				if($item->is_assignable()){
					$r[$cod]=$item;
				}
			}
		}
		return $r;
	}
	
	final function add_item($rol){
		if(!$cod=$this->check_str_key($rol->get_code())){
			return false;	
		}
		$this->_items[$cod]=$rol;	
		return $rol;
	}
	
	/*
	
	
	private function _init_items(){
		if(isset($this->_items)){
			return;
		}
		$this->_items=array();
		if(!$items=$this->create_permisions()){
			return;	
		}
		foreach($items as $item){
			if($cod=$item->get_code()){
				$this->_items[$cod]=$item;	
			}
		}
	}
	
	
	
	
	final function init_roles(){
		if($this->_roles_init_done){
			return;	
		}
		$this->_init_items();
		$this->_roles_init_done=true;
		if(!$man=$this->usersman->get_rols_man()){
			return false;	
		}
		$man->get_items();
		
		
		
	}
	function allow_by_permission($user,$cod,$params=false){
		$this->init_roles();
		if(!$item=$this->get_item($cod)){
			return false;
		}
		//echo $cod."<br>";

		return $item->allow($user,$params);
	}
	function create_permisions(){
		//	
	}
	*/

	final function get_item($cod){
		if(!$this->check_str_key($cod)){
			return false;	
		}
		
		return $this->_items[$cod];	
	}
	final function get_items(){
		return $this->_items;	
	}
	
	final function init($usersman){
		$this->usersman=$usersman;
		$this->set_mainap($this->usersman->mainap);	
	}

}
?>