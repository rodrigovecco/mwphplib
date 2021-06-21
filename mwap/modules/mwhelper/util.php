<?php
//no usada
class mwhelper_util{
	private static $_helper;
	private static $_app;
	function __construct(){
		
	}
	/**
	* @access private
	*/
	private function createHelper(){
		$app=$this->app();
		$h= new mwmod_mw_ap_helper($app);
		return $h;	
	}
	/**
	* @return mwmod_mw_ap_helper helper
	*/
	public function helper(){
		return $this->_getHelper();
		
	}
	/**
	* @access private
	*/
	final protected function _getHelper(){
		if(!self::$_helper){
			
			self::$_helper=$this->createHelper();
		}
		return self::$_helper;
		
	}
	/**
	* @return mwmod_mw_ap_apbase main app
	*/
	public function app(){
		return $this->_getApp();
	}
	/**
	* @access private
	*/
	final protected function _getApp(){
		if(!self::$_app){
			echo "creando _app<br>";
			self::$_app=mw_get_main_ap();
		}
		return self::$_app;
	}
	
	
	
}

?>