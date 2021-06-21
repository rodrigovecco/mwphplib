<?php
class mwmod_mw_ui_debug_mainuitest extends mwmod_mw_ui_sub_uiabs{
	function mwmod_mw_ui_debug_mainuitest($cod,$parent){
		$this->init_as_subinterface($cod,$parent);
		$this->set_def_title("Main UI");
		
	}
	function do_exec_no_sub_interface(){
	}
	function do_exec_page_in(){
		
		$m=$this->mainap->bxMan()->operators->tokensMan;
		/*
		for($x=0;$x<500;$x++){
			
			$str=$m->numToSecretCh($x);
			$n=$m->secretChToNum($str);
			echo "<p>$x: $str $n</p>";	
		}
		*/
		
		/*
		$var=$this->maininterface->get_js_ui_man_name();
		$this->maininterface->ui_js_init_params->set_prop("xxx.s",2);
		
		
		echo "<div onclick=\"{$var}.show_popup_notify('hola')\" >notify</div>";
		echo "<div onclick=\"{$var}.confirm('hola',false,function(){console.log('ok')},function(){console.log('f')})\" >Confirm</div>";
		echo "<div onclick=\"{$var}.alert('hola',false,function(){console.log('ok')})\" >alert</div>";
		
		for($x=0;$x<500;$x++){
			echo "<p>$x</p>";	
		}
		*/
		
		
	}
	function is_allowed(){
		if($this->parent_subinterface){
			return 	$this->parent_subinterface->is_allowed();
		}
		//return $this->allow("debug");	
	}
	
}
?>