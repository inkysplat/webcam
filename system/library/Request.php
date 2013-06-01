<?php

/**
 * Parse's the browser's request.
 *
 * This class just wraps up the $_SERVER 
 * variable. There is no functionality here,
 * just use the public properties.
 *
 */
class Request
{
    
    /**
     * Singleton property
     * @static
     * @access  private
     * @var object
     */
    private static $_instance = null;
    
    /**
     * Full Domain - https://subomain.hostname.com/
     * @access private
     * @var string
     */
    private $full_domain = '';

    private $request = array();
    
    /**
     * Standard class constructor
     * 
     * @access public
     * @return void
     */
    public function __construct(){}
    
    /**
     * Singleton method
     * 
     * Calls some methods to seed this class
     * 
     * @static
     * @access public
     * @return object
     */
    public static function getInstance()
    {
        $class = __CLASS__;
        
        if(!(self::$_instance instanceof $class))
        {
            self::$_instance = new $class();
            self::$_instance->_setSecure();
            self::$_instance->_setFullDomain();
            self::$_instance->_setSubDomain();
            self::$_instance->_setRequestURI();
            self::$_instance->_setRequestParams();
            self::$_instance->_setAgentLanguage();
            self::$_instance->_setFullURL();
            self::$_instance->_setSelfURL();
        }
        
        return self::$_instance;
    }
    
    /**
     * Set whether this connection is SSL or not.
     * 
     * @access private
     * @return void
     */
    private function _setSecure()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        {
            $this->ssl = true;
            $this->full_domain .= 'https://';
        }else{
            $this->ssl = false;
            $this->full_domain .= 'http://';
        }

        $this->request['ssl'] = $this->ssl;
    }
    
    /**
     * Appends the domain and corporate name to the URL
     * 
     * @access private
     * @return void
     */
    private function _setFullDomain()
    {
        $this->full_domain .= $_SERVER['HTTP_HOST'];
    }
    
    /**
     * Determines the User Agent's language
     * 
     * @access  private
     * @return  void
     */
    private function _setAgentLanguage()
    {
        if($_SERVER['HTTP_ACCEPT_LANGUAGE']){
            $languages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
            foreach($languages as &$language)
            {
                $language = substr($language,0,2);
            }
            $this->request['language'] = array_unique($languages);
        } // defends from no brower user agent i.e external script consuming an API end point  
    }
    
    /**
     * Finds everything after the domain upto the request string
     * 
     * @access  private
     * @return  void
     */
    private function _setRequestURI()
    {
        $request = current(explode('?',$_SERVER['REQUEST_URI']));
        $this->request['uri'] = $request;
    }

    private function _setRequestParams()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (!empty($uri))
        {
            if (substr($uri, 0, 1) == '?')
            $uri = substr($uri, 1);
            if (substr($uri, 0, 1) == '/')
            $uri = substr($uri, 1);
            if (substr($uri, -1) == '/')
            $uri = substr($uri, 0, -1);

            $parts = explode("/", $uri);

            $mvc = array(
                'controller','action'
            );

            $c = 0;
            foreach($parts as $part)
            {
                if(isset($mvc[$c]))
                {
                    $this->request['params'][$mvc[$c]] = $part;
                }else{
                    if($c % 2)
                    {
                        $this->request['params'][$parts[($c-1)]] = $part;    
                    }
                }
                $c++;
            }
        }
    }
    
    /**
     * Creates a full URL from the domain and request params
     * 
     * @access  private
     * @return  void
     */
    private function _setFullURL()
    {
        $this->request['full_url'] = $this->full_domain.$this->request['uri'];
    }
    
    /**
     * Stores just the path of the URL after it's been rewritten
     * 
     * @access private
     * @return void
     */
    private function _setSelfURL()
    {
        $this->request['self_url'] = $this->full_domain.$_SERVER['SCRIPT_NAME'];
    }
    
    /**
     * Finds the sub-domain name
     * 
     * @access private
     * @return void
     */
    private function _setSubDomain()
    {
        if(isset($_SERVER['SERVER_NAME']))
        {
            $parts = explode('.', $_SERVER['SERVER_NAME']);
            if(count($parts) >= 1)
            {
                if(!empty($parts[0]) && strlen($parts[0]) > 2)
                {
                    $this->request['subdomain'] = $parts[0];
                    
                    return true;
                }
            }
        }
    }


    
    /**
     * Magic getter method for proxying
     * the properties of this class
     * 
     * @magic
     * @access  public
     * @param   string  $name
     * @return  mixed
     */
    public function __get($name)
    {
        if(isset($this->request[$name]))
        {
            return $this->request[$name];
        }
        
        return false;
    }

    public function post($name)
    {
        if(isset($_POST[$name]))
        {
            return trim($_POST[$name]);
        }

        return false;
    }
}


?>
