<?php
class  mw_autoload_manager{
	private $autoloadersByCod=array();
	private $specialAutoloadersByCod=array();
	private $prefmanagers=array();
	private $nsprefmanagers=array();
	private $defpref="mwmod";
	var $output_error=false;
	var $reports=array();
	var $keep_reports=false;
	function __construct(){
		//	
	}
	function addSpecialAutoloader($cod,$basepath,$pref="",$namespace=false){
		$autoloader=new mw_autoload_prefman_special($this,$cod,$basepath,$pref,$namespace);
		$this->add_pref_man($cod,$autoloader);
		return $autoloader;
	}
	function startReporting(){
		$prev=$this->reports;
		$this->reports=array();
		$this->keep_reports=true;
		return $prev;	
	}
	function endReporting(){
		$prev=$this->reports;
		$this->reports=array();
		$this->keep_reports=false;
		return $prev;	
	}
	
	
	function get_class_info_for_class($class_name){
		if(!$class_name){
			return false;	
		}
		if(is_array($class_name)){
			$r=array();
			foreach($class_name as $cn){
				$r[$cn]=$this->get_class_info_for_class($cn);	
			}
			return $r;
		}
		
		$info=array();
		$info["className"]=$class_name;
		$a=explode("_",$class_name,4);
		if($al=$this->getAutoloaderForClass($class_name,$a,$info)){
			$al->get_class_info($class_name,$info);	
		}else{
			$info["error"]="no autoloader for class";	
		}
		return $info;

	}
	/////nuevas
	function get_ns_pref_man_for_class($class_name){
		if(!strpos($class_name,'\\')){
			return false;	
		}
		if($mans=$this->get_ns_mans()){
			foreach($mans as $m){
				if($m->check_class_namespace($class_name)){
					return $m;
				}
			}
		}
	}
	//////////
	function get_sub_pref_man($subpref,$pref=false){
		if(!$pref){
			$pref=$this->get_def_pref();
		}
		
		if(!$pm=$this->get_pref_man($pref)){
			return false;
		}
		return $pm->get_man($subpref);
		
	}
	function get_sub_pref_man_for_class($class_name){
		if(!$class_name){
			return $report;	
		}
		if(!is_string($class_name)){
			return false;
		}
		$a=explode("_",$class_name,4);
		return $this->get_sub_pref_man($a[1],$a[0]);
	}
	function create_and_add_sub_pref_man($subpref,$path,$pref=false){
		if(!$pref){
			$pref=$this->get_def_pref();
		}
		if(!$this->check_pref($subpref)){
			return false;	
		}
		if(!$prefman=$this->get_or_create_pref_man($pref)){
			return false;		
		}
		$sm=new mw_autoload_subprefmanfilebased_width_subclases($this,$subpref,$path);
		
		return $this->add_manager($sm,$pref);
			
	}
	function class_exists($class_name){
		if(!$class_name){
			return false;	
		}
		if(class_exists($class_name,false)){
			return "class_exists";
		}
		$this->output_error=false;
		$ok=false;
		if(class_exists($class_name)){
			$ok="loaded";
		}
		$this->output_error=true;
		return $ok;
	
			
	}
	
	final function do_autoload($class_name,$silent=false){
		ob_start();
		$report=$this->_do_autoload($class_name);
		ob_end_clean();
		if($this->keep_reports){
			$this->reports[$class_name]=$report;	
		}
		if($this->output_error){
			if(!$silent){
				if(!$report["result"]){
					mw_array2list_echo($report);	
				}
			}
		}
		
		return $report["result"];
	}
	private function _do_autoload($class_name){
		$report=array();
		$report["classname"]=$class_name;
		$report["result"]=false;
		
		
		if(!$class_name){
			return $report;	
		}
		if(!is_string($class_name)){
			return $report;	
		}
		$nsmode=false;
		$pm=false;
		$a=array();
		if(!$pm=$this->getAutoloaderForClass($class_name,$a,$report)){
			return $report;	
		}
		//$report["a"]=$a;
		$report["prefman"]=$pm;
		
		$pm->do_autoload($class_name,$a,$report);
		
		
		
		if(class_exists($class_name,false)){
			$report["result"]=true;
		}else{
			if(interface_exists ($class_name,false)){
				$report["result"]=true;
				$report["isinterface"]=true;
			}
				
		}
		return $report;		
			
	}
	//nueva
	function getAutoloaderForClass($class_name,&$nameparts=array(),&$report=array()){
		if(!$class_name){
			return false;	
		}
		$nameparts=explode("_",$class_name,4);
		if(strpos($class_name,'\\')){
			if($pm=$this->get_ns_pref_man_for_class($class_name)){
				$report["autoloaderselectmode"]="ns";
				
				return $pm;	
			}
		}else{
			if($pm=$this->get_pref_man($nameparts[0])){
				$report["autoloaderselectmode"]="pref";
				return $pm;	
			}
		}
		if($pm=$this->getSpecialAutoloaderForClass($class_name)){
			$report["autoloaderselectmode"]="special";
			return $pm;	
				
		}
	}
	function getSpecialAutoloaderForClass($class_name){
		if($items=$this->getSpecialAutoloaders()){
			foreach($items as $item){
				if($item->specialCheckClassName($class_name)){
					return $item;	
				}
			}
		}
	}
	
	function add_alt_man($pref,$basepath){
		$man= new mw_autoload_prefman_alt($this,$pref,$basepath);
		return $this->add_pref_man($pref,$man);
	}

	final function add_manager($man,$pref=false){
		if(!$pref){
			$pref=$this->get_def_pref();
		}
		$subpref=$man->pref;
		if(!$this->check_pref($subpref)){
			return false;	
		}
		if(!$prefman=$this->get_or_create_pref_man($pref)){
			return false;		
		}
		return $prefman->add_manager($man);
		
	}
	final function get_pref_man($pref){
		if(!$this->check_pref($pref)){
			return false;	
		}
		if($this->prefmanagers[$pref]){
			return 	$this->prefmanagers[$pref];
		}
		return false;
		
	}
	final function get_ns_mans(){
		return $this->nsprefmanagers;	
	}
	function create_pref_man($pref){
		if(!$this->check_pref($pref)){
			return false;	
		}
		$man=new mw_autoload_prefman($this,$pref);
		return $man;
			
	}
	final function getSpecialAutoloaders(){
		return $this->specialAutoloadersByCod;	
	}
	final function getAutoloaders(){
		return $this->autoloadersByCod;	
	}
	final function add_pref_man($pref,$man){
		if(!$this->check_pref($pref)){
			return false;	
		}
		$this->autoloadersByCod[$pref]=$man;
		if(!$man->isSpecial()){
			$this->prefmanagers[$pref]=$man;
			if($man->is_ns_mode()){
				$this->nsprefmanagers[$pref]=$man;	
			}
		}else{
			if($man->includeOnSpecialList()){
				$this->specialAutoloadersByCod[$pref]=$man;
			}
			if($man->is_ns_mode()){
				$this->nsprefmanagers[$pref]=$man;	
			}
			
		}
		return $man;
			
	}
	function create_and_add_prefman($pref){
		if($man=$this->create_pref_man($pref)){
			return $this->add_pref_man($pref,$man);
		}
	}
	function get_or_create_pref_man($pref){
		if($man=$this->get_pref_man($pref)){
			return $man;	
		}
		if($man=$this->create_and_add_prefman($pref)){
			return $man;	
		}
		
	}
	final function get_def_pref(){
		return $this->defpref;	
	}
	final function set_def_pref($pref){
		if(!$this->check_pref($pref)){
			return false;	
		}
		$this->defpref=$pref;
		return true;
	}
	final function check_pref($pref){
		if(!$pref){
			return false;	
		}
		if(!is_string($pref)){
			return false;	
		}
		if(!ctype_alpha($pref)){
			return false;	
		}
		return $pref;
		
	}
	function get_debug_info(){
		$r=array();
		$r["managers"]=array();
		if($items=$this->getAutoloaders()){
			foreach($items as $cod=>$m){
				$r["managers"][$cod]=$m->get_debug_info();
			}
		}
		return $r;
	}
	final function get_managers(){
		return $this->prefmanagers;	
	}
}
include_once "prefman.php";
include_once "subprefman.php";
?>