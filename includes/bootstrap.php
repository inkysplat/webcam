<?php
/**
 * Bootstrap Class
 *
 * Just instaniate it and it'll
 * load all the configuration in
 * and begin the dispatch
 */
class Bootstrap
{
    /**
     * Config Object
     * @access private
     * @var object
     */
    private $_config;

    /**
     * Doctrine DB Object
     * @access private
     * @var object
     */
    private $_db;

    /**
     * PDO Object
     * @access private
     * @var object
     */
    private $_pdo;

    /**
     * Session Object
     * @access private
     * @var object
     */
    private $_sess;

    /**
     * Cache Object
     * @access private
     * @var object
     */
    private $_cache;

    /**
     * Request Object
     * @access private
     * @var object
     */
    private $_request;

    /**
     * Keep Configuration here.
     * @access private
     * @var array
     */
    private $_storage = array();

    /**
     * Load in the configuration from
     * file and start the process.
     *
     * @access public
     * @return void
     */
    public function __construct($config_file)
    {
        $_config       = Util('Config');
        $this->_config = $_config->xml($config_file);

        $this->_dbConnect();

        $this->_session();

        $this->_cache();

        $this->_router();
    }

    /**
     * Creates a database connection
     *
     * @access private
     * @return void
     * @throws Exception
     */
    private function _dbConnect()
    {
        $params = $this->_config->get('database', 'array');

        //throw an exception on bad config
        if (!is_array($params) || empty($params))
        {
            throw new Exception(__METHOD__ . '::Failed retrieving database config from file');
        }

        $this->_storage['db_params'] = $params;

        if (defined('DOCTRINE_VER') && $params)
        {
            $this->_dbDoctrine($params);
        }

        if (!defined('DOCTRINE_VER') && $params)
        {
            $this->_dbPdo($params);
        }
    }

    /**
     * If we have Doctrine enabled load in
     * using the configuration from file.
     *
     * @access private
     * @param array $params
     * @return void
     */
    private function _dbDoctrine($params)
    {
        $_doctrine = Util('Doctrine');
        $_doctrine->config($params);
        $this->_db = $_doctrine->connect();
    }

    /**
     * If we don't have Doctrine enabled
     * instead use the standard PDO.
     *
     * @access private
     * @param array $params
     * @return void
     */
    private function _dbPdo($params)
    {
        $_dba       = Util('Dba');
        $_dba->setConfig($params);
        $this->_pdo = $_dba->connect();
    }

    /**
     * Create database session handler
     *
     * @access private
     * @return void
     */
    private function _sessionDB()
    {
        if (!($this->_pdo instanceof PDO))
        {
            $params = $this->_storage['db_params'];
            $_pdo   = $this->_dbPdo($params);
        }

        if (($this->_pdo instanceof PDO))
        {
            $_pdo = $this->_pdo;
        }

        $_sesshandle = new Dbsession($_pdo);

        session_set_save_handler(
                array($_sesshandle, 'open'),
                array($_sesshandle, 'close'),
                array($_sesshandle, 'read'),
                array($_sesshandle, 'write'),
                array($_sesshandle, 'destroy'),
                array($_sesshandle, 'gc'));

        register_shutdown_function('session_write_close');
    }

    /**
     * Create a session.
     *
     * @access private
     * @return void
     */
    private function _session()
    {
        $settings = $this->_getSettings();

        //don't bother if sessions aren't on!
        if(isset($settings['session_active']))
        {
            if($settings['session_active'] != "true")
            {
                return false;
            }
        }

        // How shall we store the session?
        if (isset($settings['session_storage']))
        {
            switch ($settings['session_storage'])
            {
                //mysql database?
                case 'database':
                    $this->_sessionDB();
                    break;
            }
        }

        //Is sessions enabled?
        if (isset($settings['session_active']))
        {
            if ($settings['session_active'] == "true")
            {
                $this->_sess = Util('Session');
                $this->_sess->setNamespace('testing');
            }
        }
    }

    /**
     * Lazy load in the settings...
     *
     * @access private
     * @return array
     */
    private function _getSettings()
    {
        if (!isset($this->_storage['app_settings']))
        {
            $this->_storage['app_settings'] = $this->_config->get('settings', 'array');
        }

        return $this->_storage['app_settings'];
    }

    /**
     * Create and store caching mechanizms
     *
     * @access private
     * @return void
     */
    private function _cache()
    {
        $this->_cache = Util('Cache');
    }

    /**
     * Determines the MVC path
     * @access private
     * @return void
     */
    private function _router()
    {
        $this->_request = Util('Request');

        $path = array(
            'controller'=>'',
            'action'=> '',
            'params'=> array()
        );

        foreach($path as $key=>&$value)
        {
            if(isset($this->_request->params[$key]) && !empty($this->_request->params[$key]))
            {
                $value = $this->_request->params[$key];
            }else{
                $value = 'index';
            }
        }

        $path['params'] = array_slice($this->_request->params,2);

        $this->_storage['paths'] = $path;
    }

    public function dispatch()
    {
        //load in the controller
        if(file_exists(CONTROLLER_PATH.strtolower($this->_storage['paths']['controller']).'.php'))
        {
            require_once(CONTROLLER_PATH.strtolower($this->_storage['paths']['controller']).'.php');

            $controller_name = ucfirst(strtolower($this->_storage['paths']['controller']));
            $controller_name = $controller_name.'Controller';

            $controllerObj = new $controller_name();
            $controllerObj->setSiteParams();

            $action_name = strtolower($this->_storage['paths']['action']);
            $action_name = $action_name.'Action';

            if(method_exists($controllerObj, $action_name))
            {
                call_user_func_array(array($controllerObj,$action_name), $this->_storage['paths']['params']);

                if(isset($controllerObj->viewParams) && !empty($controllerObj->viewParams))
                {
                    $this->_storage['viewParams'] = $controllerObj->viewParams;
                }

                $this->_storage['defaultViewType'] = $controllerObj->defaultViewType;
            }
        }

        $this->_storage['dispatched'] = true;
    }

    public function render()
    {
        if(!isset($this->_storage['dispatched']) || !$this->_storage['dispatched'])
        {
            throw new Exception(__METHOD__."::This process needs to be dispatched first");
        }

        $body_path = strtolower($this->_storage['paths']['controller']);

        if(!is_dir(PAGES_PATH.$body_path))
        {
            throw new Exception(__METHOD__."::Missing the pages directory for controller::".PAGES_PATH.$body_path);
        }

        $page_path = PAGES_PATH.strtolower($this->_storage['paths']['controller']);

        $body_file = strtolower($this->_storage['paths']['action']);

        if(!file_exists($page_path.DIR_SEP.$body_file.'.php'))
        {
            if(!file_exists($page_path.DIR_SEP.'default.php'))
            {
                throw new Exception(__METHOD__."::Missing default action view");
            }else{
                $body_file = 'default';
            }
        }

        $viewParams = array();
        if(isset($this->_storage['viewParams']))
        {
            $viewParams = $this->_storage['viewParams'];    
        }

        if($this->_config->get('site','array'))
        {
            $viewParams = array_merge($viewParams,$this->_config->get('site','array'));
        }

        $view = App('View');
        $view->params = $viewParams;
        $viewObj = $view->partial('pages/'.$body_path.'/'.$body_file);
        $view->add($viewObj);
        if(isset($this->_storage['defaultViewType']))
        {
            $type = $this->_storage['defaultViewType'];
        }else{
            $type = 'html';
        }
        return $view->render($type);

    }

}
//

////var_dump($_request);
//$_cache   = Util('Cache');
////$_cache->setCache('mysql_users',$_db->fetchAll('SELECT * FROM user'));
////print_r($_cache->getCache('mysql_users'));
//$_log     = Util('Log');
////$_log->setLogName('testing');
////$_log->log('hello world');
////$_log->marker();
//$_charset = App('Charset');
////$_charset->setCharset('ISO-8859-1');
////echo $_charset->encode('££££');
////$_sess->set($_cache->getCache('mysql_users'));
////$_sess->commit();
//$_sess = Util('Session');
//$_sess->setNamespace('testing');
//echo "ID:\t".$_sess->id;
//print_r($_sess->debug());
//print_R($_SESSION);