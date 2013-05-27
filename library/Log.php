<?php

/**
 * A Simple Logger Class
 * 
 * Uses file pointers and writes out
 * to a timestamped file.
 */
class Log
{

    /**
     * @var $log_dir
     */
    private $log_dir = null;

    /**
     * @access private
     * @static
     * @var Log     instance
     */
    private static $_instance;

    /**
     * @access private
     * @static
     * @var resource
     */
    private static $_pointer = NULL;

    /**
     * @access private
     * @static
     * @var string
     */
    private static $filename = 'errors';

    /**
     * @access private
     * @access private
     * @return null
     */
    private function __construct()
    {
	   return;
    }

    /**
     * @access public
     * @return Log    instance
     */
    public static function getInstance()
    {
    	$class = __CLASS__;
    	if (!(self::$_instance instanceof $class))
    	{
    	    self::$_instance = new $class;
    	}

    	self::$_instance->setLogPath();

    	return self::$_instance;
    }

    public function setLogPath()
    {
    	if($this->log_dir == null)
    	{
    	    if (defined('LOG_PATH'))
    	    {
    		  $this->log_dir = LOG_PATH;
    	    } else
    	    {
    		  $this->log_dir = BASE_PATH . '/log/';
    	    }
    	}
    }

    public function __destruct()
    {
    	if (is_resource(self::$_pointer))
    	{
    	    fclose(self::$_pointer);
    	}
    }

    /**
     * @access public
     * @param string $name
     * @return null
     */
    public function setLogName($filename = '')
    {
    	if (!file_exists($this->log_dir . $filename . '.log'))
    	{
    	    touch($this->log_dir . $filename . '.log');
    	    chmod($this->log_dir . $filename . '.log',0777);
    	}

    	self::$filename = $filename;
    }

    /**
     * @access public
     * @param mixed     $error
     * @throws exception
     */
    public function log($error)
    {
    	if (empty($error))
    	{
    	    throw new exception(__METHOD__ . "::Cannot Write Empty Error Message");
    	}

    	if (empty(self::$filename))
    	{
    	    throw new exception(__METHOD__ . "::Missing Filename");
    	}

    	if (!is_resource(self::$_pointer))
    	{
    	    self::$_pointer = fopen($this->log_dir . self::$filename . '.log', 'a+');
    	}

    	$msg = '';

    	$separator = "\n" . date('Ymd-His') . "::";

    	if (is_array($error))
    	{

    	    foreach ($error as $key => $value)
    	    {
    		if (!is_numeric($key))
    		{
    		    $error[$key] = "[" . $key . "] > '" . trim($value) . "'";
    		}
    	    }
    	    $msg = $separator . implode($separator, $error);
    	}

    	if (is_string($error))
    	{
    	    $msg = $separator . $error;
    	}

    	fwrite(self::$_pointer, $msg);
    }

    /**
     * Marks the log with a break point
     *
     * @access public
     * @return void
     */
    public function marker()
    {
	   $this->log(str_repeat('-', 25));
    }

}

?>
