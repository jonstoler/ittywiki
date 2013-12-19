<?php
	
	// simple shortcut
	define(DS, DIRECTORY_SEPARATOR);

	// document root (wherever this file is located)
	$root = dirname(__FILE__);
	
	// attempt to detect subdirectory
	$subdir = trim(dirname($_SERVER['SCRIPT_NAME']), DS);

	// detect http or https
	$scheme = is_null($_SERVER['HTTPS']) ? 'http://' : 'https://';

	// main url
	$rootUrl = $scheme . $_SERVER['HTTP_HOST'] . '/' . $subdir;

	// core directory (system files)
	$rootCore = $root . DS . 'core';
	$rootCoreUrl = $rootUrl . '/core';

	// try to load the core, fail gracefully
	if(!file_exists($rootCore . DS . 'system.php')){
		die('ittywiki core could not be loaded.');
	}

	require_once($rootCore . DS . 'system.php');
?>