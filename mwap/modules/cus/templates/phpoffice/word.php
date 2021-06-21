<?php
class mwcus_cus_templates_phpoffice_word extends mwmod_mw_phpoffice_word_template_def{
	function __construct($phpWord=false){
		$this->init_template($phpWord);
	}
	function do_preparePhpWord_defaults($phpWord){
		$phpWord->setDefaultFontName('Calibri');
		$phpWord->setDefaultFontSize(10);
		$phpWord->setDefaultParagraphStyle(array(
			'align'=>"justify"
		));
		
			
	}
	function do_preparePhpWord_title_styles($phpWord){
			
	}
	function do_preparePhpWord_other_styles($phpWord){
			
	}
	function do_preparePhpWord_additional($phpWord){
			
	}
	
	
	
}
?>