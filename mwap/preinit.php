<?php
include_once "core/coreclasses.php";
include_once "core/mwautoloadmanager/man.php";
include_once "core/util/include.php";
include_once "core/functions.php";



$GLOBALS["__mw_autoload_manager"]= new mw_autoload_manager();
$GLOBALS["__mw_autoload_manager"]->create_and_add_sub_pref_man("mw",dirname(__FILE__)."/modules/mw");
$GLOBALS["__mw_autoload_manager"]->create_and_add_sub_pref_man("cus",dirname(dirname(__FILE__))."/mwap/modules/cus","mwcus");
$GLOBALS["__mw_autoload_manager"]->add_pref_man("mwhelper",new mw_autoload_prefman_direct_base_path_mode(
	$GLOBALS["__mw_autoload_manager"],
	"mwhelper",
	dirname(dirname(__FILE__))."/mwap/modules/mwhelper"
	));

function __autoloadMW($class_name) {
   if($man=mw_get_autoload_manager()){
	   $man->do_autoload($class_name);
   }
}
spl_autoload_register('__autoloadMW');

function mw_autoload($class_name,$silent=false){
   if($man=mw_get_autoload_manager()){
	   $man->do_autoload($class_name,$silent);
   }
	
}
?>