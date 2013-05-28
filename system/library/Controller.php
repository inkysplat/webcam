<?php

Abstract Class Controller
{
	protected $_db = null;

	/**
	 * View Parameters to be passed into the view object
	 * @var array
	 */
	public $viewParams = array();

	/**
	 * View Type - html/json/xml etc.
	 * @var string
	 */
	public $defaultViewType = 'html';

	/**
	 * Constructor to map dependancies into the local scope
	 * @param [array] $deps [description]
	 */
	public function __construct($deps){
		foreach($deps as $name=>$dep)
		{
			$obj = strtolower('_'.$name);
			$this->{$obj} = $dep;
		}
	}

	/**
	 * Sets parameters to be passed into the View
	 * 
	 * @param [string] $name  [description]
	 * @param [mixed] $value [description]
	 */
	protected function setViewParams($name,$value)
	{
		$this->viewParams[$name] = $value;
	}

	/**
	 * Sets any site configuration from the config file.
	 */
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