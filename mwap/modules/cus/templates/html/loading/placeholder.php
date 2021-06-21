<?php
class mwcus_cus_templates_html_loading_placeholder extends mwmod_mw_bootstrap_html_template_abs{
	function __construct(){
		$main=new mwmod_mw_bootstrap_html_def("mw_loading_placeholder","div");

		$this->create_cont($main);
		
	}
	
	function create_cont($main){
		$this->set_main_elem($main);
		$iconcontainer=new mwmod_mw_bootstrap_html_def("mw_loading_placeholder_in","div");
		//$iconcontainer->add_cont("<div class='glyphicon glyphicon-refresh glyphicon-refresh-animate '>");
		$iconcontainer->add_cont("<div class='glyphicon glyphicon-refresh glyphicon-refresh-animate '></div>");
		$main->add_cont($iconcontainer);
		//$main->add_cont("<div class='glyphicon glyphicon-refresh glyphicon-refresh-animate '>");
		
		
		
		
		
		
	}
	

}

?>