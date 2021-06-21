<?php
class mwcus_cus_templates_html_user_inlineimgandname extends mwmod_mw_bootstrap_html_template_abs{
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
		$this->set_main_elem($main);
		//$main->add_cont("");
		if(!$this->user){
			return;	
		}
		
		$imgcont=new mwmod_mw_bootstrap_html_def(false,"span");
		if($imgelem=$this->user->get_img_elem($this->img_cod)){
			$imgelem->set_att("class","img-circle");
			
			$imgcont->add_cont($imgelem);
		}
		$this->set_key_cont("imgcont",$imgcont);
		$main->add_cont($imgcont);
		
		$name=new mwmod_mw_bootstrap_html_def(false,"span");
		$name->add_cont($this->user->get_real_name());
		$this->set_key_cont("name",$name);
		
		$main->add_cont($name);
		
		
		
		
		
	}
	

}

?>