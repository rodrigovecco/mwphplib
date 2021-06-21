<?php
if($_ap=mw_get_main_ap()){
	$_ap->set_root_path(dirname(dirname(__FILE__)));
	$_ap->set_system_path(dirname(__FILE__));
	$_ap->after_init();
}




?>