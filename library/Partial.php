<?php
/**
 * A Partial/View Wrapper
 *
 * Set the partial required using the
 * constructor and then pass any params
 * as properties. 
 *
 * When ready call render() and it'll give
 * you back the output butter of the template.
 *
 */
class Partial
{

    /**
     * Description for const
     */
    private $template_dir = null;

    /**
     * Sets the email name
     *
     * @access private
     * @var string
     */
    private $template_name = '';

    /**
     * Contains parameters to send
     * to the template
     *
     * @access private
     * @var array
     */
    private $params = array();

    /**
     * Public Contructor of Template class
     *
     * @access public
     * @throws exception
     */
    public function __construct()
    {
    	if($this->template_dir == null)
    	{
    	    if(defined('VIEW_PATH'))
    	    {
    	        $this->template_dir = VIEW_PATH;
    	    }
    	    else
    	    {
    			$this->template_dir = BASE_PATH.'views/';
    	    }
    	}

    	if (!is_dir($this->template_dir))
    	{
    	    throw new exception(__METHOD__ . "::Missing Template Directory");
    	}
    }

    /**
     * Sets template parameters
     *
     * @magic 'set'
     * @access public
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
	   $this->params[$name] = $value;
    }

    /**
     * Sets the template name
     *
     * @access public
     * @param string    $template_name
     * @throws exception
     */
    public function setTemplate($template_name)
    {
    	if (empty($template_name))
    	{
    	    throw new exception(__METHOD__ . "::Empty Template Name");
    	}

        $template_name = str_replace(array('/','\\'), DIR_SEP,$template_name);

    	if (!file_exists($this->template_dir . $template_name . '.php'))
    	{
    	    throw new exception(__METHOD__ . "::No Such Template");
    	}

    	$this->template_name = $template_name;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Renders the template
     *
     * @access public
     * @return string
     * @throws exception
     */
    public function render()
    {
    	if (empty($this->template_name))
    	{
    	    throw new exception(__METHOD__ . "::Empty Template Name");
    	}

    	extract($this->params);

    	ob_start();
    	include_once($this->template_dir. $this->template_name . '.php');
    	return ob_get_clean();
    }

}

?>
