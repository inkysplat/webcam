<?php

Class VisitorController extends Controller
{
	public $defaultViewType = 'html';

	private $messageFile = 'message.txt';

	public function __construct($deps)
	{
		parent::__construct($deps);

		$this->messageFile = CACHE_PATH.$this->messageFile;
	}

	public function addAction()
	{
		$params = array(
			'remote_addr' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $_SERVER['HTTP_USER_AGENT']
			);

		$this->_model->addVisitor($params);
	}

	public function currentAction($interval = 5)
	{
		$this->defaultViewType = 'plain';
		$rs = $this->_model->latestVisitorCount($interval);
		$this->viewParams['current'] = $rs[0]['count'];
	}

	public function getMessageAction()
	{		  
		  $this->defaultViewType = 'json';

		  if(!file_exists($this->messageFile))
		  {
		  	return false;
		  }

		  $request = Util('Request');

		  // infinite loop until the data file is not modified
		  $lastmodif    = isset($request->params['timestamp']) ? $request->params['timestamp'] : 0;
		  $currentmodif = filemtime($this->messageFile);
		  while ($currentmodif <= $lastmodif) // check if the data file has been modified
		  {
		    usleep(10000); // sleep 10ms to unload the CPU
		    clearstatcache();
		    $currentmodif = filemtime($this->messageFile);
		  }
		 
		  // return a json array
		  $response = array();
		  $response['msg']       = file_get_contents($this->messageFile);
		  $response['timestamp'] = $currentmodif;

		  $this->viewParams['response'] = $response;
	}

	public function postMessageAction($message = '')
	{
		$this->defaultViewType = 'html';

		$request = Util('Request');
		if($request->params['message'] != '')
		{
			if(!file_exists($this->messageFile))
			{
				touch($this->messageFile);
				chmod($this->messageFile, 0777);
			}

			file_put_contents($this->messageFile, $request->params['message']);
		}		
	}

}

