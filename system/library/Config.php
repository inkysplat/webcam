<?php
/**
 * Fetches and parses config.
 * 
 * Will accept xml and ini config files.
 * Relies on a CONFIG_PATH constant.
 *
 */
class Config
{
    /**
     * Singleton Object
     * 
     * @static  
     * @access  private
     * @var     object
     */
    private static $_instance = null;
    
    /**
     * Cache of settings
     * 
     * @access  private
     * @var     array
     */
    private $_cache = array();
    
    /**
     * Contains last used configuration file
     * 
     * @access  private
     * @var     string 
     */
    private $_thisConfig = '';

    /**
     * Default empty contructor
     * 
     * @access  public
     * @return  void
     */
    public function __construct()
    {
		return;
    }

    /**
     * Singleton Method - creates an object of SELF 
     * 
     * @access  public
     * @static  
     * @return  object
     */
    public static function getInstance()
    {
		$self = __CLASS__;

		if (!(self::$_instance instanceof $self))
		{
		    self::$_instance = new $self;
		}

		return self::$_instance;
    }

    private function _getPaths($path)
    {
    	$paths = array();

		if (defined('CONFIG_PATH'))
		{
		    $paths[] = CONFIG_PATH . DIR_SEP . $path;
		}

		if (defined('APP_PATH'))
		{
		    $paths[] = APP_PATH . DIR_SEP . 'config' . DIR_SEP . $path;
		}

		return $paths;
    }

    /**
     * Parses an XML file 
     * 
     * @access  public
     * @param   string              $path
     * @return  object              Config Object
     * @uses    SimpleXMLElement    Parses using SimpleXML
     * @throws  Exception
     */
    public function xml($path)
    {
		if (substr($path, -4) !== '.xml')
		{
		    $path.= '.xml';
		}

		$paths = $this->_getPaths($path);
	        
		$this->_thisConfig = '';

		foreach ($paths as $path)
		{
		    $key = md5($path);
		    if (!isset($this->_cache[$key]))
		    {
				if ($xml = $this->_getXML($path))
				{
				    if ($xml instanceof SimpleXMLElement)
				    {
					$this->_cache[$key] = $xml;
					$this->_thisConfig = $key;
				    }
				}
		    } else
		    {
				$this->_thisConfig[$key];
		    }
		}

		if (!isset($xml))
		{
		    throw new Exception(__METHOD__ . "::Failed loading XML file");
		}

		return $this;
    }

    /**
     * Loads XML file and returns SimpleXMLElement
     * 
     * @access  private 
     * @param   string  $path
     * @return  SimpleXMLElement|boolean
     */
    private function _getXML($path)
    {
		if (file_exists($path))
		{
		    $file = file_get_contents($path);

		    try
		    {
			$xml = new SimpleXMLElement($file);

			return $xml;
		    } catch (Exception $e)
		    {

		    }
		}

		return false;
    }

    /**
     * Parses the XML file for a configuration value and turns the value.
     * 
     * Can cast the value to a specified type.
     * 
     * @access  public
     * @param   string  $node
     * @param   string  $return
     * @return  mixed
     * @throws  Exception
     */
    public function get($node, $return = 'object')
    {
		if (empty($this->_thisConfig))
		{
		    throw new Exception(__METHOD__ . "::No configuration found::".$node);
		}

		if (!isset($this->_cache[$this->_thisConfig]))
		{
		    throw new Exception(__METHOD__ . "::Missing configuration");
		}

		$config = $this->_cache[$this->_thisConfig];

		if ($config instanceof SimpleXMLElement)
		{
		    $element = $this->_processXML($config, $node);

		    if ($element)
		    {
			return $this->_return($return, $element);
		    }
		}

		if(is_array($config))
		{
			if (array_key_exists($node, $this->_cache[$this->_thisConfig]))
			{
			    return $this->_cache[$this->_thisConfig][$node];
			}
		}
    }

    /**
     * Casts the value to a data type.
     * 
     * (SimpleXML returns everything as a SimpleXML object by default)
     * 
     * @access  private
     * @param   string  $return
     * @param   string  $result
     * @return  string|boolean
     */
    private function _return($return, $result)
    {
		switch ($return)
		{
		    case "string":
				$string = '';
				foreach ($result as $el)
				{
				    $string.= "\n" . (string) $el;
				}

				return $string;
			break;
		    case "object":
				if (is_object($result))
				{
				    return $result;
				}
				if (is_array($result))
				{
				    return (object) $result;
				}
			break;
		    case "array":
				if (is_array($result))
				{
				    return $result;
				}
				if (is_object($result))
				{
		            //hacky.
				    return json_decode(json_encode($result), true);
				}
			break;
		    default:
				return $result;
			break;
		}

		return false;
    }

    /**
     * Searches the element's attributes and returns the element
     * 
     * (so we can find by ID attribute in the XML)
     * 
     * @access  private
     * @param   SimpleXMLElement    $config
     * @param   string              $node
     * @return  mixed
     * @throws  Exception
     */
    private function _processXML($config, $node)
    {
		if (!($config instanceof SimpleXMLElement))
		{
		    throw new Exception(__METHOD__ . "::Expecting SimpleXMLElement");
		}

		foreach ($config as $conf)
		{
		    foreach ($conf->attributes() as $attr)
		    {
			if (strtolower((string)$attr) == strtolower($node))
			{
			    return $conf;
			}
		    }
		}

		return false;
    }


	public function ini($configFile = 'config.ini')
    {
    	if(substr($configFile,-4) != '.ini')
    	{
    		$configFile .= '.ini';
    	}

    	$paths = $this->_getPaths($configFile);

		$this->_thisConfig = '';

		$ini = false;

		foreach ($paths as $path)
		{
			if(file_exists($path))
			{
				$key = md5($path);
				$this->_thisConfig = $key;

				if(!isset($this->_cache[$key]))
				{
					if ($ini = parse_ini_file($path, true))
					{
						$this->_cache[$key] = array();
					    foreach ($ini as $k => $c)
					    {
							if (is_array($c))
							{
							    $this->_cache[$key][$k] = (array) $c;
							}

							if (is_string($c))
							{
							    $this->_cache[$key][$k] = (string) $c;
							}

							if (is_numeric($c))
							{
							    $this->_cache[$key][$k] = (int) $c;
							}
					    }
					}
				}else{
					$ini = $this->_cache[$key];
				}
			}
		}

		if (!isset($ini) || !$ini)
		{
		    throw new exception(__METHOD__ . "::missing configuration file");
		}


    }
}

?>
