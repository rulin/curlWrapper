<?php 

class curlDriver extends curlDriverAbstract {
	
	public function __construct() {
		$this->curlInit();		
	}
		
	public function curlInit() {
		$this->curlHandle = curl_init();
	
		if ($this->checkHandle($this->curlHandle))
			return($this->curlHandle);
		else
			throw new Exception('Unable to create a curl resource: '.$this->curlErrnoError());
	}
	
	public function __distruct () {
		if ($this->checkHandle($this->curlHandle))
			$this->curlClose();
	}
	
	public function curlClose() {
		return(curl_close($this->curlHandle));	
	}
	
	public function curlSetOption($optionName, $optionValue) {
		return(curl_setopt($this->curlHandle, $optionName, $optionValue));
	}
	
	public function curlExec() {
		return(curl_exec($this->curlHandle));
	}
	
	public function curlErrno() {
		return(curl_errno($this->curlHandle));
	}
	
	public function curlError() {
		return(curl_error($this->curlHandle));
	}	
}