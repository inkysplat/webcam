<?php

Abstract Class Controller
{
	protected $_db = null;

	public $viewParams = array();

	public $defaultViewType = 'html';

	protected function setViewParams($name,$value)
	{
		$this->viewParams[$name] = $value;
	}

	public function setDatabaseObj($obj)
	{
		$this->_db = $obj;
	}

	public function setSiteParams()
	{
		if(empty($this->siteParams))
		{
			$config = App('Config');
			$config->xml('default');
			$this->siteParams = $config->get('site','array');
		}
	}
}