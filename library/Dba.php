<?php
/**
 * A Simple PDO Wrapper
 *
 * Just creates and returns
 * a PDO instance.
 */
class Dba
{
    /**
     * Singleton Object
     *
     * @access private
     * @static
     * @var object
     */
    private static $_instance;

    /**
     * Array of connection params
     *
     * @access private
     * @var array
     */
    private $config = array();

    /**
     * List of required connection
     * parameters. Used to convert
     * the array keys from what
     * doctrine expects to this
     * class's expectation.
     *
     * @access private
     * @var array
     */
    private $default_fields = array(
        'database' => 'dbname',
        'username' => 'user',
        'password' => 'password',
        'hostname' => 'host'
    );

    /**
     * Default Constructor...
     * @return void
     */
    private function __construct()
    {
        return ;
    }

    /**
     * Singleton Method.
     *
     * @static
     * @access public
     * @return object
     */
    public static function getInstance()
    {
        $class = __CLASS__;
        if (!(self::$_instance instanceof $class))
        {
            self::$_instance = new $class;
        }

        return self::$_instance;
    }

    /**
     * Creates PDO Object
     *
     * @access public
     * @return \PDO
     */
    public function connect()
    {
        $dsn = 'mysql:host=' . $this->config['hostname'] . ';';
        $dsn.= 'dbname=' . $this->config['database'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $options  = array();

        if (isset($this->config['charset']))
        {
            $options[] = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $this->config['charset']
            );
        }

        $dbh = new PDO($dsn, $username, $password, $options);

        return $dbh;
    }

    /**
     * Takes an Array/stdClass of
     * Configuration parameters
     *
     * @param mixed $config
     * @return boolean
     * @throws Exception
     */
    public function setConfig($config)
    {
        if (empty($config))
        {
            throw new Exception(__METHOD__ . "Missing configuration");
        }

        if (!is_array($config)&& !is_object($config))
        {
            throw new Exception(__METHOD__ . "Expecting array or object");
        }

        foreach ($config as $key => $value)
        {
            if ($this_key = array_search($key, $this->default_fields))
            {
                if(is_array($value) && empty($value)){
                    $value = '';
                }
                $this->config[$this_key] = $value;
            }
        }

        if(count($this->config) !== count($this->default_fields))
        {
            throw new Exception(__METHOD__."::Missing fields");
        }

        return true;
    }

}

