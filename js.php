<?php
ini_set('display_errors','Off');
ini_set('display_startup_errors','Off');

define('SITE_PATH', realpath(dirname(__FILE__)) . '/system/');

require_once (SITE_PATH . 'bootStrap.php');

$uri = $_SERVER['QUERY_STRING'];
$parts = explode("/", $uri);
$cache_file = md5($uri);
$cache_dir = defined('CACHE_PATH')?CACHE_PATH:SITE_PATH.'tmp/';
if (!is_writable($cache_dir))
    $cache_dir = '/tmp/';

$error = array();

if (file_exists($cache_dir . $cache_file . '.cache'))
{
    try
    {
	$minified = file_get_contents($cache_dir . $cache_file . '.cache');
    } catch (exception $e)
    {
	$error[] = "Error Opening Cache File '" . $cache_file . '.cache';
    }
} else
{
    $minified = '';

    foreach ($parts as $p)
    {
	$file_location = '';
	$p = str_replace("#", "/", $p);

	if (file_exists(JS_PATH . $p . '.js'))
	{
	    $file_location = JS_PATH . $p . '.js';
	} else
	{
	    try
	    {
		$needle = $p . '.js';
		$needle_length = strlen($needle);
		$scan = new Scan(JS_PATH);
		$scan->scan();
		while ($file = $scan->next())
		{
		    if (substr($file, -$needle_length) == $needle)
		    {
			$file_location = $file;
		    }
		}
	    } catch (exception $e)
	    {
		$error[] = "Error Finding File '" . $p . ".js'::" . $e->getMessage();
	    }
	}

	if (!empty($file_location))
	{
	    $file = file_get_contents($file_location);
	    $minifier = new JSMin($file);
	    $minified .= $minifier->minify($file);

	} else
	{
	    $error[] = "Error Finding File '" . $p . ".js'";
	}
    }

    if (!empty($error))
    {

	$log = Log::getInstance();
	$log->log($error);
    }

    file_put_contents($cache_dir . $cache_file . '.cache', $minified);
}

ob_start();
header('Content-type: text/javascript');
echo $minified;
die(ob_end_flush());