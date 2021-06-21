﻿<?php
class mwmod_mw_users_ui_myaccount_data extends mwmod_mw_users_ui_myaccount_abs{
	function __construct($cod,$parent){
		$this->init_as_subinterface($cod,$parent);
		$this->set_def_title($this->lng_get_msg_txt("mydata","Mis datos"));
		
	}

	function do_exec_page_in(){
		
		if(!$user=$this->get_admin_current_user()){
			return false;
		}
		
		$frm=$this->new_frm();
		//$frm->set_enctype_urlencoded();
		$cr=$this->new_datafield_creator();
		$dm=$user->get_user_data_man();
		$msg="";
		$dm->savefromfrm_user_data(new mwmod_mw_helper_inputvalidator_request("nduser.usernd"),$user,$msg);
		$dm->set_user_data_cr($user,$cr);
		$cr->items_pref="nduser";
		$cr->add_cancel("index.php");
		$cr->add_submit($this->lng_common_get_msg_txt("save","Guardar"));
		$frm->set_datafieldcreator($cr);
		echo $frm->get_html();
		if($msg){
			$alert=new mwmod_mw_bootstrap_html_specialelem_alert($msg);
			echo $alert->get_as_html();
		}
		

		
	}
	
}
?>