<?php
	// direct access protection
	if(!isset($root)){ die('direct access is not allowed'); }

	// used for future direct access protection
	define('ITTYWIKI', true);

	// load toolkit
	require_once($rootCore . DS . 'toolkit.php');

	// load default settings
	require_once($rootCore . DS . 'defaults.php');

	// load user settings if they exist
	if(file_exists($root . DS . 'config.php')){
		require_once($root . DS . 'config.php');
	}


	// show debug statements (or not)
	if(g::get('debug')){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	} else {
		error_reporting(0);
		ini_set('display_errors', 0);
	}


	// set some global properties
	g::set('root',      $root);
	g::set('root.core', $rootCore);
	g::set('root.wiki', $root . g::get('wiki.dir'));

	g::set('root.url',      $rootUrl);
	g::set('root.core.url', $rootCoreUrl);


	// load ittywiki and parsers
	require_once(g::get('root.core') . DS . 'ittywiki.php');
	require_once(g::get('root.core') . DS . 'Parsedown.php');
	require_once(g::get('root.core') . DS . 'ParsedownExtra.php');
	require_once(g::get('root.core') . DS . 'wikiparser.php');


	if(!is_dir(g::get('root.wiki'))){
		die('no wiki content exists.');
	}

	if(!is_dir(g::get('root.core'))){
		die('ittywiki does not appear to be installed properly.');
	}

	// load ittywiki
	global $ittywiki;
	$ittywiki = new ittywiki();
	$ittywiki->load();
?>
