<?php
class mwalt_test_sub_other extends mwalt_test_obj{
	function __construct(){
			
	}
	function test(){
		echo "<div>Soy ".get_class($this)." en ".__FILE__."</div>";
	}
	
}
?>