<?php
/**
 * @category   template
 * @package    Firestorm
 * @author     Adam Nicholls <adamnicholls1987@gmail.com>
 * @name       Template
 * @since      01/05/2012
 * @subpackage library
 * @copyright  2012
 */
class Template
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
    	    if(defined('TEMPLATE_PATH'))
    	    {
    	        $this->template_dir = TEMPLATE_PATH;
    	    }
    	    else
    	    {
    		$this->template_dir = SITE_PATH.'templates/';
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

    	if (!file_exists($this->template_dir . $template_name . '.php'))
    	{
    	    throw new exception(__METHOD__ . "::No Such Template");
    	}

    	$this->template_name = $template_name;
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
