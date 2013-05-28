<?php

/**
 * A ShortHand Singleton Lazy Loader
 *
 * @global
 * @static $_inst
 * @param string $class
 * @return object
 */
function Util($class, $params = array())
{
    if (!isset($_inst))
    {
        static $_inst = array();
    }

    if (!isset($_inst[$class]))
    {
        $_inst[$class] = $class::getInstance();
    }

    return $_inst[$class];
}

/**
 * A ShortHand Instantiate Lazy Loader
 *
 * @global
 * @static $_inst
 * @param string $class
 * @return object
 */
function App($class, $params = array())
{
    if (!isset($_inst))
    {
        static $_inst = array();
    }

    if (!isset($_inst[$class]))
    {
        $_inst[$class] = new $class();
    }

    return $_inst[$class];
}



