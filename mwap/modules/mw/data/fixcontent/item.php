﻿<?php
class mwmod_mw_data_fixcontent_item extends mwmod_mw_data_fixcontent_absitem{
	
	function __construct($filepath,$fixcontentman){
		$this->init_fix_content_item($filepath,$fixcontentman);
	
	}
	function saveContent($content){
		$content=$content."";
		$this->fixcontentman->setContent($this->filepath,$content,true);	
	}
	
	
}

?>