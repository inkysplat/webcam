<?php

//start session is void
if (session_id() == '')
{
    session_start();
}

/**
 * Wrapper for managing
 * sessions.
 *
 * Uses a namespace concept
 */
class Session
{

    /**
     * Singleton Object
     *
     * @access private
     * @static
     * @var object
     */
    private static $_instance = null;

    /**
     * The session stack
     *
     * an array of session namespaces.
     *
     * @var array
     * @access private
     */
    private $session = null;

    /**
     * This Sessiond ID
     * @access private
     * @var int
     */
    private $id = null;

    /**
     * Default Constructor...
     * @access public
     * @return void
     */
    public function __construct()
    {
        return;
    }

    /**
     * Magic Getter Method.
     *
     * @magic
     * @access public
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name == 'id')
        {
            return $this->id;
        }
    }

    /**
     * Magic Setter Method.
     *
     * @magic
     * @access public
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        if ($name == 'id')
        {
            $this->id = $value;
        }
    }

    /**
     * Singleton Method.
     *
     * Initalizes some properties of the class.
     *
     * @static
     * @access public
     * @return object
     * @throws Exception
     */
    public static function getInstance()
    {
        //empty session data exception
        if (!is_array($_SESSION))
        {
            throw new Exception(__METHOD__ . " No Session Data");
        }

        $c = __CLASS__;
        //create new object of self
        if (!(self::$_instance instanceof $c))
        {
            self::$_instance = new $c;
        }

        //set the session_id inside the class
        if (self::$_instance->id == null)
        {
            self::$_instance->id = session_id();
        }

        //lazily load in the session data into the class
        if (self::$_instance->session == null)
        {
            self::$_instance->session = $_SESSION;
        }

        //set a default namespace
        if(!isset(self::$_instance->session['default']))
        {
            self::$_instance->session['default'] = array();
        }


        return self::$_instance;
    }

    /**
     * Creates namespace
     *
     * @access public
     * @throws exception
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        if (empty($namespace) || $namespace == '')
        {
            throw new Exception(__METHOD__ . "Cannot create empty namespace!");
        }
        
        if(!isset(self::$_instance->session[$namespace]))
        {
            self::$_instance->session[$namespace] = array();
        }
    }

    /**
     * Store data into the session.
     *
     * @access public
     * @param mixed $value
     * @param string $namespace
     * @return boolean
     * @throws Exception
     */
    public function set($value, $namespace = '')
    {
        //get last used namespace
        if ($namespace == '')
        {
            $namespaces = array_keys(self::$_instance->session);
            $namespace  = end($namespaces);
        }

        //throw exception no namespace provided!
        if ($namespace == '')
        {
            throw new Exception(__METHOD__ . "No namespace provided");
        }

        //if the namespace doesn't exist create it!
        if (!array_key_exists($namespace, self::$_instance->session))
        {
            $this->setNamespace($namespace);
        }

        //iterate over the value append into session
        if (is_array($value))
        {
            foreach ($value as $k => $v)
            {
                self::$_instance->session[$namespace][$k] = $v;
            }
        }
        else
        {
            //just assign single value
            self::$_instance->session[$namespace] = $value;
        }

        //commit to the global PHP session variable
        $this->commit();

        return true;
    }

    /**
     * Delete a value from the session stack.
     *
     * @access public
     * @param string $value
     * @param string $namespace
     * @return boolean
     * @throws Exception
     */
    public function delete($value, $namespace)
    {
        if (!array_key_exists($namespace, self::$_instance->session))
        {
            throw new Exception(__METHOD__ . " No Such Namespace");
        }

        if (array_key_exists($value, self::$_instance->session[$namespace]))
        {
            self::$_instance->session[$namespace][$value] = NULL;
            unset(self::$_instance->session[$namespace][$value]);

            $this->commit();

            return true;
        }
        return false;
    }

    /**
     * Get method - fetch from the stack
     * @access public
     * @param string $namespace
     * @return boolean|mixed
     */
    public function get($namespace)
    {
        if (!array_key_exists($namespace, self::$_instance->session))
        {
            return false;
        }

        return self::$_instance->session[$namespace];
    }

    /**
     * Copy our session data into
     * PHP's session variable
     *
     * @return void
     */
    public function commit()
    {
        $_SESSION = self::$_instance->session;
    }

    /**
     * Completely Kill the Session!
     *
     * @access public
     * @return void
     */
    public function destroy()
    {
        self::$_instance->session = array();

        foreach ($_SESSION as $session => $value)
        {
            $_SESSION[$session] = NULL;
            unset($_SESSION[$session]);
        }

        unset($_SESSION);

        session_unset();
        session_destroy();
    }

    /**
     * Get method for ID.
     * @access public
     * @return int
     */
    public function id()
    {
        return self::$_instance->id;
    }

    /**
     * Debug method returns session data.
     * @access public
     * @return array
     */
    public function debug()
    {
        return self::$_instance->session;
    }

}

?>
