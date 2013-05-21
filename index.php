<?php

define('SITE_PATH', realpath(dirname(__FILE__)) . '/system/');
require_once(SITE_PATH.'bootStrap.php');

$uri = $_SERVER['REQUEST_URI'];

if (isset($_GET['page']))
{
    $page = $_GET['page'];
} else
if (!empty($uri))
{
    if (substr($uri, 0, 1) == '?')
	$uri = substr($uri, 1);
    if (substr($uri, 0, 1) == '/')
	$uri = substr($uri, 1);
    if (substr($uri, -1) == '/')
	$uri = substr($uri, 0, -1);

    $parts = explode("/", $uri);
    $page = $parts[0];
}

if (empty($page))
{
    $page = 'home';
}

if (!file_exists(PAGE_PATH . $page . '.php'))
{
    $page = '404';
    header("HTTP/1.0 404 Not Found");
}

if(file_exists(CONTROLLER_PATH.$page.'.php')){
	include_once(CONTROLLER_PATH.$page.'.php');
}

ob_start();
header('Content-type: text/html;charset=utf8');
include_once (LAYOUT_PATH.'header.inc.php');
include_once (PAGE_PATH.$page.'.php');
include_once (LAYOUT_PATH.'footer.inc.php');
ob_end_flush();
