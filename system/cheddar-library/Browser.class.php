<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Browser
 *
 * @author adamnicholls
 */
class Browser
{
    
    /**
     * Singleton property
     * @static
     * @access  private
     * @var object
     */
    private static $_instance = null;
    
    /**
     * Full Domain - https://corporate.hostname.com/
     * @access private
     * @var string
     */
    private $full_domain = '';
    
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
            self::$_instance->_setCorporateName();
            self::$_instance->_setRequestURI();
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
        if($_SERVER['HTTPS'] == 'on')
        {
            $this->ssl = true;
            $this->full_domain .= 'https://';
        }else{
            $this->ssl = false;
            $this->full_domain .= 'http://';
        }
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
        $languages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach($languages as &$language)
        {
            $language = substr($language,0,2);
        }
        $this->language = array_unique($languages);
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
        $this->params = $request;
    }
    
    /**
     * Creates a full URL from the domain and request params
     * 
     * @access  private
     * @return  void
     */
    private function _setFullURL()
    {
        $this->full_url = $this->full_domain.$this->params;
    }
    
    /**
     * Stores just the path of the URL after it's been rewritten
     * 
     * @access private
     * @return void
     */
    private function _setSelfURL()
    {
        $this->self_url = $this->full_domain.$_SERVER['SCRIPT_NAME'];
    }
    
    /**
     * Finds the corporate name
     * 
     * @access private
     * @return void
     */
    private function _setCorporateName()
    {
    	if(isset($_SERVER['SERVER_NAME']))
		{
			$parts = explode('.', $_SERVER['SERVER_NAME']);
			if(count($parts) >= 1)
			{
				if(!empty($parts[0]) && strlen($parts[0]) > 2)
				{
					$this->corporate = $parts[0];
					
					return true;
				}
			}
		}
        
		throw new Exception(__METHOD__."::Something freaky going on with SERVER variable::".$_SERVER['SERVER_NAME']);
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
        if(isset($this->$name))
        {
            return $this->$name;
        }
        
        return false;
    }
}


?>
