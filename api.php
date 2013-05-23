<?php

ini_set('display_errors','Off');
ini_set('display_startup_errors','Off');

defined('SITE_PATH') OR define('SITE_PATH', realpath(dirname(__FILE__)) . '/system/');

require_once (SITE_PATH . 'bootStrap.php');

if(isset($args)){
	if(!is_array($args)){
		$args = array($args);
	}
}else{
	if($argc > 1){
		$args = array($argv[1]);
	}else{
		$uri = $_SERVER['QUERY_STRING'];
		$args = explode("/", $uri);
	}
}

$config = new Config(CONFIG_PATH.'api.ini');

$calls = array();

foreach($args as $arg){

	$make_call = false;

	if($config->{$arg}){

		if(!file_exists(CACHE_PATH.$config->{$arg}['cache_file'])){
			$make_call = true;
		}
		if(isset($conf['timetolive'])){
			if((time()-filemtime(CACHE_PATH.$config->{$arg}['cache_file'])) > $config->{$arg}['timetolive']){
				$make_call = true;
			}
		}

		if($make_call){
			$file = file_get_contents($config->{$arg}['endpoint']);
			file_put_contents(CACHE_PATH.$config->{$arg}['cache_file'], $file);
		}else{
			$file = file_get_contents(CACHE_PATH.$config->{$arg}['cache_file']);
		}

		$calls[$arg] = $file;
	}
}

if($_SERVER['QUERY_STRING'] && !empty($_SERVER['QUERY_STRING']))
{
	$json = array();
	foreach($calls as $api=>$call){
		$json[$api] = json_decode($call);
	}

	die(json_encode($json));
}

