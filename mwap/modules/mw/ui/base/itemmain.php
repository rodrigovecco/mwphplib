<?php
class  mwmod_mw_ui_base_itemmain extends mwmod_mw_ui_base_basesubui{
	function __construct($cod,$parent){
		$this->init_as_main_or_sub($cod,$parent);
		$this->set_def_title("Edit");
		//$this->editingMode="cell";
		
	}
	function is_allowed(){
		return $this->allow_admin();
	}
	function allow_admin(){
		return $this->allow("admin");	
	}

	
	function allowcreatesubinterfacechildbycode(){
		return true;	
	}
	
	function is_responsable_for_sub_interface_mnu(){
		return true;	
	}
	function create_sub_interface_mnu_for_sub_interface($su=false){
		$mnu = new mwmod_mw_mnu_mnu();
		//$this->add_2_mnu($mnu);
		if($subs=$this->get_subinterfaces_by_code($this->sucods,true)){
			foreach($subs as $su){
				$su->add_2_sub_interface_mnu($mnu);	
			}
		}
		return $mnu;
	}

	function before_exec(){
		$this->add_req_js_scripts();	
		$this->add_req_css();
		if($this->items_man){
			if($item=$this->items_man->get_item($_REQUEST["iditem"])){
				$this->set_current_item($item);
				$this->set_url_param("iditem",$item->get_id());
			}
		}

	}
	function get_title(){
		if($item=$this->get_current_item()){
			return $item->get_name();
		}
		return $this->__get_priv_def_title();	
	}

	
	
	function load_items_man(){
		return false;
	}
	function do_exec_page_in(){
		if(!$this->current_item){
			return;	
		}
		
		echo "<div class='card'><div class='card-body'>";
		echo "<h1>".$this->current_item->get_name()."</h1>";
		if($subs=$this->get_subinterfaces_by_code($this->sucods,true)){
			echo "<ul>";
			foreach($subs as $su){
				echo "<li><a href='".$su->get_url()."'>".$su->get_mnu_lbl()."</a></li>";	
			}
			echo "</ul>";
			
		}
		echo "</div></div>";
	}

}
?>