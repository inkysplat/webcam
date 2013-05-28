<?php

use Doctrine\Common\ClassLoader;

/**
 * A Wrapper Class for Doctrine (Singleton)
 *
 */
class Doctrine
{

    /**
     * Singleton Variable
     *
     * @static
     * @access private
     * @var object
     */
    private static $_instance;

    /**
     * Doctrine Connection
     *
     * @access private
     * @var object
     */
    private $_conn;

    /**
     * Doctrine Config
     *
     * @access private
     * @var object
     */
    private $_config;

    /**
     * Default Connection Configuration
     *
     * @access private
     * @var array
     */
    private $_defaultParams = array(
        'dbname'   => 'information_schema',
        'user'     => 'root',
        'password' => '',
        'host'     => 'localhost',
        'driver'   => 'pdo_mysql',
    );

    /**
     * Doctrine ConnectionParam
     *
     * @access private
     * @var array
     */
    private $_connectionParams;

    /**
     * Default Constructor
     */
    public function __construct()
    {
        return;
    }

    /**
     * Singleton Method creates instance of self
     *
     * @static
     * @access public
     * @return object
     */
    public static function getInstance()
    {
        $self = __CLASS__;
        if (!(self::$_instance instanceof $self))
        {
            self::$_instance = new $self();
            self::$_instance->_loader();
        }

        return self::$_instance;
    }

    /**
     * Loads the Doctrine ClassLoader
     *
     * @access public
     * @return void
     *
     */
    public function _loader()
    {
        $classLoader = new ClassLoader('Doctrine', DOCTRINE_PATH);
        $classLoader->register();
    }

    /**
     * Loads in Configration and creates Connection
     *
     * @access public
     * @param array $params
     * @return object
     */
    public function config($params = array())
    {
        $this->_config = new \Doctrine\DBAL\Configuration();

        $connectionParams = $this->_defaultParams;

        if (!empty($params))
        {
            foreach($params as &$param)
            {
                if(is_array($param) && empty($param)){
                    $param = '';
                }
            }

            $connectionParams = array_merge($connectionParams, $params);
        }

        $this->_connectionParams = $connectionParams;
    }

    /**
     * Lazyloader for Doctrine Connection
     *
     * @access public
     * @return object
     */
    public function connect()
    {
        if (!($this->_conn instanceof DriverManager))
        {
            $this->_conn = \Doctrine\DBAL\DriverManager::getConnection(
                            $this->_connectionParams, $this->_config);
        }

        return $this->_conn;
    }

}