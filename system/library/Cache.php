<?php
/**
 * A cache class, that stores data
 * in a serialized format on the local
 * hard-disk. This is intended for 
 * caching database query results.
 * 
 * The only 2 methods you will need are:
 * setCache()
 * getCache()
 * 
 * @class Cache
 */
class Cache
{

    /**
     * Singleton Object
     * 
     * @access  private
     * @static
     * @var     object
     */
    private static $_instance = null;

    /**
     * A place to store the cache on disk
     * @access  private
     * @var     string
     */
    private $_cache_dir = '';
    
    /**
     * Cache file for this request
     * @access  private
     * @var     string 
     */
    private $_cache_file = 'default';

    /**
     * An object to store things
     * @access  private
     * @var     array
     */
    private $_cache_mem = array();

    /**
     * A default contructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        return;
    }

    /**
     * Disable cloning for this object
     * @access private
     * @return void
     */
    private function __clone()
    {
        return;
    }

    /**
     * Single method, to fetch and create one's self.
     * @access  public
     * @static
     * @return  object
     */
    public static function getInstance()
    {
        $self = __CLASS__;
        if (!(self::$_instance instanceof $self))
        {
            self::$_instance = new $self();
            self::$_instance->_setCacheDir();

            $filename = self::$_instance->_getCacheFilename();            
            $cached = self::$_instance->_readCache($filename);
            if(!empty($cached))
            {
                self::$_instance->_cache_mem = $cached;
            }
        }

        return self::$_instance;
    }

    /**
     * Sets the cache file name for internal use.
     * 
     * @param string $filename
     */
    public function setCacheFilename($filename)
    {
        $this->_cache_file = $filename;
    }

    /**
     * Get's the cache file name internally
     * 
     * @access  private
     * @return  string
     */
    private function _getCacheFilename()
    {
        if(!empty($this->_cache_file))
        {
            return $this->_cache_file;
        }

        return false;
    }    

    /**
     * Figures out and sets the cache directory
     * 
     * @access public
     * @return boolean - directory found or not.
     * @throws Exception - no directory found.
     */
    public function _setCacheDir()
    {

        if (defined('CACHE_PATH'))
        {
            if ($this->_isWriteableDir(CACHE_PATH))
            {
                $this->_cache_dir = CACHE_PATH;

                return true;
            }
        }

        if (array_key_exists('TMP', $_SERVER))
        {
            if ($this->_isWriteableDir($_SERVER['TMP']))
            {
                $this->_cache_dir = $_SERVER['TMP'];

                return true;
            }
        }

        if (array_key_exists('TEMP', $_SERVER))
        {
            if ($this->_isWriteableDir($_SERVER['TEMP']))
            {
                $this->_cache_dir = $_SERVER['TEMP'];

                return true;
            }
        }

        if (ini_get('upload_tmp_dir'))
        {
            $directory = ini_get('upload_tmp_dir');
            if ($this->_isWriteableDir($directory))
            {
                $this->_cache_dir = $directory;

                return true;
            }
        }

        if (empty($this->_cache_dir))
        {
            throw new Exception(__METHOD__ . "::Unable to set Cache Directory");
        }
    }

    /**
     * Is this directory writable by apache?
     * 
     * @access  private
     * @param   string  $directory
     * @return  boolean
     */
    private function _isWriteableDir($directory)
    {
        if (!is_dir($directory))
        {
            return false;
        }

        if (is_writable($directory))
        {
            if (is_readable($directory))
            {
                return true;
            }
        }
        
        $filename = realpath($directory) . DIR_SEP . 'cache-test-' . date('Ymd-His') . '.dat';
        
        touch($filename);
        
        if(file_exists($filename))
        {
            return true;
        }
        
        return false;
    }

    /**
     * Sets the stuff to memory.
     * 
     * 
     * @access  public
     * @param   string  $name
     * @param   mixed   $value
     * @throws  Exception - invalid parameters
     */
    public function setCache($name, $value, $ttl = 0)
    {
        if (empty($name))
        {
            throw new Exception(__METHOD__ . "::Cannot have an empty name pair");
        }

        if (empty($value))
        {
            throw new Exception(__METHOD__ . "::Cannot have an empty value pair");
        }

        if (!isset($this->_cache_mem[$name]))
        {
            $this->_cache_mem[$name] = array();
        }

        $this->_cache_mem[$name]['value'] = $value;
        $this->_cache_mem[$name]['time']  = time();
        if($ttl > 0)
        {
            $this->_cache_mem[$name]['ttl'] = (int)$ttl;
        }
    }

    /**
     * Gets the thing cached in memory....
     * 
     * @access  public
     * @param   string  $name
     * @return  mixed   - false on non-existant
     * @throws  Exception - invalid parameters
     */
    public function getCache($name)
    {
        if (empty($name))
        {
            throw new Exception(__METHOD__ . "::Cannot have an empty name");
        }

        if (isset($this->_cache_mem[$name]))
        {
            if(isset($this->_cache_mem[$name]['ttl'])
                && $this->_cache_mem[$name]['ttl'] > 0)
            {
                if((time()-$this->_cache_mem[$name]['time']) > $this->_cache_mem[$name]['ttl'])
                {
                    return false;
                }
            }

            return $this->_cache_mem[$name]['value'];
        }else{

            $filename = self::$_instance->_getCacheFilename();
            if($cached = self::$_instance->_readCache($filename))
            {
                if(isset($cached[$name]) && $cached[$name])
                {
                    $cached = $cached[$name];
                }

                if($cached['value'])
                {
                    if(isset($cached['ttl']) && $cached['ttl'] > 0)
                    {
                        if(isset($cached['time']))
                        {
                            if((time()-$cached['time']) > $cached['ttl'])
                            {
                                return false;
                            }
                        }
                    }

                    $this->_cache_mem = array_merge($this->_cache_mem,$cached);

                    return $cached['value'];
                }
            }
        }
        return false;
    }

    /**
     * Debug Method for exposing everything in the cache
     * 
     * @access  public
     * @return  string
     */
    public function exposeCache()
    {
        return print_r($this->_cache_mem, true);
    }
    
    /**
     * Destructor which saves the cache to file
     * 
     * @magic
     * @access  public
     * @return  boolean
     */
    public function __destruct()
    {
        $this->writeCache();
    }

    /**
     * Saves the cache to file
     * 
     * @access  public
     * @return  boolean
     */
    public function writeCache()
    {
        if(!empty($this->_cache_dir) && !empty($this->_cache_mem))
        {
            $cached = serialize($this->_cache_mem);
            $filename = $this->_getCacheFilename();

            if(!file_exists($this->_cache_dir.DIR_SEP.$filename.'.cache'))
            {
                touch($this->_cache_dir.DIR_SEP.$filename.'.cache');
                chmod($this->_cache_dir.DIR_SEP.$filename.'.cache',0777);
            }

            if(file_put_contents($this->_cache_dir.DIR_SEP.$filename.'.cache', $cached))
            {
                //empty cache
                $this->_cache_mem = array();
                return true;
            }
        }

        return false;
    }

    public function writeRaw($filename, $raw_data)
    {
        if(empty($raw_data) || $raw_data == '')
        {
            throw new Exception(__METHOD__."::Cannot save empty data");
        }

        if(file_exists($this->_cache_dir.DIR_SEP.$filename))
        {
            unlink($this->_cache_dir.DIR_SEP.$filename);
        }

        if(!file_exists($this->_cache_dir.DIR_SEP.$filename))
        {
            touch($this->_cache_dir.DIR_SEP.$filename);
            chmod($this->_cache_dir.DIR_SEP.$filename, 0777);
        }

        @file_put_contents($this->_cache_dir.DIR_SEP.$filename, $raw_data);
    }


    /**
     * Read the cache file from the disk
     * 
     * @access  private
     * @param   string  $filename
     * @return  boolean|array
     */
    private function _readCache($filename)
    {
        $filename = $this->_cache_dir.DIR_SEP.$filename.'.cache';
        if(file_exists($filename))
        {
            if($file = file_get_contents($filename))
            {
                if(empty($file))
                {
                   return false;
                }

                if($data = unserialize($file))
                {
                    return $data;
                }
            }
        }
        
        return false;
    }
}