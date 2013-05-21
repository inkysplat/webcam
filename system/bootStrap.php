<?php

if(!defined('DIR_SEP'))
    define('DIR_SEP', DIRECTORY_SEPARATOR);

if(!defined('SITE_PATH'))
    define('SITE_PATH', realpath(dirname(__FILE__)).DIR_SEP);

if(!defined('CONFIG_PATH'))
    define('CONFIG_PATH', realpath(SITE_PATH.'config').DIR_SEP);

if(!defined('CONTROLLER_PATH'))
    define('CONTROLLER_PATH', realpath(SITE_PATH.'controllers').DIR_SEP);    


if(!defined('VIEW_PATH'))
    define('VIEW_PATH', realpath(SITE_PATH.'views').DIR_SEP);    

if(!defined('LAYOUT_PATH'))
    define('LAYOUT_PATH', realpath(VIEW_PATH.'layout').DIR_SEP);

if(!defined('PARTIAL_PATH'))
    define('PARTIAL_PATH',realpath(VIEW_PATH.'partials').DIR_SEP);

if(!defined('PAGE_PATH'))
    define('PAGE_PATH',realpath(VIEW_PATH.'pages').DIR_SEP);

if(!defined('TEMPLATE_PATH'))
    define('TEMPLATE_PATH',realpath(VIEW_PATH.'partials').DIR_SEP);


if(!defined('ASSET_PATH'))
    define('ASSET_PATH',realpath(SITE_PATH.'assets').DIR_SEP);

if(!defined('CSS_PATH'))
    define('CSS_PATH',realpath(ASSET_PATH.'css').DIR_SEP);    

if(!defined('JS_PATH'))
    define('JS_PATH',realpath(ASSET_PATH.'js').DIR_SEP);    


if(!defined('TMP_PATH'))
    define('TMP_PATH',realpath(SITE_PATH.'tmp').DIR_SEP);

if(!defined('CACHE_PATH'))
    define('CACHE_PATH',realpath(TMP_PATH.'cache').DIR_SEP);

if(!defined('LOG_PATH'))
    define('LOG_PATH',realpath(TMP_PATH.'log').DIR_SEP);

if(!defined('UPLOAD_PATH'))
    define('UPLOAD_PATH',realpath(TMP_PATH.'uploads').DIR_SEP);

if(!defined('EMAIL_PATH'))
    define('EMAIL_PATH',realpath(TMP_PATH.'emails').DIR_SEP);



if(!defined('LIBRARY_PATH'))
    define('LIBRARY_PATH',realpath(SITE_PATH.'/cheddar-library/'));


require_once(LIBRARY_PATH.'/3rdParty/cssMinifier.class.php');
require_once(LIBRARY_PATH.'/3rdParty/JSMin.class.php');
require_once(LIBRARY_PATH.'/Config.class.php');
require_once(LIBRARY_PATH.'/Scan.class.php');
require_once(LIBRARY_PATH.'/Mail.class.php');
require_once(LIBRARY_PATH.'/Log.class.php');
require_once(LIBRARY_PATH.'/Template.class.php');

