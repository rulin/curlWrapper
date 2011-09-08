<?php

abstract class curlMultiDriverAbstract {
	
	protected function checkCurlHandle(&$curlHandle) {
		if (is_resource($curlHandle) and
			get_resource_type($curlHandle) == 'curl')
			return(true);
		else
			return(false);
	}
	
	protected function checkMultiHandle(&$curlHandle) {
		if (is_resource($curlHandle) and
		get_resource_type($curlHandle) == 'curl')
		return(true);
		else
		return(false);
	}	
} 