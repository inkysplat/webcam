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
    private $_cache_file = '';
    
    /**
     * Cache file prefix
     * @access private
     * @var     string
     */
    private $_cache_prefix = '';

    /**
     * An object to store things
     * @access  private
     * @var     array
     */
    private $_cache_mem = array();
    
    /**
     * Loaded cache files
     * 
     * @access private
     * @var array 
     */
    private $_loaded_cache_files = array();

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
        }
        
        return self::$_instance;
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
     * Sets the prefix for the cache filename and refreshes the cache list
     * 
     * @param string $prefix
     * @return void
     */
    public function setCachePrefix($prefix = '')
    {
        if($prefix != '')
        {
            $this->_cache_prefix = $prefix;
            
            $this->_loadCacheFile();
            
        }
    }
    
    /**
     * Get the filename and load it into this classes' memory
     * 
     * @access private
     * @return void
     */
    private function _loadCacheFile()
    {
        //get the new file name with prefix
        $filename = $this->_cacheFilename();      
        
        if(in_array($filename, $this->_loaded_cache_files))
        {
            return true;
        }
        
        if(!empty($filename))
        {   
            //look for the file and load it into memory
            $cached = $this->_getCacheFile($filename);
            if(!empty($cached))
            {
                //see how many times we load a cache file into memory!
                $GLOBALS['CACHE_COUNT']++;
                
                //we've loaded this one in alreay!
                $this->_loaded_cache_files[] = $filename;
                
                //merge with existing translations
                self::$_instance->_cache_mem = 
                        array_merge(self::$_instance->_cache_mem,$cached);
            }
        }
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
    public function setCache($name, $value)
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
        
        //we have no translations but we have a cache_filename!
        if(empty($this->_cache_mem) && empty($this->_cache_file))
        {
           $this->_loadCacheFile();
        }

        if (isset($this->_cache_mem[$name]))
        {
            return $this->_cache_mem[$name]['value'];
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
        if(!empty($this->_cache_dir)
                && !empty($this->_cache_mem))
        {
            $cached = serialize($this->_cache_mem);
            $filename = $this->_cacheFilename();
            
            return $this->_saveCacheFile($filename, $cached);
        }
        
        return false;
    }
    
    /**
     * Create a filename based on the REQUEST parameters
     * 
     * @access  private
     * @return  string
     */
    private function _cacheFilename()
    {   
        if(!empty($this->_cache_file))
        {
            if(!empty($this->_cache_prefix))
            {
                return $this->_cache_prefix.'_'.$this->_cache_file;
            }
            
            return $this->_cache_file;
        }
        
        $query = false;
        
        if(!$query && isset($_SERVER['REQUEST_URI']))
        {
            $query = $_SERVER['REQUEST_URI'];
        }
        
        if(!$query && isset($_SERVER['QUERY_STRING']))
        {
            $query = $_SERVER['QUERY_STRING'];
        }
        
        if($query && !empty($query))
        {
            $this->_cache_file = md5($query);
            
            if(!empty($this->_cache_prefix))
            {
                return $this->_cache_prefix.'_'.$this->_cache_file;
            }
            
            return $this->_cache_file;
        }
        
        return false;
    }
    
    /**
     * Write the cache to file on disk
     * 
     * @access  private
     * @param   string  $filename
     * @param   string  $data
     * @return  boolean
     */
    private function _saveCacheFile($filename, $data = '')
    {
        if(file_put_contents($this->_cache_dir.DIR_SEP.$filename.'.cache', $data))
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * Read the cache file from the disk
     * 
     * @access  private
     * @param   string  $filename
     * @return  boolean|array
     */
    private function _getCacheFile($filename)
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