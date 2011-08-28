<?php 

abstract class curlDriverAbstract {
	
	protected $curlHandle;
	
	public function getHandle() {
		return($this->curlHandle);
	}
	
	public function curlErrnoError() {
		return ($this->curlErrno().' : '.$this->curlError());
	}
	
	protected function checkHandle(&$curlHandle) {
		if (is_resource($curlHandle) and
			get_resource_type($curlHandle) == 'curl')
			return(true);
		else
			return(false);
	}

} 