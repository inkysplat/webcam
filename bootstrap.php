<?php

//base directory paths
define('APP_PATH', realpath(BASE_PATH . 'application') . DIR_SEP);
define('INCLUDE_PATH', realpath(BASE_PATH . 'includes') . DIR_SEP);
define('LIBRARY_PATH', realpath(BASE_PATH . 'library') . DIR_SEP);
define('PUBLIC_PATH', realpath(BASE_PATH . 'public') . DIR_SEP);
define('CACHE_PATH', realpath(BASE_PATH . 'cache') . DIR_SEP);
define('VENDOR_PATH', realpath(BASE_PATH . 'vendor') . DIR_SEP);

//application directory paths
define('CONTROLLER_PATH', realpath(APP_PATH . 'controllers') . DIR_SEP);
define('CONFIG_PATH', realpath(APP_PATH . 'config') . DIR_SEP);
define('MODEL_PATH', realpath(APP_PATH . 'models') . DIR_SEP);
define('VIEW_PATH', realpath(APP_PATH . 'views') . DIR_SEP);

//view specific paths
define('LAYOUTS_PATH', realpath(VIEW_PATH . 'layouts') . DIR_SEP);
define('PARTIALS_PATH', realpath(VIEW_PATH . 'partials') . DIR_SEP);
define('PAGES_PATH', realpath(VIEW_PATH . 'pages') . DIR_SEP);

//the version of doctrine library to use
define('DOCTRINE_VER', '2.3.2');

//set the include paths
$included     = get_include_path();
$add_includes = array(LIBRARY_PATH, MODEL_PATH, VENDOR_PATH, INCLUDE_PATH);

//if we have a doctrine config
if (defined('DOCTRINE_VER') && DOCTRINE_VER != '')
{
    //add doctrine if found
    if (is_dir(VENDOR_PATH . 'DoctrineDBAL-' . DOCTRINE_VER))
    {
        //define the doctrine path
        define('DOCTRINE_PATH', realpath(VENDOR_PATH . 'DoctrineDBAL-' . DOCTRINE_VER) . DIR_SEP);
        //load in the doctrine class loader
        require_once(DOCTRINE_PATH . 'Doctrine' . DIR_SEP . 'Common' . DIR_SEP . 'ClassLoader.php');
        //add it to the include paths
        $add_includes[] = DOCTRINE_PATH;
    }
}

//load the paths into global for the autoloader
$GLOBALS['AUTOLOAD_PATHS'] = $add_includes;

//add them into the PHP include path
$add_includes = implode(PATH_SEPARATOR, $add_includes);
set_include_path($included . PATH_SEPARATOR . $add_includes);

//lets specify our autoload function
spl_autoload_register('__autoload');

/**
 * Autoload Method
 * @global
 * @param string $class - class name
 * @throws exception -  on missing class
 * @return boolean
 */
function __autoload($class)
{
    if (class_exists($class))
    {
        return true;
    }

    $dontload = array('Bootstrap');
    if(in_array($class, $dontload))
    {
        return true;
    }

    //capitalize the class name
    $class = ucfirst($class);
    $class = str_replace(array('\\', '/'), DIR_SEP, $class);
    $file = DIR_SEP . $class . '.php';

    foreach ($GLOBALS['AUTOLOAD_PATHS'] as $path)
    {
        $path = $path . $file;
        if (file_exists($path))
        {
            include_once($path);
            //we found it so return true
            return true;
        }
    }

    //throw exception on missing class
    throw new Exception(__FUNCTION__ . '::Cannot find class \'' . $class . '\'');
}

//include some global functions
require_once(INCLUDE_PATH . 'functions.php');

//continue loading this application's specific's
require_once(INCLUDE_PATH . 'bootstrap.php');

//BANGARANG!!
$bs = new Bootstrap('default');
$bs->dispatch();
echo $bs->render();
