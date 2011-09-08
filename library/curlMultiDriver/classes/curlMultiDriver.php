<?php

class curlMultiDriver extends curlMultiDriverAbstract {
	
	protected $curlMultiHandle;
	protected $curlHandles = array();
	
	public function __construct() {
		$this->curlMultiInit();
	}
	
	public function curlMultiInit() {
		$this->curlMultiHandle = curl_multi_init();
	
		if ($this->checkMultiHandle($this->curlMultiHandle))
			return($this->curlMultiHandle);
		else
			throw new Exception('Unable to create a curl multi resource'); // MESSAGE_RU: Не удается создать curl multi ресурс
	}
		
	protected function __distruct () {
		if ($this->checkMultiHandle($this->curlMultiHandle)) {
			if (count($this->curlHandles))
				foreach ($this->curlHandles as $curlHandle)
					$this->curlMultiRemoveHandle($curlHandle);
			$this->curlMultiClose();
		}
	}
		
	public function curlMultiAddHandle(&$curlHandle) {
		if ($this->checkCurlHandle($curlHandle)) {		
			$this->curlHandles[] = $curlHandle;
			return(curl_multi_add_handle($this->curlMultiHandle, $curlHandle));
		} else
			throw new Exception('Resource is not a curl resource'); // MESSAGE_RU: Ресурс не является curl ресурсом	
	} 
	
	public function curlMultiRemoveHandle(&$curlHandle) {		
		return(curl_multi_remove_handle($this->curlMultiHandle, $curlHandle));
	}
	
	public function curlMultiExec(&$stillRunning) {
		return(curl_multi_exec($this->curlMultiHandle, $stillRunning));
	}
	
	public function curlMultiGetContent(&$curlHandle) {
		return(curl_multi_getcontent($curlHandle));
	}
	
	public function curlMultiSelect() {
		return(curl_multi_select($this->curlMultiHandle));
	}
	
	public function curlMultiInfoRead() {
		return(curl_multi_info_read($this->curlMultiHandle));
	}
			
	public function curlMultiClose() {
		if ($this->checkMultiHandle($this->curlMultiHandle))
			$this->curl_multi_close($this->curlMultiHandle);		
	}
}