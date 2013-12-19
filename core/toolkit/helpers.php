<?php

	// cleanly dump variables (especially arrays/objects)
	function dump($var = null){
		if(is_null($var)){ dump('NULL'); }
		if($var === false){ dump('false'); }

		if(is_object($var) && method_exists($var, '__toDump')){
			$var = $var->__toDump();
		}

		$out  = '<pre>';
		$out .= htmlspecialchars(print_r($var, true));
		$out .= '</pre>';

		echo $out;
	}

	// returns $p cast as path (if it isn't already)
	function path(&$p){
		if(!is_a($p, 'path')){
			$p = new path($p);
		}

		return $p;
	}

	// returns a wiki-friendly name for a file or directory
	function wikiname($name){
		return str_replace(' ', '-', $name);
	}

	// create a linked stylesheet based on filename
	function css($filename){
		$path = g::get('root.core.url') . DS . 'css' . DS . $filename . '.css';
		return '<link rel="stylesheet" type="text/css" href="' . $path . '" />';
	}

	// create a linked javascript based on filename
	function js($filename){
		$path = g::get('root.core.url') . DS . 'js' . DS . $filename . '.js';
		return '<script type="text/javascript" src="' . $path . '"></script>';
	}

?>