<?php
class mwcus_cus_templates_html_user_img extends mwmod_mw_bootstrap_html_template_abs{
	var $user;
	var $img_cod="inline";
	function __construct($user,$imgcod="inline"){
		$this->user=$user;
		if($imgcod){
			$this->img_cod=$imgcod;	
		}
		$main=new mwmod_mw_bootstrap_html_def(false,"span");

		$this->create_cont($main);
		
	}
	
	function create_cont($main){
		
		if(!$this->user){
			$this->set_main_elem($main);
			return;	
		}
		if($imgelem=$this->user->get_img_elem($this->img_cod)){
			$imgelem->set_att("class","img-circle");
			$this->set_key_cont("imgcont",$imgelem);
			$this->set_main_elem($imgelem);
			return true;	
			
		}else{
			$this->set_main_elem($main);
		}
		
		
		
		
		
	}
	

}

?>