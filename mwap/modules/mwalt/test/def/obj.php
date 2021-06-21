<?php
class mwalt_test_obj extends mw_apsubbaseobj{
	function __construct(){
			
	}
	function test(){
		echo "<div>Soy ".get_class($this)." en ".__FILE__."</div>";
	}
	
}
?>