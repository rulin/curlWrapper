<?php 

class curlWrapper {
	
	protected $curlHandle;
	protected $curlMultiHandle;
	
	protected $cookiePath;
	protected $userAgent;
	
	public function __construct($curlOptions) {
		$this->optionsProof($curlOptions);
		$this->init();
		$this->setDefaultOptions();
	}	
	
	protected function init() {
		$this->curlHandle = new curlDriver();
		$this->curlMultiHandle = curlMultiDriverSingleton::getInstance();		
		$this->curlMultiHandle->curlMultiAddHandle($this->curlHandle->getHandle());
	}
		
	public function request($url, $type='get', $options=false) {
		
		$this->curlHandle->curlSetOption(CURLOPT_URL, $url);
		switch ($type) {
			case 'get':
				$this->curlHandle->curlSetOption(CURLOPT_HTTPGET, true);
				
				break;
			case 'post':
				$this->curlHandle->curlSetOption(CURLOPT_POST, true);
				if (is_array($options) && isset($options['postData']) && is_array($options['postData']))				
					$this->curlHandle->curlSetOption(CURLOPT_POSTFIELDS, $options['postData']);
				
				break;
		}		
	}
	
	public function getContent() {
		
		$active = null;
		//execute the handles
		do {
			$mrc = $this->curlMultiHandle->curlMultiExec($active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		
		while ($active && $mrc == CURLM_OK) {
			if ($this->curlMultiHandle->curlMultiSelect() != -1) {
				do {
					$mrc = $this->curlMultiHandle->curlMultiExec($active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		
		return($this->curlMultiHandle->curlMultiGetContent($this->curlHandle->getHandle()));	
	}
	
	protected function optionsProof(&$curlOptions) {
		
		//Cookie path or Temp Dir for cookie
		if (isset($curlOptions['cookiePath'])) {
			$this->cookiePath = $curlOptions['cookiePath'];
		} elseif (isset($curlOptions['tmpDir'])) {
			$this->cookiePath = tempnam($curlOptions['tmpDir'],__CLASS__.'Cookie.');
		}
		
		//User Agent
		if (isset($curlOptions['userAgent'])) {
			$this->userAgent = $curlOptions['userAgent'];
		}		
	} 
		
	protected function setDefaultOptions() {		
		
		$this->curlHandle->curlSetOption(CURLOPT_RETURNTRANSFER, true);
		$this->curlHandle->curlSetOption(CURLOPT_HEADER, false);
		$this->curlHandle->curlSetOption(URLOPT_FOLLOWLOCATION, true);
		$this->curlHandle->curlSetOption(CURLOPT_VERBOSE, false);
		$this->curlHandle->curlSetOption(CURLOPT_MAXREDIRS, 20);
		$this->curlHandle->curlSetOption(CURLOPT_COOKIESESSION, true);
		$this->curlHandle->curlSetOption(CURLOPT_AUTOREFERER, true);
		$this->curlHandle->curlSetOption(CURLOPT_CONNECTTIMEOUT, 120);
		$this->curlHandle->curlSetOption(CURLOPT_TIMEOUT, 120);
		$this->curlHandle->curlSetOption(CURLOPT_SSL_VERIFYHOST, 0);
		$this->curlHandle->curlSetOption(CURLOPT_SSL_VERIFYPEER, false);
		
		if ($this->userAgent != NULL) {
			$this->curlHandle->curlSetOption(CURLOPT_USERAGENT, $this->userAgent);		
		}
		
		if ($this->cookiePath != NULL) {
			$this->curlHandle->curlSetOption(CURLOPT_COOKIEJAR, $this->cookiePath);
			$this->curlHandle->curlSetOption(CURLOPT_COOKIEFILE, $this->cookiePath);			
		}
	}
}