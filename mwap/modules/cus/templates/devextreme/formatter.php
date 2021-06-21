<?php
class mwcus_cus_templates_devextreme_formatter extends mwmod_mw_devextreme_formatter{
	function __construct(){
	}
	
	function format_datagrid($widget){
		$widget->js_props->set_prop("columnAutoWidth",true);
		$widget->js_props->set_prop("allowColumnResizing",true);
		$widget->js_props->set_prop("showRowLines",false);
		//$widget->js_props->set_prop("showColumnLines",false);
		
		
		$widget->js_props->set_prop("rowAlternationEnabled",true);
		
	}
	
	
	
}
?>