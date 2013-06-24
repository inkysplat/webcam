<?php

Class VisitorController extends Controller
{
	public $defaultViewType = 'html';

	private $messageFile = 'message.txt';

	private $counterFile = 'counter.txt';

	public function __construct($deps)
	{
		parent::__construct($deps);

		$this->messageFile = CACHE_PATH.$this->messageFile;
		$this->counterFile = CACHE_PATH.$this->counterFile;
	}

	public function addAction()
	{
		$this->defaultViewType = 'plain';

		$params = array(
			'remote_addr' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $_SERVER['HTTP_USER_AGENT']
			);

		$this->_model->addVisitor($params);

		$rs = $this->_model->latestVisitorCount(10);

		if(!file_exists($this->counterFile))
		{
			touch($this->counterFile);
			chmod($this->counterFile, 0777);
		}

		file_put_contents($this->counterFile, $rs[0]['count']);

		$this->viewParams['counter'] = $rs[0]['count'];

	}

	public function currentAction()
	{
		$this->defaultViewType = 'json';

		  if(!file_exists($this->counterFile))
		  {
		  	return false;
		  }

		  $request = Util('Request');

		  // infinite loop until the data file is modified
		  $lastmodif    = isset($request->params['timestamp']) ? $request->params['timestamp'] : 0;

		  $file = $this->loadFileDelayed($this->counterFile, $lastmodif);

		  $this->viewParams['current'] = array('count'=>$file,'timestamp'=>time());

	}

	public function getMessageAction()
	{
		  $this->defaultViewType = 'json';

		  if(!file_exists($this->messageFile))
		  {
		  	return false;
		  }

		  $request = Util('Request');

		  // infinite loop until the data file is modified
		  $lastmodif    = isset($request->params['timestamp']) ? $request->params['timestamp'] : 0;

		  $file = $this->loadFileDelayed($this->messageFile, $lastmodif);

		  // return a json array
		  $response = array();
		  $response['msg']       = urldecode($file);
		  $response['timestamp'] = time();

		  $this->viewParams['response'] = $response;
	}

	public function postMessageAction($message = '')
	{
		$this->defaultViewType = 'plain';

		$request = Util('Request');
		if($request->params['message'] != '')
		{
			if(!file_exists($this->messageFile))
			{
				touch($this->messageFile);
				chmod($this->messageFile, 0777);
			}

			file_put_contents($this->messageFile, $request->params['message']);
      
      //play remote music
      file_get_contents('http://82.152.190.66/');
		}
	}
  
  public function playAudioAction()
  {
    $this->defaultViewType = 'json';

    $opts = array(
    	'http'=>array(
    		'method'=>'GET',
    		'header'=>"User-agent:  ".$_SERVER['HTTP_USER_AGENT']."\r\n"
	));

    $context = stream_context_create($opts);

    $this->viewParams['response'] = file_get_contents('http://82.152.190.66/', false, $context);

    $subject = 'New Webcam Honk';

    $message  = "New Webcam Honk:\n".str_repeat('-',80)."\n\n";
    $message .= print_r(json_decode($this->viewParams['response'],true), true);
    $message .= "\n".str_repeat('-',80)."\n\n";
    $message .= print_r($_SERVER,true);

    mail('adam@goramandvincent.com',$subject, $message);
  }

}


