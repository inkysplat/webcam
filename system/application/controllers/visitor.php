<?php

Class VisitorController extends Controller
{
	public $defaultViewType = 'html';

	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function addAction()
	{
		$params = array(
			'remote_addr' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $_SERVER['HTTP_USER_AGENT']
			);

		$this->_model->addVisitor($params);
	}

	public function currentAction()
	{
		$this->defaultViewType = 'plain';
		$rs = $this->_model->latestVisitorCount(5);
		$this->viewParams['current'] = $rs[0]['count'];
	}

}

