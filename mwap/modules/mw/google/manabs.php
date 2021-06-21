<?php
abstract class  mwmod_mw_google_manabs extends mwmod_mw_manager_baseman{
	function initMan($code,$ap){
		$this->init($code,$ap);
		$this->enable_treedata();
	}
	function prepareMainUI($ui){
		$ui->jsmanager->add_item_by_cod_def_path("google/mw_google.js");
		$ui->ui_js_init_params->set_prop("managers.google",$this->getJsManObj());
	}
	function getJsManObj($js=false){
		if(!$js){
			$js=new mwmod_mw_jsobj_newobject("mw_google_man");	
		}
		$js->set_prop("clientID",$this->getAppID());
		$js->set_prop("src",$this->get_js_src());
		$js->set_prop("enabled",$this->isEnabled());
		return $js;
			
	}
	function get_js_src(){
		return "https://apis.google.com/js/platform.js";	
	}

	
	function getJSInitItem(){
		return new mwmod_mw_google_jsinit($this);	
	}
	function isEnabled(){
		if(!$td=$this->get_treedata_item("cfg")){
			return false;
		}
		if(!$td->get_data("enabled")){
			return false;	
		}
		if($this->getAppID()){
			return true;	
		}
		return false;
			
	}
	function getAppID(){
		if($td=$this->get_treedata_item("keys")){
			return $td->get_data("appId")."";	
		}
	}
	/*
	function secretOK(){
		if($this->isEnabled()){
			if($this->getAppSecret()){
				return true;	
			}
				
		}
		return false;
	}
	function getDefaultGraphVersion(){
		return $this->getJSAppVersion();	
	}
	final function __get_priv_fbApp(){
		if(!isset($this->fbApp)){
			$this->fbApp=$this->createFBappDef();
		}
		return $this->fbApp; 	
	}
	function createFBappDef(){
		$app=new mwmod_mw_facebook_app();
		$app->app_id=$this->getAppID();
		$app->app_secret=$this->getAppSecret();
		$app->isdefault=true;
		if($v=$this->getDefaultGraphVersion()){
			$app->app_version=$v;
		}
		return $app;
		
	}
	function getJsFMManObj($js=false){
		if(!$js){
			$js=new mwmod_mw_jsobj_newobject("mw_fb_man");	
		}
		$js->set_prop("fb.url",$this->getjsSDKurl());
		$js->set_prop("fb.initparams",$this->getJsInitParams());
		return $js;
			
	}
	function getJsInitParams($js=false){
		if(!$js){
			$js=new mwmod_mw_jsobj_obj();	
		}
		$js->set_prop("appId",new mwmod_mw_jsobj_str($this->getAppID()));
		//$js->set_prop("cookie",true);
		$js->set_prop("xfbml",true);
		$js->set_prop("version",$this->getJSAppVersion());
		return $js;	
	}
	function getAppSecret(){
		if($td=$this->get_treedata_item("keys")){
			return $td->get_data("appsecret")."";	
		}
	}
	function getjsSDKurl(){
		if($this->isDebugMode()){
			return $this->getjsSDKurlDebug();	
		}
		return $this->getjsSDKurlProd();
	}
	function getjsSDKurlProd(){
		return $this->jsSDKurlProd;	
	}
	function getjsSDKurlDebug(){
		return $this->jsSDKurlDebug;	
	}
	
	
	function isDebugMode(){
		return $this->debugMode;	
	}
	function getJSAppVersion(){
		return $this->jsappversion;	
	}
	*/
	function createCfgUI($cod,$parent){
		$ui=new mwmod_mw_google_ui_cfg($cod,$parent,$this);
		return $ui;
	}
}
?>