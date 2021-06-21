<?php
class mwmod_mw_date_timezone_tzitem extends mwmod_mw_manager_item{
	private $timeZone;
	private $GTMoffset;
	private $GTMoffsetTxt;
	function __construct($tblitem,$man){
		$this->init($tblitem,$man);
	}
	function get_debug_data(){
		$r=$this->get_data();
		$this->__get_priv_timeZone();
		if($this->__get_priv_timeZone()){
			$r["timeZone"]=array(
				"name"=>$this->timeZone->getName(),	
			);
		}
		$r["name"]=$this->get_name();
		$r["GTMoffset"]=$this->__get_priv_GTMoffset();
		$r["GTMoffsetTxt"]=$this->__get_priv_GTMoffsetTxt();
		return $r;
	}
	function get_name(){
		if($tz=$this->__get_priv_timeZone()){
			return $tz->getName()." ".$this->__get_priv_GTMoffsetTxt()." GMT";	
		}
		
		return $this->get_data("zone_name");
	}
	
	
	
	final function __get_priv_GTMoffsetTxt(){
		if(!isset($this->GTMoffsetTxt)){
			$seconds=$this->__get_priv_GTMoffset();
			$sig="+";
			if($seconds<0){
				$seconds=$seconds*-1;
				$sig="-";
			}
			$this->GTMoffsetTxt=$sig.date('H:i', strtotime("2000-01-01 + $seconds SECONDS"));
		}
		return $this->GTMoffsetTxt;
	}
	final function __get_priv_GTMoffset(){
		if(!isset($this->GTMoffset)){
			if($this->__get_priv_timeZone()){
				$myDateTime = new DateTime(date("r"), $this->man->GMT);
				$this->GTMoffset=$this->timeZone->getOffset($myDateTime);
					
			}else{
				$this->GTMoffset=0;	
			}
		}
		return $this->GTMoffset;
	}
	
	final function __get_priv_timeZone(){
		if(!isset($this->timeZone)){
			if(!$tz=$this->get_data("zone_name")){
				$tz="GMT";		
			}
			$this->timeZone=new DateTimeZone($tz);
		}
		return $this->timeZone;
	}



}
?>