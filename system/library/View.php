<?php

Class View
{

	private $_viewStack = array();

	public $params = array();


	public function __construct()
	{
		if(!is_dir(LAYOUTS_PATH))
        {
            throw new Exception(__METHOD__."::Missing Layouts Directory");
        }
	}

	public function render($type = 'html')
	{
		if(ob_get_level() >= 1)
		{
			ob_end_clean();
		}

		switch($type)
		{
			case 'javascript':
				header('Content-type: application/javascript;');
				break;
			case 'plain':
				header('Content-type: plain/text;');
				break;
			case 'json':
				header('Content-type: application/json;');
				break;
			case 'html':
				$this->header();
				$this->footer();

				header('Content-type: text/html;');
				break;
		}

		ob_start();
		foreach($this->_viewStack as $view)
		{
			$view->setParams($this->params);
			echo $view->render();
		}
		return ob_get_clean();

	}

	public function partial($path, $params = array())
	{
		$body = new Partial();
        $body->setTemplate($path);

        $this->params = array_merge($this->params, $params);

        return $body;
	}

	public function add($partial,$name = '')
	{
		if($partial instanceof Partial)
		{
			if($name == '')
			{
				$this->_viewStack[] = $partial;
			}else{
				$this->_viewStack[$name] = $partial;
			}
		}
	}


	public function header()
	{
		if(!file_exists(LAYOUTS_PATH.'header.php'))
        {
            throw new Exception(__METHOD__."::Missing Header File in Layouts Directory");
        }

        array_unshift($this->_viewStack, $this->partial('layouts/header', $this->params));
	}

	public function footer()
	{
        if(!file_exists(LAYOUTS_PATH.'footer.php'))
        {
            throw new Exception(__METHOD__."::Missing Footer File in Layouts Directory");
        }

        array_push($this->_viewStack, $this->partial('layouts/footer', $this->params));
	}

}