<?php
class mwmod_mw_users_ui_edit extends mwmod_mw_ui_sub_uiabs{
	function __construct($cod,$parent){
		$this->init_as_subinterface($cod,$parent);
		//no usada!!
		$this->set_def_title($this->get_msg("Editar usuario"));
		
	}
	function do_exec_no_sub_interface(){
		if(!$uman=$this->mainap->get_user_manager()){
			return false;	
		}
		if(!$user=$uman->get_user($_REQUEST["iditem"])){
			return false;	
		}
		$this->set_current_item($user);
		$this->set_url_param("iditem",$user->get_id());

		
	}
	function get_title_for_box(){
		if($user=$this->get_current_item()){
			return $user->get_real_and_idname();
		}
		return $this->get_title();	
	}

	function do_exec_page_in(){
		return false;
		if(!$user=$this->get_current_item()){
			return false;
		}
		
		if(!$user->is_main()){
			if(is_array($_REQUEST["nduser"])){
				$msg="";
				//$user->save_from_admin($_REQUEST["nduser"],$msg);
				if($msg){
					echo "<p>$msg</p>";	
				}
			}
		}
		//

		$frm=$this->new_frm();
		$frm->set_enctype_urlencoded();
		$cr=$this->new_datafield_creator();
		
		
		
		$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_html("name",$this->get_msg("Nombre de usuario")));
		$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_input("complete_name",$this->get_msg("Nombre completo")),"data");
		//$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_input("email",$this->get_msg("Email")),"data");
		$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_checkbox("active",$this->get_msg("Activo")),"data");
		$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_checkbox("change",$this->get_msg("Modificar contraseña")),"pass");
		$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_password("pass",$this->get_msg("Contraseña")),"pass");
		$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_password("pass1",$this->get_msg("Confirmar contraseña")),"pass");
		$input=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_checkbox("secpass",$this->get_msg("Contraseña segura")),"pass");
		if($rolsman=$user->man->get_rols_man()){
			if($rols=$rolsman->get_assignable_items()){
				$rolsgr=$cr->add_sub_item_by_dot_cod(new mwmod_mw_datafield_groupwithtitle("rols",$this->get_msg("Roles")));
				foreach($rols as $cod=>$rol){
					$rolsgr->add_item(new mwmod_mw_datafield_checkbox($cod,$rol->get_name()));
				}
			}
		}
		//
		
		
		$cr->items_pref="nduser";
		$cr->set_value($user->get_data_for_admin());
		
		if(!$user->is_main()){
			$cr->add_submit($this->get_msg("Guardar"));
		}
		$frm->set_datafieldcreator($cr);
		echo $frm->get_html();

		

		
	}
	function is_allowed(){
		return $this->allow("adminusers");	
	}
	
}
?>