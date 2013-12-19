<?php
	// global variable storage system
	// used for configuration and other globals
	class g {
		private static $vars = [];

		// get a set key
		// return $default if $key is not set
		static function get($key = null, $default = null){
			if(empty($key)){ return self::$vars; }

			if(isset(self::$vars[$key])){
				return self::$vars[$key];
			} else {
				return $default;
			}
		}

		// set a key
		static function set($key, $value = null){
			self::$vars[$key] = $value;
		}
	}
?>