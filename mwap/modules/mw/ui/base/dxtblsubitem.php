<?php
//
abstract class  mwmod_mw_ui_base_dxtblsubitem extends mwmod_mw_ui_base_dxtbladmin{
	public $mainitemMan;
	public $subItemRelFieldName;
	function loadMainItem(){
		if(!$item=$this->mainitemMan->get_item($_REQUEST["iditem"])){
			return false;	
		}
		$this->set_current_item($item);
		$this->set_url_param("iditem",$item->get_id());
		return $item;

	}
	function isAdminMode(){
		return true;
	}
	function is_allowed(){
		if($this->parent_subinterface){
			return 	$this->parent_subinterface->is_allowed();
		}
		if(!$this->isAdminMode()){
			return $this->allow("view");	
		}
		return $this->allow("editor");
	}
	function allow_admin(){
		if(!$this->isAdminMode()){
			return false;
		}
		return $this->allow("editor");	
	}

	function checkSubItem($item){
		if(!$item){
			return false;
		}
		if(!$this->subItemRelFieldName){
			return false;	
		}
		if(!$this->current_item){
			return false;	
		}
		if($this->current_item->get_id()==$item->get_data($this->subItemRelFieldName)){
			return true;	
		}
	}
	function loadMainItemCMDMode($params){
		if(!is_array($params)){
			return false;
		}
		
		if(!$item=$this->mainitemMan->get_item($params["iditem"])){
			return false;	
		}
		$this->set_current_item($item);
		$this->set_url_param("iditem",$item->get_id());
		return $item;

	}
	
	function get_exec_cmd_sxml_url($xmlcmd="debug",$params=array()){
		if($this->maininterface){
			if($this->current_item){
				if(!is_array($params)){
					$params=array();
				}
				$params["iditem"]=$this->current_item->get_id();
			}
			
			//$this->maininterface must by mwmod_mw_ui_main_uimainabsajax
			return $this->maininterface->get_exec_cmd_sxml_url($xmlcmd,$this,$params);	
		}
		
		
	}
	function do_exec_page_in(){
		if(!$this->loadMainItem()){
			return false;	
		}
		
		
		
		$container=$this->get_ui_dom_elem_container();
		

		$gridcontainer=$this->set_ui_dom_elem_id("datagrid_container");
		$body=$this->set_ui_dom_elem_id("datagrid_body");
		$loading=new mwcus_cus_templates_html_loading_placeholder();
		$body->add_cont($loading);
		$gridcontainer->add_cont($body);
		
		$container->add_cont($gridcontainer);
		
		$this->getBotHtml($container);

		echo $container->get_as_html();
		
		//
		$js=new mwmod_mw_jsobj_jquery_docreadyfnc();
		$this->set_ui_js_params();
		$jsui=$this->new_ui_js();
		$var=$this->get_js_ui_man_name();
		$js->add_cont("var {$var}=".$jsui->get_as_js_val().";\n");
		$js->add_cont($var.".init(".$this->ui_js_init_params->get_as_js_val().");\n");
		
		echo $js->get_js_script_html();
		return;
		
		

		
	}
	function execfrommain_getcmd_sxml_loaddata($params=array(),$filename=false){
		$xml=$this->new_getcmd_sxml_answer(false);
		$this->xml_output=$xml;
		//$xml->set_prop("htmlcont",$this->lng_get_msg_txt("not_allowed","No permitido"));
		if(!$this->loadMainItemCMDMode($params)){
			$xml->root_do_all_output();
			return false;
		}
		
		if(!$this->is_allowed()){
			$xml->root_do_all_output();
			return false;
	
		}
		if(!$man=$this->getItemsMan()){
			$xml->root_do_all_output();
			return false;
		}
		if(!$query=$this->getQueryFromReq()){
			$xml->root_do_all_output();
			return false;
		}
		
		$xml->set_prop("ok",true);
		$js=new mwmod_mw_jsobj_obj();
		//$xml->set_prop("debug.sqlbefore",$query->get_sql());
		//$xml->set_prop("debug.loadoptions",$_REQUEST["lopts"]);
		$dataqueryhelper=$this->queryHelper;
		$dataqueryhelper->setLoadOptions($_REQUEST["lopts"]);
		//$xml->set_prop("debug.dataqueryhelper",$dataqueryhelper->getDebugData());
		$dataqueryhelper->aplay2Query($query);
		if(!$dataqueryhelper->sorted){
			$this->setDefaultQuerySort($query);
		}
		$xml->set_prop("debug.sql",$query->get_sql());
		
		$js->set_prop("totalCount",$query->get_total_regs_num());
		
		
		
		$dataoptim=new mwmod_mw_jsobj_dataoptim();
		$dataoptim->set_key("id");
		$js->set_prop("dsoptim",$dataoptim);
		if($items=$man->get_items_by_query($query)){
			foreach($items as $id=>$item){
				//$xml->set_prop("debug.item.".$id,"d");
				$data=$this->get_item_data($item);
				$dataoptim->add_data($data);	
			}
			
		}
		$xml_js=new mwmod_mw_data_xml_js("js",$js);
		
		
		$xml->add_sub_item($xml_js);
		$xml->root_do_all_output();
		
		
		//
		
			
	}
	
	function execfrommain_getcmd_sxml_loaddatagrid($params=array(),$filename=false){
		$xml=$this->new_getcmd_sxml_answer(false);
		$this->xml_output=$xml;
		$xml->set_prop("htmlcont",$this->lng_get_msg_txt("not_allowed","No permitido"));
		if(!$this->loadMainItemCMDMode($params)){
			$xml->root_do_all_output();
			return false;
		}
		
		if(!$this->is_allowed()){
			$xml->root_do_all_output();
			return false;
	
		}
		
		if(!$this->items_man){
			$xml->root_do_all_output();
			return false;
		}
		
		$xml->set_prop("ok",true);
		$xml->set_prop("htmlcont","");
		
		$var=$this->get_js_ui_man_name();

		$datagrid=new mwmod_mw_devextreme_widget_datagrid(false);
		$datagrid->setFilerVisible();
		$datagrid->js_props->set_prop("columnAutoWidth",true);	
		$datagrid->js_props->set_prop("allowColumnResizing",true);
		if($this->allowUpdate()){
			$datagrid->js_props->set_prop("editing.allowUpdating",true);
			$datagrid->js_props->set_prop("editing.mode",$this->editingMode);
		}
		if($this->allowInsert()){
			$datagrid->js_props->set_prop("editing.allowAdding",true);
		}
		if($this->allowDelete()){
			$datagrid->js_props->set_prop("editing.allowDeleting",true);
		}
		$datagrid->js_props->set_prop("editing.useIcons",true);
		
		//$datagrid->js_props->set_prop("editing.mode","row");
		$datagrid->js_props->set_prop("paging.pageSize",$this->getDefPageSize());
		$datagrid->js_props->set_prop("remoteOperations.paging",true);
		$datagrid->js_props->set_prop("remoteOperations.filtering",true);
		$datagrid->js_props->set_prop("remoteOperations.sorting",true);
		
		
		$gridhelper=$datagrid->new_mw_helper_js();
		$datagrid->mw_helper_js_set_rdata_mode_from_ui($this,$gridhelper);
		$gridhelper->set_fnc_name("mw_devextreme_datagrid_man_rdataedit");
		

		$this->add_cols($datagrid);

		$columns=$datagrid->columns->get_items();

		$list=$gridhelper->get_array_prop("columns");
		foreach($columns as $col){
			$coljs=$col->get_mw_js_colum_obj();
			$list->add_data($coljs);
			
		}
		
		$list->add_data($coljs);
		
		
		if($d=$this->getUniqItemsIds()){
			$gridhelper->set_prop("uniqItemsIds",$d);
			//$xml->set_prop("uniqItemsIds",$d);
		}
		
		$gridhelper->set_prop("dsoptim",$dataoptim);
		
		$this->afterDatagridCreated($datagrid,$gridhelper);
		
		$js=new mwmod_mw_jsobj_obj();
		$js->set_prop("datagridman",$gridhelper);
		$xml_js=new mwmod_mw_data_xml_js("jsresponse",$js);
		
		
		$xml->add_sub_item($xml_js);
		$xml->root_do_all_output();
		
		
		
		
			
	}
	function execfrommain_getcmd_sxml_newitem($params=array(),$filename=false){
		$xml=$this->new_getcmd_sxml_answer(false);
		$this->xml_output=$xml;
		if(!$this->loadMainItemCMDMode($params)){
			$xml->root_do_all_output();
			return false;
		}
		if(!$this->is_allowed()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$this->allowInsert()){
			$xml->root_do_all_output();
			return false;	
		}
		
		if(!$man=$this->items_man){
			$xml->root_do_all_output();
			return false;	
		}
		$input=new mwmod_mw_helper_inputvalidator_request("nd");
		if(!$input->is_req_input_ok()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$nd=$input->get_value_as_list()){
			$xml->root_do_all_output();
			return false;	
		}
		//$xml->set_prop("nd",$nd);
		unset($nd["id"]);
		$nd[$this->subItemRelFieldName]=$this->current_item->get_id();
		
		if(!$item=$this->create_new_item($nd)){
			$xml->set_prop("notify.message",$this->lng_get_msg_txt("unableToCreateElement","No se pudo crear el elemento."));
			$xml->set_prop("notify.type","error");
			$xml->root_do_all_output();
			return false;	
		}
		$xml->set_prop("ok",true);
		$xml->set_prop("itemid",$item->get_id());
		$xml->set_prop("itemdata",$this->get_item_data($item));
		$xml->set_prop("notify.message",$this->lng_get_msg_txt("registrycreated","Registro creado"));
		$xml->set_prop("notify.type","success");
		
		$xml->root_do_all_output();

	}

	function execfrommain_getcmd_sxml_deleteitem($params=array(),$filename=false){
		$xml=$this->new_getcmd_sxml_answer(false);
		
		if(!$this->is_allowed()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$this->loadMainItemCMDMode($params)){
			$xml->root_do_all_output();
			return false;
		}
		if(!$this->allowDelete()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$man=$this->__get_priv_items_man()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$item=$man->get_item($_REQUEST["itemid"])){
			$xml->root_do_all_output();
			return false;	
				
		}
		if(!$this->checkSubItem($item)){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$this->allowDeleteItem($item)){
			$xml->root_do_all_output();
			return false;	
		}

		
		$this->delete_item($item,$xml);
		if($d=$this->getUniqItemsIds()){
			$xml->set_prop("uniqItemsIds",$d);
		}
		
		$xml->root_do_all_output();
		

	}
	function getQuery(){
		
		$this->queryHelper=new mwmod_mw_devextreme_data_queryhelper();
		if(!$man=$this->getItemsMan()){
			return false;
		}
		if(!$tblman=$man->get_tblman()){
			return false;	
		}
		if(!$query=$tblman->new_query()){
			return false;	
		}
		$query->select->add_select($tblman->tbl.".*");
		if(!$this->current_item){
			return false;	
		}
		if(!$this->subItemRelFieldName){
			return false;	
		}
		$query->where->add_where_crit($tblman->tbl.".".$this->subItemRelFieldName,$this->current_item->get_id());
		$this->queryHelper->addAllTblFields($tblman);
		$this->afterGetQuery($query);
		return $query;
	}
	
	function execfrommain_getcmd_sxml_saveitem($params=array(),$filename=false){
		$xml=$this->new_getcmd_sxml_answer(false);
		if(!$this->loadMainItemCMDMode($params)){
			$xml->root_do_all_output();
			return false;
		}
		
		if(!$this->is_allowed()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$this->allow_admin()){
			$xml->root_do_all_output();
			return false;	
		}
		
		if(!$man=$this->__get_priv_items_man()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$item=$man->get_item($_REQUEST["itemid"])){
			$xml->root_do_all_output();
			return false;	
				
		}
		if(!$this->checkSubItem($item)){
			$xml->root_do_all_output();
			return false;	
		}
		$input=new mwmod_mw_helper_inputvalidator_request("nd");
		if(!$input->is_req_input_ok()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$nd=$input->get_value_as_list()){
			$xml->root_do_all_output();
			return false;	
		}
		if(!$this->allowUpdateItem($item)){
			$xml->root_do_all_output();
			return false;	
		}
		unset($nd[$this->subItemRelFieldName]);
		
		
		
		$this->saveItem($item,$nd);
		$xml->set_prop("ok",true);
		$xml->set_prop("itemid",$item->get_id());
		$xml->set_prop("itemdata",$this->get_item_data($item));
		
		if($d=$this->getUniqItemsIds()){
			$xml->set_prop("uniqItemsIds",$d);
		}
		
		$xml->root_do_all_output();
		
		//$item->tem

	}

	
	

}
?>