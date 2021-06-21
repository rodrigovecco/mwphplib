<?php

function randPass($len){
	$pw = ''; 
	for($i=0;$i<$len;$i++) {
		switch(rand(1,3))   {
			case 1: $pw.=chr(rand(49,57));  break; 
			case 2: $pw.=chr(rand(65,90));  break;
			case 3: $pw.=chr(rand(97,122)); break;
		}
	}
	return $pw;
}
?>