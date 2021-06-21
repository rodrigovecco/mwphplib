<?php
class  mw_autoload_subprefman extends mw_baseobj{
	private $pref;
	private $mainman;
	private $basepath;
	private $prefman;
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref,$basepath);	
	}
	///new
	function get_class_info($class_name){
		$r=array(
			"class_name"=>$class_name,
			"sub_cod"=>$this->get_class_sub_code($class_name),
			"subpath"=>$this->get_class_sub_path($class_name),
			"file"=>$this->get_class_file_basename($class_name),
			
		);
		return $r;
	}
	function get_class_sub_path($class_name){
		return false;
		
	}
	function get_class_file_basename($class_name){
		return false;
	}
	function get_class_sub_code($class_name){
		if(!$class_name){
			return false;	
		}
		if(!is_string($class_name)){
			return false;	
		}
		$pref=$this->get_complete_class_pref()."_";
		$len=strlen($pref);
		if(substr($class_name,0,$len)!=$pref){
			return false;	
		}
		$subcod=substr($class_name,$len);
		return $subcod;
		
	}
	
	///
	
	final function set_pref_man($man){
		$this->prefman=$man;	
	}
	final function get_pref_man(){
		return $this->prefman;	
	}
	function get_debug_info(){
		$r=array();
		$r["class"]=get_class($this);
		$r["basepath"]=$this->basepath;
		$r["classpref"]=$this->get_info_class_pref();
		
		return $r;
	}
	function get_info_class_pref(){
		return $this->get_complete_class_pref();	
	}
	
	
	function do_autoload($cod,$class_name,&$report=array()){
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		
		$this->do_autoload_base($report);
		$file=$this->basepath."/$cod/class.php";
		$report["file"]=$file;
		if(file_exists($file)){
			include_once $file;	
		}
		return true;	
	}
	function do_autoload_base(&$report=array()){
		if($this->autoload_base_done){
			return true;	
		}
		$this->autoload_base_done=true;
		$file=$this->basepath."/base.php";
		$report["basefile"]=$file;
		if(file_exists($file)){
			include_once $file;	
		}
		return true;	
	}
	function get_complete_class_pref(){
		if(!$pm=$this->get_pref_man()){
			return false;	
		}
		return $pm->pref."_".$this->pref;
		
	}
	final function init($mainman,$pref,$basepath){
		$this->mainman=$mainman;
		$this->pref=$pref;
		$this->basepath=$basepath;
	}
	final function __get_priv_basepath(){
		return $this->basepath; 	
	}
	final function __get_priv_pref(){
		return $this->pref; 	
	}
	final function __get_priv_prefman(){
		return $this->prefman; 	
	}
	final function __get_priv_mainman(){
		return $this->mainman; 	
	}
	
	function __toString(){
		return $this->get_complete_class_pref()."";	
	}
	
}
class  mw_autoload_subprefmanwithsubclass extends mw_autoload_subprefman{
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref,$basepath);	
	}
	function do_autoload_class($cod,$class_name,&$report=array()){
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		
		$this->do_autoload_base($report);
		$file=$this->basepath."/$cod/class.php";
		$report["classfile"]=$file;
		if(file_exists($file)){
			include_once $file;
			if(class_exists($class_name,false)){
				return true;	
			}
		}
			
	}
	function do_autoloadsubclass($cod,$class_name,&$report=array()){
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
	
		if($subclasscode=$this->get_sub_class_code($class_name)){
			$scpath=$this->basepath."/$cod/subclasses";
			$report["subclasscode"]=$subclasscode;
			if(is_dir($scpath)){
				$scfile=$scpath."/".$subclasscode.".php";
				$report["subclassfile"]=$scfile;
				if(file_exists($scfile)){
					include_once $scfile;	
				}
				if(class_exists($class_name,false)){
					return true;	
				}

			}
			
		}
		
	}
	function do_autoload($cod,$class_name,&$report=array()){
		
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		
		$this->do_autoload_base($report);
		if($this->do_autoload_class($cod,$class_name,$report)){
			return true;	
		}
		if($this->do_autoloadsubclass($cod,$class_name,$report)){
			return true;	
		}
		
		return true;	
	}
	function get_sub_class_code($class_name){
		if(!$class_name){
			return false;	
		}
		if(!is_string($class_name)){
			return false;	
		}
		$a=explode("_",$class_name);
		if(!$a[3]){
			return false;	
		}
		if($r=basename($a[3])){
			return $r;	
		}
	}
	
}
class  mw_autoload_subprefmanwithsubclasspathbased extends mw_autoload_subprefmanwithsubclass{
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref,$basepath);	
	}
	function do_autoload($cod,$class_name,&$report=array()){
		
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		
		$this->do_autoload_base($report);
		if($this->do_autoload_class($cod,$class_name,$report)){
			return true;	
		}
		if($this->do_autoloadsubclass($cod,$class_name,$report)){
			return true;	
		}
		if($this->do_autoloadsubclass_path_based($cod,$class_name,$report)){
			return true;	
		}
		
	}
	function do_autoloadsubclass_path_based($cod,$class_name,&$report=array()){
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		if(!$class_name=basename($class_name)){
			return false;	
		}
		if(!$complte_pref=$this->get_complete_class_pref()){
			return false;	
		}
		$complte_pref.="_$cod";
		$report["complte_pref"]=$complte_pref;
		$l=strlen($complte_pref);
		if(substr($class_name,0,$l)!=$complte_pref){
			return false;	
		}
		if(!$classnopref=substr($class_name,$l+1)){
			return false;	
		}
		$parts=explode("_",$classnopref);
		$filepath=implode("/",$parts);
		$file=$this->basepath."/$cod/subclasses/".$filepath.".php";
		$report["pathbasedfile"]=$file;
		if(file_exists($file)){
			include_once $file;	
		}
		
		if(class_exists($class_name,false)){
			return true;	
		}

		
	}


}
class  mw_autoload_subprefmanfilebased extends mw_autoload_subprefman{
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref,$basepath);	
	}
	function do_autoload_base(&$report=array()){
		
		return true;	
	}
	function do_autoload($cod,$class_name,&$report=array()){
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		$this->do_autoload_base($report);
		$file=$this->basepath."/".$cod.".php";
		$report["file"]=$file;
		if(file_exists($file)){
			include_once $file;	
		}
		return true;	
	}

}
class  mw_autoload_subprefmanfilebased_width_subclases extends mw_autoload_subprefmanfilebased{
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref,$basepath);	
		
	}
	function get_class_sub_path($class_name){
		if(!$sub_cod=$this->get_class_sub_code($class_name)){
			return false;	
		}
		$parts=explode("_",$sub_cod);
		$filepath=implode("/",$parts);
		
		return dirname($filepath);

		
	}
	function get_class_file_basename($class_name){
		if(!$sub_cod=$this->get_class_sub_code($class_name)){
			return false;	
		}
		$parts=explode("_",$sub_cod);
		$filepath=implode("/",$parts);
		return basename($filepath.".php");
	}

	
	function do_autoload_base(&$report=array()){
		
		return true;	
	}
	function do_autoload($cod,$class_name,&$report=array()){
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		if(!$class_name=basename($class_name)){
			return false;	
		}
		$this->do_autoload_base($report);
		if(!$complte_pref=$this->get_complete_class_pref()){
			return false;	
		}
		$l=strlen($complte_pref);
		if(substr($class_name,0,$l)!=$complte_pref){
			return false;	
		}
		if(!$classnopref=substr($class_name,$l+1)){
			return false;	
		}
		$parts=explode("_",$classnopref);
		$filepath=implode("/",$parts);
		$file=$this->basepath."/".$filepath.".php";
		$report["file"]=$file;
		if(file_exists($file)){
			include_once $file;	
		}
		return true;	
	}

}
class  mw_autoload_subprefmanfilebased_width_subclases_alt extends mw_autoload_subprefmanfilebased_width_subclases{
	private $alt_folder;
	private $def_folder="def";
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref,$basepath);	
		
	}
	final function set_alt($alt){
		if(!$alt=$this->mainman->check_pref($alt)){
			return false;	
		}
		$this->alt_folder=$alt;
		return true;
	}
	
	function do_autoload($cod,$class_name,&$report=array()){
		if(!$cod){
			return false;	
		}
		if(!$cod=basename($cod)){
			return false;	
		}
		if(!$class_name=basename($class_name)){
			return false;	
		}
		$this->do_autoload_base($report);
		if(!$complte_pref=$this->get_complete_class_pref()){
			return false;	
		}
		$l=strlen($complte_pref);
		if(substr($class_name,0,$l)!=$complte_pref){
			return false;	
		}
		if(!$classnopref=substr($class_name,$l+1)){
			return false;	
		}
		$parts=explode("_",$classnopref);
		$filepath=implode("/",$parts);
		if($this->alt_folder){
			$file=$this->basepath."/".$this->alt_folder."/".$filepath.".php";
			$report["file"]=$file;
			$report["altfile"]=$file;
			if(file_exists($file)){
				include_once $file;
				return true;
			}
			
				
		}
		$file=$this->basepath."/".$this->def_folder."/".$filepath.".php";
		$report["file"]=$file;
		if(file_exists($file)){
			include_once $file;	
		}
		return true;	
	}
	
	final function __get_priv_alt_folder(){
		return $this->alt_folder; 	
	}
	final function __get_priv_def_folder(){
		return $this->def_folder; 	
	}
	
	
}

?>