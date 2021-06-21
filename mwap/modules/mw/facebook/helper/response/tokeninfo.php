<?php
class  mwmod_mw_facebook_helper_response_tokeninfo extends mwmod_mw_facebook_helper_response {
	public $inputToken;
	function __construct($inputToken=false,$fbHelper=false){
		$this->init(false,$fbHelper);
		$this->inputToken=$inputToken;
		$this->endPoint="debug_token";
	}
	function getParams(){
		if(!$token=$this->getInputToken()){
			return false;	
		}
		return array("input_token"=>$token);	
	}
	
	
	function getInputToken(){
		if($this->inputToken){
			return $this->inputToken;	
		}
		if($this->fbHelper){
			return 	$this->getDefaultAccessToken();
		}
	}
	
	function tokenProfileID(){
		return $this->getResponseData("data.profile_id");	
	}
	
	function tokenIsValid(){
		if($this->getResponseData("data.is_valid")){
			return true;
		}
		return false;
	}
	function tokenExpireDate(){
		if(!$t=$this->getResponseData("data.expires_at")){
			return "";
		}
		return date("Y-m-d H:i:s",$t);
	}
	
	function tokenExpires(){
		if($this->getResponseData("data.expires_at")){
			return true;
		}
		return false;
	}

}
?>