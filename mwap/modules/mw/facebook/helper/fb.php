﻿<?php
class  mwmod_mw_facebook_helper_fb extends mw_apsubbaseobj {
	public $fb;
	public $debugMode=false;
	public $errors;
	
	
	function __construct($fb=false){
		if($fb){
			$this->fb=$fb;
		}
	}
	function request($method, $endpoint, $params = array(), $accessToken = null, $eTag = null, $graphVersion = null){
		if($this->fb){
			return $this->fb->request($method, $endpoint, $params , $accessToken , $eTag , $graphVersion );	
		}
		
	
	}
	function getDefaultAccessToken(){
		if($this->fb){
			return $this->fb->getDefaultAccessToken();	
		}
	}
	function getLongLivedAccessToken($accessToken){
		if(!$this->fb){
			return false;	
		}
		if(!$accessToken=$accessToken.""){
			return false;	
		}
		try {
			$OAuth2Client=$this->fb->getOAuth2Client();
			$newToken=$OAuth2Client->getLongLivedAccessToken($accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			$this->reportError($e);
			return false;	
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$this->reportError($e);
			return false;	
		} catch (Exception $e) {
			$this->reportError($e);
			return false;	
		}
		return $newToken;
	}
	
	function getTokenInfo($token=false){
		$mwResponse=new mwmod_mw_facebook_helper_response_tokeninfo($token,$this);
		$mwResponse->proc();
		return $mwResponse;
		
	}
	function sendRequestProccess($mwResponse,$method, $endpoint, $params = array(), $accessToken = null, $eTag = null, $graphVersion = null){
		if(!$this->fb){
			$mwResponse->reportError("No FB OBJ");
			return false;	
		}
		try {
			  $response = $this->fb->sendRequest(
				$method, $endpoint, $params , $accessToken , $eTag , $graphVersion
			);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
			$mwResponse->reportError($e);
			$mwResponse->reportError($e."");
			$mwResponse->reportError("FacebookResponseException");
			//return false;	
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$mwResponse->reportError($e);
			$mwResponse->reportError($e."");
			$mwResponse->reportError("FacebookSDKException");
			//return false;	
		} catch (Exception $e) {
			$mwResponse->reportError($e);
			$mwResponse->reportError("Exception");
			//return false;	
		}
		if($response){
			$mwResponse->setResponse($response);
			return true;
		}else{
			$mwResponse->reportError("No Response OBJ");	
		}
		return false;
		
	
	}
	
	function sendRequest($method, $endpoint, $params = array(), $accessToken = null, $eTag = null, $graphVersion = null,$mwResponse=false){
		
		
		if(!$mwResponse){
			$mwResponse=new mwmod_mw_facebook_helper_response(null,$this);
				
		}
		$this->sendRequestProccess($mwResponse,$method, $endpoint, $params , $accessToken , $eTag , $graphVersion);
		return $mwResponse;
	
	}
	function post($endpoint="me",$params=array(), $accessToken = null, $eTag = null, $graphVersion = null){
		$mwResponse=$this->sendRequest('POST',
				$endpoint,
				$params,
				$accessToken,
				$eTag,
				$graphVersion);
		if(!$mwResponse->isOK()){
			$this->outputError($mwResponse->errors);
		}
		return $mwResponse;
		
	
	}
	
	function get($endpoint="me",$params=array(), $accessToken = null, $eTag = null, $graphVersion = null){
		$mwResponse=$this->sendRequest('GET',
				$endpoint,
				$params,
				$accessToken,
				$eTag,
				$graphVersion);
		if(!$mwResponse->isOK()){
			$this->outputError($mwResponse->errors);
		}
		return $mwResponse;
		
	
	}
	function reportError($e){
		if(!$e){
			return;	
		}
		if(!$this->errors){
			$this->errors=array();	
		}
		$this->errors[]=$e;
		
	}
	
	function outputError($e){
		$this->reportError($e);
		if(!$this->debugMode){
			return;	
		}
		if(!$e){
			return;	
		}
		if(is_array($e)){
			foreach($e as $ee){
				$this->outputError($ee);	
			}
			return;
		}
		if(is_object($e)){
		  echo 'Error: ' . $e->getMessage();
		  return;
		}
		echo $e;
	}

}
?>