<?php
	$start = microtime();
	//echo "<pre>";

	//error reporting
	ini_set('display_errors','Off');
	error_reporting(-1);

	//directory separator shorthand
	define('DIR_SEP',DIRECTORY_SEPARATOR);

	//get base path
	$public_dir = realpath(dirname(__FILE__));
	define('BASE_PATH',$public_dir.DIR_SEP.'..'.DIR_SEP.'system'.DIR_SEP);
	define('PUBLIC_PATH', $public_dir.DIR_SEP);

	//kick everything off...
	require_once(BASE_PATH.'bootstrap.php');

    $end = microtime();
    //echo "\n\n".($end-$start);


