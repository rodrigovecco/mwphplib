<?php
abstract class  mwmod_mw_service_abs extends mw_apsubbaseobj{
	public $cod;
	public $baseurl="";
	public $isfinal=false;
	public $isRoot=false;
	private $_children=array();
	private $loadAllChildrenDone;
	public $defChildCod;
	public $parentService;
	public $name;
	public $autocreateChildrenCods="";
	function execServiceByREQUEST_URI($baseurl=false){
		if($baseurl){
			$this->baseurl=$baseurl;	
		}
		$b=$this->baseurl."";
		$sub=false;
		$req="";
		if($url_p=parse_url($_SERVER['REQUEST_URI'])){
			$req=trim($url_p['path'],"/");
		}
		$l=strlen($b);
		if(substr($req,0,$l)!=$b){
			return false;	
		}
		$sub=trim(substr($req,$l),"/")."";
		$this->execAsRoot($sub);
	}
	
	function isAllowedByParent(){
		if($p=$this->getParent()){
			return $p->isAllowed();	
		}
	}
	
	
	function getChildren(){
		return $this->_get_children();	
	}
	final function _get_children(){
		$this->initAllChildren();
		return $this->_children;	
	}
	function initAllChildren(){
		$this->_initAllChildren();	
	}
	final function _initAllChildren(){
		if($this->loadAllChildrenDone){
			return;
		}
		$this->loadAllChildrenDone=true;
		$this->loadAllChildren();
	}
	function getChildrenByList($cods){
		if(!$cods){
			return false;	
		}
		
		if(!is_array($cods)){
			$cods=explode(",",$cods);	
		}
		$r=array();
		foreach($cods as $c){
			if($ch=$this->getChild($c)){
				$r[$c]=$ch;	
			}
		}
		return $r;
	}
	function loadAllChildren(){
		if($this->autocreateChildrenCods){
			return $this->getChildrenByList($this->autocreateChildrenCods);
		}
	}
	
	function execAsChild($path=false){
		$this->validateAllowedAsChild();
		$this->doExec($path);
	
	}
	function execAsDefault($path=false){
		$this->validateAllowedAsChild();
		$this->doExec($path);
	
	}
	
	function doExec($path=false){
		if($this->isAllowed()){
			$this->doExecOk($path);	
		}else{
			$this->execNotAllowed($path);	
		}
	}
	function doExecOk($path=false){
			
	}
	function validateAllowedAsChild(){
		//acá podrá validar usuarios si root no lo ha hecho
		return $this->isAllowed();
	}
	
	function validateAllowedAsRoot(){
		//acá podrá validar usuarios, etc
		return $this->isAllowed();
	}
	function isAllowed(){
		return false;	
	}
	
	function execNotAllowed($path=false){
		
	}
	function outputJSON($data){
		ob_end_clean();
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	function execAsRoot($path){
		if(!$this->validateAllowedAsRoot()){
			$this->execNotAllowed($path);
			return false;	
		}
		$codslist=array();
		$ch=$this->getChildByPath($path,$codslist);
		//mw_array2list_echo($codslist);
		$subpath="";
		if(sizeof($codslist)){
			$subpath=implode("/",$codslist);	
		}
		if($ch){
			
			$ch->execAsChild($subpath);	
		}else{
			$this->execAsDefault($subpath);	
		}
		
	}
	
	function getChildByPath($path,&$codslist=array()){
		if(!is_array($codslist)){
			$codslist=array();	
		}
		return $this->getChildBySrtSepCod($path,"/",$codslist);
	}
	function getChildBySrtSepCod($fullcod,$sep="/",&$codslist=array()){
		$fullcod=trim($fullcod."");
		$list=explode($sep,$fullcod);
		if(!is_array($codslist)){
			$codslist=array();	
		}
		if(sizeof($codslist)){
			$codslist=array();	
		}
		foreach($list as $c){
			if($c=trim($c)){
				$codslist[]=$c;	
			}
		}
		if(!sizeof($codslist)){
			return false;	
		}
		return $this->getChildByCodsList($codslist);
		
	}
	
	function getChildByCodsList(&$list=array()){
		if(!sizeof($list)){
			return false;	
		}
		$cod=array_shift($list);
		if(!$ch=$this->getChild($cod)){
			return false;	
		}
		if($ch->isFinal()){
			return $ch;	
		}
		if(!sizeof($list)){
			return $ch;
		}
		return $ch->getChildByCodsList($list);
		
	}
	
	
	function init(){
	}
	function initAsChild($cod){
		$this->setCod($cod);
		$this->init();
	}
	function initAsRoot($baseurl=false){
		if($baseurl){
			$this->baseurl=$baseurl;	
		}
		$this->isRoot=true;
		$this->init();
	}
	
	function getChild($cod){
		return $this->getChildCreationMode($cod,true);
	}
	function getChildCreationMode($cod,$create=false){
		if($ch=$this->get_child($cod)){
			return $ch;
		}
		if(!$create){
			return false;	
		}
		if($ch=$this->createNewChild($cod)){
			return $ch;	
		}
	}
	function createNewChild($cod){
		if(!$ch=$this->_createChild($cod)){
			return false;
		}
		$this->addChild($ch,$cod);
		return $ch;
		
	}
	function get_cod(){
		return $this->cod;
	}
	function addChild($child,$cod=false){
		if($cod){
			$child->setCodIfNone($cod);	
		}
		$child->setParent($this);
		$this->_addChild($child,$cod);
		return $child;
	}
	final function _addChild($child,$cod=false){
		if(!$cod){
			$cod=$child->get_cod();
		}
		if(!$cod=$this->check_child_cod($cod)){
			return false;	
		}
		$this->_children[$cod]=$child;
		
	}
	final function _createChild($cod){
		if(!$this->allowCreateChild($cod)){
			return false;	
		}
		return $this->createChild($cod);
		
		
	}
	function createChild($cod){
		return false;
	}
	function childrenCreationEnabled(){
		return false;	
	}
	function isFinal(){
		return 	$this->isfinal;
	}
	function allowCreateChild($cod){
		//
		if(!$this->childrenCreationEnabled()){
			return false;
		}
		return false;
		//return $this->check_child_cod($cod);
	}
	final function get_child($cod){
		if(!$cod=$this->child_exists($cod)){
			return false;	
		}
		return $this->_children[$cod];
		
	}
	final function child_exists($cod){
		if(!$cod=$this->check_child_cod($cod)){
			return NULL;	
		}
		if(array_key_exists($cod,$this->_children)){
			return $cod;	
		}
		return false;
		
	}
	function check_child_cod($cod){
		if(!$cod=basename(trim($cod))){
			return false;	
		}
		return $cod;
	}
	////////child methods
	function setCodIfNone($cod){
		if($this->cod){
			return false;
		}
		return $this->setCod($cod);
	}
	function setCod($cod){
		return $this->_setCod($cod);
	}
	final function _setCod($cod){
		if(!$cod=trim($cod)){
			return false;	
		}
		$this->cod=$cod;
		return $cod;
		
	}
	function setParent($parent){
		return $this->_setParent($parent);
	}
	final function _setParent($parent){
		if(!$parent){
			return false;	
		}
		$this->parentService=$parent;
		return true;
	}
	function getUrlabs($params=array(),$sub=false,$filename=false,$https=NULL,$host=false){
		$url=$this->getUrl($params,$sub,$filename);
		$r="";
		if(is_null($https)){
			if($_SERVER['HTTPS']){
				$https=true;
			}else{
				$https=false;
			}
				
		}
		if($https){
			$r="https://";	
		}else{
			$r="http://";	
		}
		if(!$host){
			$host=$_SERVER['HTTP_HOST'];	
		}
		$r.=$host;
		return $r.$url;
		
	}

	
	function getUrl($params=array(),$sub=false,$filename=false){
		$url=$this->getUrlBase($sub);
		if($filename){
			$url.="/$filename";	
		}
		if($params){
			if(is_array($params)){
				if($q=mw_array2urlquery($params)){
					$url.="?".$q;	
				}
			}
		}
		return $url;
	}
	function isRoot(){
		return $this->isRoot;	
	}
	function getUrlBaseAsRoot($sub=false){
		$s="";
		if($this->baseurl){
			$s=$this->baseurl;	
		}
		if($sub){
			$s.="/$sub";	
		}
		return $r;
		
	}
	function getUrlBase($sub=false){
		if($this->isRoot()){
			return $this->getUrlBaseAsRoot($sub);	
		}
		$s="";
		if($this->cod){
			$s=$this->cod;	
		}
		if($sub){
			$s.="/$sub";	
		}
		if($p=$this->getParent()){
			$p->getUrlBase($s);	
		}
		
	}
	
	
	function getBaseUrl(){
			
	}
	function add2ParentsList(&$list){
		$list[]=$this;
		if($p=$this->getParent()){
			$p->add2ParentsList($list);	
		}
		
	}
	function getParents($includeSelf=false){
		$list=array();
		if($includeSelf){
			$list[]=$this;	
		}
		if($p=$this->getParent()){
			$p->add2ParentsList($list);	
		}
		return $list;
	}
	function getParent(){
		return $this->parentService;	
	}
	function getRoot(){
		if($this->isRoot){
			return $this;	
		}
		if($p=$this->getParent()){
			return $p->getRoot();	
		}
	}
	
	
	
	
	
	
	
	
	
	
	//////////////
	
	
	
	
	
	
	function getItem($cod){
		return $this->get_item($cod);	
	}
	function getItems(){
		return $this->get_items();
	}
	function getItemsEnabled($opossite=false){
		return $this->getItemsByMethod($this->isEnabledMethod,$opossite);
	}
	function getItemsByMethod($method,$opossite=false){
		if(!$method){
			return false;	
		}
		$r=array();
		if($items=$this->get_items()){
			foreach($items as $id=>$item){
				$ok=-1;
				if($item->$method()){
					$ok=1;
				}
				if($opossite){
					$ok=$ok*-1;
				}
				if($ok==1){
					$r[$id]=$item;
				}
			}
		}
		
		return $r;
	}
	
	
	function addItemsAssoc($items){
		$n=0;
		if(is_array($items)){
			foreach($items as $id=>$item){
				if($this->add_itemByCod($id,$item)){
					$n++;	
				}
			}
		}
		return $n;
	}
	function addItemsUnssoc($items){
		$n=0;
		if(is_array($items)){
			foreach($items as $id=>$item){
				if($this->add_item($item)){
					$n++;	
				}
			}
		}
		return $n;
	}
	function addItems($items){
		if($this->addItemsAssocMode){
			return $this->addItemsAssoc($items);
				
		}
		return $this->addItemsUnssoc($items);
	}
	
	function get_items(){
		return $this->items;
	}
	
	function get_item($cod){
		if(!$cod){
			return false;	
		}
		return $this->items[$cod];	
	}
	function add_item($item){
		$cod=$this->get_item_cod($item);
		return $this->add_itemByCod($cod,$item);
	}
	function get_item_cod($item){
		if($this->getItemCodMethod){
			$methods=explode(",",$this->getItemCodMethod);
			foreach($methods as $m){
				if($m=trim($m)){
					if(method_exists($item,$m)){
						if($c=$item->$m()){
							return $c;	
						}
					}
				}
				
			}
		}
	}
	function add_itemByCod($cod,$item){
		if(!$cod){
			return false;	
		}
		$this->items[$cod]=$item;
		return $item;	
	}
	
}
?>