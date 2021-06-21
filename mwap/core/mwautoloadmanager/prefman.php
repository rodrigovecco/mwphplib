<?php
class  mw_autoload_prefman extends mw_baseobj{
	
	private $pref;
	private $mainman;
	private $managers=array(); 
	function __construct($mainman,$pref){
		$this->init($mainman,$pref);	
	}
	function get_class_info($class_name,&$info=array()){
		if(!$info){
			$info=array();	
		}
		$info["className"]=$class_name;
		$info["autoloader"]=get_class($this);
		$info["pref"]=$this->pref;
		return $info;
		
		
		
		
	}
	
	
	
	
	function is_ns_mode(){
		return false;	
	}
	function check_class_namespace($class_name){
		return false;	
	}
	function isSpecial(){
		return false;	
	}
	function includeOnSpecialList(){
		return $this->isSpecial();	
	}
	function specialCheckClassName($class_name){
		return false;	
	}

	function get_debug_info(){
		$r=array();
		$r["class"]=get_class($this);
		if($items=$this->get_managers()){
			$r["managers"]=array();
			foreach($items as $cod=>$m){
				$r["managers"][$cod]=$m->get_debug_info();
			}
		}
		return $r;
	}
	
	final function get_managers(){
		return $this->managers;	
	}
	
	function get_man($pref){
		return $this->get_existing_man($pref);
	}
	
	final function get_existing_man($pref){
		if(!$pref){
			return false;
		}
		if($this->managers[$pref]){
			return 	$this->managers[$pref];
		}
		return false;
		
	}
	function do_autoload($class_name,$class_parts,&$report=array()){
		if(!$man=$this->get_man($class_parts[1])){
			return false;		
		}
		$report["subman"]=$man;
		
		
		
		
		$man->do_autoload($class_parts[2],$class_name,$report);

	}

	final function add_manager($man){
		$subpref=$man->pref;
		$this->managers[$subpref]=$man;
		$man->set_pref_man($this);
		return $this->managers[$subpref];
	}
	
	final function init($mainman,$pref){
		$this->mainman=$mainman;
		$this->pref=$pref;
	}
	final function __get_priv_mainman(){
		return $this->mainman; 	
	}
	final function __get_priv_pref(){
		return $this->pref; 	
	}
	function __toString(){
		return $this->pref;	
	}
}

class  mw_autoload_prefman_alt extends mw_autoload_prefman{
	private $basepath;

	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref);
		$this->set_basepath($basepath);
	}
	
	
	function get_man($pref){
		if($man=$this->get_existing_man($pref)){
			return $man;	
		}
		if(!$pref=$this->mainman->check_pref($pref)){
			return false;	
		}
		$sm=new mw_autoload_subprefmanfilebased_width_subclases_alt($this->mainman,$pref,$this->basepath."/".$pref);
		return $this->add_manager($sm);

	}
	
	function set_alt($cod,$alt){
		if($man=$this->get_man($cod)){
			return $man->set_alt($alt);
		}
			
	}
	
	final function set_basepath($basepath){
		return $this->basepath=$basepath; 	
	}
	final function __get_priv_basepath(){
		return $this->basepath; 	
	}
	
}
//nuevo
class  mw_autoload_prefman_onefile extends mw_autoload_prefman{
	private $fullfilepath;
	

	function __construct($mainman,$pref,$fullfilepath){
		$this->init($mainman,$pref);
		$this->set_full_file_path($fullfilepath);
	}
	function get_class_path_full($class_name){
		if(!$class_name){
			return false;	
		}
		if(!$pref=$this->pref){
			return false;
		}
		if(strpos($class_name, $pref) !== 0) {
			return false;	
		}
		return $this->fullfilepath;
		/*
		$pClassFilePath =str_replace('_',DIRECTORY_SEPARATOR,$class_name).'.php';
		return $pClassFilePath;
		*/
		
	}
	function get_debug_info(){
		$r=array();
		$r["class"]=get_class($this);
		$r["file"]=$this->fullfilepath;
		if($items=$this->get_managers()){
			$r["managers"]=array();
			foreach($items as $cod=>$m){
				$r["managers"][$cod]=$m->get_debug_info();
			}
		}
		return $r;
	}

	function do_autoload($class_name,$class_parts,&$report=array()){
		
		if ((class_exists($class_name,FALSE))){
			return;	
		}
		if(!$file=$this->get_class_path_full($class_name)){
			return false;	
		}
		$report["file"]=$file;
		
		if(!is_file($file)){
			return false;	
		}
		if(!file_exists($file)){
			return false;	
		}
		
		if(!is_readable($file)){
			return false;	
		}
		
		
		require_once($file);
		
		
		
		

	}
	
	final function set_full_file_path($fullfilepath){
		$this->fullfilepath=$fullfilepath;	
	}
	final function __get_priv_fullfilepath(){
		return $this->fullfilepath; 	
	}
	
	
}

class  mw_autoload_prefman_direct extends mw_autoload_prefman{
	private $basepath;
	private $_before_autoload_first_done=false;

	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref);
		$this->set_basepath($basepath);
	}
	
	function get_class_sub_path_full($class_name){
		if(!$class_name){
			return false;	
		}
		if(!$pref=$this->pref){
			return false;
		}
		if(strpos($class_name, $pref) !== 0) {
			return false;	
		}
		$pClassFilePath =str_replace('_',DIRECTORY_SEPARATOR,$class_name).'.php';
		return $pClassFilePath;
		
	}
	function get_class_path_full($class_name){
		if(!$sub=$this->get_class_sub_path_full($class_name)){
			return false;	
		}
		if(!$this->basepath){
			return false;	
		}
		return $this->basepath."/".$sub;
	}
	final function before_autoload_first(){
		if($this->_before_autoload_first_done){
			return;
		}
		$this->_before_autoload_first_done=true;
		$this->do_before_autoload_first();
	}
	function do_before_autoload_first(){
			
	}
	function do_autoload($class_name,$class_parts,&$report=array()){
		
		if ((class_exists($class_name,FALSE))){
			return;	
		}
		if(!$file=$this->get_class_path_full($class_name)){
			return false;	
		}
		$report["file"]=$file;
		
		if(!is_file($file)){
			return false;	
		}
		if(!file_exists($file)){
			return false;	
		}
		
		if(!is_readable($file)){
			return false;	
		}
		
		$this->before_autoload_first();
		
		require_once($file);
		
		
		
		

	}
	function get_debug_info(){
		$r=array();
		$r["class"]=get_class($this);
		$r["basepath"]=$this->basepath;
		if($items=$this->get_managers()){
			$r["managers"]=array();
			foreach($items as $cod=>$m){
				$r["managers"][$cod]=$m->get_debug_info();
			}
		}
		return $r;
	}

	
	final function set_basepath($basepath){
		return $this->basepath=$basepath; 	
	}
	final function __get_priv_basepath(){
		return $this->basepath; 	
	}
	
}
class  mw_autoload_prefman_direct_base_path_mode extends mw_autoload_prefman_direct{
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref);
		$this->set_basepath($basepath);
	}
	
	function get_class_sub_path_full($class_name){
		if(!$class_name){
			return false;	
		}
		if(!$pref=$this->pref){
			return false;
		}
		if(strpos($class_name, $pref) !== 0) {
			return false;	
		}
		$n=substr($class_name,(strlen($pref)+1));
		$pClassFilePath =str_replace('_',DIRECTORY_SEPARATOR,$n).'.php';
		return $pClassFilePath;
		
	}
		
}
class  mw_autoload_prefman_direct_requests extends mw_autoload_prefman_direct_base_path_mode{
	function __construct($mainman,$pref,$basepath){
		$this->init($mainman,$pref);
		$this->set_basepath($basepath);
	}
	function get_class_sub_path_full($class_name){
		if(!$class_name){
			return false;	
		}
		if($class_name==$this->pref){
			return 	$class_name.".php";
		}
		
		
		if(!$pref=$this->pref){
			return false;
		}
		if(strpos($class_name, $pref) !== 0) {
			return false;	
		}
		$n=substr($class_name,(strlen($pref)+1));
		$pClassFilePath =str_replace('_',DIRECTORY_SEPARATOR,$class_name).'.php';
		return $pClassFilePath;
		
	}
		
}

class  mw_autoload_prefman_nsmode extends mw_autoload_prefman{
	private $basepath;
	private $namespace;

	function __construct($mainman,$pref,$basepath,$namespace){
		$this->init($mainman,$pref);
		$this->set_basepath($basepath);
		$this->set_namespace($namespace);
	}
	function is_ns_mode(){
		return true;	
	}
	function get_debug_info(){
		$r=array();
		$r["class"]=get_class($this);
		$r["basepath"]=$this->basepath;
		$r["namespace"]=$this->namespace;
		if($items=$this->get_managers()){
			$r["managers"]=array();
			foreach($items as $cod=>$m){
				$r["managers"][$cod]=$m->get_debug_info();
			}
		}
		return $r;
	}
	function get_class_info($class_name,&$info=array()){
		if(!$info){
			$info=array();	
		}
		$info["className"]=$class_name;
		$info["autoloader"]=get_class($this);
		$info["pref"]=$this->pref;
		$info["namespace"]=$this->namespace;
		return $info;
		
		
		
		
	}
	
	
	function check_class_namespace($class_name){
		if(!$prefix = $this->namespace){
			return false;	
		}
		if (strpos($class_name, $prefix) === 0) {
			return true;	
		}
	}
	
	function get_class_sub_path_full($class_name){
		if(!$class_name){
			return false;	
		}
		if(!$this->check_class_namespace($class_name)){
			return false;	
		}
		if(!$prefix = $this->namespace){
			return false;	
		}
		if(!$subcod=substr($class_name,strlen($prefix))){
			return false;	
		}
		//$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
		//$file = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
		
		$pClassFilePath =str_replace('\\',DIRECTORY_SEPARATOR,$subcod).'.php';
		return $pClassFilePath;
	
		
		/*
		if(strpos($class_name, $pref) !== 0) {
			return false;	
		}
		$pClassFilePath =str_replace('_',DIRECTORY_SEPARATOR,$class_name).'.php';
		return $pClassFilePath;
		*/
		
	}
	function get_class_path_full($class_name){
		if(!$sub=$this->get_class_sub_path_full($class_name)){
			return false;	
		}
		if(!$this->basepath){
			return false;	
		}
		return $this->basepath."/".$sub;
	}
	function do_autoload($class_name,$class_parts,&$report=array()){
		
		if ((class_exists($class_name,FALSE))){
			return;	
		}
		if(!$this->check_class_namespace($class_name)){
			return;	
		}
		
		if(!$file=$this->get_class_path_full($class_name)){
			return false;	
		}
		$report["file"]=$file;
		
		if(!is_file($file)){
			return false;	
		}
		if(!file_exists($file)){
			return false;	
		}
		
		if(!is_readable($file)){
			return false;	
		}
		
		
		require_once($file);
		
		
		
		

	}
	final function set_namespace($namespace){
		return $this->namespace=$namespace; 	
	}
	final function __get_priv_namespace(){
		return $this->namespace; 	
	}
	
	final function set_basepath($basepath){
		return $this->basepath=$basepath; 	
	}
	final function __get_priv_basepath(){
		return $this->basepath; 	
	}
	
}

class  mw_autoload_prefman_special extends mw_autoload_prefman{
	private $basepath;
	private $namespace=false;
	public $nonsmode=false;
	private $realPref="";
	public $classdirsep="_";
	
	public $operationsdone=array();
	
	private $autoloadfile="autoload.php";
	private $classinfofile="classinfo.php";
	function __construct($mainman,$cod,$basepath,$pref="",$namespace=false){
		$this->init($mainman,$cod);
		$this->set_realPref($pref);
		$this->set_basepath($basepath);
		$this->set_namespace($namespace);
	}
	function classNameCheckNS($className,$ns=false,$prefix=""){
		if(!$ns){
			if(strpos($className,"\\")){
				return false;
			}
			if(!$prefix){
				return true;	
			}
			
			$len = strlen($prefix);
			if (strncmp($prefix, $className, $len) === 0) {
				return true;	
			}
			return false;
			
				
		}
		
		if(!strpos($className,"\\")){
			return false;
		}
		$prefix=$ns."\\".$prefix;
		$len = strlen($prefix);
		if (strncmp($prefix, $className, $len) === 0) {
			return true;	
		}
		
	}
	
	
	function getOperationIsDone($cod){
		return $this->operationsdone[$cod]+0;
	}
	function setOperationIsDone($cod){
		$this->operationsdone[$cod]=$this->operationsdone[$cod]+1;	
	}
	function is_ns_mode(){
		if($this->nonsmode){
			return false;	
		}
		if($this->namespace){
			return true;	
		}
		return false;	
	}
	
	
	//////////////
	function check_class_namespace($class_name){
		if(!$prefix = $this->namespace){
			return false;	
		}
		$prefix.="\\";
		$len = strlen($prefix);
		if (strncmp($prefix, $class_name, $len) === 0) {
			return true;	
		}

		/*
		if (strpos($class_name, $prefix) === 0) {
			return true;	
		}
		*/
	}
	
	///////
	function get_debug_info(){
		$r=array();
		$r["class"]=get_class($this);
		$r["basepath"]=$this->basepath;
		$r["namespace"]=$this->namespace;
		$r["realPref"]=$this->realPref;
		$r["autoloaderPref"]=$this->getFullPref();
		
		if($items=$this->get_managers()){
			$r["managers"]=array();
			foreach($items as $cod=>$m){
				$r["managers"][$cod]=$m->get_debug_info();
			}
		}
		return $r;
	}
	
	function isSpecial(){
		return true;	
	}
	function includeOnSpecialList(){
		if($this->is_ns_mode()){
			return false;	
		}
		return $this->isSpecial();	
	}
	
	
	function specialCheckClassName($class_name){
		if(!$prefix=$this->getFullPref()){
			return false;	
		}
		if (strpos($class_name, $prefix) === 0) {
			return true;	
		}
		
	}
	function getFullPref(){
		$r="";
		if($this->namespace){
			$r.=$this->namespace."\\";
		}
		$r.=$this->realPref;
		return $r;
	}
	function get_class_sub_path_full($class_name){
		return false;
	}
	function get_class_path_full($class_name){
		return false;
	}
	function get_autoload_file($class_name=false){
		if(!$this->basepath){
			return false;	
		}
		if(!$this->autoloadfile){
			return false;	
		}
		return $this->basepath."/".$this->autoloadfile;
			
	}
	function get_class_rel_path_sub_dir_mode($class_name){
		if(!$p=$this->className2SubDirBySep($class_name)){
			return false;	
		}
		return $p.".php";
	}
	
	function className2SubDirBySep($class_name){
		if(!$classnons=$this->get_class_name_no_ns($class_name)){
			return false;	
		}
		$a=explode($this->classdirsep,$classnons);
		return implode(DIRECTORY_SEPARATOR,$a);
		
	}
	function get_class_name_no_ns($class_name){
		//ya valido que es suya
		if(!$class_name){
			return false;	
		}
		if(!$prefix = $this->namespace){
			return $class_name;	
		}
		if($subcod=substr($class_name,strlen($prefix)+1)){
			return $subcod;	
		}
		
	}
	
	function get_class_info_file($class_name=false){
		if(!$this->basepath){
			return false;	
		}
		if(!$this->classinfofile){
			return false;	
		}
		return $this->basepath."/".$this->classinfofile;
			
	}
	
	function get_class_info($class_name,&$info=array()){
		if(!$info){
			$info=array();	
		}
		$info["className"]=$class_name;
		$info["autoloader"]=get_class($this);
		$info["basepath"]=$this->basepath;
		$info["realPref"]=$this->realPref;
		$info["autoloaderPref"]=$this->getFullPref();
		$info["classOK"]=$this->specialCheckClassName($class_name);
		$info["autoloaderFile"]=$this->get_autoload_file($class_name);
		$info["classInfoFile"]=$this->get_class_info_file($class_name);
		$info["infofromscript"]=$this->get_class_info_from_file($class_name);
		return $info;
		
		
		
		
	}
	function get_class_info_from_file($class_name){
		$info=array();
		$_file=$this->get_class_info_file($class_name);
		if($_file){
			if(is_file($_file)){
				if(file_exists($_file)){
					if(is_readable($_file)){
						include $_file;
					}
				}
			}
		}
		return $info;
			
	}
	
	function do_autoload($class_name,$class_parts,&$report=array()){
		
		if ((class_exists($class_name,FALSE))){
			return;	
		}
		if(!$this->specialCheckClassName($class_name)){
			return;	
		}
		
		if(!$file=$this->get_autoload_file($class_name)){
			return false;	
		}
		$report["file"]=$file;
		
		if(!is_file($file)){
			return false;	
		}
		if(!file_exists($file)){
			return false;	
		}
		
		if(!is_readable($file)){
			return false;	
		}
		$loadanotherfile=false;
		$finalfiletoload=false;
		
		include $file;
		/*
		if (!(class_exists($class_name,FALSE))){
			return false;	
		}
		*/
		
		//$this->doautoloadscript($class_name,$file);
		return true;
	}
	function includeAutoloadScript($file____sdfsd____dds___x,$class_name,&$report=array()){
		//already validates
		require $file____sdfsd____dds___x."";
		
	}
	
	function requireoautoloadscript($file____sdfsd____dds___x,$class_name=false,$once=true){
		//already validates
		if($once){
			require_once $file____sdfsd____dds___x."";
			
		}else{
			require $file____sdfsd____dds___x."";
				
		}
		
		
	}
	final function set_realPref($pref=""){
		return $this->realPref=$pref.""; 	
	}
	final function __get_priv_realPref(){
		return $this->realPref; 	
	}
	final function set_namespace($namespace=false){
		return $this->namespace=$namespace; 	
	}
	final function __get_priv_namespace(){
		return $this->namespace; 	
	}
	final function __get_priv_autoloadfile(){
		return $this->autoloadfile; 	
	}
	final function set_basepath($basepath){
		return $this->basepath=$basepath; 	
	}
	final function __get_priv_basepath(){
		return $this->basepath; 	
	}
	
}



?>