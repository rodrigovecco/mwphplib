<?php
function mw_text_nl_js($str){
	$str=addcslashes($str,"\\\'\"&\n\r<>"); 
	return $str;
}
function mw_text_js_check_obj_cod($str){
	$str.="";
	if(!strlen($str)){
		return false;
	}
	if(strpos($str,",")!==false){
		return false;	
	}
	if(strpos($str,".")!==false){
		return false;	
	}
	if(strpos($str,"=")!==false){
		return false;	
	}
	if(strpos($str,":")!==false){
		return false;	
	}
	if(strpos($str," ")!==false){
		return false;	
	}
	return $str;
}

function mw_text_replace_lngchars($str,$mode=false,$nonalphaandnotlistedto=false,$returnchtbl=false){
	//$mode=false: normal para nombres de archivos, etc
	//$mode=true: para ordenar
	//$mode=upper mayúsculas
	//$mode= lower: minúsculas
	//$mode=upperindex mayúsculas sin tildes, para dictectorios
	$ch=array(
	'A'=>array('À','Á','Â','Ã','Ä','Å'),
	'a'=>array('à','á','â','ã','ä','å'),
	'E'=>array('È','É','Ê','Ë'),
	'e'=>array('è','é','ê','ë'),
	'f'=>array('ƒ'),
	'I'=>array('Ì','Í','Î','Ï'),
	'i'=>array('ì','í','î','ï'),
	'O'=>array('Ò','Ó','Ô','Õ','Ö','Ø'),
	'o'=>array('ò','ó','ô','õ','ö','ø'),
	'S'=>array('Š'),
	's'=>array('š'),
	'U'=>array('Ù','Ú','Û','Ü'),
	'u'=>array('ù','ú','û','ü'),
	'y'=>array('ý','ÿ'),
	'Y'=>array('Ý','Ÿ'),
	'Z'=>array('Ž'),
	'z'=>array('ž'));
	$vocales=array("a","e","i","o","u");
	$otrascontilde=array("y","z","S");
	$speciales=array(
		'AE'=>array('Æ'),
		'ae'=>array('æ'),
		'OE'=>array('Œ'),
		'oe'=>array('œ'),
		'ss'=>array('ß')

	);
	$speciales2upper=array(
		'Æ'=>array('æ'),
		'Œ'=>array('œ'),
		'Ñ'=>array('ñ'),
		'Ð'=>array('ð'),
		'Ç'=>array('ç')
	);
	$speciales2lower=array(
		'æ'=>array('Æ'),
		'œ'=>array('Œ'),
		'ñ'=>array('Ñ'),
		'ð'=>array('Ð'),
		'ç'=>array('Ç')
	);
	if($mode==="upper"){
		$vocalesext=array_merge($vocales,$otrascontilde);
		foreach ($vocalesext as $vocal){
			if($min=$ch[$vocal]){
				if($may=$ch[strtoupper($vocal)]){
					foreach ($min as $i=>$minL){
						$mayL=$may[$i];
						$ch[$mayL]=array($minL);
					}
				}
				unset($ch[$vocal]);
				unset($ch[strtoupper($vocal)]);
			}
		}
		$ch=array_merge($ch,$speciales2upper);
		$ch["SS"]=array("ß");
		reset($ch);
	}elseif($mode==="lower"){
		$vocalesext=array_merge($vocales,$otrascontilde);
		foreach ($vocalesext as $vocal){
			if($min=$ch[$vocal]){
				if($may=$ch[strtoupper($vocal)]){
					foreach ($min as $i=>$minL){
						$mayL=$may[$i];
						$ch[$minL]=array($mayL);
					}
				}
				unset($ch[$vocal]);
				unset($ch[strtoupper($vocal)]);
			}
		}
		$ch=array_merge($ch,$speciales2lower);
		reset($ch);
	}elseif($mode==="upperindex"){
		foreach ($vocales as $vocal){
			$min=$vocal;
			$may=strtoupper($vocal);
			$ch[$may]=array_merge($ch[$may],$ch[$min]);
			unset($ch[$min]);
		}
		$ch=array_merge($ch,$speciales2upper);
		
	}elseif($mode){
		array_merge($ch,$speciales);
		$ch["Dz"]=array("Ð");		
		$ch["dz"]=array("ð");		
		$ch["Nz"]=array("Ñ");		
		$ch["nz"]=array("ñ");		
		$ch["Zz"]=array("Ç");		
		$ch["zz"]=array("ç");		
	}else{
		array_merge($ch,$speciales);
		$ch["D"]=array("Ð");		
		$ch["d"]=array("ð");		
		$ch["N"]=array("Ñ");		
		$ch["n"]=array("ñ");		
		$ch["c"]=array("Ç");		
		$ch["c"]=array("ç");		
	}
	if($returnchtbl){
		return $ch;	
	}
	foreach ($ch as $l=>$a){
		foreach ($a as $b){
			$str=str_replace($b,$l,$str);
		}
	}
	if(($mode==="upperindex")or($mode==="upper")){
		$str=strtoupper($str);	
	}elseif($mode==="lower"){
		$str=strtolower($str);	
	}
	
	if($nonalphaandnotlistedto){
		$str_a=str_split($str);
		foreach ($str_a as $i=>$l){
			if(!ctype_alpha($l)){
				if(!$ch[$l]){
					$str_a[$i]=	$nonalphaandnotlistedto;
				}
			}
		}
		$str=implode("",$str_a);
	}
	
	return $str;
}


function mw_str_parse_cdata($str){
	return str_replace("]]>","]]\n>",$str);	
}
function mw_text_js_msgalert($msg){
	if($mstjstxt=mw_array2jsalert($msg)){
		$html.="<script type='text/javascript' language='javascript'>\n";
		$html.="alert('$mstjstxt')\n;";
		$html.="</script>";
		return $html;
	}
}
function mw_text_plain_chars($str){
	$str=mw_text_replace_lngchars($str);
	$num=strlen($str);
	$str1="";
	for ($x=0;$x<$num;$x++){
		$caracter=substr($str,$x,1);
		if (!ctype_alnum($caracter)){
			$caracter="_";
		}
		$str1.=$caracter;
	}
	return $str1;
}
function mw_checkemail($address,$alloempty=false){
	if ($alloempty){
		if(empty($address)){
			return true;
		}
	}
	
	return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address);
}

?>