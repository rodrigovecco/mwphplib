<?php

function mw_convert_object_to_array($data) {

    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }
    else {
        return $data;
    }
}
function mw_array_stripslashes(&$a){
	if(!is_array($a)){
		return false;	
	}
	$keys=array_keys($a);
	
	
	foreach ($keys as $key){
		if(is_array($a[$key])){
			mw_array_stripslashes($a[$key]);	
		}elseif(is_string($a[$key])){
			$a[$key]=stripslashes($a[$key]);	
		}
	}
	
}


function mw_array_get_sub_key_array($a,$key=""){
	if(!$r=	mw_array_get_sub_key($a,$key)){
		return false;	
	}
	if(is_array($r)){
		return $r;	
	}
}
function mw_array_get_sub_key_exists($a,$key=false){
	if(!is_array($a)){
		return false;	
	}
	if(!$key){
		return true;	
	}
	$keys=explode(".",$key);
	$last = array_pop($keys);
	if(!sizeof($keys)){
		return array_key_exists($last,$a);	
	}
	$nkey=implode(".",$keys);
	if(!$na=mw_array_get_sub_key($a,$nkey)){
		return false;
	}
	if(!is_array($na)){
		return false;	
	}
	return array_key_exists($last,$na);	
	
	
}

function mw_array_get_sub_key($a,$key=""){
	
	if(mw_array_is_obj_mw_array_allowed($a)){
		return $a->get_prop_from_key_dot($key);	
	}
	if(!$key){
		return $a;	
	}
	
	
	$val=$a;
	$keys1=explode(".",$key);
	$keys=array();
	foreach ($keys1 as $k){
		if($k){
			$keys[]=$k;	
			$keysleft[]=$k;	
		}
	}
	if(sizeof($keys)==0){
		return $val;
	}
	foreach ($keys as $k){
		if(mw_array_is_obj_mw_array_allowed($val)){
			return $val->get_prop_from_key_dot(implode(".",$keysleft));	
		}

		if(!is_array($val)){
			return false;
		}
		array_shift($keysleft);
		$val=$val[$k];
	}
	return $val;
	
}

function mw_array_set_sub_key($key,$val,&$a,$reemplazarsinoesarray=true){
	if(!$key){
		return false;	
	}
	if(mw_array_is_obj_mw_array_allowed($a)){
		
		return $a->set_prop_from_key_dot_if_allowed_from_mw_array_set_sub_key($key,$val);	
	}

	
	if(!is_array($a)){
		$a=array();
		
	}
	if(!is_string($key)){
		if(!is_numeric($key)){	
			return false;
		}
	}
	/*
	if((!is_numeric($key))or(!is_string($key))){
		return false;	
	}
	*/
	$keys1=explode(".",$key);
	$keys=array();
	$keysleft=array();
	foreach ($keys1 as $k){
		if($k){
			$keys[]=$k;	
			$keysleft[]=$k;	
		}
	}
	
	if(sizeof($keys)==0){
		return false;
	}
	$lastkey=array_pop($keys);
	$cambiar=&$a;
	
	if(sizeof($keys)>0){
		foreach ($keys as $k){
			array_shift($keysleft);
			if(!isset($cambiar[$k])){
				$cambiar[$k]=array();
			}
			if(mw_array_is_obj_mw_array_allowed($cambiar[$k])){
				return $cambiar[$k]->set_prop_from_key_dot_if_allowed_from_mw_array_set_sub_key(implode(".",$keysleft),$val);	
			}
			if(!is_array($cambiar[$k])){
				
				if($reemplazarsinoesarray){
					$cambiar[$k]=array();
				}else{
					return false;	
				}
			}
			$cambiar1=&$cambiar[$k];
			$cambiar=&$cambiar1;

		}
	}
	if(mw_array_is_obj_mw_array_allowed($cambiar[$lastkey])){
		return $cambiar[$lastkey]->set_all_prop_from_mw_array_set_sub_key($val);	
	}
	$cambiar[$lastkey]=$val;
	return $a;
	
	
}
function mw_array_is_obj_mw_array_allowed($obj){
	if(!$obj){
		return false;	
	}
	if(is_object($obj)){
		if(is_subclass_of($obj,"mw_object_as_array")){
			return $obj->__mw_array_allow_use_this_object();
		}
	}
}
function mw_array2list($data,$parametros=NULL){
	$r="";
	if(!is_array($parametros)){
		$parametros=array();	
	}
	if ($parametros["nuevo"]){
		$r.="<ul>";
	}
	if (is_object($data)){
		$obj=$data;
		if($obj){
			if(mw_array_is_obj_mw_array_allowed($obj)){
				$data=$obj->get_props_as_array_or_str();
			}elseif(method_exists($obj,'__toString')){
				$data=$obj." [".get_class($obj)."]";
			}else{
				$data="[".get_class($obj)."]";	
			}
		}else{
			$data="NULL";	
		}
	}
	if (is_bool($data)){
		if($data){
			$data="true";	
		}else{
			$data="false";	
		}
	}
	$fnc_si_array=false;
	if($parametros["fnc_si_array"]){
		if(is_string($parametros["fnc_si_array"])){
			if (function_exists($parametros["fnc_si_array"])){
				$fnc_si_array=	$parametros["fnc_si_array"];
			}
		}
	}
	$fnc_si_dato=false;
	if($parametros["fnc_si_dato"]){
		if(is_string($parametros["fnc_si_dato"])){
			if (function_exists($parametros["fnc_si_dato"])){
				$fnc_si_dato=	$parametros["fnc_si_dato"];
			}
		}
	}
	
	if (is_array($data)){
		if ($fnc_si_array){
			$r.=call_user_func($fnc_si_array,$data,$parametros);
		}else{
			
			$oncl="";
			if($parametros["expandible"]){
				//$oncl.="onclick='fp_hide_show_sublist(this)' style='cursor:pointer' ";	
			}
			
			$r.="<li ><div $oncl>";
			$r.=$parametros["nombre"]."</div>";
			$r.="<ul>";
			
			/*
			while(list($key,$val)=each($data)){
				$parametros_n=$parametros;
				$parametros_n["nuevo"]=0;
				$parametros_n["nombre"]=$key;
				$r.=mw_array2list($val,$parametros_n);
			}
			*/
			foreach($data as $key=>$val){
				$parametros_n=$parametros;
				$parametros_n["nuevo"]=0;
				$parametros_n["nombre"]=$key;
				$r.=mw_array2list($val,$parametros_n);	
			}
			$r.="</ul>";
			$r.="</li>";
		}
	}else{
		if ($fnc_si_dato){
			$r.=call_user_func($fnc_si_dato,$data,$parametros);
		}else{
			$r.="<li>";	
			$r.=$parametros["nombre"]." == > ".$data;
			$r.="</li>";		
		}		
	}
	
	if ($parametros["nuevo"]){
		$r.="</ul>";
	}
	return $r;
}

function mw_array2jsval($data,$addbrline=false){
	if(is_numeric($data)){
		if($data==($data+0)){
			return $data+0;	
		}
		return "'".mw_text_nl_js($data)."'";	
	}
	if(is_null($data)){
		return "null";	
	}
	if (is_bool($data)){
		if($data){
			return "true";	
		}else{
			return "false";	
		}
	}
	if (is_object($data)){
		$obj=$data;
		if($obj){
			if(mw_array_is_obj_mw_array_allowed($obj)){
				
				if(method_exists($obj,'get_as_js_val')){
					return $obj->get_as_js_val();
				}
				
				return mw_array2jsval($obj->get_props_as_array_or_str());
			}elseif(method_exists($obj,'__toString')){
				return "'".$obj."'";
			}else{
				return "''";	
			}
		}else{
			return "null";	
		}
	}
	if(is_string($data)){
		return "'".mw_text_nl_js($data)."'";	
	}
	if(!is_array($data)){
		return "null";	
	}
	$list=array();
	foreach($data as $k=>$v){
		if($cod=mw_text_js_check_obj_cod($k)){
			
			$list[]=$cod.":".mw_array2jsval($v,$addbrline);	
		}
		
	}
	$r="{";
	$sep=",";
	if($addbrline){
		$sep=",\n";	
	}
	$r.=implode($sep,$list);
	$r.="}";
	if($addbrline){
		$r.="\n";	
	}
	return $r;
	
	
	
}
function mw_array_get_obj_as_array_or_str($data){
	
	if (is_bool($data)){
		if($data){
			$data="true";	
		}else{
			$data="false";	
		}
		return $data;
	}
	
	if (is_object($data)){
		$obj=$data;
		if($obj){
			if(mw_array_is_obj_mw_array_allowed($obj)){
				$data=$obj->get_props_as_array_or_str();
			}elseif(method_exists($obj,'__toString')){
				$data=$obj."";
			}else{
				$data="";	
			}
		}else{
			$data="NULL";	
		}
	}
	if (is_bool($data)){
		if($data){
			$data="true";	
		}else{
			$data="false";	
		}
	}
	return $data;
	
}
abstract class mw_object_as_array{
	function __mw_array_allow_use_this_object(){
		return false;	
	}
	function __mw_array_allow_write(){
		return $this->__mw_array_allow_use_this_object();	
	}
	function set_props($val){
		$this->props=$val;	
	}
	function get_props(){
		return $this->props;	
	}
	function get_prop_from_key_dot($key=false){
		$a=$this->get_props();
		if(!$key){
			return $a;	
		}
		return mw_array_get_sub_key($a,$key);
	}
	function get_props_as_array_or_str(){
		$r=$this->get_props();
		return mw_array_get_obj_as_array_or_str($r);
	}
	function __toString(){
		return get_class($this);	
	}
	function set_prop_from_key_dot_if_allowed_from_mw_array_set_sub_key($key,$val){
		if(!$this->__mw_array_allow_write()){
			return false;	
		}
		return $this->set_prop_from_key_dot($key,$val);
	}
	function set_all_prop_from_mw_array_set_sub_key($val){
		if(!$this->__mw_array_allow_write()){
			return false;	
		}
		return $this->set_props($val);
	}
	function set_prop_from_key_dot($key,$val){
		if(!$key){
			return false;	
		}
		if(!is_array($this->props)){
			$this->props=array();	
		}
		mw_array_set_sub_key($key,$val,$this->props);
		return true;
	}

	

}
function mw_array_sort_objects_by_method(&$array,$fnc_sort,$orden=SORT_ASC,$modo=SORT_STRING ,$strhuman=true){
	if (is_array($array)){
		$array_sort=array();
		foreach ($array as $key => $obj) {
			$valorsort="";
			if($obj){
				if(is_object($obj)){
					if(method_exists($obj,$fnc_sort	)){
						$valorsort=$obj->$fnc_sort();
					}
				}
			}
			if ($strhuman){
				$valorsort=mw_text_replace_lngchars($valorsort,true);
		  	}
			$valorsort=strtolower($valorsort);
			$array_sort[$key]  = $valorsort;
		}
		reset($array);
		array_multisort($array_sort,$orden,$modo,$array);		
	}
	return  $array;
}


function mw_array_human_sort($array,$subkey=false){
	if(!is_array($array)){
		return false;	
	}
	$r=array();
	if($keys=mw_array_get_keys_human_sort($array,$subkey)){
		foreach ($keys as $k){
			$r[$k]=$array[$k];	
		}
	}
	return $r;
	
}
function mw_array_get_keys_human_sort($array,$subkey=false){
	$asort=array();
	if(is_array($array)){
		foreach ($array as $k=>$v){
			$txt=$v;
			if($subkey){
				if(is_array($v)){
					$txt=$v[$txt];	
				}
			}
			$txt=mw_text_replace_lngchars($txt,true);
			$txt=strtolower($txt);
			$asort[$k]=$txt;
		}
			
	}
	reset($asort);
	asort($asort,SORT_STRING);
	$r=array();
	foreach ($asort as $k=>$v){
		$r[]=$k;	
	}
	return $r;
}

function mw_array2list_echo($data,$titulo="DATA",$echo=true){
	$parametros["nuevo"]=true;
	$parametros["expandible"]=true;
	$parametros["nombre"]=$titulo;
	
	$html= "<div align='left'>";
	$html.=  mw_array2list($data,$parametros);
	$html.= "</div>";
	if($echo){
		echo $html;	
	}
	return $html;
}
function mw_array2jsalert($data){
	if (is_array($data)){
		foreach ($data as $k=>$v){
			$data[$k]=addslashes($v);
		}
		return implode("\\n",$data);
	}else{
		return addslashes($data);
	}
}
function mw_array_strptags(&$data){
	$data= mw_array_do_fncrec($data,"strip_tags");
	//$data= mw_array_striptags_do($data);
	return $data; 
}
function mw_array_mysql_real_escape_string(&$data){
	//obsoleto
	$data= mw_array_do_fncrec($data,"mysql_real_escape_string");
	//$data= mw_array_striptags_do($data);
	return $data; 
}
function mw_array_striptags_do($data){
	if (is_array($data)){
		while(list($k,$v)=each($data)){
			$vals[$k]=mw_array_striptags_do($v);
		}
	}else{
		$vals=strip_tags($data);
	}
	return $vals;
}
function mw_array_do_fncrec($data,$fnc){
	if (is_array($data)){
		while(list($k,$v)=each($data)){
			$vals[$k]=mw_array_do_fncrec($v,$fnc);
		}
	}else{
		$vals=$fnc($data);
	}
	return $vals;
}
function mw_array_params2array($strparams,$sep=",",$igualsym="="){
	$a=explode($sep,$strparams);
	foreach ($a as $pp){
		$pp=trim($pp);
		
		if (strpos($pp,$igualsym)){
			$b=explode($igualsym,$pp);
			if ($b[0]){
				$r[$b[0]]=$b[1];	
			}
		}
	}
	return $r;
}

function mw_array2php($data,$array_name='$datos'){
	if (is_array($data)){
		foreach ($data as $k=>$v){
			$r.=mw_array2php($v,$array_name."['".$k."']");
		}
	}else{
		$r=$array_name."=\"".addslashes($data)."\";\n";
	
	}
	return $r;

}

function mw_array2urlquery($data){
	if(!is_array($data)){
		return false;	
	}
	$args=array();
	mw_array2urlarg($args,$data,"");
	if(!is_array($args)){
		return false;	
	}
	if(!sizeof($args)){
		return false;	
	}
	$r="";
	$a=array();
	foreach($args as $k=>$v){
		$a[]=$k."=".urlencode($v);	
	}
	return implode("&",$a);

}

function mw_array2urlarg(&$arg,$data,$array_name=false){
	if (is_array($data)){
		foreach ($data as $k=>$v){
			if($array_name){
				$naname=$array_name."[".$k."]";	
			}else{
				$naname=$k;	
			}
			mw_array2urlarg($arg,$v,$naname);
		}
	}else{
		if($array_name){
			$arg[$array_name]=($data);
		}
	}
	return $arg;

}
function mw_array2xml_str_data_type($data){
	//corresponde con la función js fp_ajax_xml2obj_item
	if(is_array($data)){
		return "Object";	
	}
	if(is_bool($data)){
		return "Bool";	
	}
	if(is_numeric($data)){
		if(is_int($data)){
			return "Int";		
		}
		return "Numeric";	
	}
	return "String";
}
class mw_array2xmldataitem{
	function __construct($id,$data=array(),$tags="",$isroot=false){
		$this->parametros=array("tags"=>$tags);
		$this->isroot=$isroot;
		$this->id=$id;
		$this->data=array();
		if(is_array($data)){
			$this->data=$data;
		}
		
	}
	function add_data_item($id,$val){
		$this->data[$id]=$val;
	}
	function add_data_items($data){
		if(is_array($data)){
			foreach($data as $id=>$v){
				$this->add_data_item($id,$val);	
			}
		}
	}
	function get_xml_items(){
		$parametros=$this->parametros;
		$parametros["tags"]=$this->get_items_tags();
		foreach($this->data as $cod=>$v){
			$r.=mw_array2xmlitem($cod,$v,$parametros);
		}
		return $r;
	}
	function do_echo_all_xml_open(){
		echo $this->get_xml_open();
		echo $this->get_xml_items();
	}
	function do_echo_all_xml_close(){
		echo $this->get_xml_close();
	}
	function get_xml_open(){
		$r=$this->parametros["tags"];
		if($this->isroot){
			$r.="<".$this->id.">\n<data  dataType='Object'  >\n";	
		}else{
			$r.="<item id='".$this->id."' dataType='Object'>\n";
		}
		return $r;
	}
	function get_xml_close(){
		$r=$this->parametros["tags"];
		if($this->isroot){
			$r.="</data>\n";	
			$r.="</".$this->id.">\n";	
		}else{
			$r.="</item>\n";
		}
		return $r;
	}
	function get_items_tags(){
		return 	$this->parametros["tags"]."\t";	
	}
	function do_echo_xml_cdata_open($id){
		echo $this->get_xml_cdata_open($id);
		ob_start("mw_str_parse_cdata");	
	}
	function do_echo_xml_cdata_close(){
		ob_end_flush();
		echo $this->get_xml_cdata_close();
	}
	function get_xml_cdata_open($id){
		$r=$this->get_items_tags();
		$r.="<item id='$id' dataType='String'><![CDATA[";
		return $r;
	}
	function get_xml_cdata_close(){
		$r.="]]></item>\n";
		return $r;
	}
	
}
function mw_array2xmlitem($k,$v,$parametros=array()){
	$tags=$parametros["tags"];
	$dtype=mw_array2xml_str_data_type($v);
	if (is_array($v)){
		$r.=$tags."<item id='$k' dataType='$dtype' >\n";
		$parametrosthis=$parametros;
		$parametrosthis["tags"]=$tags;
		$r.="\n".mw_array2xml($v,false,$parametrosthis)."";
		$r.="$tags</item>\n";
	}else{
		$r.=$tags."<item id='$k' dataType='$dtype'  >";
		$r.=mw_array2xml_parse_node_string_value($v);
		$r.="</item>\n";
	}
	return $r;
}
function mw_array2xml($data,$isroot=true,$parametros=array()){
	$tags=$parametros["tags"];
	if ($isroot){
		$dtype=mw_array2xml_str_data_type($data);
		if (is_array($data)){
			$r.="<data  dataType='$dtype'  >\n";
			$cierre="</data>\n".$cierre;
		}else{
			$r.="<data  dataType='$dtype'  >";
			$cierre="</data>\n".$cierre;
		}
	}
	if (is_array($data)){
		$tags.="\t";
		foreach ($data as $k=>$v){
			$kok=true;
			if(!is_numeric($k)){
				if (empty($k)){
					$kok=false;	
				}
			}
			if ($kok){
				$dtype=mw_array2xml_str_data_type($v);
				if (is_array($v)){
					$r.=$tags."<item id='$k' dataType='$dtype' >\n";
					$parametrosthis=$parametros;
					$parametrosthis["tags"]=$tags;
					$r.="\n".mw_array2xml($v,false,$parametrosthis)."";
					$r.="$tags</item>\n";
				}else{
					$r.=$tags."<item id='$k' dataType='$dtype'  >";
					$r.=mw_array2xml_parse_node_string_value($v);
					$r.="</item>\n";
					
				}
				
			}
		}
	}else{
		$r.=mw_array2xml_parse_node_string_value($data);
	}
	$r.=$cierre;
	return $r;
}

function mw_array2xml_parse_node_string_value($val){
	
	if(is_bool($val)){
		if($val){
			return 1;	
		}else{
			return 0;	
		}
	}
	/*
	if(is_object($val)){
		return get_class($val)."xxx";
	}
	*/
	if(is_numeric($val)){
		return $val;	
	}
	$cdata=false;
	if(strpos($val,"<")!==false){
		$cdata=true;
	}
	if(strpos($val,">")!==false){
		$cdata=true;
	}
	if(strpos($val,"&")!==false){
		$cdata=true;
	}
	if($cdata){
		$r="<![CDATA[";
		$r.=mw_str_parse_cdata($val);	
		$r.="]]>";
		return $r;
	}
	return $val;
}
function mw_array_multisort_keepkeys(&$array,$key_sort=false,$orden=SORT_ASC,$modo=SORT_NUMERIC,$strhuman=true){
	if (is_array($array)){
		$array_sort=array();
		foreach ($array as $key => $row) {
			if ($key_sort===false){
				$valorsort=$row;
			}else{
				$valorsort=$row[$key_sort];
			}
			if ($strhuman){
				$valorsort=mw_text_replace_lngchars($valorsort,true);
		  	}
			$valorsort=strtolower($valorsort);
			$array_sort[$key]  = $valorsort;
		}
		if($orden==SORT_DESC){
			arsort($array_sort);		
		}else{
			asort($array_sort);	
		}
		$r=array();
		foreach($array_sort as $id=>$v){
			$r[$id]=$array[$id];
			
		}
		$array=$r;
		return $r;
		
	}

	return  $array;
}

function mw_array_multisort($array,$key_sort=false,$orden=SORT_ASC,$modo=SORT_NUMERIC,$strhuman=true){
	if (is_array($array)){
		$array_sort=array();
		foreach ($array as $key => $row) {
			if ($key_sort===false){
				$valorsort=$row;
			}else{
				$valorsort=$row[$key_sort];
			}
			if ($strhuman){
				$valorsort=mw_text_replace_lngchars($valorsort,true);
		  	}
			$valorsort=strtolower($valorsort);
			$array_sort[$key]  = $valorsort;
		}
		reset($array);
		array_multisort($array_sort,$orden,$modo,$array);		
	}

	return  $array;
}
function mw_array_sortplanabyarbol($arrayplana,$arrayarbol=array(),$key_sort=false,$orden=SORT_ASC,$modo=SORT_STRING,$strhuman=true){
	if (is_array($arrayarbol)){
		$array_sort=array();
		foreach ($arrayplana as $key => $row) {
			if ($key_sort===false){
				$valorsort=$row;
			}else{
				$valorsort=$row[$key_sort];
			}
			if ($strhuman){
				$valorsort=mw_text_replace_lngchars($valorsort);
		  	}
			$valorsort=strtolower($valorsort);
			$array_sort[$key]  = $valorsort;
		}
		reset($arrayplana);
		array_multisort($array_sort,$orden,$modo,$arrayplana);		
	}

	return  $arrayplana;
}

function mw_array_add_unique (&$array,$key,$val,$num=""){
	if (is_array($array)){
		if (isset($array[$key.$num])){
			$num+=1;
			mw_array_add_unique ($array,$key,$val,$num);
		}else{
			$array[$key.$num]=$val;
		}
	}else{
		$array[$key.$num]=$val;
	}
	return $array;
}
function mw_array_bydefault($datos,$pordefecto,$strvacioaNULL=false){
	if(is_object($datos)){
		return $datos;	
	}elseif(is_object($pordefecto)){
		return $pordefecto;		
	}
	if ($strvacioaNULL){
		if (!is_array($datos)){
			if (strlen($datos)<=0){
				$datos=NULL;
			}
		}
	}
	if (is_array($pordefecto)){
		if ((is_array($datos))or(is_null($datos))){
			foreach($pordefecto as $key=>$val){
				$datos[$key]=mw_array_bydefault($datos[$key],$val,$strvacioaNULL);
			}
			/*
			while (list($key,$val)=each($pordefecto)){
				$datos[$key]=mw_array_bydefault($datos[$key],$val,$strvacioaNULL);
			}
			*/
		}
	}elseif ((!is_null($pordefecto))and(is_null($datos))){
		$datos=$pordefecto;
	}
	return $datos;
}
function mw_array_bydefaultbyset($datos,$pordefecto){
	if (is_array($pordefecto)){
		if ((is_array($datos))or(is_null($datos))){
			while (list($key,$val)=each($pordefecto)){
				$datos[$key]=mw_array_bydefaultbyset($datos[$key],$val,$strvacioaNULL);
			}
		}
	}elseif ((isset($pordefecto))and(!isset($datos))){
		$datos=$pordefecto;
	}
	return $datos;
}

function mw_array_unset_empty ($datos){
	if (is_array($datos)){
		foreach ($datos as $k=>$v){
			if (is_array($v)){
				$datos[$k]=	mw_array_unset_empty($v);
				if (sizeof($datos[$k])<=0){
					unset($datos[$k]);
				}
			}elseif(strlen($v)<=0){
				unset($datos[$k]);
			}
		}
	}
	return $datos;
}

function mw_array_get_key_val ($array, $key,$get_resto=false){
	if (is_array($array)){
		foreach($array as $k=>$v){
			$r[$k]=$v[$key];
			if ($get_resto){
				unset($array[$k][$key]);
			}
		}
	}
	if ($get_resto){
		$rr["val"]=$r;
		$rr["resto"]=$array;
		return $rr;
	}else{
		return $r;
	}
}
function mw_array_insert_val(&$array,$nuevoarray,$refkey=false,$before=true){
	if (!is_array($nuevoarray)){
		return false;	
	}
	if (!is_array($array)){
		$array=$nuevoarray;
		return true;	
	}
	$refkeyset=true;
	if ($refkey===false){
		$refkeyset=false;	
	}elseif(!isset($array[$refkey])){
		$refkeyset=false;		
	}
	if (!$refkeyset){
		if ($before){
			$a1=$nuevoarray;
			$a2=$array;
		}else{
			$a2=$nuevoarray;
			$a1=$array;
		}
		$array=array();
		foreach ($a1 as $k=>$v){
			if (!isset($array[$k])){
				$array[$k]=$v;	
			}
		}
		foreach ($a2 as $k=>$v){
			if (!isset($array[$k])){
				$array[$k]=$v;	
			}
		}
		return true;
	}
	$arrayorig=$array;
	$array=array();
	foreach ($arrayorig as $k=>$v){
		if ($k==$refkey){
			if (!$before){
				$array[$k]=$v;
			}
			foreach ($nuevoarray as $kn=>$vn){
				if (!isset($array[$kn])){
					$array[$kn]=$vn;
				}
			}
			if ($before){
				if (!isset($array[$k])){
					$array[$k]=$v;
				}
			}
		}else{
			if (!isset($array[$k])){
				$array[$k]=$v;
			}
		}
	}
	return true;
}
?>