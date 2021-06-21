<?php
class mwmod_mw_mnu_items_dropdown_top extends mwmod_mw_mnu_mnuitem{
	function __construct($cod,$etq,$parent,$url=false){
		$this->init($cod,$etq,$parent,$url);
		$this->set_dropdown();
	}
	
	function get_alink(){
		//<a class="dropdown-toggle" data-toggle="dropdown" href="#">
		$r.="<a href='#' ";
		$r.=" class='dropdown-toggle' data-toggle='dropdown' ";
		$r.=">";
		$r.=$this->get_a_inner_html();
		if($e=$this->getSubHTMLElem("beforelbl")){
			$r.=$e->get_as_html();
		}
		$r.=" <i class='fa fa-caret-down'></i>";
		$r.="</a>";
				

		return 	$r;
	}
	function get_a_inner_html(){
		$r="";
		$r=$this->innerHTMLbefore;
		if($c=$this->get_param("icon_class")){
			$r.="<span class='mr-2 d-none d-lg-inline text-gray-600 small'>".$this->get_etq()."</span>";		
			$r.="<i class='$c'></i>";
		}else{
			$r.=$this->get_etq();
		}
		$r.=$this->innerHTMLafter;
		return $r;	
	}
	
	function get_html_as_nav_child_inner(){
		$r.=$this->get_alink();
		
		//$r.="<ul class='dropdown-menu' role='menu'>";	
		$r.="<ul class='".$this->getParam("ulclass","dropdown-menu")."' role='menu'>";	
		$r.=$this->get_html_as_nav_child_inner_children();
		$r.="</ul>";
		return $r;
			
	}
	function get_html_as_nav_child_inner_children(){
		if(!$items=$this->get_items_allowed()){
			return false;	
		}
		
		foreach ($items as $item){
			
			$r.=$item->get_html_as_nav_child();	
				
		}
		
		return $r;
	}
	
}

?>