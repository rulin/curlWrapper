<?php

class curlMultiDriverSingleton {
	protected static $instances = array();
	
	private function __construct() {
	}
	private function __clone() {
	}
	
	public static function getInstance($instanceKey = 0) {
	
		if (isset(self::$instances[$instanceKey])) {
			return(self::$instances[$instanceKey]);
		} else
			return(self::$instances[$instanceKey] = new curlMultiDriver());
	
	}
}